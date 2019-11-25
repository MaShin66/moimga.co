<?php
class Team_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    //load, get, update, delete

    function load_team($type = '', $offset = '', $limit = '', $search_query){
        $this->db->select('team.*, users.nickname');
        $this->db->join('users','users.id = team.user_id');

        if($search_query['crt_date']==null){
            $this->db->order_by('crt_date','desc');
        }else{
            $this->db->order_by('crt_date',$search_query['crt_date']);
        }
        if(!is_null($search_query['status'])){
            $this->db->order_by('status',$search_query['status']);
        }else{
            $this->db->order_by('status','on');
        }

        if($search_query['search']!=null){

            $name_query = '(team.name like "%'.$search_query['search'].'%" or users.nickname like "%'.$search_query['search'].'%" or team.contents like "%'.$search_query['search'].'%" or team.title like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('team');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();

        }
        return $result;
    }

    function get_team_info($team_id)
    {//특정 필드에서 $team_id값이 이것 인것을 찾아라.
        $this->db->where('team_id' ,$team_id);

        $query = $this->db->get('team');
        $result = $query -> row_array();

        return $result;
    }


    function get_team_info_by_url($url)
    {
        $this->db->where('url' ,$url);

        $query = $this->db->get('team');
        $result = $query -> row_array();

        return $result;
    }

    function get_team_id_by_url($url)
    {
        $this->db->select('team_id');
        $this->db->where('url' ,$url);

        $query = $this->db->get('team');
        $result = $query -> row_array();

        return $result['team_id'];
    }


    function insert_team($data) {

        $result = $this->db->insert('team', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function update_team($team_id,$data){ // 전체 invoice null로 초기화

        $this->db->set( $data);
        $this->db->where('team_id' ,$team_id);
        $this->db->update('team');

        return 0;
    }

    function delete_team($team_id){

        //삭제
        $this->db->where('team_id' ,$team_id);
        $this->db->delete('team');
        return 0;
    }

    function has_team($user_id){

        $this->db->where('user_id' ,$user_id);

        $query = $this->db->get('team');
        $result = $query -> num_rows();
        if($result>0){
            return true;
        }else{

            return false;
        }
    }

    function as_member($team_id, $user_id){
        //team에서 찾기
        $this->db->where('user_id',$user_id);
        $this->db->where('team_id',$team_id);
        $query = $this->db->get('team');
        $result = $query -> num_rows();
        if($result>0){
            return true;
        }else{ //team member에서 찾기
            $this->db->flush_cache();

            $this->db->where('user_id',$user_id);
            $this->db->where('team_id',$team_id);
            $query_m = $this->db->get('team_member');
            $result_m = $query_m -> num_rows();

            if($result_m>0){
                return true;
            }else{
                return false;
            }
        }

    }

    /*blog*/

    function load_team_blog($type = '', $offset = '', $limit = '', $search_query){

        $this->db->where('team_id',$search_query['team_id']); //무조건 이 팀에만 걸린것으로 가져온다.
        if($search_query['crt_date']==null){
            $this->db->order_by('crt_date','desc');
        }else{
            $this->db->order_by('crt_date',$search_query['crt_date']);
        }
        if(!is_null($search_query['status'])){
            $this->db->order_by('status',$search_query['status']);
        }else{
            $this->db->order_by('status','on');
        }

        if($search_query['search']!=null){

            $name_query = '(team_blog.name title "%'.$search_query['search'].'%" or team_blog.contents like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('team_blog');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();

        }
        return $result;
    }

    function get_team_blog_info($team_blog_id)
    {//특정 필드에서 $team_blog_id값이 이것 인것을 찾아라.
        $this->db->where('team_blog_id' ,$team_blog_id);

        $query = $this->db->get('team_blog');
        $result = $query -> row_array();

        return $result;
    }
    function insert_team_blog($data) {

        $result = $this->db->insert('team_blog', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function update_team_blog($team_blog_id,$data){ // 전체 invoice null로 초기화

        $this->db->set( $data);
        $this->db->where('team_blog_id' ,$team_blog_id);
        $this->db->update('team_blog');

        return 0;
    }

    function delete_team_blog($team_blog_id){

        //삭제
        $this->db->where('team_blog_id' ,$team_blog_id);
        $this->db->delete('team_blog');
        return 0;
    }

    function is_opened($team_blog_id){

        $this->db->select('status');
        $this->db->where('team_blog_id' ,$team_blog_id);

        $query = $this->db->get('team_blog');
        $result = $query -> row_array();

        return $result['status'];
    }

    function update_team_blog_hit($team_blog_id){ // sql 로만 해야한다니..

        $sql = "UPDATE team_blog SET hit = hit + 1 WHERE team_blog_id = ".$team_blog_id ;
        $this->db->query($sql);

        return $team_blog_id;
    }


}
