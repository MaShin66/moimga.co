<?php
class Application_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    //load, get, update, delete

    function load_application(){

        $query = $this->db->get('application');
        $result = $query -> result_array();

        return $result;
    }

    function get_application_info($application_id)
    {
        $this->db->join('application_date','application_date.application_id=application.application_id');
        $this->db->where('application.application_id' ,$application_id);

        $query = $this->db->get('application');
        $result = $query -> row_array();

        return $result;
    }

    function load_moim_application($moim_id,$status=null){

        if($status){//조건이 있으면

            $this->db->where('status' ,$status);
        }
        $this->db->where('moim_id' ,$moim_id);
        $query = $this->db->get('application');
        $result = $query -> result_array();

        return $result;
    }


    function insert_application($data) {

        $result = $this->db->insert('application', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function get_application_info_by_unique_id($unique_id)
    {
        $this->db->where('unique_id' ,$unique_id);

        $query = $this->db->get('application');
        $result = $query -> row_array();

        return $result;
    }
    function update_application($application_id,$data){

        $this->db->set( $data);
        $this->db->where('application_id' ,$application_id);
        $this->db->update('application');

        return 0;
    }

    function delete_application($application_id){

        //삭제
        $this->db->where('application_id' ,$application_id);
        $this->db->delete('application');
        return 0;
    }

    /*날짜*/
    function insert_application_date($data) {

        $result = $this->db->insert('application_date', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }


    function update_application_date($app_date_id,$data){

        $this->db->set( $data);
        $this->db->where('app_date_id' ,$app_date_id);
        $this->db->update('application_date');

        return 0;
    }


}
