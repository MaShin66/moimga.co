<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->model(array('user_model','team_model','form_model','program_model','after_model','like_model','subscribe_model','alarm_model'));

        if ($this->tank_auth->is_logged_in()) {									// logged in
            $this->data['user_id'] = $this->tank_auth->get_user_id();
            $this->data['username'] = $this->tank_auth->get_username($this->data['user_id']);
            $this->data['level'] = $this->tank_auth->get_level($this->data['user_id']);

            $this->data['alarm'] =$this->alarm_model->get_alarm_count($this->data['user_id']);
           $this->data['realname']  = $this->tank_auth->get_realname($this->data['user_id']);
            $this->data['status'] = 'yes';

        } else{
            if($this->uri->segment(1)!='auth'){
                $this->session->set_userdata('login_before', current_url());
            }
            $this->data['user_id'] = 0;
            $this->data['username'] = 'guest';
            $this->data['level'] =0;
            $this->data['status'] = 'no';
            $this->data['alarm'] =0;
        }

    }
}

class Admin_Controller extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->model(array('user_model','team_model','form_model','program_model','after_model','like_model','subscribe_model','partner_model','alarm_model'));
        //redirect('/welcome'); //업데이트
        if ($this->tank_auth->is_logged_in()) {									// logged in
            $this->data['user_id'] = $this->tank_auth->get_user_id();
            $this->data['username'] = $this->tank_auth->get_username($this->data['user_id']);
            $this->data['level'] = $this->tank_auth->get_level($this->data['user_id']);
            $this->data['alarm'] =$this->alarm_model->get_alarm_count($this->data['user_id']);
            $this->data['status'] = 'yes';

            if($this->data['level']!=9){

                redirect('/');
            }

        } else{
            redirect('/auth/login');
        }

    }
}


class Manage_Controller extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->model(array('user_model','team_model','form_model','program_model','after_model','like_model','subscribe_model','member_model','alarm_model'));
        //redirect('/welcome'); //업데이트
        if ($this->tank_auth->is_logged_in()) {									// logged in
            $this->data['user_id'] = $this->tank_auth->get_user_id();
            $this->data['username'] = $this->tank_auth->get_username($this->data['user_id']);
            $this->data['alarm'] =$this->alarm_model->get_alarm_count($this->data['user_id']);
            $this->data['level'] = $this->tank_auth->get_level($this->data['user_id']);
            $this->data['status'] = 'yes';

            //access 권한 주기..
            $location = $this->uri->segment(2);
            $section = $this->uri->segment(3);
            $unique_id = $this->uri->segment(4);

            $basic_info = $this->team_model->get_team_info($unique_id);
//            switch ($location){
//                //unique 로 얘만 moim_id를 사용
//                case 'team':
//                    $basic_info = $this->team_model->get_team_info($unique_id);
//                    break;
//
//                case 'program':
//                    $basic_info = $this->team_model->get_team_info($unique_id);
//                    break;
//
//                case 'team_member':
//                    $basic_info = $this->team_model->get_team_info($unique_id);
//                    break;
//                case 'blog':
//                    $basic_info = $this->team_model->get_team_info($unique_id);
//                    break;
//                case 'after':
//                    $basic_info = $this->team_model->get_team_info($unique_id);
//                    break;
//
//                //unique 로 모두 application_id 을 쓴다
//                default:
////                case 'application':
////                case 'after':
////                case 'deposit':
////                $basic_info = $this->program_model->get_application_info($unique_id);
//
//                    break;
//            }

            if($location!=null&&$section!='upload'){
                //redirect 하기
                if($basic_info['user_id']!=$this->data['user_id']){
                   // redirect('/');
                }
            }

        } else{
            redirect('/auth/login');
        }

    }
}
?>