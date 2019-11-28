<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mypage extends MY_Controller {

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
       $this->info();
    }
 
    function info(){//내 정보
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];

        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
            'alarm' =>$alarm_cnt,
        );
        $this->layout->view('mypage/main', array('user'=>$user_data));
    }
    function after($type='lists', $after_id=null){ //내가 쓴 후기

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];

        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
            'alarm' =>$alarm_cnt,
        );
        
        switch ($type){
            case 'detail':
                $this->_after_detail($after_id, $user_data);
                break;
            case 'status':

                $this->_after_status($user_data);
                break;
            case 'delete':

                $this->_after_delete($user_data);
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
                'status'=>null,
                'team_id'=>null,
                'user_id'=>$user_data['user_id']
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_status= $this->input->get('status');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,

                'team_id'=>null,
                'status'=>$sort_status,
                'user_id'=>$user_data['user_id']
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&status='.$search_query['status'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/mypage/after/lists'; // 페이징 주소
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

        $this->layout->view('mypage/after/lists', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }


    function _after_detail($after_id,$user_data){ //detail - 정보

        $after_info = $this->after_model->get_after_info($after_id);
        // 정보 가져오는건 권한과 상관 없음

        $this->layout->view('mypage/after/detail', array('user'=>$user_data,'after_info'=>$after_info));
    }

    function _after_status($user_data){ //detail - 정보

        $after_id = $this->input->post('after_id');
        $status = $this->input->post('status');
        //권한 확인
        $auth = $this->_is_mine($after_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth){ //권한이 있으면 상태 변경
            $status_data = array(
                'status'=>$status,
            );
            $this->after_model->update_after($after_id,$status_data);
            alert('이 후기가 '.$this->lang->line($status).'로 변경되었습니다.');

        }else{

            alert('권한이 없습니다. [MD01]');
        }

    }

    function _after_delete($user_data){ //unique_id!=moin_id

        $after_id = $this->input->post('after_id');

        $auth = $this->_is_mine($after_id, $user_data['user_id']); //권한 확인하는 함수

        if($auth){ //권한이 있으면 삭제
            $this->after_model->delete_after($after_id); //정말 삭제할지 .. 아니면 남겨둘 것인지.
            alert('후기가 삭제되었습니다. 내 후기 목록으로 이동합니다.','/mypage/after');

        }else{
            alert('권한이 없습니다. [MD02]');
        }

    }

    function _is_mine($after_id, $user_id){

        $level = $this->user_model->get_user_level($user_id);
        if($level==9){ //super users always return true;
            return true; //it ends the function.
        }
        $after_info = $this->after_model->get_after_info($after_id);
        if($after_info['user_id']==$user_id){
            return true;
        }else{
            return false;

        }

    }
    


}
