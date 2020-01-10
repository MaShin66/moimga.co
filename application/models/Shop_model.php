<?php

class Shop_model extends CI_Model

{

    function __construct()
    {
        parent::__construct();
    }

	function get_shop_info($shop_id){

		$this->db->where('shop_id',$shop_id);

		$query = $this->db->get('shop');
		$result = $query -> row_array();

		return $result;
	}

	function insert_shop($data) {

		$result = $this->db->insert('shop', $data);

		$latest_id = $this->db->insert_id();
		return $latest_id;
	}

	function delete_shop($shop_id){

		$this->db->where('shop_id', $shop_id);
		$this->db->delete('shop');
	}


	function update_shop($shop_id, $data){

		$this->db->where('shop_id', $shop_id);
		$this->db->update('shop', $data);
		return $shop_id;
	}

    
    function load_shop($type = '', $offset = '', $limit = '', $search_query){
        $this->db->select('shop.*');

        $this->db->join('users','users.id = shop.user_id');

        if($search_query['crt_date']==null){
            $this->db->order_by('crt_date','desc');
        }else{
            $this->db->order_by('crt_date',$search_query['crt_date']);
        }

        if(!is_null($search_query['status'])){
            $this->db->where('status',$search_query['status']);
        }

        if($search_query['search']!=null){

            $name_query = '(shop.title like "%'.$search_query['search'].'%" or shop.contents like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('shop');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
        }
        //내 포지션도 써야하는데..

        return $result;
    }

    

    function update_shop_hit($shop_id){ // sql 로만 해야한다니..

        $sql = "UPDATE shop SET hit = hit + 1 WHERE shop_id = ".$shop_id ;
        $this->db->query($sql);

        return $shop_id;
    }


}
