<?php
class Basic_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    //load, get, update, delete

    function load_table($table_name)
    {

        $query = $this->db->get($table_name);
        $result = $query -> result_array();

        return $result;
    }

    function get_data($unique_field, $unique_id, $table_name)
    {//특정 필드에서 $unique_id값이 이것 인것을 찾아라.
        $this->db->where($unique_field ,$unique_id);

        $query = $this->db->get($table_name);
        $result = $query -> row_array();

        return $result;
    }

    //근데 이건 잘 안쓸듯..
    function update_data($unique_field, $unique_id, $table_name,$update_field,$data){ // 전체 invoice null로 초기화

        $this->db->set($update_field, $data);
        $this->db->where($unique_field ,$unique_id);
        $this->db->update($table_name);

        return 0;
    }

    function delete_data($unique_field, $unique_id,$table_name){

        //삭제
        $this->db->where($unique_field ,$unique_id);
        $this->db->delete($table_name);
        return 0;
    }



}
