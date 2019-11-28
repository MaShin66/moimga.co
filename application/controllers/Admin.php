<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();

        //lang 추가 !!!!//lang 추가 !!!!//lang 추가 !!!!//lang 추가 !!!!//lang 추가 !!!!//lang 추가 !!!!
        $this->load->library('tank_auth');
        $this->load->library('layout', 'layouts/admin_layout');
        $this->layout->setLayout("layouts/admin_layout");

    }


    public function index()
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );
        $this->layout->view('admin/main', array('user' => $user_data));
    }

    function faq($type = 'list',$faq_id=null)
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
                        $this->_faq_list('list',$user_data);
                        break;
                    case 'view':
                        $this->_faq_view($faq_id);
                        break;
                    default:
                        $this->_faq_list();
                        break;
                }

            }

        }
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
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_status = $this->input->get('status');
            $sort_user_id = $this->input->get('user_id');

            $search_query = array(
                'search' => $sort_search,
                'status' => $sort_status,
                'crt_date' => $sort_date,
                'user_id' => $sort_user_id,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&user_id='.$search_query['user_id'].'&status='.$search_query['status'];

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
    function blog($type = 'list',$blog_id=null)
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
                        $this->_blog_list('list',$user_data);
                        break;
                    case 'upload':
                        $this->_blog_upload($blog_id);
                        break;
                    case 'view':
                        $this->_blog_view($blog_id);
                        break;
                    default:
                        $this->_blog_list();
                        break;
                }

            }

        }
    }


    function _blog_list($user_data){

        $search = $this->uri->segment(5);

        if($search==null){
            $search_query = array(
                'search' => null,
                'status' =>null,
                'crt_date' =>null,
                'user_id' => null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_status = $this->input->get('status');
            $sort_user_id = $this->input->get('user_id');

            $search_query = array(
                'search' => $sort_search,
                'status' => $sort_status,
                'crt_date' => $sort_date,
                'user_id' => $sort_user_id,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&user_id='.$search_query['user_id'].'&status='.$search_query['status'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/blog/lists'; // 페이징 주소
        $config['total_rows'] = $this -> blog_model -> load_blog('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->blog_model->load_blog('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/blog/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }
    function _blog_detail($blog_id, $user_data){
        $blog_info = $this->blog_model->get_blog_info($blog_id);

        $search_query = array( //둘다 동일한 search_query
            'crt_date' => '',
            'search'=>null,
            'user_id'=>null,//load_after 때문에
            'status'=>null, //무조건 공개
            'blog_id'=>$blog_id,
        );
        $member_list = $this->member_model->load_blog_member('','','',$search_query);
        $program_list =  $this->program_model->load_program('','','',$search_query);
        $blog_list =  $this->blog_model->load_blog_blog('','','',$search_query);
        $after_list =  $this->after_model->load_after('','','',$search_query);
        $this->layout->view('admin/blog/detail', array('user'=>$user_data,'blog_info'=>$blog_info,
            'blog_list'=>$blog_list,'member_list'=>$member_list,'program_list'=>$program_list,'after_list'=>$after_list));
    }

    function _blog_delete(){
        $blog_id = $this->input->post('blog_id');
        //blog 복사해서 blog_delete에 넣어둔다

        $blog_info = $this->blog_model->get_blog_info($blog_id);
        $delete_info = array(
            'org_blog_id'=>$blog_info['blog_id'],
            'user_id'=>$blog_info['user_id'],
            'url'=>$blog_info['url'],
            'name'=>$blog_info['name'],
            'title'=>$blog_info['title'],
            'contents'=>$blog_info['contents'],
            'thumb_url'=>$blog_info['thumb_url'],
            'crt_date'=>$blog_info['crt_date'],
            'delete_date'=>date('Y-m-d H:i:s'),

        );
        $this->blog_model->insert_blog_delete($delete_info); //삭제된 팀 여기로 복사

        //구독
        $this->subscribe_model->delete_blog_subscribe($blog_id); //구독도 지운다 //blog id로 구독된 '모든' 구독 전부 지운다

        //팀멤버 삭제
        $this->member_model->delete_blog_member_by_blog_id($blog_id);

        //팀 포스트 삭제
        $this->blog_model->delete_blog_blog_by_blog_id($blog_id);

        //하위 프로그램 삭제
        //프로그램 아이디 가져온다 ..

        $program_list = $this->program_model->load_program_by_blog_id($blog_id);
        foreach ($program_list as $key=>$item){
            $this->_program_delete_unit($item['program_id']);
        }
        $this->blog_model->delete_blog($blog_id); //진짜 삭제 (관리자에서 복구 가능)

        alert('팀과 하위 프로그램이 삭제되었습니다.','/admin/blog');

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
                    case 'detail':
                        $this->_member_detail($member_id,$user_data);
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
                'status' =>null,
                'crt_date' =>null,
                'user_id'=>null,
                'team_id'=>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_status = $this->input->get('status');
            $sort_team_id = $this->input->get('team_id');
            $sort_user_id = $this->input->get('user_id');

            $search_query = array(
                'search' => $sort_search,
                'status' => $sort_status,
                'crt_date' => $sort_date,
                'user_id'=>$sort_user_id,
                'team_id'=>$sort_team_id,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&status='.$search_query['status'].'&team_id='.$search_query['team_id'].'&user_id='.$search_query['user_id'];

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
    function _member_detail($member_id, $user_data){
        $member_info = $this->member_model->get_member_info($member_id);
        $team_info = $this->member_model->get_team_info($member_info['team_id']);

        $this->layout->view('admin/member/detail', array('user'=>$user_data,'data'=>$member_info,'team_info'=>$team_info));
    }

    function _member_delete($member_id){

        $this->member_model->delete_member($member_id); //진짜 삭제 (관리자에서 복구 가능)

        alert('이 포스팅이 삭제되었습니다.','/admin/member');

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
                    case 'drop':

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
    

    function down_refund($faq_id = null)
        {

            $faq_info = $this->Prod_model->get_faq_info($faq_id);

            $result = $this->admin_model->load_refund($faq_id);

            $file_name = urldecode($faq_info['title']);
            //[moimga] 상품이름_폼.xls
            header("Content-type: application/vnd.ms-excel; charset=utf-8");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
            header("Pragma: no-cache");
            header("Content-Disposition: attachment; filename='moimga_" . $file_name . "_환불목록.xls");


            echo "
    <table>
    <tr>
        <td>폼번호</td>
        <td>회원번호</td>
        <td>계좌번호</td>
        
        <td>은행</td>
        <td>예금주</td>
        <td>입금액</td>
        
        <td>날짜</td>
         </tr> "; // 테이블 상단
            $result_count = count($result);


            for ($i = 0; $i < $result_count; $i++) {


                echo "<tr>";
                echo "<td>" . $result[$i]['form_id'] . "</td>";
                echo "<td>" . $result[$i]['user_id'] . "</td>";
                echo "<td style=mso-number-format:'\@'>" . $result[$i]['account'] . "</td>";

                echo "<td>" . $result[$i]['refund_bank'] . "</td>";
                echo "<td>" . $result[$i]['refund_name'] . "</td>";
                echo "<td>" . $result[$i]['money'] . "</td>";
                echo "<td>" . $result[$i]['crt_date'] . "</td>";

                echo "</tr>";
            }

            echo "</table>";


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

    function _send_email($type, $email, &$data, $title)
    {

        $config = array(
            'protocol' => "smtp",
            'smtp_host' => "ssl://smtp.gmail.com",
            'smtp_port' => "465",//"587", // 465 나 587 중 하나를 사용
            'smtp_user' => "admin@takemm.com",
            'smtp_pass' => "fortis53",
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
function _faq_list($type='list',$user_data){

    $search = $this->uri->segment(4);

    if($search==null){
        $search_query = array(
            'crt_date' => '',
            'type' => 'all',
            'search' => '',
            'status'=>null,
        );

    }else{
        $sort_date = $this->input->get('crt_date');
        $sort_search = $this->input->get('search');
        $sort_type = $this->input->get('type');

        $search_query = array(
            'crt_date' => $sort_date,
            'search' => $sort_search,
            'type' => $sort_type,
            'status'=>null,
        );

    }
    $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&type='.$search_query['type'];

    $this->load->library('pagination');
    $config['suffix'] = $q_string;
    $config['base_url'] = '/admin/faq/' . $type; // 페이징 주소
    $config['total_rows'] = $this -> admin_model -> load_prod('count','','',$search_query); // 게시물 전체 개수

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
    if($page==null||$page=='q'){
        $start=0;
    }else{

        $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
    }


    $limit = $config['per_page'];

    $data['result'] = $this->admin_model->load_prod('', $start, $limit, $search_query);
    $data['total']=$config['total_rows'];

    $this->layout->view('admin/faq', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

}

    function _payment_list($payment_type='card',$type='list',$user_data=null){

        $search = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'type' => 'all',
                'search' => '',
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_type = $this->input->get('type');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'type' => $sort_type,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&type='.$search_query['type'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/payment/' . $type; // 페이징 주소
        $config['total_rows'] = $this -> admin_model -> load_payment('count',$payment_type,'','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 13; // 한 페이지에 표시할 게시물 수
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

        $data['result'] = $this->admin_model->load_payment('',$payment_type, $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/payment', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }
    function _user_list($user_data){

        $search = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => null,
                'search' => null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'];

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

    function _user_drop($user_id){
        $this->user_model->drop_user($user_id);

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
                'user_id'=>null,
                'status'=>null,
                'team_id'=>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_status = $this->input->get('status');
            $sort_user_id = $this->input->get('user_id');
            $sort_team_id = $this->input->get('status');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'user_id'=>$sort_user_id,
                'status'=>$sort_status,
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
            case 'blog':
                $this->team_model->update_team_blog($unique_id,$status_data);
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

}
