<?php

class After_model extends CI_Model

{

    function __construct()

    {
        parent::__construct();
    }


    function get_after_info($after_id){

        $this->db->select('after.*, team.title as team_title, users.nickname as nickname');
        $this->db->join('team','team.team_id = after.team_id');
        $this->db->join('users','users.id = after.user_id');
        $this->db->where('after.after_id',$after_id);

        $query = $this->db->get('after');
        $result = $query -> row_array();

        return $result;
    }

    function insert_after($data) {

        $result = $this->db->insert('after', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function delete_after($after_id){

        $this->db->where('after_id', $after_id);
        $this->db->delete('after');
    }


    function update_after($after_id, $data){

        $this->db->where('after_id', $after_id);
        $this->db->update('after', $data);
        return $after_id;
    }

    //team 후기만 올리기로함


    function load_after($type = '', $offset = '', $limit = '', $search_query){
        $this->db->select('after.*, users.nickname,users.realname, team.name as team_name');
        $this->db->join('team','team.team_id = after.team_id');
        $this->db->join('users','users.id = after.user_id');

        if(!is_null($search_query['status'])){
            $this->db->where('after.status',$search_query['status']);
        }
        if(!is_null($search_query['team_id'])){
            $this->db->where('after.team_id', $search_query['team_id']); //아예 고정
        }

        if(!is_null($search_query['user_id'])){
            $this->db->where('after.user_id', $search_query['user_id']); //아예 고정
        }
        if(!is_null($search_query['search'])){
            //*팀 이름, 팀 title,  프로그램 title, 후기 쓴 사람, 후기 내용//*/

            $name_query = '(team.name like "%'.$search_query['search'].'%" or users.nickname like "%'.$search_query['search'].'%" 
            or after.contents like "%'.$search_query['search'].'%" or team.title like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if(!is_null($search_query['crt_date'])){
            $this->db->order_by('crt_date',$search_query['crt_date']);
        }else{

            $this->db->order_by('crt_date','desc');
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('after');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();

        }
        return $result;
    }
//
//
//
//    function load_after($type = '', $offset = '', $limit = '', $search_query){
//        $name_query_after = null; //야.. 다 쓸모가 있다..
//
//        if($search_query['type']=='all' || is_null($search_query['type'])){
//            //all인 경우에 team_id 를 기준으로 가져옴..
//            $this->db->select('after.*, users.nickname, team.name as team_name');
//            $this->db->join('team','team.team_id = after.team_id');
//            //after를 insert할 경우에 type=='program'이면 program_id 만 쓰지 말고 team_id도 쓴다.. 무조건 그렇게 해야됨 무조건!!!
//
//            if(!is_null($search_query['unique_id'])){
//                $this->db->where('after.team_id', $search_query['unique_id']); //아예 고정
//            }
//
//        }else{
//
//            $this->db->select('after.*, users.nickname, team.name as team_name, program.title as program_title');
//            $this->db->join('team','team.team_id = after.team_id');
//            if($search_query['type']=='program'){
//                $this->db->join('program','program.program_id = after.program_id');
//                $name_query_after = 'or program.title like "%'.$search_query['search'].'%"';
//            }
//            $this->db->where('after.type', $search_query['type']);
//
//            if(!is_null($search_query['unique_id'])){
//                $this->db->where('after.'.$search_query['type'].'_id', $search_query['unique_id']); //아예 고정
//            }
//
//        }
//
//
//        $this->db->join('users','users.id = after.user_id');
//
//        if(!is_null($search_query['status'])){
//            $this->db->order_by('after.status',$search_query['status']);
//        }
//
//
//        if(!is_null($search_query['search'])){
//            //*
//            //팀 이름, 팀 title,  프로그램 title, 후기 쓴 사람, 후기 내용
//            //*/
//            $name_query = '(team.name like "%'.$search_query['search'].'%" or users.nickname like "%'.$search_query['search'].'%"
//            or after.contents like "%'.$search_query['search'].'%" or team.title like "%'.$search_query['search'].'%" '.$name_query_after.')';
//            $this->db->where($name_query);
//
//        }
//        if(!is_null($search_query['crt_date'])){
//            $this->db->order_by('after.crt_date',$search_query['crt_date']);
//        }else{
//            $this->db->order_by('after.crt_date','desc');
//        }
//        if ($limit != '' || $offset != '') {
//            $this->db->limit($limit, $offset);
//        }
//
//        $query = $this->db->get('after');
//
//        if ($type == 'count') {
//            $result = $query -> num_rows();
//        } else {
//            $result = $query -> result_array();
//
//        }
//        return $result;
//    }

    function update_after_hit($after_id){ // sql 로만 해야한다니..

        $sql = "UPDATE after SET hit = hit + 1 WHERE after_id = ".$after_id ;
        $this->db->query($sql);

        return $after_id;
    }


}
