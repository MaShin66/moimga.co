<?php
class Member_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    //load, get, update, delete

    function load_team_member($type = '', $offset = '', $limit = '', $search_query){
        $this->db->select('team_member.*, users.nickname, users.realname, users.email, team_member.crt_date as set_date,, team.name as team_name');
        $this->db->join('users','users.id = team_member.user_id');
        $this->db->join('team','team.team_id = team_member.team_id');

        if(!is_null($search_query['user_id'])) {
            $this->db->where('team_member.user_id',$search_query['user_id']);
        }
        if(!is_null($search_query['team_id'])) {
            $this->db->where('team_member.team_id',$search_query['team_id']);
        }
        if(!is_null($search_query['search'])){

            $name_query = '(team.title like "%'.$search_query['search'].'%" or users.realname like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if(!is_null($search_query['crt_date'])){
            $this->db->order_by('team_member.crt_date',$search_query['crt_date']);
        }else{
            $this->db->order_by('team_member.crt_date','desc');
        }
        if(!is_null($search_query['type'])){
            $this->db->where('team_member.type',$search_query['type']);
        }

        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('team_member');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();

        }
        return $result;
    }

    function get_team_member_info($team_member_id)
    {//특정 필드에서 $team_member_id값이 이것 인것을 찾아라.
        $this->db->select('team_member.*, users.realname, team_member.crt_date as set_date');
        $this->db->join('users','users.id = team_member.user_id');
        $this->db->where('team_member_id' ,$team_member_id);

        $query = $this->db->get('team_member');
        $result = $query -> row_array();

        return $result;
    }


    function get_team_member_info_by_url($url_name)
    {
        $this->db->where('url_name' ,$url_name);

        $query = $this->db->get('team_member');
        $result = $query -> row_array();

        return $result;
    }
    function insert_team_member($data) {

        $result = $this->db->insert('team_member', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function update_team_member($team_member_id,$data){ // 전체 invoice null로 초기화

        $this->db->set($data);
        $this->db->where('team_member_id' ,$team_member_id);
        $this->db->update('team_member');

        return 0;
    }

    function delete_team_member($team_member_id){

        //삭제
        $this->db->where('team_member_id' ,$team_member_id);
        $this->db->delete('team_member');
        return 0;
    }
    function check_dup_member($user_id, $team_id){
        //is the user id team boss?

        $this->db->where('user_id' ,$user_id);
        $this->db->where('team_id' ,$team_id);
        $query_t = $this->db->get('team');
        $result_t = $query_t -> num_rows();

        if($result_t==0){ //no result -> can be a member

            $this->db->flush_cache();

            $this->db->where('user_id' ,$user_id);
            $this->db->where('team_id' ,$team_id);
            $query_m = $this->db->get('team_member');
            $result_m = $query_m -> num_rows();
            $result = $result_m;
        }else{ // yes -> cannot be a member


            $result = -2;
        }

        return $result;

    }

    function is_team_member($team_id, $user_id){// only team member (team boss not included)

        $this->db->where('user_id' ,$user_id);
        $this->db->where('team_id' ,$team_id);
        $query = $this->db->get('team_member');
        $result = $query -> row_array();
        if($query -> num_rows()>0){
            return $result['type']; //대표 1, 일반 멤버 2
        }else{
            return 3; //일반
        }
    }

    function get_team_member_by_user_id($team_id, $user_id){
        $this->db->where('user_id' ,$user_id);
        $this->db->where('team_id' ,$team_id);
        $query = $this->db->get('team_member');
        $result = $query -> row_array();
        return $result; //대표 1, 일반 멤버 2

    }
    function delete_team_member_by_team_id($team_id){

        $this->db->where('team_id', $team_id);
        $this->db->delete('team_member');
    }

}
