<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(array('store_model'));

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

        $search = $this->uri->segment(4);
        $meta_title = '스토어 - 모임가';
        $meta_desc = '모임가 스토어 목록';
        $sort_search = null;

        if($search==null){
            $search_query = array(
                'search' => null,
                'status' =>'on',
                'crt_date' =>null,
                'category' =>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_category = $this->input->get('category');

            $search_query = array(
                'search' => $sort_search,
                'status' => 'on',
                'crt_date' => $sort_date,
                'category' => $sort_category,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&category='.$search_query['category'];

		$this->load->library('pagination');
		$config['suffix'] = $q_string;
		$config['base_url'] = '/store/lists'; // 페이징 주소
		$config['total_rows'] =$this->store_model->load_store_category('count','','',$search_query);

        $config['per_page'] = 8; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 3; // 페이지 번호가 위치한 세그먼트

        $config = pagination_config($config);

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

		$data['result'] = $this->store_model->load_store_category('', $start, $limit, $search_query);
		$data['total']=$config['total_rows'];

        if(($sort_search!=null || $sort_search !='')){
            $meta_title = '스토어 검색 > '.$sort_search.' - 모임가';
        }

        $meta_array = array(
            'location' => 'store',
            'section' => 'lists',
            'title' => $meta_title,
            'desc' => $meta_desc,
        );
		$this->layout->view('contents/list', array('user'=>$user_data, 'data'=>$data,'search_query'=>$search_query,'meta_array'=>$meta_array));
	}

    function view($store_id=''){

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

		$result = $this->store_model->get_store_info($store_id);
		if($result['status']=='on' || ($result['status']=='off' && $level==9 )){

            $this->store_model->update_store_hit($store_id);

            $text = substr($result['contents'], 0, 500);
            $text = addslashes($text);
            $content = strip_tags($text);
            $real_content = str_replace("&nbsp;", "", $content);

            $meta_array = array(
                'location' => 'store',
                'section' => 'view',
                'title' => $result['title'].' - 모임가',
                'desc' => $real_content,
                'img' => $result['thumb_url']
            );

            $this->layout->view('contents/view', array('user'=>$user_data,'result'=>$result,'meta_array'=>$meta_array));
        }else{

            alert($this->lang->line('hidden_alert'),'/store');
        }
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
        $title = $this->input->post('title');

        if($title){ //입력
			//새로 쓰는거

			$user_id = $this->input->post('user_id');
            $category_id = $this->input->post('category_id');
            $contents = $this->input->post('contents');
            $author = $this->input->post('author');
			$status = $this->input->post('status');

            if($status!='on'){
                $status = 'off';
            }

            $data=array(
                'title'=>$title,
                'user_id'=>$user_id,
                'category_id'=>$category_id,
                'author'=>$author,
                'contents'=>$contents,
                'status'=>$status,

            );

			if($write_type=='modify') {

				$store_id = $this->input->post('store_id');
                $store_info = $this->store_model->get_store_info($store_id);

				$this->store_model->update_store($store_id, $data);

			}else{

                $data['thumb_url'] = '/www/thumbs/store/basic.jpg'; //새로쓸때는 이렇게..
                $data['crt_date']= date("Y-m-d H:i:s");
                $store_id = $this->store_model->insert_store($data);

			}

            //thumb 지정.. thumbs_helper 이용한다..
            $thumbs['thumb_url'] = thumbs_upload('store', $store_id); // 바로 업데이트

            if(!is_null($thumbs['thumb_url'] )){ //파일을 업로드 했다는 뜻

                if($write_type=='modify'){  //만약 type== modify 면 이전의 파일을 지운다.
                    unlink(FCPATH . $store_info['thumb_url']);
                }
                $this->store_model->update_store($store_id,$thumbs);
            }


            redirect('/store/view/'.$store_id);
        }else{
			if($write_type=='modify'){

				$store_id=$this->input->get('id');
				$result = $this->store_model->get_store_info($store_id);

                $meta_title = '스토어 수정 - '.$result['title'].' - 모임가';
                $meta_desc = '모임가 스토어 수정';

				if($result['user_id']!=$user_id){
					redirect('/');
				}

			}else{
                $meta_title = '스토어 등록 - 모임가';
                $meta_desc = '모임가 스토어 등록';
				$result = array(
				    'category_id'=>null
                );

			}

            $meta_array = array(
                'location' => 'store',
                'section' => 'upload',
                'title' => $meta_title,
                'desc' => $meta_desc,
            );

            $cate_query = array(
                'search' => null,
                'crt_date' =>null,
                'category'=>null, //category_id
            );
			$cate_list = $this->store_model->load_store_category_plain('','','',$cate_query);
			$this->layout->view('store/upload', array('user'=>$user_data,'result'=>$result,'meta_array'=>$meta_array,'cate_list'=>$cate_list));
		}

	}

    function category(){

        $category_id = $this->input->get('category');

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
        if(is_null($category_id)){ //
            alert('스토어 카테고리를 선택해주세요.');
        }else{
            $category_info = $this->store_model->get_store_category_info($category_id);
        }

        $search = $this->uri->segment(4);
        $meta_title = $category_info['title'].' - 모임가';
        $meta_desc = '모임가 '.$category_info['title'].' 스토어 목록';
        $sort_search = null;

        if($search==null){
            $search_query = array(
                'search' => null,
                'category_id'=>$category_id,
                'crt_date'=>null,
                'status' =>'on',
            );

        }else{
            $sort_search = $this->input->get('search');
            $sort_crt_date = $this->input->get('crt_date');

            $search_query = array(
                'category_id'=>$category_id,
                'crt_date'=>$sort_crt_date,
                'search' => $sort_search,
                'status' => 'on',
            );

        }

        $q_string = '/q?search='.$search_query['search'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/contents/lists'; // 페이징 주소
        $config['total_rows'] =$this->store_model->load_store('count', null, null, $search_query);

        $config['per_page'] = 8; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 3; // 페이지 번호가 위치한 세그먼트

        $config = pagination_config($config);

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

        $data['result'] = $this->store_model->load_store('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        if(($sort_search!=null || $sort_search !='')){
            $meta_title = '스토어 > '.$sort_search.' - 모임가';
        }

        $meta_array = array(
            'location' => 'store',
            'section' => 'lists',
            'title' => $meta_title,
            'desc' => $meta_desc,
        );
        $this->layout->view('contents/category/list', array('user'=>$user_data, 'data'=>$data,'search_query'=>$search_query,'meta_array'=>$meta_array,'category_info'=>$category_info));


    }


}
