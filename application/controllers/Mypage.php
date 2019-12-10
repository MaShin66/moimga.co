<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mypage extends Mypage_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('verify'));

        $this->load->library('tank_auth');
        $this->load->library('layout', 'layouts/default');
        $this->layout->setLayout("layouts/default");

    }


    public function index()
    {
       $this->info();
    }
 
    function info(){//내 정보
        $this->load->model('verify_model');
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];

        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
            'alarm' =>$alarm_cnt,
        );
        $my_info = $this->user_model->get_user_basic_info($user_id);
        $verify = $this->verify_model->get_verify_by_user_id($user_id);
        $this->layout->view('mypage/main', array('user'=>$user_data,'my_info'=>$my_info,'verify'=>$verify));
    }
    function after($type='lists', $after_id=null){ //내가 쓴 후기

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];

        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
            'alarm' =>$alarm_cnt,
        );
        
        switch ($type){
            case 'detail':
                $this->_after_detail($after_id, $user_data);
                break;
            case 'status':

                $this->_after_status($user_data);
                break;
            case 'delete':

                $this->_after_delete($user_data);
                break;
                
            default:
            case 'lists':

            $this->_after_lists($user_data);
                break;
                
        }
    }

    function _after_lists($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'search' => '',
                'status'=>null,
                'team_id'=>null,
                'user_id'=>$user_data['user_id']
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_status= $this->input->get('status');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,

                'team_id'=>null,
                'status'=>$sort_status,
                'user_id'=>$user_data['user_id']
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&status='.$search_query['status'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/mypage/after/lists'; // 페이징 주소
        $config['total_rows'] = $this -> after_model -> load_after('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 4; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this->uri->segment(4);
        if($page==null){
            $start=0;
        }else{

            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }

        $limit = $config['per_page'];

        $data['result'] = $this->after_model->load_after('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('mypage/after/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }


    function _after_detail($after_id,$user_data){ //detail - 정보

        $after_info = $this->after_model->get_after_info($after_id);

        $auth = $this->_is_my_after($after_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth){  //내 후기만 볼 수있음
            $this->layout->view('mypage/after/detail', array('user'=>$user_data,'after_info'=>$after_info));

        }else{
            alert('권한이 없습니다. [MD01]');
        }

    }

    function _after_status($user_data){ //detail - 정보

        $after_id = $this->input->post('after_id');
        $status = $this->input->post('status');
        //권한 확인
        $auth = $this->_is_my_after($after_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth){ //권한이 있으면 상태 변경
            $status_data = array(
                'status'=>$status,
            );
            $this->after_model->update_after($after_id,$status_data);
            alert('이 후기가 '.$this->lang->line($status).'로 변경되었습니다.');

        }else{

            alert('권한이 없습니다. [MD01]');
        }

    }

    function _after_delete($user_data){ //unique_id!=moin_id

        $after_id = $this->input->post('after_id');

        $auth = $this->_is_my_after($after_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth){ //권한이 있으면 삭제
            $this->after_model->delete_after($after_id); //정말 삭제할지 .. 아니면 남겨둘 것인지.
            alert('후기가 삭제되었습니다. 내 후기 목록으로 이동합니다.','/mypage/after');

        }else{
            alert('권한이 없습니다. [MD02]');
        }

    }

    function subscribe($type='lists', $subscribe_id=null){ //내가 쓴 후기

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];

        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
            'alarm' =>$alarm_cnt,
        );

        switch ($type){
            case 'delete':

                $this->_subscribe_delete($user_data);
                break;

            default:
            case 'lists':

                $this->_subscribe_lists($user_data);
                break;

        }
    }

    function _subscribe_lists($user_data){

        $search = $this->uri->segment(5);

        $this->load->library('pagination');
        $config['base_url'] = '/mypage/subscribe/lists'; // 페이징 주소
        $config['total_rows'] = $this -> subscribe_model -> load_subscribe_by_user_id($user_data['user_id'],'','','count'); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 4; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1'; // 첫페이지에 query string 에러나서..
        $config = pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this->uri->segment(4);
        if($page==null){
            $start=0;
        }else{

            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }

        $limit = $config['per_page'];

        $data['result'] = $this->subscribe_model->load_subscribe_by_user_id($user_data['user_id'],$start,$limit,'');
        $data['total']=$config['total_rows'];

        $this->layout->view('mypage/subscribe/lists', array('user' => $user_data, 'data' => $data));

    }

    function _subscribe_delete($user_data){ //unique_id!=moin_id

        $subscribe_id = $this->input->post('subscribe_id');

        $auth = $this->_is_my_subscribe($subscribe_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth){ //권한이 있으면 삭제
            $this->subscribe_model->delete_subscribe($subscribe_id); //정말 삭제할지 .. 아니면 남겨둘 것인지.
            alert('구독이 취소되었습니다. 내 구독 목록으로 이동합니다.','/mypage/subscribe');

        }else{
            alert('권한이 없습니다. [MD02]');
        }

    }

    function _is_my_after($after_id, $user_id){

        $level = $this->user_model->get_user_level($user_id);
        if($level==9){ //super users always return true;
            return true; //it ends the function.
        }
        $after_info = $this->after_model->get_after_info($after_id);
        if($after_info['user_id']==$user_id){
            return true;
        }else{
            return false;

        }

    }

    function _is_my_subscribe($subscribe_id, $user_id){

        $subscribe_info = $this->subscribe_model->get_subscribe_info($subscribe_id);
        if($subscribe_info['user_id']==$user_id){
            return true;
        }else{
            return false;
        }

    }

    function verify($type='ready'){
        $this->load->model('verify_model');
        //$type=ready, success, error, cgi, back,

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' =>$level,
            'alarm' =>$alarm_cnt
        );

        switch ($type){
            case 'error':
                $this->layout->view('mypage/verify/error', array('user'=>$user_data));
                break;
            case 'success':
                alert($this->lang->line('verify_done'),'/mypage');
                //$this->layout->view('mypage/verify/success', array('user'=>$user_data));
                break;

            case 'cgi':

                $TransR = array();

                $Addition = array( "TID" );
                $TransR = MakeAddtionalInput( $TransR,$_POST,$Addition );

                /*
                 * CONFIRM
                 * - CONFIRMOPTION
                 *	0 : NONE( default )
                 * 	1 : CPID 및 ORDERID 체크
                 * - IDENOPTION
                 * 0 : 생년월일(6자리) 및 성별 IDEN 필드로 Return (ex : 1401011)
                 * 1 : 생년월일(8자리) 및 성별 별개 필드로 Return (연동 매뉴얼 참조. ex : DOB=20140101&SEX=1)
                 */
                $nConfirmOption = 0;
                $nIdenOption = 1;
                $TransR["TXTYPE"] = "CONFIRM";
                $TransR["CONFIRMOPTION"] = $nConfirmOption;
                $TransR["IDENOPTION"] = $nIdenOption;

                /*
                 * CONFIRMOPTION이 1이면 CPID 및 ORDERID 필수 전달
                 */
                if( $nConfirmOption )
                {
                    $TransR["CPID"] = 'B010037917';
                    $TransR["ORDERID"] = 0;
                }

                $Res = CallTrans( $TransR,false );
                if( $Res["RETURNCODE"] == "0000" ){
                    //db작업하고 넘긴다..

                    //ci, di는 둘 다 같은사람이면 동일하니까 그냥 둘 중에 아무거나 해도됨..

                    $now_ci = $Res['CI'];
                    $now_di = $Res['DI'];

                    //이 CI가 이미 있는지 확인하기
                    $has_DI = $this->verify_model->get_verify_by_DI($now_di);
                    if($has_DI['DI']==$now_di){

                        $verify_try = array(
                            'user_id'=>$user_id,
                            'same_user_id'=>$has_DI['user_id'],
                            'sex'=>$Res['SEX'],
                            'birth_year'=>substr($Res['DOB'], 0, 4),
                            'dob'=>$Res['DOB'],
                            'TID'=>$Res['TID'],
                            'CI'=>$now_ci,
                            'DI'=>$now_di,
                            'try_date'=>date('Y-m-d H:i:s'),
                        );

                        $this->verify_model->insert_verify_try($verify_try);

                        alert(sprintf($this->lang->line('verify_already'), $has_DI['crt_date']),'/info/faq/verify/view/3');
                    }else{ //없으면
                        //user_id로 진행된거 찾기..

                        $veri_info = $this->verify_model->get_verify_by_user_id($user_id);
                        $verify = array(
                            'sex'=>$Res['SEX'],
                            'birth_year'=>substr($Res['DOB'], 0, 4),
                            'dob'=>$Res['DOB'],
                            'TID'=>$Res['TID'],
                            'CI'=>$now_ci,
                            'DI'=>$now_di,
                            'success'=>1,
                            'crt_date'=>date('Y-m-d H:i:s'),
                        );

                        $this->verify_model->update_verify($veri_info['verify_id'],$verify);

                        $age =  date('Y')-$verify['birth_year']+1;
                        $age_status = 0;
                        if($age>=20) $age_status = 1;
                        //실명을 usertable에 넣기
                        $realname = array(
                            'realname'=>$Res['NAME'],
                            'verify'=>1,
                            'adult'=>$age_status,
                        );
                        $this->User_model->update_users($user_id, $realname);
                        // 성별 $Res['SEX'];; 1이면 남, 2면 여자
                        // 이름: $Res['NAME'];
                        // DOB:나오는거..

                        $this->_issue_coupon($user_id,15); //발급

                        $this->layout->view('mypage/verify/cgi', array('user'=>$user_data,'_POST'=>$_POST, 'TransR'=>$TransR,'Res'=>$Res));

                    }

                }else {

                    /**************************************************************************
                     *
                     * 인증 실패에 대한 작업
                     *
                     **************************************************************************/
                    $Result = $Res["RETURNCODE"];
                    $ErrMsg = $Res["RETURNMSG"];
                    $AbleBack = false;
                    $BackURL = $_POST["BackURL"];
                    $BgColor = $_POST["BgColor"];

                    $this->layout->view('mypage/verify/error', array('user' => $user_data, 'Res' => $Res));
                }
                break;
            case 'back':
                $this->veri_back($user_data);
                break;

            default:
            case 'ready':
                $phone= $this->input->post('phone');
                //이 회원 아이디로 본인인증 한 경우 있는지 찾기
                $veri_info = $this->verify_model->get_verify_by_user_id($user_id);
                if(!empty($veri_info)){
                    $success = (int) $veri_info['success'];
                    if($success==1){
                        alert($this->lang->line('verify_already_short'),'/mypage');
                    }else{ // 아니면 새로 쓰기 ...
                        $data = array(
                            'user_id'=>$user_id,
                            'phone'=>$phone,
                            'success'=>0, //처음에는 실패라고 두고, 성공 후에 1로 설정함
                        );
                        $this->verify_model->update_verify($user_id,$data);
                    }

                }else{

                    //아예 없는경우 새로 쓴다..
                    $data = array(
                        'user_id'=>$user_id,
                        'phone'=>$phone,
                        'success'=>0, //처음에는 실패라고 두고, 성공 후에 1로 설정함
                    );
                    $this->verify_model->insert_verify($data);
                }

                $TransR = array();
                $TransR["TXTYPE"] = "ITEMSEND";
                $TransR["SERVICE"] = "UAS";
                $TransR["AUTHTYPE"] = "36";
                $TransR["CPID"] = 'B010037917';
                $TransR["CPPWD"] = 'pCofnWoasL';
                $TransR["USERID"] = $user_id;
                $TransR["TARGETURL"] = base_url()."mypage/verify/cgi";
                $TransR["CPTITLE"] = "www.takemm.com";
                // $TransR["AGELIMIT"] = "019";

                $ByPassValue = array();
                $ByPassValue["BackURL"] = base_url()."mypage/verify/back";
                $ByPassValue["IsCharSet"] = 'UTF-8';
                $ByPassValue["phone"] = $phone;

                $Res = CallTrans( $TransR,false );

                if( $Res["RETURNCODE"] == "0000" ) {
                    $this->layout->view('mypage/verify/ready', array('user'=>$user_data, 'TransR'=>$TransR, 'ByPassValue'=>$ByPassValue,'Res'=>$Res));
                }else{

                    $Result		= $Res["RETURNCODE"];
                    $ErrMsg		= $Res["RETURNMSG"];
                    $AbleBack	= false;
                    $BackURL	= $ByPassValue["BackURL"];
                    $BgColor 	= $ByPassValue["BgColor"];

                    $this->layout->view('mypage/verify/error', array('user'=>$user_data, 'Res'=>$Res));
                }
                break;
        }

    }

    function veri_back($user_data){

        $this->layout->view('mypage/verify/back', array('user'=>$user_data));
    }
    function change_name(){

        $type = $this->input->post('type');
        $user_id = $this->input->post('user_id');
        $name = $this->input->post('name');
        if(is_null($name) || $name==''){
            alert('이름은 비워둘 수 없습니다.');
        }else if($name=='admin'||$name=='관리자'||$name=='TMM'){
            alert('입력하신 이름을 사용할 수 없습니다.');
        }

        $data = array(
            $type=>$name
        );
        $result = $this->user_model->update_users($user_id, $data);

        alert('수정되었습니다.');


    }
}
