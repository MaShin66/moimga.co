<?php

class Like_model extends CI_Model

{



    function __construct()

    {

        parent::__construct();

    }


    function get_moim_like_user($user_id, $moim_id){


        $this->db->where('user_id',$user_id);
        $this->db->where('moim_id',$moim_id);

        $query = $this->db->get('moim_like');
        $result = $query -> row_array();

        return $result;
    }


    function get_moim_like_info($moim_like_id=null){

        if($moim_like_id==null){
            $this->db->order_by('mod_date','desc');
            $this->db->limit(1);
        }else{

            $this->db->where('moim_like_id',$moim_like_id);

        }
        $query = $this->db->get('moim_like');
        $result = $query -> row_array();

        return $result;
    }

    function insert_moim_like($data) {

        $result = $this->db->insert('moim_like', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function delete_moim_like($moim_like_id){

        $this->db->where('moim_like_id', $moim_like_id);
        $this->db->delete('moim_like');
    }


    function update_moim_like($moim_like_id, $data){

        $this->db->where('moim_like_id', $moim_like_id);
        $this->db->update('moim_like', $data);
        return $moim_like_id;
    }




    function load_moim_like($offset='',$limit='',$type=''){

        $this->db->order_by('crt_date','desc');
        $this->db->where('status','on');
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('moim_like');

        if($type=='count'){

            $result = $query -> num_rows();
        }else {
            $result = $query -> result_array();

        }
        return $result;
    }


    function load_moim_like_by_user_id($user_id, $offset='',$limit='',$type=''){

        $this->db->from('moim_like');
        $this->db->join('product', 'product.moim_id = moim_like.moim_id');
        $this->db->join('thumbs', 'thumbs.moim_id = product.moim_id');
        $this->db->where('moim_like.user_id',$user_id);

        $this->db->order_by('product.close_date','asc'); //종료날 오름차순
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get();

        if($type=='count'){

            $result = $query -> num_rows();
        }else{

            $result = $query -> result_array();
            $today = date("Y-m-d H:i:s",time());
            foreach ($result as $key => $item){

                $result[$key]['left_date'] = $this->set_left_date($today,$item['close_date']);
            }
        }

        return $result;
    }


    function set_left_date($today,$close_date){

        $this_date = trim($close_date);
        $result = (intval((strtotime($this_date)-strtotime($today)) / 86400));

        return $result;
    }



}
