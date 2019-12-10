<?php

class Subscribe_model extends CI_Model

{
    function __construct()
    {
        parent::__construct();
    }
    function get_subscribe_info_team_user($user_id, $team_id){

        $this->db->where('user_id',$user_id);
        $this->db->where('team_id',$team_id);

        $query = $this->db->get('subscribe');
        $result = $query -> row_array();

        return $result;
    }

    function get_subscribe_info($subscribe_id=null){

        $this->db->where('subscribe_id',$subscribe_id);
        $query = $this->db->get('subscribe');
        $result = $query -> row_array();

        return $result;
    }

    function insert_subscribe($data) {

        $result = $this->db->insert('subscribe', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function delete_subscribe($subscribe_id){

        $this->db->where('subscribe_id', $subscribe_id);
        $this->db->delete('subscribe');
    }


    function update_subscribe($subscribe_id, $data){

        $this->db->where('subscribe_id', $subscribe_id);
        $this->db->update('subscribe', $data);
        return $subscribe_id;
    }


    function load_subscribe_by_user_id($user_id, $offset='',$limit='',$type=''){

        $this->db->from('subscribe');
        $this->db->join('team', 'team.team_id = subscribe.team_id');
        $this->db->where('subscribe.user_id',$user_id);

        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();

        if($type=='count'){

            $result = $query -> num_rows();
        }else{

            $result = $query -> result_array();
        }

        return $result;
    }
    function delete_team_subscribe($team_id){

        $this->db->where('team_id', $team_id);
        $this->db->delete('subscribe');
    }


    function load_subscribe($type = '', $offset = '', $limit = '', $search_query){
        $this->db->select('subscribe.*, users.nickname, users.realname, users.email, subscribe.crt_date as set_date, team.name as team_name');
        $this->db->join('users','users.id = subscribe.user_id');
        $this->db->join('team','team.team_id = subscribe.team_id');

        if(!is_null($search_query['user_id'])) {
            $this->db->where('subscribe.user_id',$search_query['user_id']);
        }
        if(!is_null($search_query['team_id'])) {
            $this->db->where('subscribe.team_id',$search_query['team_id']);
        }
        if(!is_null($search_query['search'])){

            $name_query = '(team.title like "%'.$search_query['search'].'%" or users.realname like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if(!is_null($search_query['crt_date'])){
            $this->db->order_by('subscribe.crt_date',$search_query['crt_date']);
        }else{
            $this->db->order_by('subscribe.crt_date','desc');
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('subscribe');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();

        }
        return $result;
    }


}
