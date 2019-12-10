<?php

class Magazine_model extends CI_Model

{

    function __construct()
    {
        parent::__construct();
    }

	function get_magazine_info($magazine_id){

		$this->db->where('magazine_id',$magazine_id);

		$query = $this->db->get('magazine');
		$result = $query -> row_array();

		return $result;
	}

	function insert_magazine($data) {

		$result = $this->db->insert('magazine', $data);

		$latest_id = $this->db->insert_id();
		return $latest_id;
	}

	function delete_magazine($magazine_id){

		$this->db->where('magazine_id', $magazine_id);
		$this->db->delete('magazine');
	}


	function update_magazine($magazine_id, $data){

		$this->db->where('magazine_id', $magazine_id);
		$this->db->update('magazine', $data);
		return $magazine_id;
	}

    
    function load_magazine($type = '', $offset = '', $limit = '', $search_query){
        $this->db->select('magazine.*');

        $this->db->join('users','users.id = magazine.user_id');

        if($search_query['crt_date']==null){
            $this->db->order_by('crt_date','desc');
        }else{
            $this->db->order_by('crt_date',$search_query['crt_date']);
        }

        if(!is_null($search_query['status'])){
            $this->db->where('status',$search_query['status']);
        }

        if($search_query['search']!=null){

            $name_query = '(magazine.title like "%'.$search_query['search'].'%" or magazine.contents like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('magazine');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
        }
        //내 포지션도 써야하는데..

        return $result;
    }

    

    function update_magazine_hit($magazine_id){ // sql 로만 해야한다니..

        $sql = "UPDATE magazine SET hit = hit + 1 WHERE magazine_id = ".$magazine_id ;
        $this->db->query($sql);

        return $magazine_id;
    }


}
