<?php
class Program_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    //load, get, update, delete

    function load_program($type = '', $offset = '', $limit = '', $search_query){
        $this->db->select('program.*, team.name as team_name, team.url as team_url, users.realname as realname, users.nickname as nickname');
        $this->db->join('team','team.team_id = program.team_id');
        $this->db->join('users','users.id = program.user_id');

        if(!is_null($search_query['crt_date'])){
            $this->db->order_by('program.crt_date',$search_query['crt_date']);
        }else{
            $this->db->order_by('program.crt_date','desc');
        }

        if(!is_null($search_query['status'])){
            $this->db->where('program.status',$search_query['status']);
        }

        if($search_query['team_id']!=null){
            $this->db->where('program.team_id',$search_query['team_id']);
        }

        if(!is_null($search_query['search'])){
            $name_query = '(program.title like "%'.$search_query['search'].'%" or program.contents like "%'.$search_query['search'].'%" or team.name like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

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
    {

        $this->db->select('program.*, users.realname as realname, users.nickname as nickname, team_member.type as member_type');
        $this->db->join('users','users.id = program.user_id');
        $this->db->join('team_member','team_member.user_id = program.user_id');

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

    function load_program_by_team_id($team_id){

        $this->db->where('team_id' ,$team_id);

        $query = $this->db->get('program');
        $result = $query -> result_array();

        return $result;
    }
    
    /*qualify*/

    function get_program_qualify_info($qualify_id)
    {

        $this->db->where('qualify_id' ,$qualify_id);

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

    function update_program_qualify($qualify_id,$data){

        $this->db->set( $data);
        $this->db->where('qualify_id' ,$qualify_id);
        $this->db->update('program_qualify');

        return 0;
    }

    function delete_program_qualify($qualify_id){

        //삭제
        $this->db->where('qualify_id' ,$qualify_id);
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

    function delete_program_option_by_program_id($table='date',$program_id){
        //이 프로그램 아이디로 작성된 것들 모두 지운다
        $this->db->where('program_id' ,$program_id);
        $this->db->delete('program_'.$table);
        return 0;
    }

    function update_program_hit($program_id){ // sql 로만 해야한다니..

        $sql = "UPDATE program SET hit = hit + 1 WHERE program_id = ".$program_id ;
        $this->db->query($sql);

        return $program_id;
    }

}
