<?php
class Program_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    //load, get, update, delete

    function load_program($type = '', $offset = '', $limit = '', $search_query){
        $this->db->select('program.*, users.nickname');
        $this->db->join('users','users.id = program.user_id');

        if(!is_null($search_query['status'])){
            $this->db->order_by('status',$search_query['status']);
        }else{
            $this->db->order_by('status','on');
        }
        if(!is_null($search_query['team_id'])){

            $this->db->where('program.team_id',$search_query['team_id']);
        }

        if(!is_null($search_query['search'])){

            $name_query = '(program.title like "%'.$search_query['search'].'%" or users.nickname like "%'.$search_query['search'].'%" or program.contents like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }

        if(!is_null($search_query['crt_date'])){
            $this->db->order_by('crt_date',$search_query['crt_date']);
        }else{
            $this->db->order_by('crt_date','desc');
        }

        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('program');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();

        }
        return $result;
    }

    function get_program_info($program_id)
    {//특정 필드에서 $program_id값이 이것 인것을 찾아라.
        $this->db->where('program_id' ,$program_id);

        $query = $this->db->get('program');
        $result = $query -> row_array();

        return $result;
    }


    function get_program_info_by_url($url)
    {
        $this->db->where('url' ,$url);

        $query = $this->db->get('program');
        $result = $query -> row_array();

        return $result;
    }
    function insert_program($data) {

        $result = $this->db->insert('program', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function update_program($program_id,$data){ // 전체 invoice null로 초기화

        $this->db->set( $data);
        $this->db->where('program_id' ,$program_id);
        $this->db->update('program');

        return 0;
    }

    function delete_program($program_id){

        //삭제
        $this->db->where('program_id' ,$program_id);
        $this->db->delete('program');
        return 0;
    }
    
    /*qualify*/

    function get_program_qualify_info($pqualify_id)
    {

        $this->db->where('pqualify_id' ,$pqualify_id);

        $query = $this->db->get('program_qualify');
        $result = $query -> row_array();

        return $result;
    }

    function load_program_qualify_info_by_p_id($program_id) //전부 array로 들고오기
    {

        $this->db->where('program_id' ,$program_id);

        $query = $this->db->get('program_qualify');
        $result = $query -> result_array();

        return $result;
    }

    function insert_program_qualify($data) {

        $result = $this->db->insert('program_qualify', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function update_program_qualify($pqualify_id,$data){

        $this->db->set( $data);
        $this->db->where('pqualify_id' ,$pqualify_id);
        $this->db->update('program_qualify');

        return 0;
    }

    function delete_program_qualify($pqualify_id){

        //삭제
        $this->db->where('pqualify_id' ,$pqualify_id);
        $this->db->delete('program_qualify');
        return 0;
    }


    /*qna*/

    function get_program_qna_info($pqna_id)
    {

        $this->db->where('pqna_id' ,$pqna_id);

        $query = $this->db->get('program_qna');
        $result = $query -> row_array();

        return $result;
    }

    function load_program_qna_info_by_p_id($program_id) //전부 array로 들고오기
    {

        $this->db->where('program_id' ,$program_id);

        $query = $this->db->get('program_qna');
        $result = $query -> result_array();

        return $result;
    }

    function insert_program_qna($data) {

        $result = $this->db->insert('program_qna', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function update_program_qna($pqna_id,$data){

        $this->db->set( $data);
        $this->db->where('pqna_id' ,$pqna_id);
        $this->db->update('program_qna');

        return 0;
    }

    function delete_program_qna($pqna_id){

        //삭제
        $this->db->where('pqna_id' ,$pqna_id);
        $this->db->delete('program_qna');
        return 0;
    }


    /*date time*/

    function get_program_date_info($pdate_id)
    {

        $this->db->where('pdate_id' ,$pdate_id);

        $query = $this->db->get('program_date');
        $result = $query -> row_array();

        return $result;
    }

    function load_program_date_info_by_p_id($program_id) //전부 array로 들고오기
    {

        $this->db->where('program_id' ,$program_id);

        $query = $this->db->get('program_date');
        $result = $query -> result_array();

        return $result;
    }

    function insert_program_date($data) {

        $result = $this->db->insert('program_date', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function update_program_date($pdate_id,$data){

        $this->db->set( $data);
        $this->db->where('pdate_id' ,$pdate_id);
        $this->db->update('program_date');

        return 0;
    }

    function delete_program_date($pdate_id){

        //삭제
        $this->db->where('pdate_id' ,$pdate_id);
        $this->db->delete('program_date');
        return 0;
    }

}
