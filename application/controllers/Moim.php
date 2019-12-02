<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Moim extends MY_Controller {

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
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );
        $this->layout->view('main', array('user'=>$user_data));
    }

    function info($url_name){ // moim 인포

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );
        $moim_info = $this->moim_model->get_moim_info_by_url($url_name); //이것도 중복될 수 없으니까 unique 임
        $app_list['open'] = $this->application_model->load_moim_application($moim_info['moim_id'],1);
        $app_list['close'] = $this->application_model->load_moim_application($moim_info['moim_id'],0);
        //지원서 목록 출력
        $this->layout->view('/moim/info', array('user'=>$user_data,'moim_info'=>$moim_info,'app_list'=>$app_list));


    }

    function view($url_name, $application_id){ // application view 보기


        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );
        $moim_info = $this->moim_model->get_moim_info_by_url($url_name); //이것도 중복될 수 없으니까 unique 임
        $app_info = $this->application_model->get_application_info($application_id); //이것도 중복될 수 없으니까 unique 임
        $this->layout->view('/moim/application/view', array('user'=>$user_data,'moim_info'=>$moim_info,'app_info'=>$app_info));

    }


}
