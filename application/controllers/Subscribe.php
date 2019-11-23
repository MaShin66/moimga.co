<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscribe extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('tank_auth');
        $this->load->library('layout', 'layouts/default');
        $this->layout->setLayout("layouts/default");

    }


    public function index()  { }

    function register($team_id){ // 북마크
        $user_id = $this->data['user_id'];
        if($user_id==0){
            echo 'login';
        }else{
            //이미 내가 누른지 확인
            $today = date('Y-m-d H:i:s');
            $mark_info = $this->subscribe_model->get_subscribe_info_prod_user($user_id, $team_id);
            $team_info = $this->team_model->get_team_info($team_id); //detail_product

            if($mark_info==null){ // 안눌렀으면 새로 쓰기
                //이벤트 용으로 돈 주기
                //이 episode subscribe에 하나 추가
                $subscribe_data = array(
                    'team_id'=>$team_id,
                    'user_id'=>$user_id,
                    'crt_date'=>$today,
                );
                $this->subscribe_model->insert_subscribe($subscribe_data);

                    //product_detail
                $detail_data['subscribe_count'] =$team_info['subscribe_count']+1;
                $this->team_model->update_team($team_info['team_id'], $detail_data);

                //알람 넣기

                echo 'done';

            }else{// 눌렀으면 누른거 취소
                //이 episode subscribe에 하나 빼기
                $this->subscribe_model->delete_subscribe($mark_info['subscribe_id']); //취소 - 아예 지운다

                $detail_data['subscribe_count'] =$team_info['subscribe_count']-1;
                $this->team_model->update_team($team_info['team_id'], $detail_data);

                echo 'cancel';
            }
        }


    }
}
