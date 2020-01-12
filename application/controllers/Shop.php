<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(array('shop_model'));

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
        $meta_title = '샵 - 모임가';
        $meta_desc = '모임가 샵 목록';
        $sort_search = null;

        if($search==null){
            $search_query = array(
                'search' => null,
                'status' =>'on',
                'crt_date' =>null,
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');

            $search_query = array(
                'search' => $sort_search,
                'status' => 'on',
                'crt_date' => $sort_date,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'];

		$this->load->library('pagination');
		$config['suffix'] = $q_string;
		$config['base_url'] = '/shop/lists'; // 페이징 주소
		$config['total_rows'] =$this->shop_model->load_shop('count','','',$search_query);

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

		$data['result'] = $this->shop_model->load_shop('', $start, $limit, $search_query);
		$data['total']=$config['total_rows'];

        if(($sort_search!=null || $sort_search !='')){
            $meta_title = '샵 검색 > '.$sort_search.' - 모임가';
        }

        $meta_array = array(
            'location' => 'shop',
            'section' => 'lists',
            'title' => $meta_title,
            'desc' => $meta_desc,
        );
		$this->layout->view('shop/list', array('user'=>$user_data, 'data'=>$data,'search_query'=>$search_query,'meta_array'=>$meta_array));
	}

    function view($shop_id=''){

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

		$result = $this->shop_model->get_shop_info($shop_id);
		if($result['status']=='on' || ($result['status']=='off' && $level==9 )){

            $this->shop_model->update_shop_hit($shop_id);

            $text = substr($result['contents'], 0, 500);
            $text = addslashes($text);
            $content = strip_tags($text);
            $real_content = str_replace("&nbsp;", "", $content);

            $meta_array = array(
                'location' => 'shop',
                'section' => 'view',
                'title' => $result['title'].' - 모임가',
                'desc' => $real_content,
                'img' => $result['thumb_url']
            );

            $this->layout->view('shop/view', array('user'=>$user_data,'result'=>$result,'meta_array'=>$meta_array));
        }else{

            alert($this->lang->line('hidden_alert'),'/shop');
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
			$status = $this->input->post('status');

            if($status!='on'){
                $status = 'off';
            }

            $data=array(
                'title'=>$title,
                'user_id'=>$user_id,
                'category_id'=>$category_id,
                'contents'=>$contents,
                'status'=>$status,

            );

			if($write_type=='modify') {

				$shop_id = $this->input->post('shop_id');
                $shop_info = $this->shop_model->get_shop_info($shop_id);

				$this->shop_model->update_shop($shop_id, $data);

			}else{

                $data['thumb_url'] = '/www/thumbs/shop/basic.jpg'; //새로쓸때는 이렇게..
                $data['crt_date']= date("Y-m-d H:i:s");
                $shop_id = $this->shop_model->insert_shop($data);

			}

            //thumb 지정.. thumbs_helper 이용한다..
            $thumbs['thumb_url'] = thumbs_upload('shop', $shop_id); // 바로 업데이트

            if(!is_null($thumbs['thumb_url'] )){ //파일을 업로드 했다는 뜻

                if($write_type=='modify'){  //만약 type== modify 면 이전의 파일을 지운다.
                    unlink(FCPATH . $shop_info['thumb_url']);
                }
                $this->shop_model->update_shop($shop_id,$thumbs);
            }


            redirect('/shop/view/'.$shop_id);
        }else{
			if($write_type=='modify'){

				$shop_id=$this->input->get('id');
				$result = $this->shop_model->get_shop_info($shop_id);

                $meta_title = '샵 수정 - '.$result['title'].' - 모임가';
                $meta_desc = '모임가 샵 수정';

				if($result['user_id']!=$user_id){
					redirect('/');
				}

			}else{
                $meta_title = '샵 등록 - 모임가';
                $meta_desc = '모임가 샵 등록';
				$result = array(
				    'category_id'=>null
                );

			}

            $meta_array = array(
                'location' => 'shop',
                'section' => 'upload',
                'title' => $meta_title,
                'desc' => $meta_desc,
            );

			$cate_list = $this->shop_model->load_shop_category_plain();
			$this->layout->view('shop/upload', array('user'=>$user_data,'result'=>$result,'meta_array'=>$meta_array,'cate_list'=>$cate_list));
		}


	}


}
