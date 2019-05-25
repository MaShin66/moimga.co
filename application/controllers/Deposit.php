<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deposit extends MY_Controller {

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

    function write($form_id){//이건 form_id여야한다..
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' => $level,
        );
        $deposit_info = $this->deposit_model ->get_deposit_info($form_id);
        $form_info = $this->form_model ->get_form_info($form_id);
        $write_type = $this->input->get('type');
        //나만 접속할 수 있음
        if(($deposit_info['user_id']!=$user_id)||($form_info['user_id']!=$user_id)){
            redirect('/');
        }
        if($deposit_info!=null&&$write_type==null){
            //이미 이 form_id로 작성한거 있고 && 수정이 아니면 절대 작성 못함
            alert('이미 이 폼으로 작성한 입금 정보가 있습니다. mypage에서 확인해주세요.','/mypage/deposit/view/'.$form_id);
        }else{
            //이제서야 작성할 수 있다..
            $app_info = $this->application_model->get_application_info($form_info['application_id']);
            if($this->input->post()){ //정보

                $form_data = $this->input->post();
                $deposit_date = $form_data['deposit_date'].' '.$form_data['time'].':00:00';

                $deposit_data = array(
                    'user_id'=>$user_data['user_id'],
                    'form_id'=>$form_data['form_id'],
                    'application_id'=>$form_data['application_id'],
                    'bank'=>$form_data['bank'],
                    'money'=>$form_data['money'],
                    'description'=>$form_data['description'],
                    'date'=>$deposit_date,

                );
                //날짜 정보...

                $redirect_url = '/mypage/deposit/view/'.$form_id;

                if($write_type=='modify'){ //수정

                    $this->application_model->update_deposit($form_id,$deposit_data);
                    //
                    alert('입금 정보가 수정되었습니다.',$redirect_url);
                }else{//등록
                    $app_data['crt_date'] = date('Y-m-d H:i:s');
                    $deposit_id = $this->application_model->insert_deposit($deposit_data);

                    alert('입금 정보가 제출되었습니다.',$redirect_url);
                }

            }else{

                if($deposit_info==null){

                    $deposit_info = array(
                        'bank'=>null,
                        'deposit_date'=>null,
                        'time'=>null,

                    );
                }

                $this->layout->view('deposit/write', array('user'=>$user_data,'deposit_info'=>$deposit_info,'app_info'=>$app_info));

            }



        }

    }

    function delete($form_id){//이건 form_id여야한다..


    }
    function view($form_id){//이건 form_id여야한다.. view는 mypage에서만 가능함..


    }
}
