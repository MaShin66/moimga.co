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


    function like($moim_id){ //좋아요
        $user_id = $this->data['user_id'];
        if($user_id==0){
            echo 'login';
        }else{
            //이미 내가 누른지 확인
            $today = date('Y-m-d H:i:s');
            $like_info = $this->like_model->get_moim_like_user($user_id, $moim_id);
            $moim_info = $this->moim_model->get_moim_info($moim_id); //detail_product

            if($like_info==null){ // 안눌렀으면 새로 쓰기
                $like_data = array(
                    'moim_id'=>$moim_id,
                    'user_id'=>$user_id,
                    'crt_date'=>$today,
                );
                $this->like_model->insert_moim_like($like_data);

                $detail_data['like'] =$moim_info['like']+1;
                $this->moim_model->update_moim($moim_info['moim_id'], $detail_data);

                echo 'done';

            }else{// 눌렀으면 누른거 취소
                //이 episode bookmark에 하나 빼기
                $this->like_model->delete_moim_like($like_info['moim_like_id']); //취소 - 아예 지운다

                $detail_data['like'] =$moim_info['like']-1;
                $this->moim_model->update_moim($moim_info['moim_id'], $detail_data);

                echo 'cancel';
            }
        }


    }
    



}
