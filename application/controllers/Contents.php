<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contents extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(array('contents_model'));

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
        $meta_title = '콘텐츠 - 모임가';
        $meta_desc = '모임가 콘텐츠 목록';
        $sort_search = null;

        if($search==null){
            $search_query = array(
                'search' => null,
                'status' =>'on',
                'category' =>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_category = $this->input->get('category');

            $search_query = array(
                'search' => $sort_search,
                'status' => 'on',
                'category' => $sort_category,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&category='.$search_query['category'];

		$this->load->library('pagination');
		$config['suffix'] = $q_string;
		$config['base_url'] = '/contents/lists'; // 페이징 주소
		$config['total_rows'] =$this->contents_model->load_contents_category('count','','',$search_query);

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

		$data['result'] = $this->contents_model->load_contents_category('', $start, $limit, $search_query);
		$data['total']=$config['total_rows'];

        if(($sort_search!=null || $sort_search !='')){
            $meta_title = '콘텐츠 검색 > '.$sort_search.' - 모임가';
        }

        $meta_array = array(
            'location' => 'contents',
            'section' => 'lists',
            'title' => $meta_title,
            'desc' => $meta_desc,
        );
        //category에 따라 다르게 보이게
        $this->layout->view('contents/list', array('user'=>$user_data, 'data'=>$data,'search_query'=>$search_query,'meta_array'=>$meta_array));


    }

    function view($contents_id=''){

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

		$result = $this->contents_model->get_contents_info($contents_id);
		if($result['status']=='on' || ($result['status']=='off' && $level==9 )){

            $this->contents_model->update_contents_hit($contents_id);

            $text = substr($result['contents'], 0, 500);
            $text = addslashes($text);
            $content = strip_tags($text);
            $real_content = str_replace("&nbsp;", "", $content);

            $meta_array = array(
                'location' => 'contents',
                'section' => 'view',
                'title' => $result['title'].' - 모임가',
                'desc' => $real_content,
                'img' => $result['thumb_url']
            );
            $result['new_date'] = get_kr_date($result['crt_date'] );


            $this->layout->view('contents/view', array('user'=>$user_data,'result'=>$result,'meta_array'=>$meta_array));
        }else{

            alert($this->lang->line('hidden_alert'),'/contents');
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
			$contents = $this->input->post('contents');
            $status = $this->input->post('status');
            $author= $this->input->post('author');
            $category_id = $this->input->post('category_id');

            if($status!='on'){
                $status = 'off';
            }

            $data=array(
                'title'=>$title,
                'user_id'=>$user_id,
                'category_id'=>$category_id,
                'contents'=>$contents,
                'author'=>$author,
                'status'=>$status,

            );

			if($write_type=='modify') {

				$contents_id = $this->input->post('contents_id');
                $contents_info = $this->contents_model->get_contents_info($contents_id);

				$this->contents_model->update_contents($contents_id, $data);

			}else{

                $data['thumb_url'] = '/www/thumbs/contents/basic.jpg'; //새로쓸때는 이렇게..
                $data['crt_date']= date("Y-m-d H:i:s");
                $contents_id = $this->contents_model->insert_contents($data);

			}

            //thumb 지정.. thumbs_helper 이용한다..
            $thumbs['thumb_url'] = thumbs_upload('contents', $contents_id,'horizontal'); // 바로 업데이트

            if(!is_null($thumbs['thumb_url'] )){ //파일을 업로드 했다는 뜻

                if($write_type=='modify'){  //만약 type== modify 면 이전의 파일을 지운다.
                    unlink(FCPATH . $contents_info['thumb_url']);
                }
                $this->contents_model->update_contents($contents_id,$thumbs);
            }


            redirect('/contents/view/'.$contents_id);
        }else{
			if($write_type=='modify'){

				$contents_id=$this->input->get('id');
				$result = $this->contents_model->get_contents_info($contents_id);

                $meta_title = '콘텐츠 수정 - '.$result['title'].' - 모임가';
                $meta_desc = '모임가 콘텐츠 수정';

				if($result['user_id']!=$user_id){
					redirect('/');
				}

			}else{
                $meta_title = '콘텐츠 등록 - 모임가';
                $meta_desc = '모임가 콘텐츠 등록';

                $result = array(
                    'category_id'=>null
                );

			}

            $meta_array = array(
                'location' => 'contents',
                'section' => 'upload',
                'title' => $meta_title,
                'desc' => $meta_desc,
            );

            $cate_list = $this->contents_model->load_contents_category_plain();
			$this->layout->view('contents/upload', array('user'=>$user_data,'result'=>$result,'meta_array'=>$meta_array,'cate_list'=>$cate_list));
		}


	}

    function category($page=null){

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
            alert('콘텐츠 카테고리를 선택해주세요.');
        }else{
            $category_info = $this->contents_model->get_contents_category_info($category_id);
        }

        $search = $this->uri->segment(4);
        $meta_title = $category_info['title'].' - 모임가';
        $meta_desc = '모임가 '.$category_info['title'].' 콘텐츠 목록';
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
        $config['total_rows'] =$this->contents_model->load_contents('count', null, null, $search_query);

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

        $data['result'] = $this->contents_model->load_contents('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        if(($sort_search!=null || $sort_search !='')){
            $meta_title = '콘텐츠 > '.$sort_search.' - 모임가';
        }

        $meta_array = array(
            'location' => 'contents',
            'section' => 'lists',
            'title' => $meta_title,
            'desc' => $meta_desc,
        );
        $this->layout->view('contents/category/list', array('user'=>$user_data, 'data'=>$data,'search_query'=>$search_query,'meta_array'=>$meta_array,'category_info'=>$category_info));


    }



}
