<?php
class Form_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    //load, get, update, delete

    function load_form(){

        $query = $this->db->get('form');
        $result = $query -> result_array();

        return $result;
    }

    function load_application_forms($application_id){
        $this->db->where('application_id' ,$application_id);
        $query = $this->db->get('form');
        $result = $query -> result_array();

        return $result;
    }

    function get_form_info($form_id)
    {//특정 필드에서 $form_id값이 이것 인것을 찾아라.
        $this->db->where('form_id' ,$form_id);

        $query = $this->db->get('form');
        $result = $query -> row_array();

        return $result;
    }


    function get_form_info_by_unique_id($unique_id)
    {
        $this->db->where('unique_id' ,$unique_id);

        $query = $this->db->get('form');
        $result = $query -> row_array();

        return $result;
    }
    function update_form($form_id,$data){ // 전체 invoice null로 초기화

        $this->db->set( $data);
        $this->db->where('form_id' ,$form_id);
        $this->db->update('form');

        return 0;
    }

    function delete_form($form_id){

        //삭제
        $this->db->where('form_id' ,$form_id);
        $this->db->delete('form');
        return 0;
    }



}
