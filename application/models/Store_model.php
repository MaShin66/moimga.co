<?php

class Store_model extends CI_Model

{

    function __construct()
    {
        parent::__construct();
    }

	function get_store_info($store_id){

		$this->db->where('store_id',$store_id);

		$query = $this->db->get('store');
		$result = $query -> row_array();

		return $result;
	}

	function insert_store($data) {

		$result = $this->db->insert('store', $data);

		$latest_id = $this->db->insert_id();
		return $latest_id;
	}

	function delete_store($store_id){

		$this->db->where('store_id', $store_id);
		$this->db->delete('store');
	}


	function update_store($store_id, $data){

		$this->db->where('store_id', $store_id);
		$this->db->update('store', $data);
		return $store_id;
	}

    
    function load_store($type = '', $offset = '', $limit = '', $search_query){

        if($search_query['crt_date']==null){
            $this->db->order_by('crt_date','desc');
        }else{
            $this->db->order_by('crt_date',$search_query['crt_date']);
        }

        if(!is_null($search_query['status'])){
            $this->db->where('status',$search_query['status']);
        }
        if(!is_null($search_query['category_id'])){
        $this->db->where('category_id',$search_query['category_id']);
        }

        if($search_query['search']!=null){

            $name_query = '(store.title like "%'.$search_query['search'].'%" or store.contents like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('store');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
        }
        //내 포지션도 써야하는데..

        return $result;
    }

    

    function update_store_hit($store_id){ // sql 로만 해야한다니..

        $sql = "UPDATE store SET hit = hit + 1 WHERE store_id = ".$store_id ;
        $this->db->query($sql);

        return $store_id;
    }

    /*category*/


    function load_store_category($type='', $offset='',$limit='',$search_query){ //basic listing

        $this->db->order_by('store_category_id','asc');
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        if(!is_null($search_query['category'])){
            $this->db->where('store_category_id', $search_query['category']);
        }
        if(!is_null($search_query['search'])){
            //*팀 이름, 팀 title,  프로그램 title, 후기 쓴 사람, 후기 내용//*/

            $name_query = '(store_category.title like "%'.$search_query['search'].'%" or store_category.desc like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        $query = $this->db->get('store_category');

        if($type=='count'){

            $result = $query -> num_rows();
        }else {
            $result = $query -> result_array();
            // 이 뒤에 콘텐츠 불러오기
            foreach ($result as $key => $item){
                $sub_query = array(
                    'crt_date'=>null,
                    'status'=>'on',
                    'search'=>null,
                    'category_id'=>$item['store_category_id'],
                );

                $contents_result = $this->load_store('',null, 3, $sub_query);
                $result[$key]['sub_cont'] = $contents_result;
                $result[$key]['sub_cont_count'] = $this->load_store('count',null, '', $sub_query);
                if($result[$key]['sub_cont_count']==0){ //입력된게 없으면 아예 출력 안됨
                    unset( $result[$key]);
                }

            }


        }
        return $result;
    }

    function get_store_category_info($store_category_id){

        $this->db->where('store_category_id',$store_category_id);

        $query = $this->db->get('store_category');
        $result = $query -> row_array();

        return $result;
    }

    function insert_store_category($data) {

        $result = $this->db->insert('store_category', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function delete_store_category($store_category_id){

        $this->db->where('store_category_id', $store_category_id);
        $this->db->delete('store_category');
    }


    function update_store_category($store_category_id, $data){

        $this->db->where('store_category_id', $store_category_id);
        $this->db->update('store_category', $data);
        return $store_category_id;
    }


    function load_category_contents($category_id){
        $this->db->select('*, store.title as title, store_category.title as cate_name, store_category.order as cate_order');
        $this->db->join('store_category','store_category.store_category_id = faq.store_category_id');
        $this->db->where('store_category.store_category_id', $category_id);
        $this->db->order_by('store.order','asc');
        $query = $this->db->get('store');
        $result = $query -> result_array();

        return $result;
    }

    function load_store_category_plain($type='', $offset='',$limit='',$search_query){

        $this->db->order_by('store_category_id','asc');
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        if(!is_null($search_query['category'])){
            $this->db->where('store_category_id', $search_query['category']);
        }
        if(!is_null($search_query['search'])){
            //*팀 이름, 팀 title,  프로그램 title, 후기 쓴 사람, 후기 내용//*/

            $name_query = '(store_category.title like "%'.$search_query['search'].'%" or store_category.desc like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        $query = $this->db->get('store_category');

        if($type=='count'){

            $result = $query -> num_rows();
        }else {
            $result = $query -> result_array();


        }

        return $result;
    }

    function get_faq_info_by_order($category_id=1, $faq_order=1){

        $this->db->where('store_category_id', $category_id);
        $this->db->where('order', $faq_order);
        $query = $this->db->get('store');
        $result = $query -> row_array();

        return $result;
    }

}
