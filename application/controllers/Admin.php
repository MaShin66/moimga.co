<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();

        $this->load->library('tank_auth');
        $this->load->library('layout', 'layouts/admin_layout');
        $this->layout->setLayout("layouts/admin_layout");

    }


    public function index()
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level,
            'alarm' =>$alarm_cnt,
            'username' =>$this->data['username'],
        );
        $this->layout->view('admin/main', array('user' => $user_data));
    }

    function faq($type = 'list',$faq_id=null)
    {

        $this->load->model(array('faq_model'));

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level,
            'alarm' =>$alarm_cnt,
            'username' =>$this->data['username'],
        );
        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_faq_list($user_data);
                        break;
                    case 'detail':
                        $this->_faq_detail($faq_id,$user_data);
                        break;
                    case 'upload':
                        $faq_id = $this->uri->segment(4);
                        $this->_faq_upload($faq_id,$user_data);
                        break;
                    case 'delete':
                        $faq_id = $this->input->post('faq_id');
                        $this->_faq_delete($faq_id);
                        break;
                    default:
                        $this->_faq_list($user_data);
                        break;
                }

            }

        }
    }
    function _faq_list($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'search' => null,
                'crt_date' =>null,
                'category'=>null, //category_id
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_category = $this->input->get('category');

            $search_query = array(
                'search' => $sort_search,
                'crt_date' => $sort_date,
                'category'=>$sort_category,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&category='.$search_query['category'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/faq/lists'; // 페이징 주소
        $config['total_rows'] = $this -> faq_model -> load_faq('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->faq_model->load_faq('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];
        $cate_list =  $this->faq_model->load_faq_category_plain();

        $this->layout->view('admin/faq/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query,'cate_list'=>$cate_list));

    }
    function _faq_detail($faq_id, $user_data){
        $faq_info = $this->faq_model->get_faq_info($faq_id);

        $this->layout->view('admin/faq/detail', array('user'=>$user_data,'faq_info'=>$faq_info));
    }

    
    function _faq_upload($faq_id, $user_data){
        $title = $this->input->post('title');

        $write_type = $this->input->post('write_type');
        if(!is_null($title)){ //쓰기 프로세스

            $contents = $this->input->post('contents');
            $category_id = $this->input->post('faq_category_id');
            $order = $this->input->post('order');

            $faq_data = array(
                'title' =>$title,
                'contents'=>nl2br($contents),
                'faq_category_id'=>$category_id,
                'order'=>$order
            );
            if($write_type=='new'){
                $faq_data['hit']=0;
                $faq_data['crt_date'] = date('Y-m-d H:i:s');
                $this->faq_model->insert_faq($faq_data);
                alert('자주 묻는 질문이 등록되었습니다.','/admin/faq');
            }else{ //modify
                $this->faq_model->update_faq($this->input->post('faq_id'), $faq_data);
                alert('자주 묻는 질문이 수정되었습니다.','/admin/faq');
            }


        }else{ //글쓰기 페이지

            $search_query = array(
                'search' => null,
                'crt_date' => null,
                'category'=>null,
            );


            $cate_list = $this->faq_model->load_faq_category('','','',$search_query);
            if(!is_null($faq_id)){
                $data = $this->faq_model->get_faq_info($faq_id);
                $data['submit_txt'] = '수정';
                $data['write_type'] = 'modify';
            }else{
                $data  = array(
                    'faq_id'=>null,
                    'faq_category_id'=>null,
                    'title'=>null,
                    'contents'=>null,
                    'order'=>1,
                    'submit_txt'=>'등록',
                    'write_type'=>'new',
                );
            }


            $this->layout->view('admin/faq/upload', array('user'=>$user_data,'data'=>$data,'cate_list'=>$cate_list));
        }

    }

    function _faq_delete($faq_id){

        $this->faq_model->delete_faq($faq_id); //진짜 삭제
        alert('이 자주 묻는 질문이 삭제되었습니다.','/admin/faq');

    }


    function deleted($type = 'list',$deleted_id=null)
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_deleted_list($user_data);
                        break;
                    case 'detail':
                        $this->_deleted_view($deleted_id,$user_data);
                        break;

                    case 'terminate':
                        $deleted_id = $this->input->post('team_delete_id');
                        $this->_deleted_terminate($deleted_id);
                        break;
                    case 'recover':
                        $deleted_id = $this->input->post('team_delete_id');
                        $this->_deleted_recover($deleted_id);
                        break;
                    default:
                        $this->_deleted_list($user_data);
                        break;
                }

            }

        }
    }

    function _deleted_list($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'search' => null,
                'crt_date' =>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');

            $search_query = array(
                'search' => $sort_search,
                'crt_date' => $sort_date,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/deleted/lists'; // 페이징 주소
        $config['total_rows'] = $this -> team_model -> load_team_delete('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->team_model->load_team_delete('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/deleted/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }

    function _deleted_view($deleted_id,$user_data){

        $data = $this->team_model->get_team_delete_info($deleted_id);
        $this->layout->view('admin/deleted/detail', array('user' => $user_data, 'data' => $data));

    }
    function _deleted_recover($deleted_id){

        $data = $this->team_model->get_team_delete_info($deleted_id);

        //url 중복 확인
        $dup_url = $this->team_model->get_team_info_by_url($data['url']);
        if(!is_null($dup_url)){ //있으면
            $data['url'] = generate_random_code(6);
        }

        $data = array(
            'user_id'=>$data['user_id'],
            'url'=>$data['url'],
            'title'=>$data['title'],
            'name'=>$data['name'],
            'contents'=>$data['contents'],
            'status'=>'on', //공개 여부
            'thumb_url'=>$data['thumb_url'],
            'subscribe_count'=>0,
            'hit'=>0,
            'crt_date'=>date('Y-m-d H:i:s'),
        );

        $team_id = $this->team_model->insert_team($data);

        //team_member에 나도 쓰기
        $member_info = array(
            'user_id'=>$data['user_id'],
            'team_id'=>$team_id,
            'type'=>1,
            'crt_date'=>date('Y-m-d H:i:s')
        );
        $this->member_model->insert_team_member($member_info);

        //recover됐다고 쓰기
        $recover_data = array(
            'is_recovered'=>1, //1이면 복구됨, 0이면 아님
        );

        $this->team_model->update_team_delete($deleted_id,$recover_data);

        alert('복구되었습니다.');

    }

    function _deleted_terminate($deleted_id){

        $this->team_model->delete_team_delete($deleted_id);

        alert('완전히 삭제되었습니다.');

    }
    function team($type = 'list',$team_id=null)
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_team_list($user_data);
                        break;
                    case 'detail':
                        $this->_team_detail($team_id,$user_data);
                        break;
                    case 'delete':
                        $this->_team_delete();
                        break;
                    default:
                        $this->_team_list($user_data);
                        break;
                }

            }

        }
    }


    function _team_list($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'search' => null,
                'status' =>null,
                'crt_date' =>null,
                'user_id' => null,
                'subscribe'=>null,
                'after'=>null,
                'login_user'=>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_status = $this->input->get('status');
            $sort_user_id = $this->input->get('user_id');
            $sort_subscribe = $this->input->get('subscribe');
            $sort_after = $this->input->get('after');

            $search_query = array(
                'search' => $sort_search,
                'status' => $sort_status,
                'crt_date' => $sort_date,
                'user_id' => $sort_user_id,
                'subscribe'=>$sort_subscribe,
                'after'=>$sort_after,
                'login_user'=>null,
            );

        }
