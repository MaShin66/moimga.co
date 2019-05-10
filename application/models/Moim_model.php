<?php
class Moim_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    //load, get, update, delete

    function load_moim($type = '', $offset = '', $limit = '', $search_query){
        $this->db->join('users','users.id = moim.user_id');

        if($search_query['user_id']!=null) {
            $this->db->where('moim.user_id',$search_query['user_id']);
        }
        if($search_query['search']!=null){

            $name_query = '(moim.title like "%'.$search_query['search'].'%" or realname like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if($search_query['crt_date']==null){
            $this->db->order_by('crt_date','desc');
        }else{
            $this->db->order_by('crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('moim');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();

        }
        return $result;
    }

    function get_moim_info($moim_id)
    {//특정 필드에서 $moim_id값이 이것 인것을 찾아라.
        $this->db->where('moim_id' ,$moim_id);

        $query = $this->db->get('moim');
        $result = $query -> row_array();

        return $result;
    }


    function get_moim_info_by_url($url_name)
    {
        $this->db->where('url_name' ,$url_name);

        $query = $this->db->get('moim');
        $result = $query -> row_array();

        return $result;
    }
    function insert_moim($data) {

        $result = $this->db->insert('moim', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function update_moim($moim_id,$data){ // 전체 invoice null로 초기화

        $this->db->set( $data);
        $this->db->where('moim_id' ,$moim_id);
        $this->db->update('moim');

        return 0;
    }

    function delete_moim($moim_id){

        //삭제
        $this->db->where('moim_id' ,$moim_id);
        $this->db->delete('moim');
        return 0;
    }

    function load_category(){


        $query = $this->db->get('category');
        $result = $query -> result_array();
        return $result;
    }


}
