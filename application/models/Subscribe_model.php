<?php

class Subscribe_model extends CI_Model

{
    function __construct()
    {
        parent::__construct();
    }
    function get_subscribe_info_prod_user($user_id, $team_id){

        $this->db->where('user_id',$user_id);
        $this->db->where('team_id',$team_id);

        $query = $this->db->get('subscribe');
        $result = $query -> row_array();

        return $result;
    }

    function get_subscribe_info($subscribe_id=null){

        if($subscribe_id==null){
            $this->db->order_by('mod_date','desc');
            $this->db->limit(1);
        }else{

            $this->db->where('subscribe_id',$subscribe_id);

        }
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
    function load_subscribe($offset='',$limit='',$type=''){

        $this->db->order_by('crt_date','desc');
        $this->db->where('status','on');
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('subscribe');

        if($type=='count'){

            $result = $query -> num_rows();
        }else {
            $result = $query -> result_array();

        }
        return $result;
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

}
