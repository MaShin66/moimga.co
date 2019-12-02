<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller { //통합검색
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

        $this->main();
    }

    function main(){
        //들어오자마자

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $alarm_cnt = $this->data['alarm'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'username' =>$this->data['username'],
            'level' =>$level,
            'alarm' =>$alarm_cnt
        );

        $search = $this->input->get('search');
        if(is_null($search)){
            alert('검색어를 입력해주세요.','/');
        }else{

            $search_query = array( //둘다 동일한 search_query
                'crt_date' => null,
                'search'=>$search,
                'user_id'=>null,//load_after 때문에
                'status'=>'on', //무조건 공개
                'team_id'=>null,
            );
            $team_list =  $this->team_model->load_team('',0,8,$search_query);
            $program_list =  $this->program_model->load_program('',0,8,$search_query);
            $after_list =  $this->after_model->load_after('',0,8,$search_query);

            $this->layout->view('search/main', array('user'=>$user_data, 'team_list'=>$team_list,
                'program_list'=>$program_list,'after_list'=>$after_list,'search_query'=>$search_query));
        }

    }

    function title($type){
        //제목만 검색함 type에 따라서
        //제목, id 등 검색 기준에 맞춰서 검색에 걸리도록 함
        $search = $this->input->post('search');
        if(!is_null($search)){

            $search_query = array(
                'crt_date' => null,
                'search' => $search,
                'user_id'=>null, //because of load_after
                'status'=>'on', //무조건 공개
            );

            switch ($type){
                case 'after':
                    $result =  $this->after_model->load_after('', '', '',$search_query);;
                    break;
                case 'program':
                    $search_query['team_id']=null;
                    $result = $this->program_model->load_program('', '', '',$search_query);
                    break;
                default:
                case 'team':
                    $result =  $this -> team_model->load_team('','','',$search_query);
                    break;
            }
            echo json_encode($result);
        }else{
            echo 0; //검색어를 입력하세요
        }
    }
    function team_url(){

        $team_url = $this->input->post('team_url');
        $result = $this->team_model->get_team_info_by_url($team_url);

        echo json_encode($result); //
    }
}
