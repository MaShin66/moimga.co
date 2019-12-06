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

        if(!is_null($search_query['user_id'])){
            $this->db->where('team.user_id',$search_query['user_id']);
        }

        //둘 중에 뭐가 더 먼저일까요..
        // 후기, crt_date, subscribe
        // 후기, subscribe 는 둘 다 선택 될 수 없다.
        // crt_date 는 무조건 후순위

        if($search_query['subscribe']!=null){
            $this->db->order_by('team.subscribe_count',$search_query['subscribe']);
        }
        if($search_query['after']!=null){
            $this->db->order_by('team.after_count',$search_query['after']);
        }

        if(!is_null($search_query['crt_date'])){
            $this->db->order_by('team.crt_date',$search_query['crt_date']);
        }else{
            $this->db->order_by('team.crt_date','desc');

        }

        if($search_query['status']!=null){
            $this->db->where('team.status',$search_query['status']);
        }

        /* 후기 까지 적는 쿼리 ..  후기를 입력할때마다 자동으로 count를 한번에 세도록 한다
      이유: 매번 list 뽑을 때마다 쿼리 날리는게 부하가 큼\
         *
SELECT * FROM team as A
        LEFT OUTER JOIN (
        SELECT team_id, COUNT(status) AS after_count
        FROM after
        WHERE status = 'on'
        GROUP BY team_id) as B
on (A.team_id = B.team_id)
where A.status = 'on'
GROUP BY A.team_id

        */

        if($search_query['search']!=null){

            $name_query = '(team.name like "%'.$search_query['search'].'%" or users.nickname like "%'.$search_query['search'].'%"
             or team.contents like "%'.$search_query['search'].'%" or team.title like "%'.$search_query['search'].'%" 
             or team.url like "%'.$search_query['search'].'%")';
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
        //내 포지션도 써야하는데..

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
        //team에서 찾기 .. team 장이나 멤버나 둘 다 여기에 들어가있다
        $this->db->where('user_id',$user_id);
        $this->db->where('team_id',$team_id);
        $query= $this->db->get('team_member');
        $result = $query -> num_rows();

        if($result>0){
            return true;
        }else{
            return false;
        }

    }

    /*blog*/

    function load_team_blog($type = '', $offset = '', $limit = '', $search_query){

        $this->db->select('team_blog.*, team.name as team_name');
        $this->db->join('team','team.team_id = team_blog.team_id');
        if(!is_null($search_query['team_id'])){
            $this->db->where('team_blog.team_id',$search_query['team_id']); //무조건 이 팀에만 걸린것으로 가져온다.
        }

        if(!is_null($search_query['crt_date'])){

            $this->db->order_by('team_blog.crt_date',$search_query['crt_date']);
        }else{
            $this->db->order_by('team_blog.crt_date','desc');

        }

        if(!is_null($search_query['status'])){
            $this->db->order_by('team_blog.status',$search_query['status']);
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
    function delete_team_blog_by_team_id($team_id){

        //삭제
        $this->db->where('team_id' ,$team_id);
        $this->db->delete('team_blog');
        return 0;
    }


    function is_post_opened($team_blog_id){

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

    //내가 멤버로 있는것과 동시에 출력하기..
    function load_assigned_team($type = '', $offset = '', $limit = '', $search_query){
//        //감동적 ㅠㅠㅠ
//        $this->db->select('team.*, ANY_VALUE(team.team_id),users.nickname, ANY_VALUE(team_member.user_id) as member_user_id');

        $this->db->select('team.*,users.nickname, team_member.user_id as member_user_id');
        $this->db->join('team','team.team_id = team_member.team_id');
        $this->db->join('users','users.id = team.user_id');

        if($search_query['crt_date']==null){
            $this->db->order_by('crt_date','desc');
        }else{
            $this->db->order_by('crt_date',$search_query['crt_date']);
        }

        if(!is_null($search_query['user_id'])){ // 둘다 가져온다
            $this->db->where('team_member.user_id',$search_query['user_id']);
            $this->db->or_where('team.user_id',$search_query['user_id']);
        }

        if(!is_null($search_query['status'])){
            $this->db->where('status',$search_query['status']);
        }else{
            $this->db->where('status','on');
        }

        if($search_query['search']!=null){

            $name_query = '(team.name like "%'.$search_query['search'].'%" or users.nickname like "%'.$search_query['search'].'%" or team.contents like "%'.$search_query['search'].'%" or team.title like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $this->db->group_by('team.team_id');
        $query = $this->db->get('team_member');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
        }

        return $result;
    }

    function update_team_hit($team_id){ // sql 로만 해야한다니..

        $sql = "UPDATE team SET hit = hit + 1 WHERE team_id = ".$team_id ;
        $this->db->query($sql);

        return $team_id;
    }

    /*delete 삭제되면 복사돼서 여기로 옮긴다*/
    function load_team_delete($type = '', $offset = '', $limit = '', $search_query){

        if($search_query['crt_date']==null){
            $this->db->order_by('crt_date','desc');
        }else{
            $this->db->order_by('crt_date',$search_query['crt_date']);
        }

        if($search_query['search']!=null){

            $name_query = '(team_delete.name like "%'.$search_query['search'].'%" or team_delete.contents like "%'.$search_query['search'].'%" or team_delete.title like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('team_delete');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();

        }
        return $result;
    }

    

    function get_team_delete_info($team_delete_id)
    {//특정 필드에서 $team_delete_id값이 이것 인것을 찾아라.
        $this->db->where('team_delete_id' ,$team_delete_id);

        $query = $this->db->get('team_delete');
        $result = $query -> row_array();

        return $result;
    }


    function get_team_delete_info_by_url($url)
    {
        $this->db->where('url' ,$url);

        $query = $this->db->get('team_delete');
        $result = $query -> row_array();

        return $result;
    }
    function insert_team_delete($data) {

        $result = $this->db->insert('team_delete', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function update_team_delete($team_delete_id,$data){ // 전체 invoice null로 초기화

        $this->db->set( $data);
        $this->db->where('team_delete_id' ,$team_delete_id);
        $this->db->update('team_delete');

        return 0;
    }

    function delete_team_delete($team_delete_id){

        //삭제
        $this->db->where('team_delete_id' ,$team_delete_id);
        $this->db->delete('team_delete');
        return 0;
    }


    function get_team_count($type='subscribe',$team_id){

        $this->db->select($type.'_count');
        $this->db->where('team_id' ,$team_id);

        $query = $this->db->get('team');
        $result = $query -> row_array();

        return $result[$type.'_count'];
    }
    

}
