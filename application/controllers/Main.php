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

        $this->layout->view('main', array('user'=>$user_data));
    }


}
