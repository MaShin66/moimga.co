<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Info extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
//        $this->load->model(array('user_model','faq_model'));

        $this->load->library('tank_auth');
        $this->load->library('layout', 'layouts/default');
        $this->layout->setLayout("layouts/default");

    }


    public function index()
    {

    }

    function terms(){

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );
        $this->layout->view('info/terms', array('user'=>$user_data));

    }

    function privacy()
    {

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );
        $this->layout->view('info/privacy', array('user'=>$user_data));

    }

    function pricing()
    {

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );
        $this->layout->view('info/pricing', array('user'=>$user_data));

    }



    function faq()
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );
        $search = $this->uri->segment(3);

        if($search==null){
            $search_query = array(
                'search' => '',
                'crt_date'=>null,
            );

        }else{
            $sort_search = $this->input->get('search');

            $search_query = array(
                'search' => $sort_search,
                'crt_date'=>null,
            );

        }
        $q_string = '/q?search='.$search_query['search'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/faq/list' ; // 페이징 주소
        $config['total_rows'] = $this -> faq_model -> load_faq('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 13; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 3; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this->uri->segment(3);
        if($page==null){
            $start=0;
        }else{
            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }


        $limit = $config['per_page'];

        $data['result'] = $this->faq_model->load_faq('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('info/faq', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));
    }

    function _pagination_config($config){

        $config['first_link'] = '≪';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '≫';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '＞';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '＜';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="page-item active"><a href="" class="page-link">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        $config['use_page_numbers'] = TRUE;

        return $config;
    }


}
