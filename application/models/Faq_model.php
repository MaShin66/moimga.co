<?php

class Faq_model extends CI_Model

{



    function __construct()

    {

        parent::__construct();

    }


	function get_blog($blog_id){

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




	function load_blog($offset='',$limit='',$type=''){

        $this->db->order_by('crt_date','desc');
        $this->db->where('status','on');
		if ($limit != '' || $offset != '') {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get('blog');

		if($type=='count'){

			$result = $query -> num_rows();
		}else {
			$result = $query -> result_array();

		}
		return $result;
	}





}
