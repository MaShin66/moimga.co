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

        $this->layout->view('manage/team/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

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
            );
            $member_list = $this->member_model->load_team_member('','','',$search_query);
            $program_list =  $this->program_model->load_program('','','',$search_query);
            $blog_list =  $this->team_model->load_team_blog('','','',$search_query);
            $after_list =  $this->after_model->load_after('','','',$search_query);
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
        $auth = $this->_get_auth_code('team',$team_id, $user_data['user_id']); //권한 확인하는 함수

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
    function _program_detail($program_id,$user_data){ //detail - 정보

        $auth = $this->_get_auth_code('program',$program_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth<3) {

            $program_info = $this->program_model->get_program_info($program_id);
            $program_info['auth_code'] = $auth;

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
        $auth = $this->_get_auth_code('program',$program_id, $user_data['user_id']); //권한 확인하는 함수

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
            $this->layout->view('manage/blog/detail', array('user'=>$user_data,'blog_info'=>$blog_info));
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
                'type'=>2, //일반 멤버는 type:2, 대표는 1
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

        $member_info = $this->member_model->get_team_member_info($member_id);
        $team_info =   $this->team_model->get_team_info($member_info['team_id']);

        $auth = $this->_get_auth_code('team',$member_info['team_id'], $user_data['user_id']);

        if($auth<3) { //권한이 있으면 삭제 (admin, boss)
            $this->layout->view('manage/member/detail', array('user'=>$user_data,'member_info'=>$member_info,'team_info'=>$team_info));
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
