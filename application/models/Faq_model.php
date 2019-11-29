<?php

class Faq_model extends CI_Model

{

    function __construct()

    {
        parent::__construct();
    }


    function load_faq($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->select('*, faq.title as title, faq_category.name as cate_name, faq.order as order, faq_category.order as cate_order');
        $this->db->join('faq_category','faq_category.faq_category_id = faq.faq_category_id');
        //조건문

        if($search_query['search']!=null){

            $name_query = '(faq.title like "%'.$search_query['search'].'%" or faq.contents like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        $this->db->order_by('faq.order','asc');
        if($search_query['crt_date']==null){
            $this->db->order_by('faq.crt_date','desc');
        }else{
            $this->db->order_by('faq.crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('faq');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();

        }
        return $result;

    }

	function get_faq_info($faq_id){
        $this->db->join('faq_category','faq_category.faq_category_id = faq.faq_category_id');
		$this->db->where('faq_id',$faq_id);

		$query = $this->db->get('faq');
		$result = $query -> row_array();

		return $result;
	}

	function insert_faq($data) {

		$result = $this->db->insert('faq', $data);

		$latest_id = $this->db->insert_id();
		return $latest_id;
	}

	function delete_faq($faq_id){

		$this->db->where('faq_id', $faq_id);
		$this->db->delete('faq');
	}


	function update_faq($faq_id, $data){

		$this->db->where('faq_id', $faq_id);
		$this->db->update('faq', $data);
		return $faq_id;
	}

	/*category*/

    function load_faq_category($type='', $offset='',$limit='',$search_query){

        $this->db->order_by('faq_category_id','asc');
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        if(!is_null($search_query['category'])){
            $this->db->where('faq_category_id', $search_query['category']);
        }
        if(!is_null($search_query['search'])){
            //*팀 이름, 팀 title,  프로그램 title, 후기 쓴 사람, 후기 내용//*/

            $name_query = '(faq_category.name like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        $query = $this->db->get('faq_category');

        if($type=='count'){

            $result = $query -> num_rows();
        }else {
            $result = $query -> result_array();

        }
        return $result;
    }

    function get_faq_category_info($faq_category_id){

        $this->db->where('faq_category_id',$faq_category_id);

        $query = $this->db->get('faq_category');
        $result = $query -> row_array();

        return $result;
    }

    function insert_faq_category($data) {

        $result = $this->db->insert('faq_category', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function delete_faq_category($faq_category_id){

        $this->db->where('faq_category_id', $faq_category_id);
        $this->db->delete('faq_category');
    }


    function update_faq_category($faq_category_id, $data){

        $this->db->where('faq_category_id', $faq_category_id);
        $this->db->update('faq_category', $data);
        return $faq_category_id;
    }


    function load_category_contents($category_id){
        $this->db->select('*, faq.title as title, faq_category.name as cate_name, faq.order as order, faq_category.order as cate_order');
        $this->db->join('faq_category','faq_category.faq_category_id = faq.faq_category_id');
        $this->db->where('faq_category.faq_category_id', $category_id);
        $this->db->order_by('faq.order','asc');
        $query = $this->db->get('faq');
        $result = $query -> result_array();

        return $result;
    }

    function load_faq_category_plain(){
        $query = $this->db->get('faq_category');
        $result = $query -> result_array();
        return $result;
    }

    function get_faq_info_by_order($category_id=1, $faq_order=1){

        $this->db->where('faq_category_id', $category_id);
        $this->db->where('order', $faq_order);
        $query = $this->db->get('faq');
        $result = $query -> row_array();

        return $result;
    }

    function get_category_id($category){

        $this->db->where('url_name', $category);
        $query = $this->db->get('faq_category');
        $result = $query -> row_array();

        return $result['faq_category_id'];
    }
    function update_faq_hit($faq_id){ // sql 로만 해야한다니..

        $sql = "UPDATE faq SET hit = hit + 1 WHERE faq_id = ".$faq_id ;
        $this->db->query($sql);


        return $faq_id;
    }

}
