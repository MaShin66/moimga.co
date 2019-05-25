<?php

class After_model extends CI_Model

{

    function __construct()

    {
        parent::__construct();
    }


    function get_after_info($after_id){

        $this->db->where('after_id',$after_id);

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




    function load_after($offset='',$limit='',$type=''){

        $this->db->order_by('crt_date','desc');
        $this->db->where('status','on');
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('after');

        if($type=='count'){

            $result = $query -> num_rows();
        }else {
            $result = $query -> result_array();

        }
        return $result;
    }


}
