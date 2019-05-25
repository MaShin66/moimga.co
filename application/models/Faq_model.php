<?php

class Faq_model extends CI_Model

{

    function __construct()

    {
        parent::__construct();
    }


	function get_faq_info($faq_id){

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




	function load_faq($offset='',$limit='',$type=''){

        $this->db->order_by('crt_date','desc');
        $this->db->where('status','on');
		if ($limit != '' || $offset != '') {
			$this->db->limit($limit, $offset);
		}
		$query = $this->db->get('faq');

		if($type=='count'){

			$result = $query -> num_rows();
		}else {
			$result = $query -> result_array();

		}
		return $result;
	}





}
