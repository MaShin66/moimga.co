<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends MY_Controller {

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
        $alarm_cnt = $this->data['alarm'];
        $user_data = array(
            'username' => $this->data['username'],
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level,
            'alarm' => $alarm_cnt
        );

        $meta_array = array(
            'location' => 'main',
            'section' => 'lists',
            'title' => '모임가',
            'desc' => '모임가는 어쩌구 저쩌구',
        );

        $this->layout->view('main', array('user'=>$user_data,'meta_array'=>$meta_array));
    }


}
