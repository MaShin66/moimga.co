<?php

class Deposit_model extends CI_Model

{

    function __construct()

    {
        parent::__construct();
    }

    /*여기서는 deposit_id가 PK 지만 허가받은 1개의 폼에 대해서만 deposit info를 쓸 수 있으므로 form_id 를 key로 사용한다.*/
    function get_deposit_info($form_id){

        $this->db->where('form_id',$form_id);

        $query = $this->db->get('deposit');
        $result = $query -> row_array();

        return $result;
    }

    function insert_deposit($data) {

        $result = $this->db->insert('deposit', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function delete_deposit($form_id){

        $this->db->where('form_id', $form_id);
        $this->db->delete('deposit');
    }

    function update_deposit($form_id, $data){

        $this->db->where('form_id', $form_id);
        $this->db->update('deposit', $data);
        return $form_id;
    }

    function set_deposit_status($form_id, $status=1){

        $this->db->set('status',$status);
        if($status==1){
            $this->db->set('done_date',date('Y-m-d H:i:s'));
        }else{
            $this->db->set('done_date',null);
        }

        $this->db->where('form_id', $form_id);
        $this->db->update('deposit');
        return $form_id;
    }

    function load_deposit($type, $offset='',$limit='',$search_query){

        $this->db->order_by('crt_date','desc');
        $this->db->where('status','on');
        if($search_query['application_id']!=null){
            $this->db->where('application_id',$search_query['application_id']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('deposit');

        if($type=='count'){

            $result = $query -> num_rows();
        }else {
            $result = $query -> result_array();

        }
        return $result;
    }





}
