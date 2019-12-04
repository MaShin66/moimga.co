<?php

class Heart_model extends CI_Model

{

    function __construct()

    {
        parent::__construct();

    }
    
    function get_heart_user($type='team', $user_id, $unique_id){
        $this->db->where('user_id',$user_id);
        $this->db->where($type.'_id',$unique_id);

        $query = $this->db->get($type.'_heart');
        $result = $query -> row_array();

        return $result;
    }


    function get_heart_info($type='team',$heart_id=null){

        $this->db->where($type.'_heart_id',$heart_id);
        $query = $this->db->get($type.'_heart');
        $result = $query -> row_array();

        return $result;
    }

    function insert_heart($type='team', $data) {

        $result = $this->db->insert($type.'_heart', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function delete_heart($type='team', $heart_id){

        $this->db->where($type.'_heart_id', $heart_id);
        $this->db->delete($type.'_heart');
    }


    function update_heart($type='team', $heart_id, $data){

        $this->db->where($type.'_heart_id', $heart_id);
        $this->db->update($type.'_heart', $data);
        return $heart_id;
    }


    function load_heart($table='team', $offset='',$limit='',$type=''){

        $this->db->order_by('crt_date','desc');
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get($table.'_heart');

        if($type=='count'){

            $result = $query -> num_rows();
        }else {
            $result = $query -> result_array();

        }
        return $result;
    }

}
