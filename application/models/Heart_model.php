<?php

class Heart_model extends CI_Model

{

    function __construct()

    {
        parent::__construct();

    }
    function get_program_heart_user($user_id, $program_id){
        $this->db->where('user_id',$user_id);
        $this->db->where('program_id',$program_id);

        $query = $this->db->get('program_heart');
        $result = $query -> row_array();

        return $result;
    }


    function get_program_heart_info($program_heart_id=null){

        if($program_heart_id==null){
            $this->db->order_by('mod_date','desc');
            $this->db->limit(1);
        }else{

            $this->db->where('program_heart_id',$program_heart_id);

        }
        $query = $this->db->get('program_heart');
        $result = $query -> row_array();

        return $result;
    }

    function insert_program_heart($data) {

        $result = $this->db->insert('program_heart', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function delete_program_heart($program_heart_id){

        $this->db->where('program_heart_id', $program_heart_id);
        $this->db->delete('program_heart');
    }


    function update_program_heart($program_heart_id, $data){

        $this->db->where('program_heart_id', $program_heart_id);
        $this->db->update('program_heart', $data);
        return $program_heart_id;
    }


    function load_program_heart($offset='',$limit='',$type=''){

        $this->db->order_by('crt_date','desc');
        $this->db->where('status','on');
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('program_heart');

        if($type=='count'){

            $result = $query -> num_rows();
        }else {
            $result = $query -> result_array();

        }
        return $result;
    }


    function load_program_heart_by_user_id($user_id, $offset='',$limit='',$type=''){

        $this->db->from('program_heart');
        $this->db->join('program', 'program.program_id = program_heart.program_id');
        $this->db->where('program_heart.user_id',$user_id);

        $this->db->order_by('product.close_date','asc'); //종료날 오름차순
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();

        if($type=='count'){

            $result = $query -> num_rows();
        }else{

            $result = $query -> result_array();
            $today = date("Y-m-d H:i:s",time());
            foreach ($result as $key => $item){

            }
        }

        return $result;
    }

}
