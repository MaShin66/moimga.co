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

        //team
        $search_query = array(
            'crt_date' => null,
            'search'=>null,
            'user_id'=>null,
            'status'=>'on',
            'after'=>null,
            'subscribe'=>null,
            'heart'=>null,
            'team_id'=>null, //program
            'price'=>null, //program
            'event'=>null,//program
            'login_user'=>$user_id,
        );

        $team_list = $this->team_model->load_team('', 0, 3,$search_query);

        foreach ($team_list as $d_key => $d_item){ //desc 가져오기

            $team_list[$d_key]['contents'] = tag_strip($d_item['contents']);
            if(!is_null($d_item['program'])){
                $day_array = get_kr_date($team_list[$d_key]['program']['date']);
                $team_list[$d_key]['program']['event_date'] = $day_array['kr_date'];
                $team_list[$d_key]['program']['weekday'] = $day_array['weekday'];
            }
        }

        $program_list = $this->program_model->load_program('', 0, 4,$search_query);

        foreach ($program_list as $p_key => $p_item){ //desc 가져오기
            $program_list[$p_key]['contents'] = tag_strip($p_item['contents']);

            $day_array = get_kr_date($program_list[$p_key]['event_date']);
            $program_list[$p_key]['event_date'] =  $day_array['kr_date'];
            $program_list[$p_key]['weekday'] =  $day_array['weekday'];
        }

        $after_list = $this->after_model->load_after('', 0, 4,$search_query);

        foreach ($after_list as $a_key => $a_item){ //desc 가져오기
            $after_list[$a_key]['contents'] = tag_strip($a_item['contents']);
        }


        $main_info = $this->main_model->get_latest_main();
        
        $this->layout->view('main/main', array('user'=>$user_data,'meta_array'=>$meta_array,
            'team_list'=>$team_list,'program_list'=>$program_list,'after_list'=>$after_list,'main_info'=>$main_info));
    }


}
