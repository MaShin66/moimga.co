<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->load->model(array('user_model','moim_model','form_model','application_model'));

        if ($this->tank_auth->is_logged_in()) {									// logged in
            $this->data['user_id'] = $this->tank_auth->get_user_id();
            $this->data['username'] = $this->tank_auth->get_username($this->data['user_id']);
            $this->data['level'] = $this->tank_auth->get_level($this->data['user_id']);

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
        $this->load->model(array('user_model','moim_model','form_model','application_model'));
        //redirect('/welcome'); //업데이트
        if ($this->tank_auth->is_logged_in()) {									// logged in
            $this->data['user_id'] = $this->tank_auth->get_user_id();
            $this->data['username'] = $this->tank_auth->get_username($this->data['user_id']);
            $this->data['level'] = $this->tank_auth->get_level($this->data['user_id']);
            $this->data['status'] = 'yes';

            if($this->data['level']!=9){

                redirect('/');
            }


        } else{
            redirect('/auth/login');
        }

    }
}

?>