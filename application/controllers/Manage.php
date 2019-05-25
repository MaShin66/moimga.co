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
        $this->moim('lists');
    }

    function moim($type='lists', $moim_id=null){

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
                $this->_moim_upload($moim_id,$user_data);
                break;
            case 'detail':
                $this->_moim_detail($moim_id,$user_data);
                break;

            case 'delete':
                $this->_moim_delete($moim_id,$user_data);
                break;
            default:
            case 'lists':
                $this->_moim_lists($user_data);
                break;
        }
    }

    function _moim_lists($user_data){

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
        $config['base_url'] = '/manage/moim/lists'; // 페이징 주소
        $config['total_rows'] = $this -> moim_model -> load_moim('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 4; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = $this->_pagination_config($config);
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

        $data['result'] = $this->moim_model->load_moim('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('manage/moim/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }

    function _moim_upload($moim_id = null,$user_data){
        //업로드 합시다..

        $cate_list = $this->moim_model->load_category();
        if($this->input->post()){

            $form_data = $this->input->post();
            $moim_info = array(
                'user_id'=>$user_data['user_id'],
                'title'=>$form_data['title'],
                'url_name'=>$form_data['url_name'],
                'category_id'=>$form_data['category_id'],
                'description'=>$form_data['description'],
                'district'=>$form_data['district'],

            );
            if($moim_id){ //수정

                $this->moim_model->update_moim($moim_id,$moim_info);
                alert('수정되었습니다.','/moim/'.$form_data['url_name']);
            }else{//등록
                $moim_info['crt_date'] = date('Y-m-d H:i:s');
                $this->moim_model->insert_moim($moim_info);
                alert('모임이 생성되었습니다.','/moim/'.$form_data['url_name']);
            }
        }else{
            $moim_info = array(
                'title'=>null,
                'url_name'=>null,
                'category_id'=>null,
                'description'=>null,
                'district'=>null,

            );
            if($moim_id){//이거면 수정
                $moim_info =   $this->moim_model->get_moim_info($moim_id);
            }
            $this->layout->view('/manage/moim/upload', array('user' => $user_data,'moim_info'=>$moim_info,'cate_list'=>$cate_list));
        }

    }

    function _moim_detail($moim_id,$user_data){ //detail - 정보

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );
        $moim_info = $this->moim_model->get_moim_info($moim_id);

        if($moim_info['user_id']!=$user_id||$level<9){ //관리자이거나 본인만 지울 수 있다.

            $app_list = $this->application_model->load_moim_application($moim_info['moim_id']);
            $partner_list = $this->partner_model->load_moim_partner($moim_info['moim_id']);
            $this->layout->view('manage/moim/detail', array('user'=>$user_data,'moim_info'=>$moim_info,'app_list'=>$app_list,'partner_list'=>$partner_list));

        }else{
            alert('권한이 없습니다. [MD01]');
        }
    }

    function _moim_delete($moim_id,$user_data){ //unique_id!=moin_id
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];

        $moim_info = $this->moim_model->get_moim_info($moim_id);

        //이 전에 조건을 걸어둬야겠지.. 제출된 게 있으면 절대 못함
        if($moim_info['user_id']!=$user_id&&$level<9){ //관리자이거나 본인만 지울 수 있다.

             $this->moim_model->delete_moim($moim_id);
             alert('삭제되었습니다.','/manage');
        }else{
            alert('권한이 없습니다. [MD02]');
        }


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
        $config = $this->_pagination_config($config);
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
        $cate_list = $this->moim_model->load_category();
        if($this->input->post()){

            $form_data = $this->input->post();
            if($form_data['moim_id']){

                $moim_info = $this->moim_model->get_moim_info($form_data['moim_id']);
                $app_data = array(
                    'user_id'=>$user_data['user_id'],
                    'moim_id'=>$form_data['moim_id'],
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
                    $redirect_url = '/moim/'.$moim_info['url_name'].'/view/'.$app_id;

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

                    $redirect_url = '/moim/'.$moim_info['url_name'].'/view/'.$app_id;
                    alert('지원서가 생성되었습니다.',$redirect_url);
                }
            }else{ // moim_id 없으면 생성 불가능함
                alert('모임을 꼭 선택하셔야합니다.');
            }
        }else{

            $moim_id = $this->input->get('moim');
            $moim_info = null; //일단 null로 지정한다..
            if($moim_id!=null){
                $moim_info = $this->moim_model->get_moim_info($moim_id);
            }

            //내가 만든 모든 모임 불러오기
            $search_query = array(
                'crt_date' => '',
                'search' => '',
                'user_id'=>$user_data['user_id']
            );
            $moim_list = $this->moim_model->load_moim('', '', '', $search_query);
            if($moim_list==null){
                alert('지원서를 만들기 위해서는 모임을 먼저 만드셔야 합니다. 모임 생성 페이지로 이동합니다.','/manage/moim/upload');
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
                $this->layout->view('/manage/application/upload', array('user' => $user_data,'app_info'=>$app_info,'cate_list'=>$cate_list,'moim_info'=>$moim_info,'moim_list'=>$moim_list,'moim_id'=>$moim_id));
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
        $config = $this->_pagination_config($config);
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
        $config['base_url'] = '/manage/after/lists'; // 페이징 주소
        $config['total_rows'] = $this -> after_model -> load_after('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 4; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = $this->_pagination_config($config);
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
            }else{ // moim_id 없으면 생성 불가능함
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


    function partner($type='lists', $partner_id=null){

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
                $moim_id = $this->input->get('moim');
                $this->_partner_upload($moim_id,$user_data);
                break;
            case 'detail':
                $this->_partner_detail($partner_id,$user_data);
                break;

            case 'delete':
                $this->_partner_delete($partner_id,$user_data);
                break;
            default:
            case 'lists':
                //$partner_id == $moim_id임..
                $this->_partner_lists($partner_id,$user_data);
                break;
        }
    }

    function _partner_lists($moim_id, $user_data){

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
        $config['base_url'] = '/manage/partner/lists'; // 페이징 주소
        $config['total_rows'] = $this -> partner_model -> load_partner('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 4; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = $this->_pagination_config($config);
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

        $data['result'] = $this->partner_model->load_partner('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $moim_info =   $this->moim_model->get_moim_info($moim_id);
        $this->layout->view('manage/partner/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query,'moim_info'=>$moim_info));

    }

    function _partner_upload($moim_id = null,$user_data){
        //업로드 합시다..

        if($this->input->post('user_id')!=null){ //여기에는 수정이 있을 수 없음.. 그냥 지우면 됨

            $form_data = $this->input->post();
            $partner_info = array(
                'user_id'=>$form_data['user_id'],
                'moim_id'=>$form_data['moim_id'],
                'crt_date'=>date('Y-m-d H:i:s')
            );
            $this->partner_model->insert_partner($partner_info);
            //이 사람의 레벨을 3으로 지정
            $level_info = array(
                'level'=>3,
            );
            $this->user_model->update_users($form_data['user_id'],$level_info);

            alert('파트너가 등록되었습니다.','/manage/partner/lists/'.$form_data['moim_id']);
        }else{

            $moim_info =   $this->moim_model->get_moim_info($moim_id);
            $this->layout->view('/manage/partner/upload', array('user' => $user_data,'moim_info'=>$moim_info));
        }

    }

    function _partner_detail($partner_id,$user_data){ //detail - 정보

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );
        $partner_info = $this->partner_model->get_partner_info($partner_id);
        $moim_info =   $this->moim_model->get_moim_info($partner_info['moim_id']);

        if($moim_info['user_id']!=$user_id||$level<9){ //관리자이거나 본인만 지울 수 있다.
            $this->layout->view('manage/partner/detail', array('user'=>$user_data,'partner_info'=>$partner_info,'moim_info'=>$moim_info));

        }else{
            alert('권한이 없습니다. [MD01]');
        }
    }

    function _partner_delete($partner_id,$user_data){ //unique_id!=moin_id
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];

        $partner_info = $this->partner_model->get_partner_info($partner_id);
        $moim_info =   $this->moim_model->get_moim_info($partner_info['moim_id']);

        //이 전에 조건을 걸어둬야겠지.. 제출된 게 있으면 절대 못함
        if($moim_info['user_id']!=$user_id||$level<9){ //관리자이거나 본인만 지울 수 있다.

            $this->partner_model->delete_partner($partner_id);
            //이 사람의 레벨을 1 로 지정
            $level_info = array(
                'level'=>1,
            );
            $this->user_model->update_users($partner_info['user_id'],$level_info);

            alert('파트너에서 제외되었습니다.','/manage/partner/lists/'.$moim_info['moim_id']);
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


}
