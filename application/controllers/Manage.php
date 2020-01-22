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
            case 'detail':
                $this->_team_detail($team_id,$user_data);
                break;

            case 'status':
                $this->_team_status($user_data);
                break;

            case 'delete':
                $this->_team_delete($user_data);
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

        $meta_array = array(
            'location' => 'manage',
            'section' => 'team',
            'title' => '팀 관리 - 모임가',
            'desc' => '모임가 팀 관리',
        );

        $this->layout->view('manage/team/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query,'meta_array'=>$meta_array));

    }

    function _team_detail($team_id,$user_data){ //detail - 정보
        $team_info = $this->team_model->get_team_info($team_id);

        $team_info['auth_code'] = $this->_get_auth_code('team',$team_id, $user_data['user_id']);
        if($team_info['auth_code']<3){

            $search_query = array( //둘다 동일한 search_query
                'crt_date' => '',
                'search'=>null,
                'user_id'=>null,//load_after 때문에
                'status'=>null, //무조건 공개
                'team_id'=>$team_id,
                'type'=>null, //member_list
                'event'=>null,
                'heart'=>null,
                'price'=>null,
                'login_user'=>null,
            );
            $member_list = $this->member_model->load_team_member('',0,8,$search_query);
            $program_list =  $this->program_model->load_program('',0,8,$search_query);
            $blog_list =  $this->team_model->load_team_blog('',0,8,$search_query);
            $after_list =  $this->after_model->load_after('',0,8,$search_query);
            $subs_list =  $this->subscribe_model->load_subscribe('',0,8,$search_query);

            $meta_array = array(
                'location' => 'manage',
                'section' => 'team',
                'title' => '팀 관리 > '.$team_info['name'].' - 모임가',
                'desc' => '모임가 팀 상세 관리',
            );

            $this->layout->view('manage/team/detail', array('user'=>$user_data,'team_info'=>$team_info,'subs_list'=>$subs_list,
                'blog_list'=>$blog_list,'member_list'=>$member_list,'program_list'=>$program_list,'after_list'=>$after_list,'meta_array'=>$meta_array));

        }else{
            alert('권한이 없습니다. [MD01]');
        }
    }


    function _team_status($user_data){ //detail - 정보

        $team_id = $this->input->post('team_id');
        $status = $this->input->post('status');
        //권한 확인
        $auth = $this->_get_auth_code('team',$team_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth<3){ //권한이 있으면 상태 변경
            $status_data = array(
                'status'=>$status,
            );
            $this->team_model->update_team($team_id,$status_data);

            //모든 팀 멤버

            $team_info = $this->team_model->get_team_info($team_id);

            $search_query = array(
                'crt_date' => '',
                'search' => '',
                'user_id' => null,
                'type'=>null,
                'team_id'=>$team_id
            );
            $alarm_data = array(
                'from_user_id'=>$team_info['user_id'],//팀 대표
                'team_id'=>$team_id,
                'program_id'=>null,
                'status'=>'unread',
                'crt_date'=>date('Y-m-d H:i:s')
            );

            //팀멤버에게 알람 -T5, T6
            $member_list = $this->member_model->load_team_member('', '', '', $search_query);   //팀멤버 리스트
            $alarm_data['type'] = 'T5'; //on 공개 기본으로 T5
            if($status=='off'){
                $alarm_data['type'] = 'T6'; //비공개
            }

            foreach ($member_list as $m_key => $m_item){

                $alarm_data['user_id'] = $m_item['user_id'];
                $this->alarm_model->insert_alarm($alarm_data);
            }

            alert('이 팀이 '.$this->lang->line($status).'로 변경되었습니다.');

        }else{

            alert('권한이 없습니다. [MD01]');
        }

    }


    function _team_delete($user_data){ //unique_id!=moin_id
        $team_id = $this->input->post('team_id');

        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        //권한 확인
        $auth = $this->_get_auth_code('team',$team_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth<2){

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
                'is_recovered'=>0,  //1이면 복구됨, 0이면 아님

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

            alert('팀과 하위 프로그램이 삭제되었습니다.','/manage/team');
        }else{
            alert('권한이 없습니다. [MD02]');
        } //권한이 있으면 상태 변경

    }


    function after($type='lists', $after_id=null){

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];
        $user_data = array(
            'username' => $this->data['username'],
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level,
            'alarm' => $alarm_cnt
        );


        switch ($type){ //after는 list만 있어도됨
            default:
            case 'lists':
                $this->_after_lists($user_data);
                break;
        }
    }

    function _after_lists($user_data){

        $team_id = $this->uri->segment(4);
        if(is_null($team_id) || $team_id==''){

            $meta_array = array(
                'location' => 'manage',
                'section' => 'basic',
                'title' => '팀을 찾을 수 없어요! - 모임가',
                'desc' => '모임가 팀 후기 관리',
            );

            $this->layout->view('manage/empty', array('user' => $user_data,'meta_array'=>$meta_array));

        }else{

            $search = $this->uri->segment(6);
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

            $team_info = $this->team_model->get_team_info($team_id);
            $meta_array = array(
                'location' => 'manage',
                'section' => 'after',
                'title' => '후기 목록 > '.$team_info['name'].' - 모임가',
                'desc' => '모임가 후기 관리',
            );

            $this->layout->view('manage/after/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query,'meta_array'=>$meta_array));

        }

    }

    function subscribe($type='lists'){

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];
        $user_data = array(
            'username' => $this->data['username'],
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level,
            'alarm' => $alarm_cnt
        );


        switch ($type){ //subscribe는 list만 있어도됨
            default:
            case 'lists':
                $this->_subscribe_lists($user_data);
                break;
        }
    }

    function _subscribe_lists($user_data){

        $team_id = $this->uri->segment(4);
        if(is_null($team_id) || $team_id==''){

            $meta_array = array(
                'location' => 'manage',
                'section' => 'basic',
                'title' => '팀을 찾을 수 없어요! - 모임가',
                'desc' => '모임가 팀 후기 관리',
            );

            $this->layout->view('manage/empty', array('user' => $user_data,'meta_array'=>$meta_array));

        }else{

            $search = $this->uri->segment(6);
            if($search==null){
                $search_query = array(
                    'crt_date' => null,
                    'search' => null,
                    'team_id'=>$team_id, //기본은 team_id임 로 남긴다..
                    'user_id'=>null,
                );

            }else{
                $sort_date = $this->input->get('crt_date');
                $sort_search = $this->input->get('search');

                $search_query = array(
                    'crt_date' => $sort_date,
                    'search' => $sort_search,
                    'team_id'=>$team_id,
                    'user_id'=>null,
                );

            }
            $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'];

            $this->load->library('pagination');
            $config['suffix'] = $q_string;
            $config['base_url'] = '/manage/subscribe/lists'; // 페이징 주소
            $config['total_rows'] = $this -> subscribe_model -> load_subscribe('count','','',$search_query); // 게시물 전체 개수

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

            $data['result'] = $this->subscribe_model->load_subscribe('', $start, $limit, $search_query);
            $data['total']=$config['total_rows'];

            $team_info = $this->team_model->get_team_info($team_id);
            $meta_array = array(
                'location' => 'manage',
                'section' => 'subscribe',
                'title' => '구독 목록 > '.$team_info['name'].' - 모임가',
                'desc' => '모임가 구독자 관리',
            );

            $this->layout->view('manage/subscribe/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query,'meta_array'=>$meta_array));

        }

    }


    function program($type='lists', $program_id=null){
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];
        $user_data = array(
            'username' => $this->data['username'],
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level,
            'alarm' => $alarm_cnt
        );

        switch ($type){
            case 'detail': //view
                $this->_program_detail($program_id,$user_data);
                break;

            case 'status': //view
                $this->_program_status($user_data);
                break;

            case 'delete':
                $this->_program_delete($user_data);
                break;
            default:
            case 'lists':
                $this->_program_lists($user_data);
                break;
        }
    }

    function _program_lists($user_data){

        $team_id =$this->uri->segment(4);
        if(is_null($team_id) || $team_id==''){

            $meta_array = array(
                'location' => 'manage',
                'section' => 'basic',
                'title' => '팀을 찾을 수 없어요! - 모임가',
                'desc' => '모임가 팀 후기 관리',
            );

            $this->layout->view('manage/empty', array('user' => $user_data,'meta_array'=>$meta_array));

        }else{

            $search = $this->uri->segment(6);
            if($search==null){
                $search_query = array(
                    'crt_date' => null,
                    'search' => null,
                    'status'=>null,
                    'team_id'=>$team_id,
                    'event'=>null,
                    'price'=>null,
                    'user_id'=>null,
                    'heart'=>null,
                    'login_user'=>null,
                );

            }else{
                $sort_date = $this->input->get('crt_date');
                $sort_search = $this->input->get('search');
                $sort_status = $this->input->get('status');

                $search_query = array(
                    'crt_date' => $sort_date,
                    'search' => $sort_search,
                    'status'=>$sort_status,
                    'team_id'=>$team_id,
                    'event'=>null,
                    'price'=>null,
                    'heart'=>null,
                    'user_id'=>null,
                );

            }
            $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&status='.$search_query['status'];

            $this->load->library('pagination');
            $config['suffix'] = $q_string;
            $config['base_url'] = '/manage/program/lists/'.$team_id; // 페이징 주소
            $config['total_rows'] = $this -> program_model -> load_program('count','','',$search_query); // 게시물 전체 개수

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

            $data['result'] = $this->program_model->load_program('', $start, $limit, $search_query);
            $data['total']=$config['total_rows'];

            $team_info = $this->team_model-> get_team_info($team_id);

            $meta_array = array(
                'location' => 'manage',
                'section' => 'program',
                'title' => '프로그램 관리 > '.$team_info['name'].' - 모임가',
                'desc' => '모임가 프로그램 관리',
            );

            $this->layout->view('manage/program/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query,'team_info'=>$team_info,'meta_array'=>$meta_array));

        }

    }
    function _program_detail($program_id,$user_data){ //detail - 정보

        $auth = $this->_get_auth_code('program',$program_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth<3) {

            $program_info = $this->program_model->get_program_info($program_id);
            $team_info = $this->team_model->get_team_info($program_info['team_id']); //이것도 중복될 수 없으니까 unique 임
            $program_info['auth_code'] = $auth;

            if($program_info!=null){

                $meta_array = array(
                    'location' => 'manage',
                    'section' => 'program',
                    'title' => '프로그램 관리 > '.$program_info['title'].'('.$team_info['name'].') - 모임가',
                    'desc' => '프로그램 관리 > '.$program_info['title'].'('.$team_info['name'].') - 모임가',
                );
                $this->layout->view('manage/program/detail', array('user'=>$user_data,'program_info'=>$program_info,'team_info'=>$team_info,'meta_array'=>$meta_array));
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
        $auth = $this->_get_auth_code('program',$program_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth<3){ //권한이 있으면 상태 변경
            $status_data = array(
                'status'=>$status,
            );
            $this->program_model->update_program($program_id,$status_data);
            $program_info = $this->program_model->get_program_info($program_id);
            $team_info = $this->team_model->get_team_info($program_info['team_id']); //이것도 중복될 수 없으니까 unique 임

            $search_query = array(
                'crt_date' => '',
                'search' => '',
                'user_id' => null,
                'type'=>null,
                'team_id'=>$program_info['team_id']
            );

            $alarm_data = array(
                'from_user_id'=>$team_info['user_id'],//팀 대표
                'team_id'=>$program_info['team_id'],
                'program_id'=>$program_id,
                'status'=>'unread',
                'crt_date'=>date('Y-m-d H:i:s')
            );

            //팀멤버에게 알람 -T5, T6
            $member_list = $this->member_model->load_team_member('', '', '', $search_query);   //팀멤버 리스트
            $alarm_data['type'] = 'P1'; //on 공개 기본으로 T5
            if($status=='off'){
                $alarm_data['type'] = 'P2'; //비공개
            }

            foreach ($member_list as $m_key => $m_item){

                $alarm_data['user_id'] = $m_item['user_id'];
                $this->alarm_model->insert_alarm($alarm_data);
            }

            alert('이 프로그램이 '.$this->lang->line($status).'로 변경되었습니다.');

        }else{

            alert('권한이 없습니다. [MD01]');
        }

    }

    function _program_delete($user_data){ //unique_id!=moin_id
        $program_id = $this->input->post('program_id');

        $program_info = $this->program_model->get_program_info($program_id);

        $auth = $this->_get_auth_code('program',$program_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth<2){ //권한이 있으면 삭제 (admin, boss)
            //program delete
            $this->_program_delete_unit($program_id); //batch delete를 위해 함수로 만들었음

            alert('프로그램이 삭제되었습니다. 팀 관리 페이지로 이동합니다.','/manage/team/detail/'.$program_info['team_id']);

        }else{
            alert('권한이 없습니다. [MD02]');
        }

    }
    function blog($type='lists', $blog_id=null){
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];
        $user_data = array(
            'username' => $this->data['username'],
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level,
            'alarm' => $alarm_cnt
        );

        switch ($type){
            case 'detail': //view
                $this->_blog_detail($blog_id,$user_data);
                break;

            case 'status':
                $this->_blog_status($user_data);
                break;
            case 'delete':
                $this->_blog_delete($user_data);
                break;
            default:
            case 'lists':
                $this->_blog_lists($user_data);
                break;
        }
    }

    function _blog_lists($user_data){


        $team_id = $this->uri->segment(4);//list만 segment 다르다..

        if(is_null($team_id) || $team_id==''){

            $meta_array = array(
                'location' => 'manage',
                'section' => 'basic',
                'title' => '팀을 찾을 수 없어요! - 모임가',
                'desc' => '모임가 팀 후기 관리',
            );
            $this->layout->view('manage/empty', array('user' => $user_data,'meta_array'=>$meta_array));

        }else{
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

            $meta_array = array(
                'location' => 'manage',
                'section' => 'team_blog',
                'title' => '팀 블로그 관리 > '.$team_info['name'].' - 모임가',
                'desc' =>  '팀 블로그 관리 > '.$team_info['name'].' - 모임가',
            );
            $this->layout->view('manage/blog/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query,'team_info'=>$team_info,'meta_array'=>$meta_array));

        }
    }

    function _blog_status($user_data){ //detail - 정보

        $blog_id = $this->input->post('blog_id');
        $status = $this->input->post('status');
        $blog_info = $this->team_model->get_team_blog_info($blog_id);
        $auth = $this->_get_auth_code('team',$blog_info['team_id'], $user_data['user_id']);

        if($auth<3){ //권한이 있으면 상태 변경
            $status_data = array(
                'status'=>$status,
            );
            $this->team_model->update_team_blog($blog_id,$status_data);
            alert('이 포스트가 '.$this->lang->line($status).'로 변경되었습니다.');

        }else{
            alert('권한이 없습니다. [MD01]');
        }

    }

    function _blog_detail($blog_id,$user_data){ //detail - 정보

        $blog_info = $this->team_model->get_team_blog_info($blog_id);
        $blog_info['auth_code'] = $this->_get_auth_code('team',$blog_info['team_id'], $user_data['user_id']);
        if($blog_info['auth_code']<3){
            $meta_array = array(
                'location' => 'manage',
                'section' => 'team_blog',
                'title' => '팀 블로그 관리 > 상세 > '.$blog_info['title'].' - 모임가',
                'desc' =>  '팀 블로그 관리 > 상세 > '.$blog_info['title'].' - 모임가',
            );
            $this->layout->view('manage/blog/detail', array('user'=>$user_data,'blog_info'=>$blog_info,'meta_array'=>$meta_array));
        }else{
            alert('권한이 없습니다. [MD02]');
        }
    }

    function _blog_delete($user_data){ //unique_id!=moin_id

        $blog_id = $this->input->post('blog_id');
        $blog_info = $this->team_model->get_team_blog_info($blog_id);
        $auth = $this->_get_auth_code('team',$blog_info['team_id'], $user_data['user_id']);

        if($auth<3){
            $this->team_model->delete_team_blog($blog_id);
            alert('포스트가 삭제되었습니다. 블로그 목록으로 이동합니다.','/manage/blog/lists/'.$blog_info['team_id']);

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
            'username' => $this->data['username'],
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level,
            'alarm' => $alarm_cnt
        );

        switch ($type){
            case 'upload':
                $team_id = $this->input->get('team');
                $this->_member_upload($team_id,$user_data);
                break;
            case 'detail':
                $this->_member_detail($member_id,$user_data);
                break;
            case 'set':
                $member_id = $this->input->post('member_id');
                $this->_member_set($member_id,$user_data);
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

    function _member_lists($user_data){

        $team_id = $this->uri->segment(4);
        if(is_null($team_id)||$team_id==''){ //아무것도 없는 경우 먼저 팀 구성해야됨

            $meta_array = array(
                'location' => 'manage',
                'section' => 'basic',
                'title' => '팀을 찾을 수 없어요! - 모임가',
                'desc' => '모임가 팀 후기 관리',
            );
            $this->layout->view('manage/empty', array('user' => $user_data,'meta_array'=>$meta_array));

        }else{
            $search = $this->uri->segment(6);
            if($search==null){
                $search_query = array(
                    'crt_date' => '',
                    'search' => '',
                    'user_id' => null,
                    'type'=>null,
                    'team_id'=>$team_id
                );

            }else{
                $sort_date = $this->input->get('crt_date');
                $sort_search = $this->input->get('search');

                $search_query = array(
                    'crt_date' => $sort_date,
                    'search' => $sort_search,
                    'user_id' => null,
                    'type'=>null,
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

            $meta_array = array(
                'location' => 'manage',
                'section' => 'member',
                'title' => '팀 멤버 목록 - '.$team_info['name'].' - 모임가',
                'desc' => '모임가 팀 멤버 목록',
            );

            $this->layout->view('manage/member/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query,'team_info'=>$team_info,'meta_array'=>$meta_array));

        }

    }

    function _member_upload($team_id = null,$user_data){
        //업로드 합시다..

        if($this->input->post('user_id')!=null){ //여기에는 수정이 있을 수 없음.. 그냥 지우면 됨
            $form_data = $this->input->post();
            $team_info = $this->team_model->get_team_info($form_data['team_id']);
            $member_info = array(
                'user_id'=>$form_data['user_id'],
                'team_id'=>$form_data['team_id'],
                'type'=>2, //일반 멤버는 type:2, 대표는 1
                'crt_date'=>date('Y-m-d H:i:s')
            );

            //모든 팀멤버한테 알람
            $search_query = array(
                'crt_date' => '',
                'search' => '',
                'user_id' => null,
                'type'=>null,
                'team_id'=>$form_data['team_id']
            );

            $member_list = $this->member_model->load_team_member('', '', '', $search_query);

            $this->member_model->insert_team_member($member_info);

            foreach ($member_list as $key => $item){

                $member_data = array(
                    'type'=>'T3',
                    'user_id'=>$item['user_id'],// 팀 member id
                    'from_user_id'=>$team_info['user_id'],//팀 대표
                    'team_id'=>$team_id,
                    'program_id'=>null,
                    'status'=>'unread',
                    'crt_date'=>date('Y-m-d H:i:s')
                );

                $this->alarm_model->insert_alarm($member_data);
            }

            //나한테 알람

            $my_alarm_data = array(
                'type'=>'T10',
                'user_id'=>$form_data['user_id'],// 팀 관리자 id
                'from_user_id'=>$team_info['user_id'],
                'team_id'=>$team_id,
                'program_id'=>null,
                'status'=>'unread',
                'crt_date'=>date('Y-m-d H:i:s')
            );

            $this->alarm_model->insert_alarm($my_alarm_data);

            //이 사람의 레벨을 3으로 지정
//            $level_info = array(
//                'level'=>3,
//            );
//            $this->user_model->update_users($form_data['user_id'],$level_info);

            alert('팀 멤버가 등록되었습니다.','/manage/member/lists/'.$form_data['team_id']);
        }else{

            $team_info =   $this->team_model->get_team_info($team_id);

            $meta_array = array(
                'location' => 'manage',
                'section' => 'member',
                'title' => '팀 멤버 지정 - '.$team_info['name'].' - 모임가',
                'desc' => '모임가 팀 멤버 지정',
            );

            $this->layout->view('/manage/member/upload', array('user' => $user_data,'team_info'=>$team_info,'meta_array'=>$meta_array));
        }

    }

    function _member_set($member_id,$user_data){
        $team_id = $this->input->post('team_id');
        $type = $this->input->post('type');

        $auth = $this->_get_auth_code('team',$team_id, $user_data['user_id']); //지정하는 사람이 지정할 수 있는지 확인

        if($auth<3) { //권한이 있으면 변경 (admin, boss)

            $alarm_type = 'T8'; //멤버
            if($type=='1'){
                $alarm_type = 'T9'; //대표
            }

            $this_user_info = $this->member_model->get_team_member_info($member_id);
            $my_alarm_data = array(
                'type'=>$alarm_type,
                'user_id'=>$this_user_info['user_id'],// 받는 사람,.. 변경된 사람 user_id
                'from_user_id'=>$user_data['user_id'],
                'team_id'=>$team_id,
                'program_id'=>null,
                'status'=>'unread',
                'crt_date'=>date('Y-m-d H:i:s')
            );

            $member_info = array(
                'type'=>$type, //일반 멤버는 type:2, 대표는 1
            );
            $this->member_model->update_team_member($member_id, $member_info);
            //이 사람한테만 바꾼다..
            $my_team_info = $this->member_model->get_team_member_by_user_id($team_id, $user_data['user_id']);//내 type 가져오기

            if($my_team_info['type']=='1'&&$type=='1'){  //만약 내가 대표이고 post 값이 type1 (대표)면
                // 지정한 사람이 대표가 되고 (위에서 했음)
                $my_info = array(
                    'type'=>2,  //내가 type2가 된다.
                );
                $this->member_model->update_team_member($my_team_info['team_member_id'], $my_info);
                //내가 팀 멤버가 되었다는 알람 넣음
                $my_alarm_data['type'] = 'T8';
                $my_alarm_data['user_id'] = $my_team_info['user_id'];

                $this->alarm_model->insert_alarm($my_alarm_data);
            }

            $this->alarm_model->insert_alarm($my_alarm_data);

            alert('선택하신 회원이 '.$this->lang->line('member_'.$type).'로 지정되었습니다.','/manage/member/detail/'.$member_id);


        }else{
            alert('권한이 없습니다. [MD01]','/manage/member');
        }
    }
    function _member_detail($member_id,$user_data){ //detail - 정보

        $member_info = $this->member_model->get_team_member_info($member_id);
        $team_info =   $this->team_model->get_team_info($member_info['team_id']);
        $my_info = $this->member_model->get_team_member_by_user_id($member_info['team_id'], $user_data['user_id']);

        $auth = $this->_get_auth_code('team',$member_info['team_id'], $user_data['user_id']);

        if($auth<3) { //권한이 있으면 삭제 (admin, boss)
            $meta_array = array(
                'location' => 'manage',
                'section' => 'member',
                'title' => '팀 멤버 관리 > '.$member_info['realname'].' - 모임가',
                'desc' => '모임가 팀 멤버 관리',
            );

            $this->layout->view('manage/member/detail', array('user'=>$user_data,'member_info'=>$member_info,
                'my_info'=>$my_info,'team_info'=>$team_info,'meta_array'=>$meta_array));
        }else{
            alert('권한이 없습니다. [MD01]');
        }
    }

    function _member_delete($user_data){ //unique_id!=moin_id

        $member_id = $this->input->post('member_id');
        $member_info = $this->member_model->get_team_member_info($member_id);
        $team_info =   $this->team_model->get_team_info($member_info['team_id']);

        $auth = $this->_get_auth_code('team',$member_info['team_id'], $user_data['user_id']);

        if($auth<2){ //권한이 있으면 삭제 (admin, boss)

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
    function _get_auth_code($type='team',$unique_id, $user_id){
        /*code: 0: admin /   1: boss /2: member /  3: nothing */
        $level = $this->user_model->get_user_level($user_id);

        if($type=='program'){
            $program_info =$this->program_model->get_program_info($unique_id);
            $team_id = $program_info['team_id'];
        }else{ //team
            $team_id = $unique_id;
        }
        $code = $this->member_model->is_team_member($team_id, $user_id);

        if($level==9){ //super users always return true;
            $code = 0;
        }
        return $code;
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
