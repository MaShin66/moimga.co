<?php

class Verify_model extends CI_Model

{



    function __construct()

    {

        parent::__construct();

    }


    function get_verify_info($verify_id){

        $this->db->where('verify_id',$verify_id);

        $query = $this->db->get('verify');
        $result = $query -> row_array();

        return $result;
    }

    function get_verify_by_user_id($user_id){

        $this->db->where('user_id',$user_id);
        $query = $this->db->get('verify');
        $result = $query -> row_array();

        return $result;
    }

    function get_verify_by_DI($DI){

        $this->db->where('DI',$DI);

        $query = $this->db->get('verify');
        $result = $query -> row_array();

        return $result;
    }

    function insert_verify($data) {

        $result = $this->db->insert('verify', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function insert_verify_try($data) {

        $result = $this->db->insert('verify_try', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }
    function delete_verify($verify_id){

        $this->db->where('verify_id', $verify_id);
        $this->db->delete('verify');
    }


    function update_verify($verify_id, $data){

        $this->db->where('verify_id', $verify_id);
        $this->db->update('verify', $data);
        return $verify_id;
    }




    function load_verify($offset='',$limit='',$type=''){

        $this->db->order_by('crt_date','desc');
        $this->db->where('status','on');
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('verify');

        if($type=='count'){

            $result = $query -> num_rows();
        }else {
            $result = $query -> result_array();

        }
        return $result;
    }





}
