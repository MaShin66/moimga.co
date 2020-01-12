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

    /*category*/

    function load_shop_category($type='', $offset='',$limit='',$search_query){

        $this->db->order_by('shop_category_id','asc');
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        if(!is_null($search_query['category'])){
            $this->db->where('shop_category_id', $search_query['category']);
        }
        if(!is_null($search_query['search'])){
            //*팀 이름, 팀 title,  프로그램 title, 후기 쓴 사람, 후기 내용//*/

            $name_query = '(shop_category.title like "%'.$search_query['search'].'%" or shop_category.desc like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        $query = $this->db->get('shop_category');

        if($type=='count'){

            $result = $query -> num_rows();
        }else {
            $result = $query -> result_array();

        }
        return $result;
    }

    function get_shop_category_info($shop_category_id){

        $this->db->where('shop_category_id',$shop_category_id);

        $query = $this->db->get('shop_category');
        $result = $query -> row_array();

        return $result;
    }

    function insert_shop_category($data) {

        $result = $this->db->insert('shop_category', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function delete_shop_category($shop_category_id){

        $this->db->where('shop_category_id', $shop_category_id);
        $this->db->delete('shop_category');
    }


    function update_shop_category($shop_category_id, $data){

        $this->db->where('shop_category_id', $shop_category_id);
        $this->db->update('shop_category', $data);
        return $shop_category_id;
    }


    function load_category_contents($category_id){
        $this->db->select('*, shop.title as title, shop_category.title as cate_name, shop_category.order as cate_order');
        $this->db->join('shop_category','shop_category.shop_category_id = faq.shop_category_id');
        $this->db->where('shop_category.shop_category_id', $category_id);
        $this->db->order_by('shop.order','asc');
        $query = $this->db->get('shop');
        $result = $query -> result_array();

        return $result;
    }

    function load_shop_category_plain(){
        $query = $this->db->get('shop_category');
        $result = $query -> result_array();
        return $result;
    }

    function get_faq_info_by_order($category_id=1, $faq_order=1){

        $this->db->where('shop_category_id', $category_id);
        $this->db->where('order', $faq_order);
        $query = $this->db->get('shop');
        $result = $query -> row_array();

        return $result;
    }

}
