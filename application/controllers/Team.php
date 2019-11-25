<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends MY_Controller {

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
        $this->lists();

    }


    function lists(){

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

        $search = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'search'=>null,
                'status'=>'on',
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'status'=>'on', //무조건 공개
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/team/lists/'; // 페이징 주소
        $config['total_rows'] = $this -> team_model->load_team('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 3; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1'; // 첫페이지에 query string 에러나서..
        $config = pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this -> pagination -> create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this -> uri -> segment(3);


        if($page==null){
            $start=0;
        }else{

            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }

        $limit = $config['per_page'];

        $data['result'] = $this->team_model->load_team('', $start, $limit,$search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('team/list', array('user'=>$user_data,'result'=>$data));
    }

    function upload(){

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

        $title = $this->input->post('title');
        $type = $this->input->get('type'); //type get으로 받는다... url 에 있음

        if($title){ //입력

            $url = $this->input->post('url');
            $name = $this->input->post('name');
            $title = $this->input->post('title');
            $contents = $this->input->post('contents');
            $status = $this->input->post('status');

            if($status!='on'){
                $status = 'off';
            }

            $data = array(
                'user_id'=>$user_id,
                'url'=>$url,
                'title'=>$title,
                'name'=>$name,
                'contents'=>$contents,
                'status'=>$status, //공개 여부
            );

            //print_r($data);

            if($type=='modify'){

                $team_id = $this->input->post('team_id');
                $this->team_model->update_team($team_id,$data);

            }else{ //새로 쓰기
                $data['thumb_url'] = '/www/thumbs/team/basic.jpg'; //새로쓸때는 이렇게..
                $data['subscribe_count'] = 0;
                $data['crt_date'] = date('Y-m-d H:i:s');
                $team_id = $this->team_model->insert_team($data);

            }

            //thumbs는 저장이 모두 끝난 후에 한다..
            //thumbs 지정 안했을 경우에는 그냥 안한다..
            //null일 경우에 아무것도 안함

            //thumb 지정.. thumbs_helper 이용한다..

            $thumbs['thumb_url'] = thumbs_upload('team', $team_id); // 바로 업데이트
            if(!is_null($thumbs['thumb_url'] )){
                 $this->team_model->update_team($team_id,$thumbs);
            }


            //다 끝나면 redirect
            redirect('team/@'.$url);
        }else{ //없으면 글쓰기 화면

            if($type=='modify'){

                $team_id = $this->uri->segment(3);
                $data = $this->team_model->get_team_info($team_id); //이것도 중복될 수 없으니까 unique 임

            }else{

                $data = array(
                    'url'=>null,
                    'name'=>null,
                    'title'=>null,
                    'contents'=>null,
                    'status'=>'on',
                    'thumbs_url'=>null,//기본 섬네일 지정,
                    'type'=>'new' //새로 글쓰기
                );
            }

            $this->layout->view('team/upload', array('user'=>$user_data,'result'=>$data));
        }

    }

//
//    function info($url_name){ // moim 인포
//
//        $status = $this->data['status'];
//        $user_id = $this->data['user_id'];
//        $level = $this->data['level'];
//        $alarm_cnt = $this->data['alarm'];
//        $user_data = array(
//            'status' => $status,
//            'user_id' => $user_id,
//            'username' =>$this->data['username'],
//            'level' => $level,
//            'alarm' =>$alarm_cnt
//        );
//        $team_info = $this->team_model->get_team_info_by_url($url_name); //이것도 중복될 수 없으니까 unique 임
//        $app_list['open'] = $this->application_model->load_team_application($team_info['team_id'],1);
//        $app_list['close'] = $this->application_model->load_team_application($team_info['team_id'],0);
//        //지원서 목록 출력
//        $this->layout->view('/team/info', array('user'=>$user_data,'team_info'=>$team_info,'app_list'=>$app_list));
//
//
//    }

    function view($url){ 

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
        $team_info = $this->team_model->get_team_info_by_url($url); //이것도 중복될 수 없으니까 unique 임

        $search_query = array(
            'crt_date' => '',
            'search'=>null,
            'status'=>'on',
            'team_id'=>$team_info['team_id'],
        );

        $at_url = $this->uri->segment(1); //@가 붙은 url
        $programs = $this->program_model->load_program('','',4,$search_query);
        $team_blog = $this->team_model->load_team_blog('','',4,$search_query);
        $this->layout->view('/team/view', array('user'=>$user_data,'team_info'=>$team_info,
            'team_blog'=>$team_blog,'programs'=>$programs,'at_url'=>$at_url));

    }


    function heart($team_id){ //좋아요
        $user_id = $this->data['user_id'];
        if($user_id==0){
            echo 'login';
        }else{
            //이미 내가 누른지 확인
            $today = date('Y-m-d H:i:s');
            $like_info = $this->like_model->get_team_like_user($user_id, $team_id);
            $team_info = $this->team_model->get_team_info($team_id); //detail_product

            if($like_info==null){ // 안눌렀으면 새로 쓰기
                $like_data = array(
                    'team_id'=>$team_id,
                    'user_id'=>$user_id,
                    'crt_date'=>$today,
                );
                $this->like_model->insert_team_like($like_data);

                $detail_data['like'] =$team_info['like']+1;
                $this->team_model->update_moim($team_info['team_id'], $detail_data);

                echo 'done';

            }else{// 눌렀으면 누른거 취소
                //이 episode bookmark에 하나 빼기
                $this->like_model->delete_team_like($like_info['team_like_id']); //취소 - 아예 지운다

                $detail_data['like'] =$team_info['like']-1;
                $this->team_model->update_moim($team_info['team_id'], $detail_data);

                echo 'cancel';
            }
        }
    }

    function blog($type='lists'){ //팀 고유 블로그 {@team_url}/blog/{$post_id}
        //모임가고유 blog는 moimga.co/blog임..
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
        //blog에는 무조건 team_url있으니까 이걸로 team_id 찾아낸다.
        $team_id = get_team_id($this->uri->segment(1));

        switch ($type){
            case 'view':
                $this->_blog_view($user_data,$team_id);
                break;
            case 'upload':
                $this->_blog_upload($user_data,$team_id);
                break;
            case 'lists':
            default:
                $this->_blog_list($user_data,$team_id);
                break;
        }

    }

    function _blog_list($user_data,$team_id){

        $at_url = $this->uri->segment(1); //@가 붙은 url
        $search = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'search'=>null,
                'status'=>'on',
                'team_id'=>$team_id,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'status'=>'on', //무조건 공개
                'team_id'=>$team_id,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/team/blog/lists/'; // 페이징 주소
        $config['total_rows'] = $this -> team_model->load_team_blog('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 3; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1'; // 첫페이지에 query string 에러나서..
        $config = pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this -> pagination -> create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this -> uri -> segment(3);


        if($page==null){
            $start=0;
        }else{

            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }

        $limit = $config['per_page'];

        $data['result'] = $this->team_model->load_team_blog('', $start, $limit,$search_query);
        $data['total']=$config['total_rows'];

        $as_member = $this->team_model-> as_member($team_id, $user_data['user_id']);
        //as_member

        $this->layout->view('team/blog/list', array('user'=>$user_data,'data'=>$data,'as_member'=>$as_member,'at_url'=>$at_url));
    }
    function _blog_view($user_data,$team_id){
        $at_url = $this->uri->segment(1); //@가 붙은 url
        $team_blog_id = $this->uri->segment(3);
        $is_opened = $this->team_model->is_opened($team_blog_id);

        if($is_opened=='on'){

            $post_info = $this->team_model->get_team_blog_info($team_blog_id);
        }else{
            $as_member = $this->team_model-> as_member($team_id, $user_data['user_id']);
            if(!$as_member) {
                $post_info  =array();
                alert('공개되어있지 않습니다.');
            }else{
                //off면 멤버만 볼 수 있다.
                $post_info = $this->team_model->get_team_blog_info($team_blog_id);
            }
        }
       $this->team_model->update_team_blog_hit($team_blog_id);
        $team_info = $this->team_model->get_team_info($post_info['team_id']);
        //들어오면 힛수 +1
        $this->layout->view('/team/blog/view', array('user'=>$user_data,'post_info'=>$post_info,'team_info'=>$team_info,'at_url'=>$at_url));
    }

    function _blog_upload($user_data,$team_id){
        $at_url = $this->uri->segment(1); //@가 붙은 url
        $as_member = $this->team_model-> as_member($team_id, $user_data['user_id']);
        //내 정보가 team 장, 팀 멤버에 등록돼있는지 확인
        if($as_member){ //멤버 임

            $title = $this->input->post('title');
            $type = $this->input->get('type'); //type get으로 받는다... url 에 있음

            if($title){ //입력

                $contents = $this->input->post('contents');
                $status = $this->input->post('status');

                if($status!='on'){
                    $status = 'off';
                }

                $data = array(
                    'team_id'=>$team_id, //team_id는 front  에 보낼 필요가 없다.
                    'user_id'=>$user_data['user_id'],
                    'title'=>$title,
                    'contents'=>$contents,
                    'status'=>$status, //공개 여부
                );

                //print_r($data);

                if($type=='modify'){

                    $team_blog_id = $this->uri->segment(4);
                    $this->team_model->update_team_blog($team_blog_id,$data);

                }else{ //새로 쓰기
                    $data['hit'] = 0;
                    $data['crt_date'] = date('Y-m-d H:i:s');
                    $team_blog_id = $this->team_model->insert_team_blog($data);

                }
                
                //다 끝나면 redirect
                redirect($at_url.'/blog/'.$team_blog_id);
            }else{ //없으면 글쓰기 화면

                if($type=='modify'){

                    $team_blog_id = $this->uri->segment(4);
                    $data = $this->team_model->get_team_blog_info($team_blog_id); //이것도 중복될 수 없으니까 unique 임

                }else{

                    $data = array(
                        'title'=>null,
                        'contents'=>null,
                        'status'=>'on',
                        'type'=>'new' //새로 글쓰기
                    );
                }

                $this->layout->view('team/blog/upload', array('user'=>$user_data,'result'=>$data,'at_url'=>$at_url));
            }

        }else{ //멤버 아님
            alert('해당 팀에 포스팅을 쓸 수 없습니다.','/'.$at_url);
        }



    }

}
