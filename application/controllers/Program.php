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

        $search = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'search'=>null,
                'status'=>'on',
                'team_id'=>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'status'=>'on', //무조건 공개
                'team_id'=>null,
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

        $this->layout->view('program/list', array('user'=>$user_data,'result'=>$data));
    }

    function view($program_id){

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
        $program_info = $this->program_model->get_program_info($program_id); //이것도 중복될 수 없으니까 unique 임
        $this->layout->view('/program/view', array('user'=>$user_data,'program_info'=>$program_info));

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
        $team_id = $this->input->get('team');
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

                $qualify_array = $this->input->post('qualify');

                $question_array = $this->input->post('question');
                $answer_array = $this->input->post('answer');
                $date_array = $this->input->post('input_event_date');
                $time_array = $this->input->post('input_event_time');

                $team_id = $this->input->post('team'); //여기서 가져온다

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
//                print_r($data);
//                print_r($question_array);
//                print_r($answer_array);
//                print_r($date_array);
//                print_r($time_array);

                if($type=='modify'){

                    $program_id = $this->input->post('program_id');
                    $this->program_model->update_program($program_id,$data);

                    //id있는것: 덮어 씌움, 없는것: 새로 입력
                    //qualify

                    //qna

                }else{ //새로 쓰기

                    $data['like_count'] = 0;
                    $data['crt_date'] = date('Y-m-d H:i:s');
                    $program_id = $this->program_model->insert_program($data);
                    //qualify

                    $array_basic = array( //이거 3개 고정
                        'user_id'=>$user_id,
                        'program_id' => $program_id,
                        'crt_date'=> $data['crt_date'],
                    );

                    $pqualify_data = $array_basic; // 복사
                    $pqna_data = $array_basic;
                    $pdate_data = $array_basic;

                    foreach ($qualify_array as $pqa_key=> $pqa_value){
                        $pqualify_data['contents']=$pqa_value;
                        $this->program_model->insert_program_qualify($pqualify_data); //집어넣기
                    }

                    //qna
                    foreach ($question_array as $pqs_key=> $pqs_value){
                        $pqna_data['question']=$pqs_value;
                        $pqna_data['answer']=$answer_array[$pqs_key];
                        $this->program_model->insert_program_qualify($pqna_data); //집어넣기
                    }

                    //날짜와.. 시간 입력한다..
                    foreach ($date_array as $pdt_key=> $pdt_value){
                        $pdate_data['date']=$pdt_value;
                        $pdate_data['time']=$time_array[$pqs_key];
                        $this->program_model->insert_program_date($pdate_data); //집어넣기
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
                redirect('/program/view/'.$program_id);
            }else{ //없으면 글쓰기 화면


                if(is_null($team_id)) alert('프로그램을 올릴 팀을 선택해주세요.'.$team_id);
                if($type=='modify'){

                    $program_id = $this->uri->segment(3);
                    $data = $this->program_model->get_program_info($program_id); //이것도 중복될 수 없으니까 unique 임

                }else{

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
                }
                $team_info = $this->team_model-> get_team_info($team_id);
                $this->layout->view('program/upload', array('user'=>$user_data,'result'=>$data, 'team_info'=>$team_info));
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
            $like_info = $this->like_model->get_program_like_user($user_id, $program_id);
            $program_info = $this->program_model->get_program_info($program_id); //detail_product

            if($like_info==null){ // 안눌렀으면 새로 쓰기
                $like_data = array(
                    'program_id'=>$program_id,
                    'user_id'=>$user_id,
                    'crt_date'=>$today,
                );
                $this->like_model->insert_program_like($like_data);

                $detail_data['like'] =$program_info['like']+1;
                $this->program_model->update_moim($program_info['program_id'], $detail_data);

                echo 'done';

            }else{// 눌렀으면 누른거 취소
                //이 episode bookmark에 하나 빼기
                $this->like_model->delete_program_like($like_info['program_like_id']); //취소 - 아예 지운다

                $detail_data['like'] =$program_info['like']-1;
                $this->program_model->update_moim($program_info['program_id'], $detail_data);

                echo 'cancel';
            }
        }
    }

    function delete_qualify(){

        $user_id = $this->data['user_id'];
        $program_id = $this->post->input('program_id');
        $qualify_id = $this->post->input('qualify_id');

        //내 프로그램인지 검사
        //내 퀄리파이인지 검사
        $program_info = $this->program_model->get_program_info($program_id);
        $qualify_info = $this->program_model->get_program_qualify_info($qualify_id);

        if($program_info['user_id']!=$user_id||$qualify_info['user_id']!=$user_id){
            echo 'auth';
        } else{ //모든 검사 다 통과하면 지운다.
            $this->program_model-> delete_program_qualify($qualify_id);
            echo 'done';
        }
    }

    function delete_qna(){

        $user_id = $this->data['user_id'];
        $program_id = $this->post->input('program_id');
        $qna_id = $this->post->input('qna_id');

        //내 프로그램인지 검사
        //내 퀄리파이인지 검사
        $program_info = $this->program_model->get_program_info($program_id);
        $qna_info = $this->program_model->get_program_qna_info($qna_id);

        if($program_info['user_id']!=$user_id||$qna_info['user_id']!=$user_id){
            echo 'auth';
        } else{ //모든 검사 다 통과하면 지운다.
            $this->program_model-> delete_program_qna($qna_id);
            echo 'done';
        }
    }
}
