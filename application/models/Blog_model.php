<?php

class Blog_model extends CI_Model

{

    function __construct()
    {
        parent::__construct();
    }

	function get_blog_info($blog_id){

		$this->db->where('blog_id',$blog_id);

		$query = $this->db->get('blog');
		$result = $query -> row_array();

		return $result;
	}

	function insert_blog($data) {

		$result = $this->db->insert('blog', $data);

		$latest_id = $this->db->insert_id();
		return $latest_id;
	}

	function delete_blog($blog_id){

		$this->db->where('blog_id', $blog_id);
		$this->db->delete('blog');
	}


	function update_blog($blog_id, $data){

		$this->db->where('blog_id', $blog_id);
		$this->db->update('blog', $data);
		return $blog_id;
	}

    
    function load_blog($type = '', $offset = '', $limit = '', $search_query){
        $this->db->select('blog.*');

        $this->db->join('users','users.id = blog.user_id');

        if($search_query['crt_date']==null){
            $this->db->order_by('crt_date','desc');
        }else{
            $this->db->order_by('crt_date',$search_query['crt_date']);
        }

        if(!is_null($search_query['status'])){
            $this->db->where('status',$search_query['status']);
        }

        if($search_query['search']!=null){

            $name_query = '(blog.title like "%'.$search_query['search'].'%" or blog.user_id ='.$search_query['search'].' or blog.contents like "%'.$search_query['search'].'%"")';
            $this->db->where($name_query);

        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('blog');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
        }
        //내 포지션도 써야하는데..

        return $result;
    }

    

    function update_blog_hit($blog_id){ // sql 로만 해야한다니..

        $sql = "UPDATE blog SET hit = hit + 1 WHERE blog_id = ".$blog_id ;
        $this->db->query($sql);

        return $blog_id;
    }


}
