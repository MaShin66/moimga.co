<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class After extends MY_Controller
{
//https://www.codeigniter.com/userguide3/helpers/form_helper.html
    function __construct()
    {
        parent::__construct();
        $this->load->database();

        $this->load->library('tank_auth');
        $this->load->library('layout', 'layouts/default');
        $this->layout->setLayout("layouts/default");

    }

    function index(){
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
        $team_id = null;
        $team_info = array();

        $meta_title = '후기 - 모임가';
        $meta_desc = '모임가 후기 목록';

        if($search==null){
            $search_query = array(
                'crt_date' => null,
                'search'=>null,
                'team_id'=>null,
                'status'=>'on',
                'user_id'=>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $team_id = $this->input->get('team_id');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'team_id'=>$team_id,
                'status'=>'on',
                'user_id'=>null,
            );


        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&team_id='.$search_query['team_id'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/after/lists/'; // 페이징 주소
        $config['total_rows'] = $this -> after_model->load_after('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 3; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1'.$config['suffix']; // 첫페이지에 query string 에러나서..
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

        $data['result'] = $this->after_model->load_after('', $start, $limit,$search_query);
        $data['total']=$config['total_rows'];

        if(!is_null($team_id)){
            $team_info = $this->team_model->get_team_info($team_id);
            if(!$team_info){ //입력한 팀 id 가 없는 경우
                alert('해당 팀을 찾을 수 없습니다.');
            }else{
                $as_member = $this->team_model-> as_member($team_id, $user_data['user_id']);
                if($team_info['status']=='on' ||($team_info['status']=='off' && $as_member) || $level==9){
                    //관리자는 프로그램이 닫혀있어도 접근 가능

                    if(($sort_search!=null || $sort_search !='')&&!is_null($team_id)){
                        $meta_title = '팀 후기 > '.$team_info['name'].' > '.$sort_search.' - 모임가';

                    }else if(!is_null($team_id)){//팀 이름만
                        $meta_title = '팀 후기 > '.$team_info['name'].' - 모임가';

                    }else{//query만 있는 경우
                        $meta_title = '후기 검색 > '.$sort_search.' - 모임가';
                    }
                    $meta_desc = $meta_title;
                }else{
                    $team_info = array();
                }
            }

        }
        $meta_array = array(
            'location' => 'after',
            'section' => 'lists', //list여도 search 와 같은 기능 함
            'title' => $meta_title,
            'desc' => $meta_desc,
        );

        foreach ($data['result'] as $d_key => $d_item){ //desc 가져오기
            $data['result'][$d_key]['contents'] = tag_strip($d_item['contents']);
        }

        $this->layout->view('after/list', array('user'=>$user_data,'result'=>$data,'search_query'=>$search_query,
            'team_info'=>$team_info,'meta_array'=>$meta_array));
    }

    function view($after_id){

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

        $after_info = $this->after_model->get_after_info($after_id);

        $text = substr($after_info['contents'], 0, 500);
        $text = addslashes($text);
        $content = strip_tags($text);
        $real_content = str_replace("&nbsp;", "", $content);

        $meta_array = array(
            'location' => 'after',
            'section' => 'view',
            'title' => $after_info['title'].' - 모임가',
            'desc' => $real_content,
        );

        if($after_info['status']=='on' || ($after_info['status']=='off' && ($after_info['user_id']==$user_id))){
            $this->after_model->update_after_hit($after_id); //후기 hit
            $this->layout->view('after/view', array('user'=>$user_data,'after_info'=>$after_info,'meta_array'=>$meta_array));
            }else{
            alert($this->lang->line('hidden_alert'),'/after');

        }

    }

    function upload($after_id=null){

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


        $meta_array = array(
            'location' => 'after',
            'section' => 'upload',
            'title' => '후기 등록  - 모임가	',
            'desc' => '모임가 후기 등록',
        );

        if($title){ //입력

            $team_id = $this->input->post('team_id');
            $contents = $this->input->post('contents');
            $status = $this->input->post('status');
            $program = $this->input->post('program');

            if($status!='on'){
                $status = 'off';
            }

            $data = array(
                'user_id'=>$user_id,
                'title'=>$title,
                'team_id'=>$team_id,
                'contents'=>$contents,
                'program'=>$program,
                'status'=>$status, //공개 여부
            );

            if($type=='modify'){

                $after_id = $this->input->post('after_id');
                $before_info = $this->after_model->get_after_info($after_id);
                $this->after_model->update_after($after_id,$data);
                //이전거랑 비교
                if($before_info['team_id']!=$team_id){

                    $before_count = $this->team_model->get_team_count('after',$before_info['team_id']);
                    $after_count = $this->team_model->get_team_count('after',$team_id);

                    $before_array = array( //이전거 -1
                        'after_count' => $before_count-1
                    );
                    $this->team_model->update_team($before_info['team_id'],$before_array);

                    $after_array = array(//지금거 +1
                        'after_count' => $after_count+1
                    );
                    $this->team_model->update_team($team_id,$after_array);


                }//같으면 아무런 액션 취하지 않음


            }else{ //새로 쓰기
                $data['hit']=0;
                $data['crt_date'] = date('Y-m-d H:i:s');

                $after_id = $this->after_model->insert_after($data);

                $after_count = $this->team_model->get_team_count('after',$team_id);

                //team에 after_count에 +1
                $after_array = array(
                    'after_count' => $after_count+1
                );
                $this->team_model->update_team($team_id,$after_array);

                $team_info = $this->team_model->get_team_info($after_id);
                //알람
                $alarm_data = array(
                    'type'=>'T2',
                    'user_id'=>$team_info['user_id'],// 팀 관리자 id
                    'from_user_id'=>$user_id,
                    'team_id'=>$team_id,
                    'program_id'=>null,
                    'status'=>'unread',
                    'crt_date'=>date('Y-m-d H:i:s')
                );

                $this->alarm_model->insert_alarm($alarm_data);


            }

            $thumbs['thumb_url'] = thumbs_upload('after', $after_id); // 바로 업데이트
            if(!is_null($thumbs['thumb_url'] )){

            }

            if(!is_null($thumbs['thumb_url'] )){ //파일을 업로드 했다는 뜻

                if($type=='modify'){  //만약 type== modify 면 이전의 파일을 지운다.
                    unlink(FCPATH . $before_info['thumb_url']);
                }
                $this->after_model->update_after($after_id,$thumbs);
            }


            //다 끝나면 redirect
            redirect('/after/view/'.$after_id);
        }else{ //없으면 글쓰기 화면

            if($type=='modify'){
                $data = $this->after_model->get_after_info($after_id);
                if($data['user_id']!=$user_id){
                    alert($this->lang->line('forbidden'),'/mypage/after');
                }
            }else{
                $data = array(
                    'title'=>null,
                    'team_id'=>null,
                    'contents'=>null,
                    'team_title'=>null,
                    'team_name'=>null,
                    'program'=>null,
                    'after_id'=>null,
                    'status'=>'on',
                    'type'=>'new' //새로 글쓰기
                );
            }

            $this->layout->view('after/upload', array('user'=>$user_data,'result'=>$data,'meta_array'=>$meta_array));
        }

    }

}
?>