//        print_r($search_query);
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&user_id='.$search_query['user_id']
            .'&status='.$search_query['status'].'&subscribe='.$search_query['subscribe'].'&after='.$search_query['after'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/team/lists'; // 페이징 주소
        $config['total_rows'] = $this -> team_model -> load_team('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->team_model->load_team('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/team/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }
    function _team_detail($team_id, $user_data){
        $team_info = $this->team_model->get_team_info($team_id);

        $search_query = array( //둘다 동일한 search_query
            'crt_date' => '',
            'search'=>null,
            'user_id'=>null,//load_after 때문에
            'status'=>null, //무조건 공개
            'team_id'=>$team_id,
            'type'=>null,// $member_list 때문에
            'event'=>null, //program
            'price'=>null, //program
        );
        $member_list = $this->member_model->load_team_member('','','',$search_query);
        $program_list =  $this->program_model->load_program('','','',$search_query);
        $blog_list =  $this->team_model->load_team_blog('','','',$search_query);
        $after_list =  $this->after_model->load_after('','','',$search_query);
        $this->layout->view('admin/team/detail', array('user'=>$user_data,'team_info'=>$team_info,
            'blog_list'=>$blog_list,'member_list'=>$member_list,'program_list'=>$program_list,'after_list'=>$after_list));
    }

    function _team_delete(){
        $team_id = $this->input->post('team_id');
        //team 복사해서 team_delete에 넣어둔다

        $team_info = $this->team_model->get_team_info($team_id);
        $delete_info = array(
            'org_team_id'=>$team_info['team_id'],
            'user_id'=>$team_info['user_id'],
            'url'=>$team_info['url'],
            'name'=>$team_info['name'],
            'title'=>$team_info['title'],
            'contents'=>$team_info['contents'],
            'thumb_url'=>$team_info['thumb_url'],
            'crt_date'=>$team_info['crt_date'],
            'delete_date'=>date('Y-m-d H:i:s'),

        );
        $this->team_model->insert_team_delete($delete_info); //삭제된 팀 여기로 복사

        //구독
        $this->subscribe_model->delete_team_subscribe($team_id); //구독도 지운다 //team id로 구독된 '모든' 구독 전부 지운다

        //팀멤버 삭제
        $this->member_model->delete_team_member_by_team_id($team_id);

        //팀 포스트 삭제
        $this->team_model->delete_team_blog_by_team_id($team_id);

        //하위 프로그램 삭제
        //프로그램 아이디 가져온다 ..

        $program_list = $this->program_model->load_program_by_team_id($team_id);
        foreach ($program_list as $key=>$item){
            $this->_program_delete_unit($item['program_id']);
        }
        $this->team_model->delete_team($team_id); //진짜 삭제 (관리자에서 복구 가능)

        alert('팀과 하위 프로그램이 삭제되었습니다.','/admin/team');

    }
    function contents($type = 'list')
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_contents_list($user_data);
                        break;
                    case 'delete':
                        $contents_id = $this->input->post('contents_id');
                        $this->_contents_delete($contents_id);
                        break;
                    default:
                        $this->_contents_list($user_data);
                        break;
                }

            }

        }
    }
    function _contents_list($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'search' => null,
                'status' =>null,
                'crt_date' =>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_status = $this->input->get('status');

            $search_query = array(
                'search' => $sort_search,
                'status' => $sort_status,
                'crt_date' => $sort_date,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&status='.$search_query['status'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/contents/lists'; // 페이징 주소
        $config['total_rows'] = $this -> contents_model -> load_contents('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->contents_model->load_contents('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/contents/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }

    function _contents_delete($contents_id){
        $this->contents_model->delete_contents($contents_id); //진짜 삭제

        alert('포스트가 삭제되었습니다.','/admin/contents');

    }

    function contents_category($type = 'list',$category_id=null)
    {
        $this->load->model(array('contents_model'));
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_contents_category_list($user_data);
                        break;
                    case 'upload':
                        $category_id = $this->uri->segment(4);
                        $this->_contents_category_upload($category_id,$user_data);
                        break;
                    case 'delete':
                        $category_id = $this->input->post('contents_category_id');
                        $this->_contents_category_delete($category_id);
                        break;
                    default:
                        $this->_contents_category_list($user_data);
                        break;
                }

            }

        }
    }

    function _contents_category_list($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'search' => null,
                'crt_date' =>null,
                'category'=>null, //category_id
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_category = $this->input->get('category');

            $search_query = array(
                'search' => $sort_search,
                'crt_date' => $sort_date,
                'category'=>$sort_category,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&category='.$search_query['category'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/contents_category/lists'; // 페이징 주소
        $config['total_rows'] = $this -> contents_model -> load_contents_category('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->contents_model->load_contents_category('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/contents/category/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }
    function _contents_category_upload($contents_category_id, $user_data){
        $title = $this->input->post('title');
        $write_type = $this->input->post('write_type');
        if(!is_null($title)){ //쓰기 프로세스
            $order = $this->input->post('order');
            $desc = $this->input->post('desc');
            $cate_data = array(
                'title'=>$title,
                'desc'=>$desc,
                'order'=>$order
            );
            if($write_type=='new'){
                $cate_data['crt_date'] = date('Y-m-d H:i:s');
                $this->contents_model->insert_contents_category($cate_data);
                alert('카테고리가 등록되었습니다.','/admin/contents_category');
            }else{ //modify
                $this->contents_model->update_contents_category($this->input->post('contents_category_id'), $cate_data);
                alert('카테고리가 수정되었습니다.','/admin/contents_category');
            }


        }else{ //글쓰기 페이지
            if(!is_null($contents_category_id)){
                $data = $this->contents_model->get_contents_category_info($contents_category_id);
                $data['submit_txt'] = '수정';
                $data['write_type'] = 'modify';
            }else{
                $data  = array(
                    'contents_category_id'=>null,
                    'title'=>null,
                    'desc'=>null,
                    'order'=>null,
                    'submit_txt'=>'등록',
                    'write_type'=>'new',
                );
            }


            $this->layout->view('admin/contents/category/upload', array('user'=>$user_data,'data'=>$data));
        }

    }

    function _contents_category_delete($contents_category_id){

        $this->contents_model->delete_contents_category($contents_category_id); //진짜 삭제
        alert('이 카테고리가 삭제되었습니다.','/admin/contents_category');

    }


    function store($type = 'list')
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_store_list($user_data);
                        break;
                    case 'delete':
                        $store_id = $this->input->post('store_id');
                        $this->_store_delete($store_id);
                        break;
                    default:
                        $this->_store_list($user_data);
                        break;
                }

            }

        }
    }
    function _store_list($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'search' => null,
                'status' =>null,
                'crt_date' =>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_status = $this->input->get('status');

            $search_query = array(
                'search' => $sort_search,
                'status' => $sort_status,
                'crt_date' => $sort_date,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&status='.$search_query['status'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/store/lists'; // 페이징 주소
        $config['total_rows'] = $this -> store_model -> load_store('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->store_model->load_store('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/store/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }

    function _store_delete($store_id){
        $this->store_model->delete_store($store_id); //진짜 삭제

        alert('포스트가 삭제되었습니다.','/admin/store');

    }

    function store_category($type = 'list',$category_id=null)
    {
        $this->load->model(array('store_model'));
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_store_category_list($user_data);
                        break;
                    case 'upload':
                        $category_id = $this->uri->segment(4);
                        $this->_store_category_upload($category_id,$user_data);
                        break;
                    case 'delete':
                        $category_id = $this->input->post('store_category_id');
                        $this->_store_category_delete($category_id);
                        break;
                    default:
                        $this->_store_category_list($user_data);
                        break;
                }

            }

        }
    }

    function _store_category_list($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'search' => null,
                'crt_date' =>null,
                'category'=>null, //category_id
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_category = $this->input->get('category');

            $search_query = array(
                'search' => $sort_search,
                'crt_date' => $sort_date,
                'category'=>$sort_category,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&category='.$search_query['category'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/store_category/lists'; // 페이징 주소
        $config['total_rows'] = $this -> store_model -> load_store_category('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->store_model->load_store_category('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/store/category/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }
    function _store_category_upload($store_category_id, $user_data){
        $title = $this->input->post('title');
        $write_type = $this->input->post('write_type');
        if(!is_null($title)){ //쓰기 프로세스
            $order = $this->input->post('order');
            $desc = $this->input->post('desc');
            $cate_data = array(
                'title' =>$title,
                'desc'=>$desc,
                'order'=>$order
            );
            if($write_type=='new'){
                $cate_data['crt_date'] = date('Y-m-d H:i:s');
                $this->store_model->insert_store_category($cate_data);
                alert('카테고리가 등록되었습니다.','/admin/store_category');
            }else{ //modify
                $this->store_model->update_store_category($this->input->post('store_category_id'), $cate_data);
                alert('카테고리가 수정되었습니다.','/admin/store_category');
            }


        }else{ //글쓰기 페이지
            if(!is_null($store_category_id)){
                $data = $this->store_model->get_store_category_info($store_category_id);
                $data['submit_txt'] = '수정';
                $data['write_type'] = 'modify';
            }else{
                $data  = array(
                    'store_category_id'=>null,
                    'title'=>null,
                    'desc'=>null,
                    'order'=>null,
                    'submit_txt'=>'등록',
                    'write_type'=>'new',
                );
            }


            $this->layout->view('admin/store/category/upload', array('user'=>$user_data,'data'=>$data));
        }

    }

    function _store_category_delete($store_category_id){

        $this->store_model->delete_store_category($store_category_id); //진짜 삭제
        alert('이 카테고리가 삭제되었습니다.','/admin/store_category');

    }

    function main($type = 'list',$main_id=null)
    {
        $this->load->model(array('contents_model'));
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_main_list($user_data);
                        break;
                    case 'upload':
                        $main_id = $this->uri->segment(4);
                        $this->_main_upload($main_id,$user_data);
                        break;
                    case 'delete':
                        $main_id = $this->input->post('main_id');
                        $this->_main_delete($main_id);
                        break;
                    default:
                        $this->_main_list($user_data);
                        break;
                }

            }

        }
    }

    function _main_list($user_data){
        $search = $this->uri->segment(5);

        $this->load->library('pagination');
        $config['base_url'] = '/admin/main/lists'; // 페이징 주소
        $config['total_rows'] = $this -> main_model -> load_main('count','',''); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 4; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'; // 첫페이지에 query string 에러나서..
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

        $data['result'] = $this->main_model->load_main('', $start, $limit);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/main/lists', array('user' => $user_data, 'data' => $data));

    }
    function _main_upload($main_id, $user_data){
        $store_cate_1 = $this->input->post('store_cate_1');
        $write_type = $this->input->post('write_type');
        if(!is_null($store_cate_1)){ //쓰기 프로세스
            $store_cate_2 = $this->input->post('store_cate_2');
            $contents_cate_1 = $this->input->post('contents_cate_1');
            $contents_cate_2 = $this->input->post('contents_cate_2');
            $cate_data = array(
                'store_cate_1'=>$store_cate_1,
                'store_cate_2'=>$store_cate_2,
                'contents_cate_1'=>$contents_cate_1,
                'contents_cate_2'=>$contents_cate_2,
            );
            if($write_type=='new'){
                $cate_data['crt_date'] = date('Y-m-d H:i:s');
                
                $cate_data['store_thumb_1'] = null;
                $cate_data['store_thumb_2'] = null;
                $cate_data['contents_thumb_1'] = null;
                $cate_data['contents_thumb_2'] = null;

                $main_id = $this->main_model->insert_main($cate_data);
            }else{ //modify
                $main_id = $this->input->post('main_id');
                $main_info = $this->main_model->get_main_info($main_id);
                $this->main_model->update_main($this->input->post('main_id'), $cate_data);
            }

            //thumb 업데이트..

            $thumbs_store_1['store_thumb_1'] = thumbs_upload('main', $main_id.'_s1','horizontal','store_thumb_1'); // 바로 업데이트
            if(!is_null($thumbs_store_1['store_thumb_1'] )){ //파일을 업로드 했다는 뜻

                if($write_type=='modify'){  //만약 type== modify 면 이전의 파일을 지운다.
                    if(!is_null($main_info['store_thumb_1'])){
                        unlink(FCPATH . $main_info['store_thumb_1']);
                    }

                }
                $this->main_model->update_main($main_id,$thumbs_store_1);
            }

            $thumbs_store_2['store_thumb_2'] = thumbs_upload('main', $main_id.'_s2','horizontal','store_thumb_2'); // 바로 업데이트
            if(!is_null($thumbs_store_2['store_thumb_2'] )){ //파일을 업로드 했다는 뜻

                if($write_type=='modify'){  //만약 type== modify 면 이전의 파일을 지운다.
                    if(!is_null($main_info['store_thumb_1'])){
                        unlink(FCPATH . $main_info['store_thumb_2']);
                    }
                }
                $this->main_model->update_main($main_id,$thumbs_store_2);
            }
            $thumbs_cont_1['contents_thumb_1'] = thumbs_upload('main', $main_id.'_c1','horizontal','contents_thumb_1'); // 바로 업데이트
            if(!is_null($thumbs_cont_1['contents_thumb_1'] )){ //파일을 업로드 했다는 뜻

                if($write_type=='modify'){  //만약 type== modify 면 이전의 파일을 지운다.
                    if(!is_null($main_info['store_thumb_1'])){
                        unlink(FCPATH . $main_info['contents_thumb_1']);
                    }
                }
                $this->main_model->update_main($main_id,$thumbs_cont_1);
            }
            $thumbs_cont_2['contents_thumb_2'] = thumbs_upload('main', $main_id.'_c2','horizontal','contents_thumb_2'); // 바로 업데이트
            if(!is_null($thumbs_cont_2['contents_thumb_2'] )){ //파일을 업로드 했다는 뜻

                if($write_type=='modify'){  //만약 type== modify 면 이전의 파일을 지운다.
                    if(!is_null($main_info['store_thumb_1'])){
                        unlink(FCPATH . $main_info['contents_thumb_2']);
                    }
                }
                $this->main_model->update_main($main_id,$thumbs_cont_2);
            }

            redirect('/admin/main'); //이동

        }else{ //글쓰기 페이지
            if(!is_null($main_id)){
                $data = $this->main_model->get_main_info($main_id);
                $data['submit_txt'] = '수정';
                $data['write_type'] = 'modify';
            }else{
                $data  = array(
                    'main_id'=>null,
                    
                    'store_cate_1'=>null,
                    'store_cate_2'=>null,
                    'contents_cate_1'=>null,
                    'contents_cate_2'=>null,

                    'store_thumb_1'=>null,
                    'store_thumb_2'=>null,
                    'contents_thumb_1'=>null,
                    'contents_thumb_2'=>null,

                    'submit_txt'=>'등록',
                    'write_type'=>'new',
                );
            }

            $store_cate_list=$this->store_model->load_store_category_plain();
            $contents_cate_list=$this->contents_model->load_contents_category_plain();

            $this->layout->view('admin/main/upload', array('user'=>$user_data,'data'=>$data,'store_cate_list'=>$store_cate_list,'contents_cate_list'=>$contents_cate_list));
        }

    }

    function _main_delete($main_id){

        $this->main_model->delete_main($main_id); //진짜 삭제
        alert('이 메인이 삭제되었습니다.','/admin/main');

    }
    function team_blog($type = 'list',$team_blog_id=null)
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_team_blog_list($user_data);
                        break;
                    case 'detail':
                        $this->_team_blog_detail($team_blog_id,$user_data);
                        break;
                    case 'delete':
                        $team_blog_id = $this->input->post('team_blog_id');
                        $this->_team_blog_delete($team_blog_id);
                        break;
                    default:
                        $this->_team_blog_list($user_data);
                        break;
                }

            }

        }
    }

    function _team_blog_list($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'search' => null,
                'status' =>null,
                'crt_date' =>null,
                'team_id'=>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_status = $this->input->get('status');
            $sort_team_id = $this->input->get('team_id');

            $search_query = array(
                'search' => $sort_search,
                'status' => $sort_status,
                'crt_date' => $sort_date,
                'team_id'=>$sort_team_id,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&status='.$search_query['status'].'&team_id='.$search_query['team_id'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/team_blog/lists'; // 페이징 주소
        $config['total_rows'] = $this -> team_model -> load_team_blog('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->team_model->load_team_blog('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/team_blog/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }
    function _team_blog_detail($team_blog_id, $user_data){
        $team_blog_info = $this->team_model->get_team_blog_info($team_blog_id);
        $team_info = $this->team_model->get_team_info($team_blog_info['team_id']);

        $this->layout->view('admin/team_blog/detail', array('user'=>$user_data,'data'=>$team_blog_info,'team_info'=>$team_info));
    }

    function _team_blog_delete($team_blog_id){
        $this->team_model->delete_team_blog($team_blog_id); //진짜 삭제 (관리자에서 복구 가능)

        alert('이 포스팅이 삭제되었습니다.','/admin/team_blog');

    }
    function member($type = 'list',$member_id=null)
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_member_list($user_data);
                        break;
                    case 'delete':
                        $member_id = $this->input->post('member_id');
                        $this->_member_delete($member_id);
                        break;
                    default:
                        $this->_member_list($user_data);
                        break;
                }

            }

        }
    }

    function _member_list($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'search' => null,
                'type' =>null,
                'crt_date' =>null,
                'user_id'=>null, // 특정 멤버의 소속팀 확인
                'team_id'=>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_type = $this->input->get('type');
            $sort_team_id = $this->input->get('team_id');
            $sort_user_id = $this->input->get('user_id');

            $search_query = array(
                'search' => $sort_search,
                'type' => $sort_type,
                'crt_date' => $sort_date,
                'user_id'=>$sort_user_id,
                'team_id'=>$sort_team_id,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&type='.$search_query['type'].'&team_id='.$search_query['team_id'].'&user_id='.$search_query['user_id'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/member/lists'; // 페이징 주소
        $config['total_rows'] = $this -> member_model -> load_team_member('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->member_model->load_team_member('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/member/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }

    function _member_delete($member_id){

        $this->member_model->delete_member($member_id); //진짜 삭제 (관리자에서 복구 가능)

        alert('이 포스팅이 삭제되었습니다.','/admin/member');

    }


    function subscribe($type = 'list',$subscribe_id=null)
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_subscribe_list($user_data);
                        break;
                    case 'delete':
                        $subscribe_id = $this->input->post('subscribe_id');
                        $this->_subscribe_delete($subscribe_id);
                        break;
                    default:
                        $this->_subscribe_list($user_data);
                        break;
                }

            }

        }
    }

    function _subscribe_list($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'search' => null,
                'type' =>null,
                'crt_date' =>null,
                'user_id'=>null, // 특정 멤버의 소속팀 확인
                'team_id'=>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_type = $this->input->get('type');
            $sort_team_id = $this->input->get('team_id');
            $sort_user_id = $this->input->get('user_id');

            $search_query = array(
                'search' => $sort_search,
                'type' => $sort_type,
                'crt_date' => $sort_date,
                'user_id'=>$sort_user_id,
                'team_id'=>$sort_team_id,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&type='.$search_query['type'].'&team_id='.$search_query['team_id'].'&user_id='.$search_query['user_id'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/subscribe/lists'; // 페이징 주소
        $config['total_rows'] = $this -> subscribe_model -> load_subscribe('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->subscribe_model->load_subscribe('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/subscribe/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }

    function _subscribe_delete($subscribe_id){

        $this->subscribe_model->delete_subscribe($subscribe_id); //진짜 삭제 (관리자에서 복구 가능)

        alert('이 포스팅이 삭제되었습니다.','/admin/subscribe');

    }

    
    function faq_category($type = 'list',$category_id=null)
    {
        $this->load->model(array('faq_model'));
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_faq_category_list($user_data);
                        break;
                    case 'upload':
                        $category_id = $this->uri->segment(4);
                        $this->_faq_category_upload($category_id,$user_data);
                        break;
                    case 'delete':
                        $category_id = $this->input->post('faq_category_id');
                        $this->_faq_category_delete($category_id);
                        break;
                    default:
                        $this->_faq_category_list($user_data);
                        break;
                }

            }

        }
    }

    function _faq_category_list($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'search' => null,
                'crt_date' =>null,
                'category'=>null, //category_id
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_category = $this->input->get('category');

            $search_query = array(
                'search' => $sort_search,
                'crt_date' => $sort_date,
                'category'=>$sort_category,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&category='.$search_query['category'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/faq_category/lists'; // 페이징 주소
        $config['total_rows'] = $this -> faq_model -> load_faq_category('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->faq_model->load_faq_category('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/faq/category/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }
    function _faq_category_upload($faq_category_id, $user_data){
        $name = $this->input->post('name');
        $write_type = $this->input->post('write_type');
        if(!is_null($name)){ //쓰기 프로세스
            $url_name = $this->input->post('url_name');
            $order = $this->input->post('order');
            $cate_data = array(
                'name' =>$name,
                'url_name'=>$url_name,
                'order'=>$order
            );
            if($write_type=='new'){
                $cate_data['crt_date'] = date('Y-m-d H:i:s');
                $this->faq_model->insert_faq_category($cate_data);
                alert('카테고리가 등록되었습니다.','/admin/faq_category');
            }else{ //modify
                $this->faq_model->update_faq_category($this->input->post('faq_category_id'), $cate_data);
                alert('카테고리가 수정되었습니다.','/admin/faq_category');
            }


        }else{ //글쓰기 페이지
            if(!is_null($faq_category_id)){
                $data = $this->faq_model->get_faq_category_info($faq_category_id);
                $data['submit_txt'] = '수정';
                $data['write_type'] = 'modify';
            }else{
                $data  = array(
                    'faq_category_id'=>null,
                    'name'=>null,
                    'url_name'=>null,
                    'order'=>null,
                    'submit_txt'=>'등록',
                    'write_type'=>'new',
                );
            }


            $this->layout->view('admin/faq/category/upload', array('user'=>$user_data,'data'=>$data));
        }

    }

    function _faq_category_delete($faq_category_id){

        $this->faq_model->delete_faq_category($faq_category_id); //진짜 삭제
        alert('이 카테고리가 삭제되었습니다.','/admin/faq_category');

    }

    function users($type = 'list',$this_user_id=null)
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_user_list($user_data);
                        break;
                    case 'detail':
                        $this->_user_detail($this_user_id,$user_data);
                        break;
                    case 'level':
                        $this_user_id = $this->input->post('user_id');
                        $this->_user_level($this_user_id);
                        break;
                    case 'agree':
                        $status = $this->input->get('status');
                        $this->_user_agree($this_user_id,$status);
                        break;
                    case 'adult':
                        $adult = $this->input->get('adult');
                        $this->_user_adult($this_user_id,$adult);
                        break;
                    case 'verify':
                        $this->_user_verify($this_user_id);
                        break;
                    case 'disprove':

                        $this->_user_disprove($this_user_id);
                        break;

                    case 'drop': //탈퇴

                        $this_user_id = $this->input->post('user_id');
                        $this->_user_drop($this_user_id);
                        break;
                    default:
                        $this->_user_list($user_data);
                        break;
                }

            }

        }
    }
    function program($type = 'list',$program_id=null)
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_program_list($user_data);
                        break;
                    case 'detail':
                        $this->_program_detail($program_id,$user_data);
                        break;
                    case 'delete':
                        $program_id = $this->input->post('program_id');
                        $this->_program_delete($program_id);
                        break;
                    default:
                        $this->_program_list($user_data);
                        break;
                }

            }

        }
    }

    function _program_list($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'search' => null,
                'status' =>null,
                'crt_date' =>null,
                'team_id'=>null,
                'price'=>null,
                'user_id'=>null,
                'event'=>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_status = $this->input->get('status');
            $sort_team_id = $this->input->get('team_id');
            $sort_user_id = $this->input->get('user_id');

            $sort_price = $this->input->get('price');
            $sort_event = $this->input->get('event');

            $search_query = array(
                'search' => $sort_search,
                'status' => $sort_status,
                'crt_date' => $sort_date,
                'team_id'=>$sort_team_id,
                'price'=>$sort_price,
                'user_id'=>$sort_user_id, //특정 사용자가 작성한 프로그램
                'event'=>$sort_event,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&status='.$search_query['status'].'&team_id='.$search_query['team_id'].'&price='.$search_query['price'].'&event='.$search_query['event'].'&user_id='.$search_query['user_id'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/program/lists'; // 페이징 주소
        $config['total_rows'] = $this -> program_model -> load_program('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->program_model->load_program('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/program/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }
    function _program_detail($program_id, $user_data){
        $program_info = $this->program_model->get_program_info($program_id);
        $team_info = $this->team_model->get_team_info($program_info['team_id']);

        $date_info = $this->program_model->load_program_date_info_by_p_id($program_id);
        $qna_info = $this->program_model->load_program_qna_info_by_p_id($program_id);
        $qualify_info = $this->program_model->load_program_qualify_info_by_p_id($program_id); //이것도 중복될 수 없으니까 unique 임


        $this->layout->view('admin/program/detail',
            array('user'=>$user_data,'data'=>$program_info,'team_info'=>$team_info, 'date_info'=>$date_info,
                'qna_info'=>$qna_info, 'qualify_info'=>$qualify_info));
    }

    function _program_delete($program_id){
        $this->_program_delete_unit($program_id);

        alert('이 프로그램이 삭제되었습니다.','/admin/program');

    }

    function download_xls($faq_id){

        $faq_info = $this->Prod_model->get_faq_info($faq_id);
        $faqs_name = explode(',',$faq_info['faq_name']);

        $delivery_name= explode(',',$faq_info['del_name']);

        $file_name = urldecode($faq_info['title']);
        //[moimga] 상품이름_폼.xls
        header( "Content-type: application/vnd.ms-excel; charset=euc-kr" );
        header( "Expires: 0" );
        header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
        header( "Pragma: no-cache" );
        header( "Content-Disposition: attachment; filename='moimga_".$file_name."_폼.xls" );

        $list = $this->Form_model->load_form($faq_id); //정보

        echo "
    <table>
    <tr>
        <td>번호</td>
        <td>입금인</td>
        <td>수령인</td>
        
        <td>이메일</td>
        <td>전화번호</td>
        <td>입금일</td>
        
        <td>주소</td>
        <td>우편번호</td>";
        foreach ($faqs_name as $key => $name_item){
            echo "<td>".$name_item."</td>";
        }

        echo "<td>입금액</td>
        <td>은행</td>
        <td>메모</td>
        
        <td>타입</td>";
        if($faq_info['online']==1){
            echo '<td>배송 방법</td>';
        }
        echo "<td>상태</td>
         <td>폼 작성 시간</td>
         </tr> "; // 테이블 상단
        $list_count = count($list);

        for($i=0; $i<$list_count; $i++) {

            $volume = explode(',',$list[$i]['volume']);
            $del_method_orig = $list[$i]['del_method'];
            if($del_method_orig==null || $del_method_orig=='') {
                $del_method = '기본';
            }else{
                $del_method = $delivery_name[$del_method_orig];
            }
            if($list[$i]['type']=='online'){
                $type = '통판';
            }else{
                $type = '현장판매';
            }
            if($list[$i]['status']=='done'){
                $status= '확인 완료';
            }else{

                $status = '대기';
            }

            echo "<tr>";
            echo "<td>".$list[$i]['form_id']."</td>";
            echo "<td>".$list[$i]['name']."</td>";
            echo "<td>".$list[$i]['rec_name']."</td>";
            echo "<td>".$list[$i]['email']."</td>";
            echo "<td style=mso-number-format:'\@'>".$list[$i]['phone']."</td>";

            echo "<td>".$list[$i]['date']."</td>";
            echo "<td>".$list[$i]['address'].' '.$list[$i]['address2']."</td>";
            echo "<td style=mso-number-format:'\@'>".$list[$i]['zipcode']."</td>";

            foreach ($volume as $key => $volume_item){
                echo "<td>".$volume_item."</td>";
            }
            echo "<td>".$list[$i]['money']."</td>";
            echo "<td>".$list[$i]['bank']."</td>";
            echo "<td>".$list[$i]['memo']."</td>";
            echo "<td>".$type."</td>";
            if($list[$i]['type']=='online'){
                echo "<td>".$del_method."</td>";
            }
            echo "<td>".$status."</td>";
            echo "<td>".$list[$i]['crt_date']."</td>";

            echo "</tr>";
        }

        echo "</table>";
    }

    function _user_list($user_data){

        $search = $this->uri->segment(5);
        if($search==null){
            $search_query = array(
                'crt_date' => null,
                'search' => null,
                'sns_type' => null,
                'level' => null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_sns_type = $this->input->get('sns_type');
            $sort_level = $this->input->get('level');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'sns_type' => $sort_sns_type,
                'level' => $sort_level,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&sns_type='.$search_query['sns_type'].'&level='.$search_query['level'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/users/lists'; // 페이징 주소
        $config['total_rows'] = $this -> admin_model -> load_users('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->admin_model->load_users('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/users/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }
    function _user_detail($user_id,$user_data){

        $result = $this->user_model->get_user_info($user_id);
        $this->layout->view('admin/users/detail', array('user' => $user_data, 'data' => $result));
    }

    function _user_level($user_id){


        $level = $this->input->post('level');
        if($level==0){
            $level = null;
        }
        $this->user_model->set_user_level($user_id,$level);

        alert('선택하신 회원의 레벨이 '.$level.'로 조정되었습니다.');
    }
    function _user_adult($user_id, $adult = 1){
        $adult_array = array(
            'user_id'=>$user_id,
            'adult'=>$adult,
        );
        $this->user_model->update_users($user_id, $adult_array);
        if($adult==1){
            alert('선택하신 회원의 성인 인증이 완료되었습니다.');
        }else if($adult==0){
            alert('선택하신 회원의 미성년자 인증이 완료되었습니다.');
        }

    }

    function _user_verify($user_id){

        //이미 있으면 수정하기

        //없으면 새로 쓰기

        $dob = $this->input->post('dob');
        if(is_null($dob)||$dob=='') alert('생년월일은 비워둘 수 없습니다.');
        $birth_year =substr($dob,0,4);

        $verify_info = $this->verify_model->get_verify_by_user_id($user_id);

        $verify_array = array(
            'user_id'=>$user_id,
            'sex'=>$this->input->post('sex'),
            'birth_year'=>$birth_year,
            'dob'=>$dob,
            'TID'=>2147483647,//이거 무조건 바꿔야된!!!! /
            'phone'=>$this->input->post('phone'),
            'CI'=>0,
            'DI'=>0,
            'success'=>1,
            'crt_date'=>date('Y-m-d H:i:s')
        );
        if($verify_info==null){ //새거

            $this->verify_model->insert_verify($verify_array);
        }else{ //수정
            $this->verify_model->update_verify($verify_info['verify_id'], $verify_array);
        }

        /*나이, 실명 - users table 에서 해야함 */
        $age =  date('Y')-$birth_year+1; // age도 설정해준다..
        if($age>=20){
            $is_adult=1;
        }else{
            $is_adult = 0;
        }
        $adult_data = array(
            'realname'=>$this->input->post('realname'), //실명도 넣는다..
            'adult'=>$is_adult,
            'verify'=>1,
        );
        $this->user_model->update_users($user_id, $adult_data);

        if($is_adult==1){
            alert('선택하신 회원의 성인, 본인 인증이 완료되었습니다.');
        }else if($is_adult==0){
            alert('선택하신 회원의 본인인증, 미성년자 인증이 완료되었습니다.');
        }

    }
    function _user_disprove($user_id){ //인증 해제

        $adult_data = array(
            'adult'=>null,
            'verify'=>null,
        );
        $this->user_model->update_users($user_id, $adult_data);
        $verify_info = $this->verify_model->get_verify_by_user_id($user_id);
        $this->verify_model->delete_verify($verify_info['verify_id']);
        alert('선택하신 회원이 인증 해제 되었습니다.');
    }



    function _user_drop($user_id){
        $this->user_model->drop_user($user_id);

    }

    function verify($type = 'list')
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    default:
                    case 'list':
                        $this->_verify_list($user_data);
                        break;
                }

            }

        }
    }

    function _verify_list($user_data){

        $search = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => null,
                'success' => null,
                'search' => null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_success = $this->input->get('success');

            $search_query = array(
                'crt_date' => $sort_date,
                'success' => $sort_success,
                'search' => $sort_search,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&success='.$search_query['success'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/verify/lists'; // 페이징 주소
        $config['total_rows'] = $this -> admin_model -> load_verify('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->admin_model->load_verify('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/verify/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }



    function after($type = 'list',$after_id=null)
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_after_list($user_data);
                        break;
                    case 'delete':
                        $after_id = $this->input->post('after_id');
                        $this->_after_delete($after_id);
                        break;
                    default:
                        $this->_after_list($user_data);
                        break;
                }

            }

        }
    }

    function _after_list($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'crt_date' => null,
                'search' => null,
                'status'=>null,
                'user_id'=>null,
                'team_id'=>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_status = $this->input->get('status');
            $sort_user_id = $this->input->get('user_id');
            $sort_team_id = $this->input->get('team_id');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'status'=>$sort_status,
                'user_id'=>$sort_user_id,
                'team_id'=>$sort_team_id,
            );

        }

        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&user_id='.$search_query['user_id'].'&status='.$search_query['status'].'&team_id='.$search_query['team_id'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/after/lists'; // 페이징 주소
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

        $this->layout->view('admin/after/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }


    function _after_delete($after_id){
        $this->after_model->delete_after($after_id); //진짜 삭제 (관리자에서 복구 가능)

        alert('이 후기가 삭제되었습니다.','/admin/after');

    }
    function set_status(){
        $status = $this->input->post('status');
        $unique_id = $this->input->post('unique_id');
        $type = $this->input->post('type');

        //선택한 것을 $status로 변경해준다.
        $status_data = array(
            'status'=>$status,
        );
        switch ($type){
            case 'program':
                $this->program_model->update_program($unique_id,$status_data);
                break;
            case 'after':
                $this->after_model->update_after($unique_id,$status_data);
                break;
            case 'team_blog':
                $this->team_model->update_team_blog($unique_id,$status_data);
                break;
            case 'contents':
                $this->contents_model->update_contents($unique_id,$status_data);
                break;
            case 'store':
                $this->store_model->update_store($unique_id,$status_data);
                break;
            default:
            case 'team':
                $this->team_model->update_team($unique_id,$status_data);
                break;
        }

        alert($this->lang->line($status).'로 변경되었습니다.');

    }

    function _program_delete_unit($program_id){ //실제로 지우는건 여기서 한다..
        $this->program_model->delete_program($program_id); //진짜 삭제한다.

        //options
        $this->program_model->delete_program_option_by_program_id('date',$program_id);
        $this->program_model->delete_program_option_by_program_id('heart',$program_id);
        $this->program_model->delete_program_option_by_program_id('qna',$program_id);
        $this->program_model->delete_program_option_by_program_id('qualify',$program_id);
    }

    function _send_email($type, $email, &$data, $title)
    {

        $config = array(
            'protocol' => "smtp",
            'smtp_host' => "ssl://smtp.gmail.com",
            'smtp_port' => "465",//"587", // 465 나 587 중 하나를 사용
            'smtp_user' => "admin@moimga.co",
            'smtp_pass' => "--",
            'charset' => "utf-8",
            'newline' => "\r\n",
            'mailtype' => "html",
            'smtp_timeout' => 10,
        );


        $this->load->library('email', $config);


        $this->email->set_newline("\r\n");
        $this->email->clear();

        $this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->to($email);
        $this->email->subject($title, $this->config->item('website_name', 'tank_auth'));
        $this->email->message($this->load->view('email/' . $type . '-txt', $data, TRUE));
        $this->email->set_alt_message($this->load->view('email/' . $type . '-html', $data, TRUE));
        if ($this->email->send()) {
            echo "성공";
        } else {
            echo "실패";
        }


    }

}
