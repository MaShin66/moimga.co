<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Program extends MY_Controller {

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

        $at_url = $this->uri->segment(1); //@가 붙은 url
        $team_id = null; //initiate
        if($at_url!='program'){ //첫번째 segment가 program이 아니면 team으로 구분한다.

            $team_id = get_team_id($at_url);
        }
        $search = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => null,
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
        $config['base_url'] = '/program/lists/'; // 페이징 주소
        $config['total_rows'] = $this -> program_model->load_program('count','','',$search_query); // 게시물 전체 개수

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

        $data['result'] = $this->program_model->load_program('', $start, $limit,$search_query);
        $data['total']=$config['total_rows'];

//        print_r($data['result'] );

        $this->layout->view('program/list', array('user'=>$user_data,'data'=>$data,'search_query'=>$search_query));
    }

    function view(){ //route때문에 .. 무조건 3으로 들어와야함

        $program_id = $this->uri->segment(3); //program/view 도 된다..

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

        $program_info = $this->program_model->get_program_info($program_id);
        $team_info = $this->team_model->get_team_info($program_info['team_id']);
        $date_info = $this->program_model->load_program_date_info_by_p_id($program_id);
        $qna_info = $this->program_model->load_program_qna_info_by_p_id($program_id);
        $qualify_info = $this->program_model->load_program_qualify_info_by_p_id($program_id); //이것도 중복될 수 없으니까 unique 임

        $this->program_model->update_program_hit($program_id); // 조회수
        $this->layout->view('/program/view', array('user'=>$user_data,'program_info'=>$program_info,'team_info'=>$team_info,
        'date_info'=>$date_info,'qna_info'=>$qna_info,'qualify_info'=>$qualify_info));

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
        $at_url = $this->uri->segment(1);
        $title = $this->input->post('title');
        $team_id =get_team_id($at_url);

        $type = $this->input->get('type'); //type get으로 받는다... url 에 있음
        // 팀을 등록한적 있는지 확인
        $has_team = $this->team_model->has_team($user_id);
        if(!$has_team){ //없으면 (return false)
            alert('팀을 먼저 등록해주세요.','/manage/team');
        }else{
            if($title){ //입력

                $contents = $this->input->post('contents');
                $participant = $this->input->post('participant');
                $title = $this->input->post('title');
                $district= $this->input->post('district');
                $venue= $this->input->post('venue');
                $price = $this->input->post('price');
                $address = $this->input->post('address');
                $status = $this->input->post('status');

                //array

                $team_id = $this->input->post('team_id'); //여기서 가져온다

                if($status!='on'){
                    $status = 'off';
                }

                $data = array(
                    'user_id'=>$user_id,
                    'team_id'=>$team_id,
                    'title'=>$title,
                    'participant'=>$participant,
                    'district'=>$district,
                    'venue'=>$venue,
                    'price'=>$price,
                    'address'=>$address,
                    'contents'=>$contents,
                    'status'=>$status,
                );
                if($type=='modify'){

                    $program_id = $this->input->post('program_id');
                    $this->program_model->update_program($program_id,$data);

                    //ajax로 이미 데이터 추가했고, 대괄호 안에 고유 id보관해서 가져옴

                    $qualify_array = $this->input->post('qualify');
                    $question_array = $this->input->post('question');
                    $answer_array = $this->input->post('answer');
                    $date_array = $this->input->post('event_date');
                    $time_array = $this->input->post('event_time');

                    //id있는것: 덮어 씌움, 없는것: 새로 입력

                    //날짜와.. 시간 입력한다..
                    foreach ($date_array as $pdate_id=> $pdt_value){
                        $pdate_data['date']=$pdt_value;
                        $pdate_data['time']=$time_array[$pdate_id];
                        $this->program_model->update_program_date($pdate_id, $pdate_data); //집어넣기
                    }


                    //qualify
                    foreach ($qualify_array as $qualify_id=> $que_value){
                        $qualify_data['contents']=$que_value;
                        $this->program_model->update_program_qualify($qualify_id, $qualify_data); //집어넣기
                    }

                    //qna
                    foreach ($question_array as $pqna_id=> $qna_value){
                        $qna_data['question']=$qna_value;
                        $qna_data['answer']=$answer_array[$pqna_id];
                        $this->program_model->update_program_qna($pqna_id, $qna_data); //집어넣기
                    }


                }else{ //새로 쓰기

                    //ajax로 데이터 추가하는것
                    $qualify_array = $this->input->post('input_qualify');
                    $question_array = $this->input->post('input_question');
                    $answer_array = $this->input->post('input_answer');
                    $date_array = $this->input->post('input_event_date');
                    $time_array = $this->input->post('input_event_time');


                    $data['heart_count'] = 0;
                    $data['hit'] = 0;
                    $data['crt_date'] = date('Y-m-d H:i:s');
                    $program_id = $this->program_model->insert_program($data);
                    //qualify

                    $array_basic = array( //이거 3개 고정
                        'user_id'=>$user_id,
                        'program_id' => $program_id,
                        'crt_date'=> $data['crt_date'],
                    );

                    $pdate_data = $array_basic;
                    $pqualify_data = $array_basic; // 복사
                    $pqna_data = $array_basic;


                    //날짜와.. 시간 입력한다..
                    foreach ($date_array as $pdt_key=> $pdt_value){
                        $pdate_data['date']=$pdt_value;
                        $pdate_data['time']=$time_array[$pdt_key];
//                        print_r($pdate_data);
                        $this->program_model->insert_program_date($pdate_data); //집어넣기
                    }

                    foreach ($qualify_array as $pqa_key=> $pqa_value){
                        $pqualify_data['contents']=$pqa_value;
//                        print_r($pqualify_data);
                        $this->program_model->insert_program_qualify($pqualify_data); //집어넣기
                    }

                    //qna
                    foreach ($question_array as $pqs_key=> $pqs_value){
                        $pqna_data['question']=$pqs_value;
                        $pqna_data['answer']=$answer_array[$pqs_key];
//                        print_r($pqna_data);
                        $this->program_model->insert_program_qna($pqna_data); //집어넣기
                    }


                }

                //thumbs는 저장이 모두 끝난 후에 한다..
                //thumbs 지정 안했을 경우에는 그냥 안한다..
                //null일 경우에 아무것도 안함

                //thumb 지정.. thumbs_helper 이용한다..

                $thumbs['thumb_url'] = thumbs_upload('program', $program_id); // 바로 업데이트
                if(!is_null($thumbs['thumb_url'] )){
                    $this->program_model->update_program($program_id,$thumbs);
                }


                //다 끝나면 redirect
               redirect($at_url.'/program/'.$program_id);
            }else{ //없으면 글쓰기 화면

                if($type=='modify'){
                    $program_id = $this->uri->segment(4);
                    $data = $this->program_model->get_program_info($program_id); //이것도 중복될 수 없으니까 unique 임

                    $team_info = $this->team_model->get_team_info($data['team_id']);
                    $date_info = $this->program_model->load_program_date_info_by_p_id($program_id);
                    $qna_info = $this->program_model->load_program_qna_info_by_p_id($program_id);
                    $qualify_info = $this->program_model->load_program_qualify_info_by_p_id($program_id); //이것도 중복될 수 없으니까 unique 임

                }else{

                    if(is_null($team_id)) alert('프로그램을 올릴 팀을 선택해주세요.'.$team_id);
                    $data = array(
                        'team_id'=>$team_id, //무조건 고정
                        'title'=>null,
                        'contents'=>null,
                        'participant'=>null,

                        'district'=>null,
                        'venue'=>null,
                        'price'=>null,

                        'address'=>null,

                        'status'=>'on',
                        'thumbs_url'=>null,//기본 섬네일 지정,
                        'type'=>'new' //새로 글쓰기
                    );
                    $team_info = $this->team_model-> get_team_info($team_id);
                    $date_info = array();
                    $qna_info = array();
                    $qualify_info = array();
                }

                $this->layout->view('program/upload', array('user'=>$user_data,'result'=>$data, 'team_info'=>$team_info,
                    'date_info'=>$date_info,'qna_info'=>$qna_info,'qualify_info'=>$qualify_info));
            }
        }


    }

    function heart($program_id){ //좋아요
        $user_id = $this->data['user_id'];
        if($user_id==0){
            echo 'login';
        }else{
            //이미 내가 누른지 확인
            $today = date('Y-m-d H:i:s');
            $heart_info = $this->heart_model->get_program_heart_user($user_id, $program_id);
            $program_info = $this->program_model->get_program_info($program_id); //detail_product

            if($heart_info==null){ // 안눌렀으면 새로 쓰기
                $heart_data = array(
                    'program_id'=>$program_id,
                    'user_id'=>$user_id,
                    'crt_date'=>$today,
                );
                $this->heart_model->insert_program_heart($heart_data);

                $detail_data['heart_count'] =$program_info['heart_count']+1;
                $this->program_model->update_program($program_info['program_id'], $detail_data);

                echo 'done';

            }else{// 눌렀으면 누른거 취소
                //이 episode bookmark에 하나 빼기
                $this->heart_model->delete_program_heart($heart_info['program_heart_id']); //취소 - 아예 지운다

                $detail_data['heart_count'] =$program_info['heart_count']-1;
                $this->program_model->update_program($program_info['program_id'], $detail_data);

                echo 'cancel';
            }
        }
    }

    function delete_event_date(){

        $user_id = $this->data['user_id'];
        $program_id = $this->input->post('program_id');
        $pdate_id = $this->input->post('pdate_id');

        //내 프로그램인지 검사
        //내 퀄리파이인지 검사
        $program_info = $this->program_model->get_program_info($program_id);
        $pdate_info = $this->program_model->get_program_date_info($pdate_id);
        if($program_info['user_id']!=$user_id||$pdate_info['user_id']!=$user_id){
            echo json_encode('auth');
        }else{
            $this->program_model-> delete_program_date($pdate_id);
            echo json_encode('done');
        }

    }
    function delete_qualify(){

        $user_id = $this->data['user_id'];
        $program_id = $this->input->post('program_id');
        $qualify_id = $this->input->post('qualify_id');

        //내 프로그램인지 검사
        //내 퀄리파이인지 검사
        $program_info = $this->program_model->get_program_info($program_id);
        $qualify_info = $this->program_model->get_program_qualify_info($qualify_id);

        if($program_info['user_id']!=$user_id||$qualify_info['user_id']!=$user_id){

            echo json_encode('auth');
        } else{ //모든 검사 다 통과하면 지운다.
            $this->program_model-> delete_program_qualify($qualify_id);

            echo json_encode('done');
        }
    }

    function delete_qna(){

        $user_id = $this->data['user_id'];
        $program_id = $this->input->post('program_id');
        $pqna_id = $this->input->post('pqna_id');

        //내 프로그램인지 검사
        //내 퀄리파이인지 검사
        $program_info = $this->program_model->get_program_info($program_id);
        $qna_info = $this->program_model->get_program_qna_info($pqna_id);

        if($program_info['user_id']!=$user_id||$qna_info['user_id']!=$user_id){

            echo json_encode('auth');
        } else{ //모든 검사 다 통과하면 지운다.
            $this->program_model-> delete_program_qna($pqna_id);
            echo json_encode('done');
        }
    }

    function add_event_date(){

        $user_id = $this->data['user_id'];
        $program_id = $this->input->post('program_id');

        //내 프로그램인지 검사
        //내 퀄리파이인지 검사
        $program_info = $this->program_model->get_program_info($program_id);

        if($program_info['user_id']!=$user_id){
            echo 'auth';
        } else{ //모든 검사 다 통과하면 지운다.
            $data = array(
                'user_id'=>$user_id,
                'program_id'=>$program_id,
                'date'=>date('Y-m-d'),
                'time'=>date('H'),
                'crt_date'=>date('Y-m-d H:i:s'),

            );
            $pdate_id = $this->program_model-> insert_program_date($data);
            echo $pdate_id;
        }
    }
    function add_qualify(){

        $user_id = $this->data['user_id'];
        $program_id = $this->input->post('program_id');

        //내 프로그램인지 검사
        //내 퀄리파이인지 검사
        $program_info = $this->program_model->get_program_info($program_id);

        if($program_info['user_id']!=$user_id){
            echo 'auth';
        } else{ //모든 검사 다 통과하면 지운다.
            $data = array(
                'user_id'=>$user_id,
                'program_id'=>$program_id,
                'contents'=>null,
                'crt_date'=>date('Y-m-d H:i:s'),

            );
            $qualify_id = $this->program_model-> insert_program_qualify($data);
            echo $qualify_id;
        }
    }

    function add_qna(){

        $user_id = $this->data['user_id'];
        $program_id = $this->input->post('program_id');

        //내 프로그램인지 검사
        //내 퀄리파이인지 검사
        $program_info = $this->program_model->get_program_info($program_id);

        if($program_info['user_id']!=$user_id){
            echo 'auth';
        } else{ //모든 검사 다 통과하면 지운다.
            $data = array(
                'user_id'=>$user_id,
                'program_id'=>$program_id,
                'question'=>null,
                'answer'=>null,
                'crt_date'=>date('Y-m-d H:i:s'),

            );
            $pqna_id = $this->program_model-> insert_program_qna($data);
            echo $pqna_id;
        }
    }

    function load_event_date(){
        $program_id = $this->input->post('program_id');
        $date_list = $this->program_model->load_program_date_info_by_p_id($program_id); //근데 이게 왜 있어야하지..
        echo json_encode($date_list);
    }

}
