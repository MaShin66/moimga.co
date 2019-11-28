<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(array('blog_model'));

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
			'level' => $level,
            'alarm' =>$alarm_cnt
		);
		$this->load->library('pagination');

		//$config['suffix'] = $q_string;
		$config['base_url'] = '/blog/lists'; // 페이징 주소

		$config['total_rows'] =$this->blog_model->load_blog('','','count');// 게시물 전체 개수

        $config['per_page'] = 8; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 3; // 페이지 번호가 위치한 세그먼트

        $config = $this->pagination_config($config);

		$config['attributes'] = array('class' => 'page-link');
        $config['use_page_numbers'] = TRUE;
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

		$data['result'] = $this->blog_model->load_blog($start,$limit,'');
		$data['total']=$config['total_rows'];


		$this->layout->view('blog/list', array('user'=>$user_data, 'data'=>$data));
	}

    function view($blog_id=''){

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

		$result = $this->blog_model->get_blog_info($blog_id);
        $this->layout->view('blog/view', array('user'=>$user_data,'result'=>$result));

    }
    
    function upload(){

		$status = $this->data['status'];
		$user_id = $this->data['user_id'];
		$level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];
		$user_data = array(
			'status' => $status,
            'username' =>$this->data['username'],
			'user_id' => $user_id,
			'level' =>$level,
            'alarm' =>$alarm_cnt
		);

		if($level!=9) alert('접근할 수 없습니다');

		$write_type=$this->input->get('write');

		if($this->input->post('title')!=null){
			//새로 쓰는거

			$title = $this->input->post('title');
			$user_id = $this->input->post('user_id');
			$contents = $this->input->post('contents');
			$status = $this->input->post('status');


            $data=array(
                'title'=>$title,
                'user_id'=>$user_id,
                'contents'=>$contents,
                'status'=>$status,

            );

			if($write_type=='modify') {

				$blog_id = $this->input->post('blog_id');
				$result = $this->blog_model->update_blog($blog_id, $data);
				alert('수정되었습니다.','/blog/view/'.$blog_id);

			}else{


                $data['crt_date']= date("Y-m-d H:i:s");
				$new_blog_id = $this->blog_model->insert_blog($data);
				alert('글이 입력 되었습니다.','/blog/view/'.$new_blog_id);

			}
		}else{
			if($write_type=='modify'){

				$blog_id=$this->input->get('id');
				$result = $this->blog_model->get_blog_info($blog_id);

				if($result['user_id']!=$user_id){
					redirect('/');
				}


			}else{

				$result = array();

			}


			$this->layout->view('blog/upload', array('user'=>$user_data,'result'=>$result));
		}


	}



    function pagination_config($config){

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
