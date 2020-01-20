<?php

class Contents_model extends CI_Model

{

    function __construct()
    {
        parent::__construct();
    }

	function get_contents_info($contents_id){

		$this->db->where('contents_id',$contents_id);

		$query = $this->db->get('contents');
		$result = $query -> row_array();

		return $result;
	}

	function insert_contents($data) {

		$result = $this->db->insert('contents', $data);

		$latest_id = $this->db->insert_id();
		return $latest_id;
	}

	function delete_contents($contents_id){

		$this->db->where('contents_id', $contents_id);
		$this->db->delete('contents');
	}


	function update_contents($contents_id, $data){

		$this->db->where('contents_id', $contents_id);
		$this->db->update('contents', $data);
		return $contents_id;
	}

    
    function load_contents($type = '', $offset = '', $limit = '', $search_query){

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

            $name_query = '(contents.title like "%'.$search_query['search'].'%" or contents.contents like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('contents');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
        }
        //내 포지션도 써야하는데..

        return $result;
    }

    

    function update_contents_hit($contents_id){ // sql 로만 해야한다니..

        $sql = "UPDATE contents SET hit = hit + 1 WHERE contents_id = ".$contents_id ;
        $this->db->query($sql);

        return $contents_id;
    }

    function load_contents_category($type='', $offset='',$limit='',$search_query){ //basic listing

        $this->db->order_by('contents_category_id','asc');
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        if(!is_null($search_query['category'])){
            $this->db->where('contents_category_id', $search_query['category']);
        }
        if(!is_null($search_query['search'])){
            //*팀 이름, 팀 title,  프로그램 title, 후기 쓴 사람, 후기 내용//*/

            $name_query = '(contents_category.title like "%'.$search_query['search'].'%" or contents_category.desc like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        $query = $this->db->get('contents_category');

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
                    'category_id'=>$item['contents_category_id'],
                );

                $contents_result = $this->load_contents('',null, 3, $sub_query);
                $result[$key]['sub_cont'] = $contents_result;
                $result[$key]['sub_cont_count'] = $this->load_contents('count',null, '', $sub_query);
                if($result[$key]['sub_cont_count']==0){ //입력된게 없으면 아예 출력 안됨
                    unset( $result[$key]);
                }

            }


        }
        return $result;
    }

    function get_contents_category_info($contents_category_id){

        $this->db->where('contents_category_id',$contents_category_id);

        $query = $this->db->get('contents_category');
        $result = $query -> row_array();

        return $result;
    }

    function insert_contents_category($data) {

        $result = $this->db->insert('contents_category', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function delete_contents_category($contents_category_id){

        $this->db->where('contents_category_id', $contents_category_id);
        $this->db->delete('contents_category');
    }


    function update_contents_category($contents_category_id, $data){

        $this->db->where('contents_category_id', $contents_category_id);
        $this->db->update('contents_category', $data);
        return $contents_category_id;
    }


    function load_category_contents($category_id){
        $this->db->select('*, contents.title as title, contents_category.title as cate_name, contents_category.order as cate_order');
        $this->db->join('contents_category','contents_category.contents_category_id = faq.contents_category_id');
        $this->db->where('contents_category.contents_category_id', $category_id);
        $this->db->order_by('contents.order','asc');
        $query = $this->db->get('contents');
        $result = $query -> result_array();

        return $result;
    }



    function load_contents_category_plain($type='', $offset='',$limit='',$search_query){
        $this->db->order_by('contents_category_id','asc');
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        if(!is_null($search_query['category'])){
            $this->db->where('contents_category_id', $search_query['category']);
        }
        if(!is_null($search_query['search'])){
            //*팀 이름, 팀 title,  프로그램 title, 후기 쓴 사람, 후기 내용//*/

            $name_query = '(contents_category.title like "%'.$search_query['search'].'%" or contents_category.desc like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        $query = $this->db->get('contents_category');

        if($type=='count'){

            $result = $query -> num_rows();
        }else {
            $result = $query -> result_array();
        }
        return $result;
    }



}
