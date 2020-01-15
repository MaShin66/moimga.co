<?php

class Main_model extends CI_Model

{

    function __construct()
    {
        parent::__construct();
    }

	function get_main_info($main_id){

		$this->db->where('main_id',$main_id);

		$query = $this->db->get('main');
		$result = $query -> row_array();

		return $result;
	}

	function insert_main($data) {

		$result = $this->db->insert('main', $data);

		$latest_id = $this->db->insert_id();
		return $latest_id;
	}

	function delete_main($main_id){

		$this->db->where('main_id', $main_id);
		$this->db->delete('main');
	}


	function update_main($main_id, $data){

		$this->db->where('main_id', $main_id);
		$this->db->update('main', $data);
		return $main_id;
	}

    
    function load_main($type = '', $offset = '', $limit = '', $search_query=null){

        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('crt_date','desc');
        $query = $this->db->get('main');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
            //여기에 따라서 정보도 따로 가져와야함
            foreach ($result as $key=>$item){

                $store_info_1 = $this->get_main_cate_title('store',$item['store_cate_1']);
                $result[$key]['store_title_1'] =$store_info_1['title'];
                $result[$key]['store_desc_1'] =$store_info_1['desc'];

                $store_info_2 = $this->get_main_cate_title('store',$item['store_cate_2']);
                $result[$key]['store_title_2'] =$store_info_2['title'];
                $result[$key]['store_desc_2'] =$store_info_2['desc'];

                $contents_info_1 = $this->get_main_cate_title('contents',$item['contents_cate_1']);
                $result[$key]['contents_title_1'] =$contents_info_1['title'];
                $result[$key]['contents_desc_1'] =$contents_info_1['desc'];

                $contents_info_2 = $this->get_main_cate_title('contents',$item['contents_cate_2']);
                $result[$key]['contents_title_2'] =$contents_info_2['title'];
                $result[$key]['contents_desc_2'] =$contents_info_2['desc'];

            }
        }
        //내 포지션도 써야하는데..

        return $result;
    }

    function get_main_cate_title($type='store',$unique_id){
        $this->db->select('title, desc');
        $this->db->where($type.'_category_id',$unique_id);
        $query = $this->db->get($type.'_category');
        $result = $query -> row_array();

        return $result;
    }
    function get_latest_main(){

        $this->db->order_by('crt_date','desc');
        $this->db->limit(1);
        $query = $this->db->get('main');
        $result = $query -> row_array();

        $result['store'] = array();
        $result['contents'] = array();

        $store_info_1 = $this->get_main_cate_title('store',$result['store_cate_1']);
        $store_info_2 = $this->get_main_cate_title('store',$result['store_cate_2']);

        $result['store'][0] = $store_info_1;
        $result['store'][1] = $store_info_2;
        $result['store'][0]['category_id'] = $result['store_cate_1'];
        $result['store'][0]['thumb_url'] = $result['store_thumb_1'];
        $result['store'][0]['count'] = $this-> count_category_item('store',$result['store_cate_1']);

        $result['store'][1]['category_id'] = $result['store_cate_2'];
        $result['store'][1]['thumb_url'] = $result['store_thumb_2'];
        $result['store'][1]['count'] = $this-> count_category_item('store',$result['store_cate_2']);

        $contents_info_1 = $this->get_main_cate_title('contents',$result['contents_cate_1']);
        $contents_info_2 = $this->get_main_cate_title('contents',$result['contents_cate_2']);

        $result['contents'][0] = $contents_info_1;
        $result['contents'][1] = $contents_info_2;
        $result['contents'][0]['category_id'] = $result['contents_cate_1'];
        $result['contents'][0]['thumb_url'] = $result['contents_thumb_1'];
        $result['contents'][0]['count'] = $this-> count_category_item('contents',$result['contents_cate_1']);

        $result['contents'][1]['category_id'] = $result['contents_cate_2'];
        $result['contents'][1]['thumb_url'] = $result['contents_thumb_2'];
        $result['contents'][1]['count'] = $this-> count_category_item('contents',$result['contents_cate_2']);



        return $result;
    }

    function count_category_item($type='store',$category_id=null){

        $this->db->where('category_id',$category_id);
        $query = $this->db->get($type);
        $result = $query -> num_rows();

        return $result;
    }

}
