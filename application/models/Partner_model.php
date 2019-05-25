<?php
class Partner_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    //load, get, update, delete

    function load_partner($type = '', $offset = '', $limit = '', $search_query){
        $this->db->join('users','users.id = partner.user_id');
        $this->db->join('moim','moim.moim_id = partner.moim_id');

        if($search_query['user_id']!=null) {
            $this->db->where('partner.user_id',$search_query['user_id']);
        }
        if($search_query['search']!=null){

            $name_query = '(moim.title like "%'.$search_query['search'].'%" or realname like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if($search_query['crt_date']==null){
            $this->db->order_by('partner.crt_date','desc');
        }else{
            $this->db->order_by('partner.crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('partner');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();

        }
        return $result;
    }

    function get_partner_info($partner_id)
    {//특정 필드에서 $partner_id값이 이것 인것을 찾아라.
        $this->db->where('partner_id' ,$partner_id);

        $query = $this->db->get('partner');
        $result = $query -> row_array();

        return $result;
    }


    function get_partner_info_by_url($url_name)
    {
        $this->db->where('url_name' ,$url_name);

        $query = $this->db->get('partner');
        $result = $query -> row_array();

        return $result;
    }
    function insert_partner($data) {

        $result = $this->db->insert('partner', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function update_partner($partner_id,$data){ // 전체 invoice null로 초기화

        $this->db->set( $data);
        $this->db->where('partner_id' ,$partner_id);
        $this->db->update('partner');

        return 0;
    }

    function delete_partner($partner_id){

        //삭제
        $this->db->where('partner_id' ,$partner_id);
        $this->db->delete('partner');
        return 0;
    }
    function load_moim_partner($moim_id){

        $this->db->join('users','users.id = partner.user_id');
        $this->db->where('moim_id' ,$moim_id);
        $query = $this->db->get('partner');
        $result = $query -> result_array();

        return $result;
    }

    function check_dup_partner($user_id, $moim_id){

        $this->db->where('user_id' ,$user_id);
        $this->db->where('moim_id' ,$moim_id);
        $query = $this->db->get('partner');
        $result = $query -> num_rows();

        return $result;
    }
}
