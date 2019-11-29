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
        $this->layout->view('info/terms', array('user'=>$user_data));

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
        $this->layout->view('info/privacy', array('user'=>$user_data));

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

        if($category=='search'&&$type=='q'){
            $category_id = 1;
            $search = $this->input->get('search');
            $search_query = array(
                'search' => $search,
            );

            $cate_cont =  $this->faq_model->load_faq('', '', '', $search_query);
            $cate_info['title']='검색 결과';
            $file = 'search';
        }else{

            $file = 'view';
            switch ($type){
                case 'view':

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

        }
        $cate_list = $this->faq_model->load_faq_category_plain();

        $result = $this->faq_model->get_faq_info_by_order($category_id, $faq_order);
        if(!is_null($result)){ // (view) 내용 있으면.. list인 경우에는 result가 null임 -- 조회수 설정
            $this->faq_model->update_faq_hit($result['faq_id']);
        }

        $this->layout->view('info/faq/'.$file, array('user'=>$user_data,'cate_list'=>$cate_list,'result'=>$result,'cate_cont'=>$cate_cont,'category'=>$category,
            'search'=>$search,'cate_info'=>$cate_info,'faq_order'=>$faq_order));
    }




}
