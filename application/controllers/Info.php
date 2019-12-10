<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Info extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model(array('user_model','faq_model'));

        $this->load->library('tank_auth');
        $this->load->library('layout', 'layouts/default');
        $this->layout->setLayout("layouts/default");

    }


    public function index()
    {
        $this->terms();

    }

    function terms(){

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level,
            'alarm' =>$alarm_cnt,
            'username' =>$this->data['username'],
        );
        $meta_array = array(
            'location' => 'info',
            'section' => 'basic',
            'title' => '이용약관 - 모임가',
            'desc' => '모임가 이용약관',
        );

        $this->layout->view('info/terms', array('user'=>$user_data,'meta_array'=>$meta_array));

    }

    function privacy()
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level,
            'alarm' =>$alarm_cnt,
            'username' =>$this->data['username'],
        );
        $meta_array = array(
            'location' => 'info',
            'section' => 'basic',
            'title' => '개인정보보호정책 - 모임가',
            'desc' => '모임가 개인정보보호정책',
        );
        $this->layout->view('info/privacy', array('user'=>$user_data,'meta_array'=>$meta_array));

    }


    function faq($category='tmm', $type='list', $faq_order= 1)
    {

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

        $search = null;

        $meta_title = '자주묻는질문 - 모임가';
        $meta_desc = '모임가 자주묻는질문'; //기본

        if($category=='search'&&$type=='q'){
            $category_id = 1;
            $search = $this->input->get('search');
            $search_query = array(
                'search' => $search,
            );

            $cate_cont =  $this->faq_model->load_faq('', '', '', $search_query);
            $cate_info['title']='검색 결과';
            $file = 'search';
            $meta_section = 'lists';
            $meta_title = '자주묻는질문 - 검색 > '.$search.' - 모임가';
            $meta_desc = '모임가 자주묻는질문 검색';
        }else{

            $meta_section = 'lists'; //기본
            $file = 'view';
            switch ($type){
                case 'view':
                    $meta_section = 'view';
                    $category_id = $this->faq_model->get_category_id($category);
                    //get_faq_id 해야한다..
                    break;
                case 'list':
                    $category_id = $this->faq_model->get_category_id($category);
                    $faq_order = 1;
                    break;
                default:

                    $category_id = 1;
                    $faq_order = 1;
                    break;
            }

            $cate_info = $this->faq_model->get_faq_category_info($category_id);
            $cate_cont = $this->faq_model->load_category_contents($category_id);
            if($cate_info){ //있으면
                $meta_title = '자주묻는질문 - '.$cate_info['name'].' - 모임가';
            }


        }
        $cate_list = $this->faq_model->load_faq_category_plain();

        $result = $this->faq_model->get_faq_info_by_order($category_id, $faq_order);
        if(!is_null($result)){ // (view) 내용 있으면.. list인 경우에는 result가 null임 -- 조회수 설정
            $this->faq_model->update_faq_hit($result['faq_id']);

            $text = substr($result['contents'], 0, 500);
            $text = addslashes($text);
            $content = strip_tags($text);
            $real_content = str_replace("&nbsp;", "", $content);

            $meta_title = $result['title'].' - 모임가';
            $meta_desc = $real_content;
        }
        $meta_array = array(
            'location' => 'faq',
            'section' => $meta_section,
            'title' => $meta_title,
            'desc' => $meta_desc
        );

        $this->layout->view('info/faq/'.$file, array('user'=>$user_data,'cate_list'=>$cate_list,'result'=>$result,'cate_cont'=>$cate_cont,'category'=>$category,
            'search'=>$search,'cate_info'=>$cate_info,'faq_order'=>$faq_order,'meta_array'=>$meta_array));
    }




}
