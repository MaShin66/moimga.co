<?php

class Faq_model extends CI_Model

{

    function __construct()

    {
        parent::__construct();
    }


    function load_faq($type='', $offset='',$limit='',$search_query){

        $this->db->order_by('crt_date','desc');
        $this->db->where('status','on');
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        if(!is_null($search_query['search'])){
            //*팀 이름, 팀 title,  프로그램 title, 후기 쓴 사람, 후기 내용//*/

            $name_query = '(faq.title like "%'.$search_query['search'].'%" or faq.contents like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        $query = $this->db->get('faq');

        if($type=='count'){

            $result = $query -> num_rows();
        }else {
            $result = $query -> result_array();

        }
        return $result;
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

}
