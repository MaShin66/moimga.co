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

    function lists(){ //너는 그냥 이거 써  != manage team_list() (load_assigned_team)

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
                'crt_date' => null,
                'search'=>null,
                'user_id'=>null,
                'status'=>'on',
                'after'=>null,
                'subscribe'=>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_after = $this->input->get('after');
            $sort_subscribe = $this->input->get('subscribe');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,

                'user_id'=>null,
                'status'=>'on', //무조건 공개
                'after'=>$sort_after,
                'subscribe'=>$sort_subscribe,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&after='.$search_query['after'].'&subscribe='.$search_query['subscribe'];

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


        $this->layout->view('team/list', array('user'=>$user_data,'result'=>$data,'search_query'=>$search_query));
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
                $data['user_id']=$user_id; //user_id 는 절대 바꾸면 안됨. 팀장이 변해 ㅠㅠ
                $data['thumb_url'] = '/www/thumbs/team/basic.jpg'; //새로쓸때는 이렇게..
                $data['subscribe_count'] = 0;
                $data['heart_count'] = 0;

                $data['after_count']=0;
                $data['hit'] = 0;
                $data['crt_date'] = date('Y-m-d H:i:s');
                $team_id = $this->team_model->insert_team($data);

                //team_member에 나도 쓰기
                $member_info = array(
                    'user_id'=>$user_id,
                    'team_id'=>$team_id,
                    'type'=>1,
                    'crt_date'=>date('Y-m-d H:i:s')
                );
                $this->member_model->insert_team_member($member_info);
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
            redirect('/@'.$url);
        }else{ //없으면 글쓰기 화면
            if($type=='modify'){

                $team_id = $this->uri->segment(3);
                $data = $this->team_model->get_team_info($team_id); //이것도 중복될 수 없으니까 unique 임
                $as_member = $this->team_model-> as_member($team_id, $user_data['user_id']);
                if($as_member || $user_data['level']===9){

                }else{
                    alert($this->lang->line('forbidden'),'/manage/team');
                }


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
        $as_member = $this->team_model-> as_member($team_info['team_id'], $user_data['user_id']);
        if($team_info['status']=='on' ||($team_info['status']!='on' && $as_member) || $level==9){

            $search_query = array(
                'crt_date' => '',
                'search'=>null,
                'status'=>'on',
                'team_id'=>$team_info['team_id'],
                'price'=>null, //program
                'event'=>null, //program
                'user_id'=>null, //after
            );

            $at_url = $this->uri->segment(1); //@가 붙은 url
            $programs = $this->program_model->load_program('','',4,$search_query);
            $team_blog = $this->team_model->load_team_blog('','',4,$search_query);
            $after_list =  $this->after_model->load_after('','',4,$search_query);
            $this->team_model->update_team_hit($team_info['team_id']);
            $this->layout->view('/team/view', array('user'=>$user_data,'team_info'=>$team_info,'after_list'=>$after_list,
                'team_blog'=>$team_blog,'programs'=>$programs,'at_url'=>$at_url,'as_member'=>$as_member));

        }else{
            alert($this->lang->line('hidden_alert'),'/team');
        }

    }


    function heart(){ //좋아요
        $user_id = $this->data['user_id'];
        $team_id = $this->input->post('unique_id');
        if($user_id==0){
            echo 'login';
        }else{
            //이미 내가 누른지 확인
            $today = date('Y-m-d H:i:s');
            $like_info = $this->heart_model->get_heart_user('team',$user_id, $team_id);
            $team_info = $this->team_model->get_team_info($team_id); //detail_product

            if($like_info==null){ // 안눌렀으면 새로 쓰기
                $like_data = array(
                    'team_id'=>$team_id,
                    'user_id'=>$user_id,
                    'crt_date'=>$today,
                );
                $this->heart_model->insert_heart('team',$like_data);

                $detail_data['heart_count'] =$team_info['heart_count']+1;
                $this->team_model->update_team($team_info['team_id'], $detail_data);

                echo 'done';

            }else{// 눌렀으면 누른거 취소
                //이 episode bookmark에 하나 빼기
                $this->heart_model->delete_heart('team',$like_info['team_heart_id']); //취소 - 아예 지운다

                $detail_data['heart_count'] =$team_info['heart_count']-1;
                $this->team_model->update_team($team_info['team_id'], $detail_data);

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

        $team_info = $this->team_model->get_team_info($team_id); //이것도 중복될 수 없으니까 unique 임
        $as_member = $this->team_model-> as_member($team_id, $user_data['user_id']);

        if($team_info['status']=='on' ||($team_info['status']!='on' && $as_member)|| $user_data['level']==9 ){
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

        }else{

            alert($this->lang->line('hidden_alert'),'/team');
        }
    }
    function _blog_view($user_data,$team_id){
        $at_url = $this->uri->segment(1); //@가 붙은 url
        $team_blog_id = $this->uri->segment(3);
        $is_opened = $this->team_model->is_post_opened($team_blog_id);
//여기 다시 짜!!!!
        if($is_opened=='on'){

            $post_info = $this->team_model->get_team_blog_info($team_blog_id);
        }else{
            $as_member = $this->team_model-> as_member($team_id, $user_data['user_id']);
            if($as_member || $user_data['level']==9){
                $post_info = $this->team_model->get_team_blog_info($team_blog_id);
                $this->team_model->update_team_blog_hit($team_blog_id);
                $team_info = $this->team_model->get_team_info($post_info['team_id']);
                //들어오면 힛수 +1
                $this->layout->view('/team/blog/view', array('user'=>$user_data,'post_info'=>$post_info,'team_info'=>$team_info,'at_url'=>$at_url));
            }else{
                $post_info  =array();
                alert($this->lang->line('hidden_alert'),'/team');
            }
        }

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
