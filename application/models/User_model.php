<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $table_name			= 'users';			// user accounts
    private $profile_table_name	= 'user_profiles';	// user profiles

    function __construct()
    {
        parent::__construct();

        $ci =& get_instance();
    }

    function load_users($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->select('*');
        $this->db->from('users');
        //조건문

        if($search_query['crt_date']==null){
            $this->db->order_by('created','desc');
        }else{
            $this->db->order_by('created',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
        }
        return $result;

    }


    function get_user_info($user_id){
        $this->db->where('id', $user_id);

        $query = $this->db->get('users');
        $data = $query->row_array();
        return $data;
    }
    function get_user_basic_info($user_id){
        $this->db->select('id, username, realname, nickname, email, level, sns_type, adult, verify');
        $this->db->where('id', $user_id);

        $query = $this->db->get('users');
        $data = $query->row_array();
        return $data;
    }
    function get_user_info_by_email($email){
        $this->db->where('email', $email);
        $this->db->limit(1); //하나만 가져온다..

        $query = $this->db->get('users');
        $data = $query->row_array();
        return $data;
    }
    function check_sns_user($sns_type,$unique_id){ //%username은 바뀔 수 있읔으

        //type, unique_id로 sns_login에서 찾는다..

        $this->db->where('unique_id',$unique_id);
        $this->db->where('sns_type',$sns_type);
        $query = $this->db->get('sns_login');

        if($query->num_rows()<1){ //없음
            $result = 0;
        }else{
            $data = $query->row_array();
            $result = $data['user_id']; //사용자 id
        }

        return $result;
    }

    function check_username($username){
        $this->db->where('username',$username);
        $query = $this->db->get('users');
        if($query->num_rows()!=1){
            $result = 0;
        }else{
            $result = 1;
        }

        return $result;
    }

    function check_email($email){
        $this->db->where('email',$email);
        $query = $this->db->get('users');
        $data = $query->row();

        if($query->num_rows()!=0){ // 값이 있으면
            $result = $data->username;
        }else{ // 데이터가 없으면
            $result = 0;
        }
        return $result;
    }

    function check_gen_email($email){
//        $this->db->where('level !=',5); //레벨이 5면 추가 안됨.. //레벨에 상관없이
        $this->db->where('email',$email);
        $query = $this->db->get('users');
        $data = $query->row();

        if($query->num_rows()!=0){ // 값이 있으면
            $result = $data->id; //user_id 출력해서 이걸 ajax로 보낸다.
        }else{ // 데이터가 없으면
            $result = 0;
        }
        return $result;
    }


    function get_realname($user_id)
    {
        $this->db->select('realname');
        $this->db->where('id', $user_id);

        $query = $this->db->get('users');
        $data = $query->row();
        return $data->realname;
    }
    function get_user_email($user_id)
    {
        $this->db->select('email');
        $this->db->where('id', $user_id);

        $query = $this->db->get('users');
        $data = $query->row_array();
        return $data['email'];
    }


    function get_user_type($user_id)
    {
        $this->db->select('user_type');
        $this->db->where('id', $user_id);

        $query = $this->db->get('users');
        $data = $query->row();
        return $data->user_type;
    }

    function get_user_level($user_id)
    {
        $this->db->select('level');
        $this->db->where('id', $user_id);

        $query = $this->db->get('users');
        $data = $query->row_array();
        return $data['level'];
    }

    function check_adult_unique($unique_id){

        $this->db->select('unique_id');
        $this->db->where('unique_id', $unique_id);
        $this->db->where('result', 1);

        $query = $this->db->get('adult_try');
        if($query->num_rows()>0){
            return 1;
        }else{
          return 0;
        }

    }

    function check_sns_profile($sns_type,$unique_id) //snsprofile에 완전히 기입되어있는지
    {
        $this->db->where('sns_type', $sns_type);
        $this->db->where('unique_id', $unique_id);
        $this->db->limit(1);

        $query = $this->db->get('sns_login');
        $data = $query->num_rows();
        if($data>0){
            return 1;
        }else{
            return 0;
        }
    }

    function isset_sns_login($user_id){

        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get('sns_login');
        $data = $query->row_array();
        if($query->num_rows()>0){ // 있는 사람
            return $data;
        }else{
            return 0;
        }
    }

    function update_sns_login($user_id,$data){

        $this->db->where('user_id', $user_id);
        $this->db->update('sns_login', $data);

        return false;
    }

    function get_user_sns_type($user_id){

        $this->db->select('sns_type');
        $this->db->where('id', $user_id);

        $query = $this->db->get('users');
        $data = $query->row_array();

        return $data['sns_type'];
    }
    function set_sns_login($data){

        $this->db->insert('sns_login', $data);

        $latest_id = $this->db->insert_id(); //4
        return $latest_id; //4
    }
	function update_users($user_id, $data){

        $this->db->where('id', $user_id);
        $this->db->update('users', $data);

        $this->db->flush_cache();
        return $user_id;
	}

	function is_adult($user_id){

        $this->db->select('adult');
        $this->db->where('id', $user_id);

        $query = $this->db->get('users');
        $data = $query->row_array();

        return $data['adult'];
    }

    function pricing_insert($data){

        $this->db->insert('pricing', $data);

        $latest_id = $this->db->insert_id(); //4
        return $latest_id; //4
    }

    function adult_try($data){

        $this->db->insert('adult_try', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    //세번에 걸쳐서 한다
    /*
     * 1. 바로 삭제 가능한 경우
     * 2. 쓴게 있으면user_id 0 으로 지정하기
     * 3. 쓴거 있으면 판매 됐으면 탈퇴X, 판매 안됐으면 탈퇴 O
     * 4. 사용자가 naver 유저인지 판단
     * */
    function drop_user($user_id){


        //이미 이 페이지로 진입하기 전에 demand, product, import product검사했음..
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        $data = $query->row_array();
        $is_sns = $data['sns_type']; // 사용자의 sns 저장. 이게 naver면 controller에서 삭제 필요

        $this->db->flush_cache();

        $this->db->where('id', $user_id);
        $this->db->delete($this->table_name);
        if ($this->db->affected_rows() > 0) {
            $this->delete_user_info('user_profiles',$user_id);
            $this->delete_user_info('user_autologin',$user_id);

            $reset_data = array(
                'user_id'=>0
            );

            //form, demand form 은 회원 0으로..
            //0으로 초기화 시키기
            $this->set_alarm_reset($user_id); //alarm은 다름
            $this->set_user_id_reset('form',$user_id,$reset_data);
            $this->set_user_id_reset('pricing',$user_id,$reset_data);
            $this->set_user_id_reset('payment',$user_id,$reset_data);
            $this->set_user_id_reset('danal',$user_id,$reset_data);
            $this->set_user_id_reset('danal_card',$user_id,$reset_data);
            $this->set_user_id_reset('danal_bank',$user_id,$reset_data);
            $this->set_user_id_reset('demand_form',$user_id,$reset_data);

            if($is_sns!=null){
                $this->delete_user_info('sns_login',$user_id);
            }
        }

        return $is_sns;
    }

    private function delete_user_info($table_name,$user_id)
    {
        $this->db->flush_cache();
        $this->db->where('user_id', $user_id);
        $this->db->delete($table_name);
    }
    private function set_user_id_reset($table_name,$user_id,$reset_data)
    {
        $this->db->flush_cache();
        $this->db->where('user_id', $user_id);
        $this->db->update($table_name, $reset_data);
        return false;
    }
    private function set_alarm_reset($user_id)
    {
        $reset_data = array(
            'user_id'=>0
        );
        $from_reset_data = array(
            'from_user_id'=>0
        );

        $this->db->flush_cache();
        $this->db->where('user_id', $user_id);
        $this->db->update('alarm', $reset_data);

        $this->db->flush_cache();
        $this->db->where('from_user_id', $user_id);
        $this->db->update('alarm', $from_reset_data);
        return false;
    }

    /*admin*/

    function set_user_level($user_id,$level)
    {
        $data = array(
            'level'=>$level
        );
        $this->db->flush_cache();
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
        return false;
    }

}