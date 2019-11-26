<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends Manage_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('tank_auth');
        $this->load->library('layout', 'layouts/default');
        $this->layout->setLayout("layouts/default");
    }

    public function index()
    {
        $this->team('lists');
    }

    function team($type='lists', $team_id=null){

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
            'alarm' =>$alarm_cnt
        );

        switch ($type){
            case 'upload':
                $this->_team_upload($team_id,$user_data);
                break;
            case 'detail':
                $this->_team_detail($team_id,$user_data);
                break;

            case 'status':
                $this->_team_status($user_data);
                break;

            case 'delete':
                $this->_team_delete($team_id,$user_data);
                break;
            default:
            case 'lists':
                $this->_team_lists($user_data);
                break;
        }
    }

    function _team_lists($user_data){ //manage 의 list는 기본 team_list 와 다르다

        $search = $this->uri->segment(6);
        $team_id = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'search' => '',
                'status'=>null,
//                'team'=>$team_id, 이게 왜있지.?
                'user_id'=>$user_data['user_id'],
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'status'=>null,
//                'team'=>$team_id,
                'user_id'=>$user_data['user_id'],
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/manage/team/lists'; // 페이징 주소
        $config['total_rows'] = $this -> team_model -> load_assigned_team('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 5; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this->uri->segment(5);
        if($page==null){
            $start=0;
        }else{

            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }

        $limit = $config['per_page'];

        $data['result'] = $this->team_model->load_assigned_team('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('manage/team/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }

    function _team_upload($team_id = null,$user_data){
        //업로드 합시다..

        $cate_list = $this->team_model->load_category();
        if($this->input->post()){

            $form_data = $this->input->post();
            $team_info = array(
                'user_id'=>$user_data['user_id'],
                'title'=>$form_data['title'],
                'url_name'=>$form_data['url_name'],
                'category_id'=>$form_data['category_id'],
                'description'=>$form_data['description'],
                'district'=>$form_data['district'],

            );
            if($team_id){ //수정

                $this->team_model->update_team($team_id,$team_info);
                alert('수정되었습니다.','/team/'.$form_data['url_name']);
            }else{//등록
                $team_info['crt_date'] = date('Y-m-d H:i:s');
                $this->team_model->insert_team($team_info);
                alert('팀이 생성되었습니다.','/team/'.$form_data['url_name']);
            }
        }else{
            $team_info = array(
                'title'=>null,
                'url_name'=>null,
                'category_id'=>null,
                'description'=>null,
                'district'=>null,

            );
            if($team_id){//이거면 수정
                $team_info =   $this->team_model->get_team_info($team_id);
            }
            $this->layout->view('/manage/team/upload', array('user' => $user_data,'team_info'=>$team_info,'cate_list'=>$cate_list));
        }

    }

    function _team_detail($team_id,$user_data){ //detail - 정보

        $team_info = $this->team_model->get_team_info($team_id);

        $auth = $this->_is_auth('team',$team_id, $user_data['user_id']);
        if($auth<3){

            $search_query = array( //둘다 동일한 search_query
                'crt_date' => '',
                'search'=>null,
                'user_id'=>null,
                'status'=>null, //무조건 공개
                'team_id'=>$team_id,
                'user_id'=>null,//load_after 때문에
            );
//            $app_list = $this->application_model->load_team_application($team_info['team_id']);
            $member_list = $this->member_model->load_team_member('','','',$search_query);
            $program_list =  $this->program_model->load_program('','','',$search_query);
            $blog_list =  $this->team_model->load_team_blog('','','',$search_query);
            $after_list =  $this->after_model->load_after('','','',$search_query);
            $team_info['position'] = auth_code_to_text($auth);
            $this->layout->view('manage/team/detail', array('user'=>$user_data,'team_info'=>$team_info,
                'blog_list'=>$blog_list,'member_list'=>$member_list,'program_list'=>$program_list,'after_list'=>$after_list));

        }else{
            alert('권한이 없습니다. [MD01]');
        }
    }


    function _team_status($user_data){ //detail - 정보

        $team_id = $this->input->post('team_id');
        $status = $this->input->post('status');
        //권한 확인
        $auth = $this->_is_auth('team',$team_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth<3){ //권한이 있으면 상태 변경
            $status_data = array(
                'status'=>$status,
            );
            $this->team_model->update_team($team_id,$status_data);
            alert('이 팀이 '.$this->lang->line($status).'로 변경되었습니다.');

        }else{

            alert('권한이 없습니다. [MD01]');
        }

    }


    function _team_delete($team_id,$user_data){ //unique_id!=moin_id
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        //권한 확인
        $auth = $this->_is_auth('team',$team_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth<2){

            $this->team_model->delete_team($team_id);
            alert('삭제되었습니다.','/manage');
        }else{
            alert('권한이 없습니다. [MD02]');
        } //권한이 있으면 상태 변경

    }


    function application($type='lists', $app_id=null){

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );

        switch ($type){
            case 'upload':
                $this->_app_upload($app_id,$user_data);
                break;

            case 'detail':
                $this->_app_detail($app_id,$user_data);
                break;
            case 'forms':
                $this->_app_forms($app_id,$user_data);
                break;
            case 'delete':
                $this->_app_delete($app_id,$user_data);
                break;
            default:
            case 'lists':
                $this->_app_lists($user_data);
                break;
        }
    }

    function _app_lists($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'search' => '',
                'user_id'=>$user_data['user_id']
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'user_id'=>$user_data['user_id']
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/manage/application/lists'; // 페이징 주소
        $config['total_rows'] = $this -> application_model -> load_app('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->application_model->load_app('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('manage/application/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }

    function _app_upload($app_id = null,$user_data){
        //업로드 합시다..
        $cate_list = $this->team_model->load_category();
        if($this->input->post()){

            $form_data = $this->input->post();
            if($form_data['team_id']){

                $team_info = $this->team_model->get_team_info($form_data['team_id']);
                $app_data = array(
                    'user_id'=>$user_data['user_id'],
                    'team_id'=>$form_data['team_id'],
                    'title'=>$form_data['title'],
                    'subtitle'=>$form_data['subtitle'],
                    'category_id'=>$form_data['category_id'],
                    'description'=>$form_data['description'],
                    'contents'=>$form_data['contents'],
                    'event_date'=>$form_data['event_date'].' '.$form_data['event_start_hour'].':'.$form_data['event_start_min'].':00', //시작 날짜만 기억
                    'close_date'=>$form_data['close_date'].' '.$form_data['close_hour'].':'.$form_data['close_min'].':00',
                    'open_date'=>$form_data['open_date'].' '.$form_data['open_hour'].':'.$form_data['open_min'].':00',

                    'address'=>$form_data['address'],
                    'address2'=>$form_data['address2'],
                    'zipcode'=>$form_data['zipcode'],

                    'capacity'=>$form_data['capacity'],
                    'account'=>$form_data['account'],
                    'bank'=>$form_data['bank'],
                    'holder'=>$form_data['holder'],
                    'status'=>1,

                );

                $date_data = array(
                    'user_id'=>$user_data['user_id'],

                    'event_date'=>$form_data['event_date'],
                    'event_start_hour'=>$form_data['event_start_hour'],
                    'event_start_min'=>$form_data['event_start_min'],
                    'event_end_hour'=>$form_data['event_end_hour'],
                    'event_end_min'=>$form_data['event_end_min'],

                    'open_date'=>$form_data['open_date'],
                    'open_hour'=>$form_data['open_hour'],
                    'open_min'=>$form_data['open_min'],
                    'close_date'=>$form_data['close_date'],
                    'close_hour'=>$form_data['close_hour'],
                    'close_min'=>$form_data['close_min'],
                );

                //날짜 정보...

                if($app_id){ //수정

                    $app_info =   $this->application_model->get_application_info($app_id);
                    $redirect_url = '/team/'.$team_info['url_name'].'/view/'.$app_id;

                    $this->application_model->update_application($app_id,$app_data);
                    $this->application_model->update_application_date($app_info['app_date_id'],$date_data);
                    //
                    alert('지원서가 수정되었습니다.',$redirect_url);
                }else{//등록
                    $app_data['crt_date'] = date('Y-m-d H:i:s');
                    $app_id = $this->application_model->insert_application($app_data);
                    $app_date_id = $this->application_model->insert_application_date($date_data);
                    //app_date_id 업데이트하기
                    //app_date_id 에 app_id 업데이트

                    $new_date['app_date_id']=$app_date_id;
                    $new_date_data['application_id']=$app_id;
                    $this->application_model->update_application($app_id,$new_date);
                    $this->application_model->update_application_date($app_date_id,$new_date_data);

                    $redirect_url = '/team/'.$team_info['url_name'].'/view/'.$app_id;
                    alert('지원서가 생성되었습니다.',$redirect_url);
                }
            }else{ // team_id 없으면 생성 불가능함
                alert('팀을 꼭 선택하셔야합니다.');
            }
        }else{

            $team_id = $this->input->get('Team');
            $team_info = null; //일단 null로 지정한다..
            if($team_id!=null){
                $team_info = $this->team_model->get_team_info($team_id);
            }

            //내가 만든 모든 팀 불러오기
            $search_query = array(
                'crt_date' => '',
                'search' => '',
                'user_id'=>$user_data['user_id']
            );
            $team_list = $this->team_model->load_team('', '', '', $search_query);
            if($team_list==null){
                alert('지원서를 만들기 위해서는 팀을 먼저 만드셔야 합니다. 팀 생성 페이지로 이동합니다.','/manage/team/upload');
            }else{

                $app_info = array(
                    'title'=>null,
                    'url_name'=>null,
                    'category_id'=>null,
                    'description'=>null,
                    'district'=>null,

                );
                if($app_id){//이거면 수정
                    $app_info =   $this->application_model->get_application_info($app_id);
                }
                $this->layout->view('/manage/application/upload', array('user' => $user_data,'app_info'=>$app_info,'cate_list'=>$cate_list,'team_info'=>$team_info,'team_list'=>$team_list,'team_id'=>$team_id));
            }

        }

    }

    function _app_detail($app_id,$user_data){ //detail - 정보

        $app_info = $this->application_model->get_application_info($app_id);

        if($app_info['user_id']!=$user_data['user_id']||$user_data['level']<9){ //관리자이거나 본인만 지울 수 있다.

            $this->layout->view('manage/application/detail', array('user'=>$user_data,'app_info'=>$app_info));

        }else{
            alert('권한이 없습니다. [MD01]');
        }
    }

    function _app_delete($app_id,$user_data){ //unique_id!=moin_id

        $app_info = $this->application_model->get_application_info($app_id);

        //이 전에 조건을 걸어둬야겠지.. 제출된 게 있으면 절대 못함
        if($app_info['user_id']!=$user_data['user_id']||$user_data['level']<9){ //관리자이거나 본인만 지울 수 있다.

            $this->application_model->delete_application($app_id);
            alert('삭제되었습니다.','/manage');
        }else{
            alert('권한이 없습니다. [MD02]');
        }


    }


    function _app_forms($application_id,$user_data){ //forms - 폼 목록
        $app_info = $this->application_model->get_application_info($application_id);

        if($app_info['user_id']!=$user_data['user_id']||$user_data['level']<9){ //관리자이거나 본인만 지울 수 있다.
            $forms = $this->form_model->load_application_forms($application_id);

            $this->layout->view('manage/application/forms', array('user'=>$user_data,'forms'=>$forms,'app_info'=>$app_info));

        }else{
            alert('권한이 없습니다. [MD01]');
        }
    }

    function deposit($type='lists', $application_id=null){

        //deposit은 $form_id 로 pk 로 찾는다..
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );

        switch ($type){

            case 'detail':
                $this->_deposit_detail($application_id,$user_data);
                break;
            case 'delete':

                $form_id = $this->input->get('form_id');
                $this->_deposit_delete($form_id,$user_data);
                break;
            case 'status':
                $form_id = $this->input->get('form_id');
                $this->_deposit_status($form_id,$user_data);
                break;
            default:
            case 'lists':
                $this->_deposit_lists($application_id,$user_data);
                break;
        }
    }

    function _deposit_lists($application_id, $user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'search' => '',
                'application_id'=>$application_id
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'application_id'=>$application_id
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/manage/deposit/lists/'; // 페이징 주소
        $config['total_rows'] = $this -> deposit_model -> load_deposit('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->deposit_model->load_deposit('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('manage/deposit/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query,'application_id'=>$application_id));

    }


    function _deposit_detail($form_id,$user_data){ //detail - 정보

        $deposit_info = $this->deposit_model->get_deposit_info($form_id);

        if($deposit_info['user_id']!=$user_data['user_id']||$user_data['level']<9){ //관리자이거나 본인만 지울 수 있다.

            $this->layout->view('manage/deposit/detail', array('user'=>$user_data,'deposit_info'=>$deposit_info));

        }else{
            alert('권한이 없습니다. [MD01]');
        }
    }

    function _deposit_status($form_id,$user_data){ //detail - 정보

        // $status ==1,0
        $deposit_info = $this->deposit_model->get_deposit_info($form_id);
        //이 application의 주인을 찾아서 만약에 이걸 신청한 사람이 app 주인 아니면 access 띄운다.

        $status = $this->input->post('status');
        if($deposit_info['user_id']!=$user_data['user_id']||$user_data['level']<9){ //관리자이거나 본인만 지울 수 있다.
            $this->deposit_model->set_deposit_status($form_id,$status); //여기서 done date 조정까지 다 해준다
            if($status==1){ //완료
                echo 'done'; //ajax 로 front 에서 처리
            }else{//대기
                echo 'pending';
            }

        }else{
            echo 'access';
        }
    }

    function _deposit_delete($form_id,$user_data){ //unique_id!=moin_id

        $deposit_info = $this->deposit_model->get_deposit_info($form_id);

        //이 전에 조건을 걸어둬야겠지.. 제출된 게 있으면 절대 못함
        if($deposit_info['user_id']!=$user_data['user_id']||$user_data['level']<9){ //관리자이거나 본인만 지울 수 있다.

            $this->deposit_model->delete_deposit($form_id);
            alert('삭제되었습니다.','/manage');
        }else{
            alert('권한이 없습니다. [MD02]');
        }


    }



    function after($type='lists', $after_id=null){

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );

        switch ($type){
            case 'upload':
                $this->_after_upload($after_id,$user_data); //nl2br
                break;

            case 'detail': //view
                $this->_after_detail($after_id,$user_data);
                break;

            case 'delete':
                $this->_after_delete($after_id,$user_data);
                break;
            default:
            case 'lists':
                $this->_after_lists($user_data);
                break;
        }
    }

    function _after_lists($user_data){

        $search = $this->uri->segment(6);
        $team_id = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => null,
                'status'=>'on', //after의 status는 'on'인것만 보여줌: 사용자가 after의 권한을 갖고있다.
                'search' => null,
                'team_id'=>$team_id, //기본은 team_id임 로 남긴다..
                'user_id'=>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');

            $search_query = array(
                'crt_date' => $sort_date,
                'status'=>'on',//after의 status는 'on'인것만 보여줌: 사용자가 after의 권한을 갖고있다.
                'search' => $sort_search,
                'team_id'=>$team_id,
                'user_id'=>null,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/manage/after/lists'; // 페이징 주소
        $config['total_rows'] = $this -> after_model -> load_after('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 5; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this->uri->segment(5);
        if($page==null){
            $start=0;
        }else{

            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }

        $limit = $config['per_page'];

        $data['result'] = $this->after_model->load_after('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('manage/after/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }

    function _after_upload($after_id = null,$user_data){
        //업로드 합시다..
        $application_id = $this->input->get('app_id');
        if($this->input->post()){

            $form_data = $this->input->post();
            if($form_data['title']){ //제목 입력하면 됨

                $after_data = array(
                    'user_id'=>$user_data['user_id'],
                    'application_id'=>$form_data['application_id'],
                    'title'=>$form_data['title'],
                    'contents'=>nl2br($form_data['contents']),

                );

                //날짜 정보...

                if($after_id){ //수정

                    $redirect_url = '/manage/after/detail/'.$after_id;

                    $this->after_model->update_after($after_id,$after_data);
                    //
                    alert('후기가 수정되었습니다.',$redirect_url);
                }else{//등록
                    $after_data['crt_date'] = date('Y-m-d H:i:s');
                    $after_id = $this->after_model->insert_after($after_data);
                    $redirect_url = '/manage/after/detail/'.$after_id;
                    alert('후기가 입력되었습니다.',$redirect_url);
                }
            }else{ // team_id 없으면 생성 불가능함
                alert('제목을 입력하세요.');
            }
        }else{

            $app_info = null; //일단 null로 지정한다..
            if($application_id!=null){
                $app_info = $this->application_model->get_application_info($application_id);
            }

            $after_info = array(
                'title'=>null,
                'contents'=>null,
                'application_id'=>$application_id,

            );
            if($after_id){//이거면 수정
                $after_info =   $this->after_model->get_after_info($after_id);
            }
            $this->layout->view('/manage/after/upload', array('user' => $user_data,'after_info'=>$after_info,'app_info'=>$app_info));
        }

    }

    function _after_detail($after_id,$user_data){ //detail - 정보

        $after_info = $this->after_model->get_after_info($after_id);

        if($after_info['user_id']!=$user_data['user_id']||$user_data['level']<9){ //관리자이거나 본인만 지울 수 있다.

            if($after_info!=null){
                $this->layout->view('manage/after/detail', array('user'=>$user_data,'after_info'=>$after_info));
            }else{
                alert('후기가 없습니다.');
            }


        }else{
            alert('권한이 없습니다. [MD01]');
        }
    }

    function _after_delete($after_id,$user_data){ //unique_id!=moin_id

        $after_info = $this->after_model->get_after_info($after_id);

        //이 전에 조건을 걸어둬야겠지.. 제출된 게 있으면 절대 못함
        if($after_info['user_id']!=$user_data['user_id']||$user_data['level']<9){ //관리자이거나 본인만 지울 수 있다.

            $this->after_model->delete_after($after_id);

            alert('후기가 삭제되었습니다. 지원서 페이지로 이동합니다.','/manage/application/detail/'.$after_info['application_id']);

        }else{
            alert('권한이 없습니다. [MD02]');
        }


    }


    function program($type='lists', $program_id=null){

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );

        switch ($type){
            case 'upload':
                $this->_program_upload($program_id,$user_data); //nl2br
                break;

            case 'detail': //view
                $this->_program_detail($program_id,$user_data);
                break;

            case 'status': //view
                $this->_program_status($user_data);
                break;

            case 'delete':
                $this->_program_delete($program_id,$user_data);
                break;
            default:
            case 'lists':
                $this->_program_lists($user_data);
                break;
        }
    }

    function _program_lists($user_data){

        $team_id =$this->uri->segment(4);
        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'crt_date' => null,
                'search' => null,
                'status'=>null,
                'team_id'=>$team_id
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_status = $this->input->get('status');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'status'=>$sort_status,
                'team_id'=>$team_id
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&status='.$search_query['status'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/manage/program/lists/'.$team_id; // 페이징 주소
        $config['total_rows'] = $this -> program_model -> load_program('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 6; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this->uri->segment(6);
        if($page==null){
            $start=0;
        }else{

            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }

        $limit = $config['per_page'];

        $data['result'] = $this->program_model->load_program('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $team_info = $this->team_model-> get_team_info($team_id);
        $this->layout->view('manage/program/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query,'team_info'=>$team_info));

    }

    function _program_upload($program_id = null,$user_data){
        //업로드 합시다..
        $application_id = $this->input->get('app_id');
        if($this->input->post()){

            $form_data = $this->input->post();
            if($form_data['title']){ //제목 입력하면 됨

                $program_data = array(
                    'user_id'=>$user_data['user_id'],
                    'application_id'=>$form_data['application_id'],
                    'title'=>$form_data['title'],
                    'contents'=>nl2br($form_data['contents']),

                );

                //날짜 정보...

                if($program_id){ //수정

                    $redirect_url = '/manage/program/detail/'.$program_id;

                    $this->program_model->update_program($program_id,$program_data);
                    //
                    alert('후기가 수정되었습니다.',$redirect_url);
                }else{//등록
                    $program_data['crt_date'] = date('Y-m-d H:i:s');
                    $program_id = $this->program_model->insert_program($program_data);
                    $redirect_url = '/manage/program/detail/'.$program_id;
                    alert('후기가 입력되었습니다.',$redirect_url);
                }
            }else{ // team_id 없으면 생성 불가능함
                alert('제목을 입력하세요.');
            }
        }else{

            $app_info = null; //일단 null로 지정한다..
            if($application_id!=null){
                $app_info = $this->application_model->get_application_info($application_id);
            }

            $program_info = array(
                'title'=>null,
                'contents'=>null,
                'application_id'=>$application_id,

            );
            if($program_id){//이거면 수정
                $program_info =   $this->program_model->get_program_info($program_id);
            }
            $this->layout->view('/manage/program/upload', array('user' => $user_data,'program_info'=>$program_info,'app_info'=>$app_info));
        }

    }

    function _program_detail($program_id,$user_data){ //detail - 정보

        $auth = $this->_is_auth('program',$program_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth<3) {

            $program_info = $this->program_model->get_program_info($program_id);
            $program_info['position'] = auth_code_to_text($auth);

            if($program_info!=null){
                $this->layout->view('manage/program/detail', array('user'=>$user_data,'program_info'=>$program_info));
            }else{
                alert('후기가 없습니다.');
            }

        }else{
            alert('권한이 없습니다. [MD01]');
        }
    }

    function _program_status($user_data){ //detail - 정보

        $program_id = $this->input->post('program_id');
        $status = $this->input->post('status');
        //권한 확인
        $auth = $this->_is_auth('program',$program_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth<3){ //권한이 있으면 상태 변경
            $status_data = array(
                'status'=>$status,
            );
            $this->program_model->update_program($program_id,$status_data);
            alert('이 프로그램이 '.$this->lang->line($status).'로 변경되었습니다.');

        }else{

            alert('권한이 없습니다. [MD01]');
        }

    }

    function _program_delete($program_id,$user_data){ //unique_id!=moin_id

        $program_info = $this->program_model->get_program_info($program_id);

        $auth = $this->_is_auth('program',$program_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth<2){ //권한이 있으면 삭제 (admin, boss)
            $this->program_model->delete_program($program_id); //정말 삭제할지 .. 아니면 남겨둘 것인지..이건 진짜 삭제한다.
            alert('프로그램이 삭제되었습니다. 팀 관리 페이지로 이동합니다.','/manage/team/detail/'.$program_info['team_id']);

        }else{
            alert('권한이 없습니다. [MD02]');
        }

    }
    function blog($type='lists', $blog_id=null){

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );
        switch ($type){
//            case 'upload': //없어도 된다..
//                $this->_blog_upload($team_id,$user_data); //nl2br
//                break;

            case 'detail': //view
                $this->_blog_detail($blog_id,$user_data);
                break;

            case 'delete':
                $this->_blog_delete($blog_id,$user_data);
                break;
            default:
            case 'lists':
                $this->_blog_lists($user_data);
                break;
        }
    }

    function _blog_lists($user_data){


        $team_id = $this->uri->segment(4);//list만 segment 다르다..
        $search = $this->uri->segment(6);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'search' => '',
                'status'=>null,
                'team_id'=>$team_id,
                'user_id'=>$user_data['user_id']
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_status = $this->input->get('status');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'team_id'=>$team_id,
                'status'=>$sort_status,
                'user_id'=>$user_data['user_id']
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&status='.$search_query['status'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/manage/blog/lists'; // 페이징 주소
        $config['total_rows'] = $this -> team_model -> load_team_blog('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 5; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this->uri->segment(5);
        if($page==null){
            $start=0;
        }else{

            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }

        $limit = $config['per_page'];

        $data['result'] = $this->team_model->load_team_blog('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $team_info = $this->team_model->get_team_info($team_id);
        $this->layout->view('manage/blog/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query,'team_info'=>$team_info));

    }

    function _blog_upload($blog_id = null,$user_data){
        //업로드 합시다..
        $application_id = $this->input->get('app_id');
        if($this->input->post()){

            $form_data = $this->input->post();
            if($form_data['title']){ //제목 입력하면 됨

                $blog_data = array(
                    'user_id'=>$user_data['user_id'],
                    'application_id'=>$form_data['application_id'],
                    'title'=>$form_data['title'],
                    'contents'=>nl2br($form_data['contents']),

                );

                //날짜 정보...

                if($blog_id){ //수정

                    $redirect_url = '/manage/blog/detail/'.$blog_id;

                    $this->team_model->update_blog($blog_id,$blog_data);
                    //
                    alert('후기가 수정되었습니다.',$redirect_url);
                }else{//등록
                    $blog_data['crt_date'] = date('Y-m-d H:i:s');
                    $blog_id = $this->team_model->insert_blog($blog_data);
                    $redirect_url = '/manage/blog/detail/'.$blog_id;
                    alert('후기가 입력되었습니다.',$redirect_url);
                }
            }else{ // team_id 없으면 생성 불가능함
                alert('제목을 입력하세요.');
            }
        }else{

            $app_info = null; //일단 null로 지정한다..
            if($application_id!=null){
                $app_info = $this->application_model->get_application_info($application_id);
            }

            $blog_info = array(
                'title'=>null,
                'contents'=>null,
                'application_id'=>$application_id,

            );
            if($blog_id){//이거면 수정
                $blog_info =   $this->team_model->get_blog_info($blog_id);
            }
            $this->layout->view('/manage/blog/upload', array('user' => $user_data,'blog_info'=>$blog_info,'app_info'=>$app_info));
        }

    }

    function _blog_detail($blog_id,$user_data){ //detail - 정보

        $blog_info = $this->team_model->get_team_blog_info($blog_id);
        $this->layout->view('manage/blog/detail', array('user'=>$user_data,'blog_info'=>$blog_info));

    }

    function _blog_delete($blog_id,$user_data){ //unique_id!=moin_id

        $blog_info = $this->team_model->get_blog_info($blog_id);

        //이 전에 조건을 걸어둬야겠지.. 제출된 게 있으면 절대 못함
        if($blog_info['user_id']!=$user_data['user_id']||$user_data['level']<9){ //관리자이거나 본인만 지울 수 있다.

            $this->team_model->delete_blog($blog_id);

            alert('후기가 삭제되었습니다. 지원서 페이지로 이동합니다.','/manage/application/detail/'.$blog_info['application_id']);

        }else{
            alert('권한이 없습니다. [MD02]');
        }


    }



    function member($type='lists', $member_id=null){

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
            'alarm' =>$alarm_cnt
        );
        switch ($type){
            case 'upload':
                $team_id = $this->input->get('team');
                $this->_member_upload($team_id,$user_data);
                break;
            case 'detail':
                $this->_member_detail($member_id,$user_data);
                break;

            case 'delete':
                $this->_member_delete($user_data);
                break;
            default:
            case 'lists':
                //$member_id == $team_id임..
                $this->_member_lists($user_data);
                break;
        }
    }

    function _member_lists( $user_data){

        $search = $this->uri->segment(6);
        $team_id = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'search' => '',
                'user_id' => null,
                'team_id'=>$team_id
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'user_id' => null,
                'team_id'=>$team_id
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/manage/member/lists'; // 페이징 주소
        $config['total_rows'] = $this -> member_model -> load_team_member('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 5; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this->uri->segment(5);
        if($page==null){
            $start=0;
        }else{

            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }

        $limit = $config['per_page'];

        $data['result'] = $this->member_model->load_team_member('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $team_info =   $this->team_model->get_team_info($team_id);
        $this->layout->view('manage/member/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query,'team_info'=>$team_info));

    }

    function _member_upload($team_id = null,$user_data){
        //업로드 합시다..

        if($this->input->post('user_id')!=null){ //여기에는 수정이 있을 수 없음.. 그냥 지우면 됨

            $form_data = $this->input->post();
            $member_info = array(
                'user_id'=>$form_data['user_id'],
                'team_id'=>$form_data['team_id'],
                'crt_date'=>date('Y-m-d H:i:s')
            );
            $this->member_model->insert_team_member($member_info);
            //이 사람의 레벨을 3으로 지정
            $level_info = array(
                'level'=>3,
            );
            $this->user_model->update_users($form_data['user_id'],$level_info);

            alert('팀 멤버가 등록되었습니다.','/manage/member/lists/'.$form_data['team_id']);
        }else{

            $team_info =   $this->team_model->get_team_info($team_id);
            $this->layout->view('/manage/member/upload', array('user' => $user_data,'team_info'=>$team_info));
        }

    }

    function _member_detail($member_id,$user_data){ //detail - 정보

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );
        $member_info = $this->member_model->get_team_member_info($member_id);
        $team_info =   $this->team_model->get_team_info($member_info['team_id']);

        if($team_info['user_id']!=$user_id||$level<9){ //관리자이거나 본인만 지울 수 있다.
            $this->layout->view('manage/member/detail', array('user'=>$user_data,'member_info'=>$member_info,'team_info'=>$team_info));

        }else{
            alert('권한이 없습니다. [MD01]');
        }
    }

    function _member_delete($user_data){ //unique_id!=moin_id

        $member_id = $this->input->post('member_id');
        $member_info = $this->member_model->get_team_member_info($member_id);
        $team_info =   $this->team_model->get_team_info($member_info['team_id']);

        //이 전에 조건을 걸어둬야겠지.. 제출된 게 있으면 절대 못함
        if($team_info['user_id']!=$user_data['user_id']||$user_data['level']<9){ //관리자이거나 본인만 지울 수 있다.

            $this->member_model->delete_team_member($member_id);
//            //이 사람의 레벨을 1 로 지정
//            $level_info = array(
//                'level'=>1,
//            );
//            $this->user_model->update_users($member_info['user_id'],$level_info);

            alert('팀 멤버에서 제외되었습니다.','/manage/member/lists/'.$team_info['team_id']);
        }else{
            alert('권한이 없습니다. [MD02]');
        }


    }
    function _pagination_config($config){

        $config['first_link'] = '≪';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '≫';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '＞';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '＜';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="page-item active"><a href="" class="page-link">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        $config['use_page_numbers'] = TRUE;

        return $config;
    }

    function _is_auth($type='team',$unique_id, $user_id){

        /*code
        0: admin
        1: boss
        2: member
        3: nothing
        */

        $code = 3;
        //이 user의 level 가져오기
        // 이 user가 type 에서 어떤 권한이 있는지 가져오기
        $level = $this->user_model->get_user_level($user_id);

        if($type=='program'){
            $program_info =$this->program_model->get_program_info($unique_id);
            $team_id = $program_info['team_id'];
        }else{ //team
            $team_id = $unique_id;
        }
        $team_info = $this->team_model->get_team_info($team_id);
        $team_member = $this->member_model->is_team_member($team_id, $user_id);

        //return if t or f;

        if($team_info['user_id']==$user_id){ //team boss? 1

            $code = 1;
        }else if($team_member){ // true or false ( //or team member?? 1)
            $code = 2;

        }else{ //or nothing 0
            $code = 3;

        }

        if($level==9){ //super users always return true;
            $code = 0;

        }
        return $code;


    }

}
