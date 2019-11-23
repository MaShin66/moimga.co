<?php

class After_model extends CI_Model

{

    function __construct()

    {
        parent::__construct();
    }


    function get_after_info($after_id){

        $this->db->select('after.*, program.title as program_title, users.nickname as nickname');
        $this->db->join('program','program.program_id = after.program_id');
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


    function load_after($type = '', $offset = '', $limit = '', $search_query){
        $this->db->select('after.*, users.nickname, team.name as team_name, program.title as program_title');
        $this->db->join('program','program.program_id = after.program_id');
        $this->db->join('team','team.team_id = program.team_id');
        $this->db->join('users','users.id = after.user_id');

        if(!is_null($search_query['status'])){
            $this->db->order_by('status',$search_query['status']);
        }else{
            $this->db->order_by('status','on');
        }

        if(!is_null($search_query['search'])){
            //*
            //팀 이름, 팀 title,  프로그램 title, 후기 쓴 사람, 후기 내용
            //*/

            $name_query = '(team.name like "%'.$search_query['search'].'%" or users.nickname like "%'.$search_query['search'].'%" 
            or after.contents like "%'.$search_query['search'].'%" or team.title like "%'.$search_query['search'].'%" or program.title like "%'.$search_query['search'].'%")';
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



}
