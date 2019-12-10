<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alarm extends Mypage_Controller
{
//https://www.codeigniter.com/userguide3/helpers/form_helper.html
    function __construct()
    {
        parent::__construct();
        $this->load->database();

        if ($this->session->userdata('language') == null) {
            $lang_text = 'korean';
        } else {
            $lang_text = $this->session->userdata('language');
        }
        $this->config->set_item('language', $lang_text);
        $this->lang->load('alarm');

        $this->load->library('tank_auth');
        $this->load->library('layout', 'layouts/default');
        $this->layout->setLayout("layouts/default");

    }

    function index()
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

        header('Content-Type: text/html; charset=UTF-8');
        $alarms = $this->alarm_model->load_alarm($user_id, 'unread');
        $read_alarms = $this->alarm_model->load_alarm($user_id, 'read');
        $alarm_result = array_merge($alarms, $read_alarms);
        //모든 내용 읽음 처리 된다..
        $this->alarm_model->set_alarm_read_all($user_id);

        //7일까지의 내용만 가져온다는거 있어야함
        //알람 기본입니다..
        //알람이 트위터식으로 보이는게 좋을까요 아니면 작은 모달로? 저는 트위터식을 추천합니다.. 그게 만들기 쉬우니까 ㅎ
        $this->layout->view('alarm/list', array('alarms' => $alarm_result, 'user' => $user_data));
    }

}