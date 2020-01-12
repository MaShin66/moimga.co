<?php
class Admin_model extends CI_Model
{
//https://www.codeigniter.com/user_guide/database/results.html
    function __construct()
    {
        parent::__construct();
        $this->load->model('Form_model','User_model');
    }

    function load_users($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->select('*');
        $this->db->from('users');
        //조건문
        if($search_query['search']!=null){
            if(is_numeric($search_query['search'])){
                $this->db->where('id',$search_query['search']);
            }else{

                $name_query = '(username like "%'.$search_query['search'].'%" or email like "%'.$search_query['search'].'%" 
                or realname like "%'.$search_query['search'].'%" or nickname like "%'.$search_query['search'].'%")';
                $this->db->where($name_query);
            }
        }

        //level
        if($search_query['level']!=null){
            $this->db->where('level',$search_query['level']);
        }
        // sns_type
        if($search_query['sns_type']!=null){
            if($search_query['sns_type']=='email'){

                $this->db->where('sns_type',null);
                $this->db->or_where('sns_type','');
            }else{

                $this->db->where('sns_type',$search_query['sns_type']);
            }
        }

        if($search_query['crt_date']==null){
            $this->db->order_by('created','desc');
        }else{
            $this->db->order_by('created',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();


        }
        return $result;

    }
    function load_comment($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('comment', 'comment.user_id = users.id');
         if($search_query['search']!=null){
            $this->db->like('comment.contents',$search_query['search']);


//             $name_query = '(demand.title like "%'.$search_query['search'].'%" or demand.contents like "%'.$search_query['search'].'%" or demand.product_name like "%'.$search_query['search'].'%" or demand.prod_username like "%'.$search_query['search'].'%")';

//             $this->db->where($name_query);
        }
        if($search_query['crt_date']==null){
            $this->db->order_by('comment.crt_date','desc');
        }else{
            $this->db->order_by('comment.crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
        }
        return $result;

    }

    function load_sms($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('sms', 'sms.user_id = users.id');
        $this->db->join('product', 'product.prod_id = sms.prod_id');
        if($search_query['search']!=null){
            $this->db->like('product.title',$search_query['search']); //판매자 이름
        }
        if($search_query['crt_date']==null){
            $this->db->order_by('sms.crt_date','desc');
        }else{
            $this->db->order_by('sms.crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
        }
        return $result;

    }
    function load_heart($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->select('*');
        $this->db->join('heart', 'heart.user_id = users.id');
        $this->db->join('product', 'product.prod_id = heart.prod_id');
        if($search_query['prod_id']!=null){
            $this->db->where('heart.prod_id',$search_query['prod_id']);
        }
        if($search_query['crt_date']==null){
            $this->db->order_by('heart.crt_date','desc');
        }else{
            $this->db->order_by('heart.crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('users');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
        }
        return $result;

    }

    function load_verify($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->join('verify', 'verify.user_id = users.id');
        if($search_query['search']!=null){
            $name_query = '(users.username like "%'.$search_query['search'].'%" or users.email like "%'.$search_query['search'].'%" or users.realname like "%'.$search_query['search'].'%" or verify.phone like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);
        }
        if(!is_null($search_query['crt_date'])){
            $this->db->order_by('verify.crt_date',$search_query['crt_date']);
        }else{
            $this->db->order_by('verify.crt_date','desc');
        }
        if(!is_null($search_query['success'])){
            $this->db->where('verify.success',$search_query['success']);
        }

        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('users');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
        }
        return $result;

    }
    function load_bookmark($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->select('*');
        $this->db->join('product', 'product.prod_id = bookmark.prod_id');
        $this->db->join('users', 'users.id = bookmark.user_id');
        if($search_query['search']!=null){
            $name_query = '(users.username like "%'.$search_query['search'].'%" or users.email like "%'.$search_query['search'].'%" or users.realname like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);
        }
        if($search_query['crt_date']==null){
            $this->db->order_by('bookmark.crt_date','desc');
        }else{
            $this->db->order_by('bookmark.crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('bookmark');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
        }
        return $result;

    }

}
