<?php
class Admin_model extends CI_Model
{
//https://www.codeigniter.com/user_guide/database/results.html
    function __construct()
    {
        parent::__construct();
        $this->load->model('Form_model','User_model','Ticket_form_model');
    }

    function load_prod($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->select('*, payment.status as payment_status, product.status as status');
        $this->db->from('users');
        $this->db->join('product', 'product.user_id = users.id');
        $this->db->join('product_detail', 'product.prod_id = product_detail.prod_id');
        $this->db->join('payment', 'product.prod_id = payment.prod_id');

        if($search_query['status']!=null){

            $this->db->where('product.status', 'fin');
            $this->db->where('payment.status', $search_query['status']);
        }
        if($search_query['user_id']!=null){

            $this->db->where('product.user_id', $search_query['user_id']);
        }
        //조건문

        if($search_query['search']!=null){

            $name_query = '(product.title like "%'.$search_query['search'].'%" or product.contents like "%'.$search_query['search'].'%" or product.product_name like "%'.$search_query['search'].'%" or product.prod_username like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if($search_query['crt_date']==null){
            $this->db->order_by('product.crt_date','desc');
        }else{
            $this->db->order_by('product.crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $this->db->group_by('product.prod_id');
        $query = $this->db->get();

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
            $today = date("Y-m-d H:i:s",time());
            foreach ($result as $key => $item){

                //$result[$key]['left_date'] = $this->set_left_date($today,$item['close_date']);
                $result[$key]['transaction'] = $this->get_transaction($item['prod_id']);

                //거래액 붙이기..
            }

        }
        return $result;

    }

    function get_product_info($prod_id){ //level, username 필요함

        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('product', 'product.user_id = users.id');
        $this->db->join('product_detail', 'product_detail.prod_id = product.prod_id');
        $this->db->where('product.prod_id',$prod_id);

        $query = $this->db->get();
        $result = $query -> row_array();

        return $result;
    }

    function load_ticket($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->select('*, payment.status as payment_status, ticket.status as status');
        $this->db->from('users');
        $this->db->join('ticket', 'ticket.user_id = users.id');
        $this->db->join('ticket_detail', 'ticket_detail.ticket_id = ticket.ticket_id');
        $this->db->join('payment' ,'`payment`.`prod_id` = `ticket`.`ticket_id` and `payment`.`type` = \'ticket\'');
//        $this->db->join('payment', 'payment.prod_id = ticket.ticket_id','payment.type=ticket');

        if($search_query['status']!=null){

            $this->db->where('ticket.status', 'fin');
//            $this->db->where('payment.status', $search_query['status']);
        }
        if($search_query['user_id']!=null){

            $this->db->where('ticket.user_id', $search_query['user_id']);
        }
        //조건문

        if($search_query['search']!=null){

            $name_query = '(ticket.title like "%'.$search_query['search'].'%" or ticket.contents like "%'.$search_query['search'].'%" or ticket.ticket_username like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if($search_query['crt_date']==null){
            $this->db->order_by('ticket.crt_date','desc');
        }else{
            $this->db->order_by('ticket.crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $this->db->group_by('ticket.ticket_id');
        $query = $this->db->get();

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
            $today = date("Y-m-d H:i:s",time());
            foreach ($result as $key => $item){

                //$result[$key]['left_date'] = $this->set_left_date($today,$item['close_date']);
                $result[$key]['transaction'] = $this->get_transaction($item['ticket_id'],'ticket');

                //거래액 붙이기..
            }

        }
        return $result;

    }


    function get_ticket_info($ticket_id){

        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('ticket', 'ticket.user_id = users.id');
        $this->db->join('ticket_detail', 'ticket_detail.ticket_id = ticket.ticket_id');
        $this->db->where('ticket.ticket_id',$ticket_id);

        $query = $this->db->get();
        $result = $query -> row_array();

        return $result;
    }
    function load_form($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->from('users');
        $this->db->join('form', 'form.user_id = users.id');
        $this->db->join('product', 'product.prod_id = form.prod_id');


        if($search_query['user_id']!=null){

            $this->db->where('form.user_id', $search_query['user_id']);
        }
        if($search_query['status']!=null){

            $this->db->where('form.status', $search_query['status']);
        }
        //조건문

        if($search_query['search']!=null){

            $name_query = '(product.title like "%'.$search_query['search'].'%" or product.contents like "%'.$search_query['search'].'%" or product.product_name like "%'.$search_query['search'].'%" or product.prod_username like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if($search_query['crt_date']==null){
            $this->db->order_by('product.crt_date','desc');
        }else{
            $this->db->order_by('product.crt_date',$search_query['crt_date']);
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

    function load_form_count($unique_id, $type='prod'){
    	if($type=='prod'||is_null($type)){

			$this->db->where('prod_id', $unique_id);
			$query = $this->db->get('form');
		}else{
			$this->db->where('ticket_id', $unique_id);
			$query = $this->db->get('ticket_form');
		}
		$result = $query -> num_rows();

//		$result = $query->result_array();
    	return $result;

	}
    function load_import($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->from('users');
        $this->db->join('import_product', 'import_product.user_id = users.id');
       // $this->db->join('payment', 'import_product.import_ = payment.prod_id');

        //조건문

        if($search_query['search']!=null){

            $name_query = '(import_product.title like "%'.$search_query['search'].'%" or users.username like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if($search_query['crt_date']==null){
            $this->db->order_by('import_product.crt_date','desc');
        }else{
            $this->db->order_by('import_product.crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $this->db->group_by('import_product.import_prod_id');
        $query = $this->db->get();

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();

        }
        return $result;

    }

    function load_demand($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('demand', 'demand.user_id = users.id');
        //조건문
        if($search_query['search']!=null && $search_query['type']!='all'){ //둘 다 있을 경우
            //$type_query = 'name like "%'.$search_query['name'].'%';
            //$result_query = 'type_id = '.$result_type;

            //title, username, contents
            if($search_query['type']=='username'){
                $this->db->like('users.username',$search_query['search']); //판매자 이름
                $this->db->or_like('demand.prod_username',$search_query['search']); //판매자 이름
            }else{ //그 외
                $this->db->like('demand.'.$search_query['type'],$search_query['search']); // 제목
            }


        }else if($search_query['search']!=null && $search_query['type']=='all'){

            //걍 전체로만 검색한다..

            $name_query = '(demand.title like "%'.$search_query['search'].'%" or demand.contents like "%'.$search_query['search'].'%" or demand.product_name like "%'.$search_query['search'].'%" or demand.prod_username like "%'.$search_query['search'].'%")';

            $this->db->where($name_query);
        }

        if($search_query['crt_date']==null){
            $this->db->order_by('demand.crt_date','desc');
        }else{
            $this->db->order_by('demand.crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
            foreach ($result as $key=> $item) {
                $this->db->flush_cache();
                $this->db->where('demand_id',$item['demand_id']);
                $query_df= $this->db->get('demand_form');
                $result[$key]['form_cnt'] = $query_df-> num_rows();
            }
        }
        return $result;

    }
    function load_users($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->select('*');
        $this->db->from('users');
        //조건문
        if($search_query['search']!=null){
            if(is_numeric($search_query['search'])){
                $this->db->where('id',$search_query['search']);
            }else{

                $name_query = '(username like "%'.$search_query['search'].'%" or email like "%'.$search_query['search'].'%")';
                $this->db->where($name_query);
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
    function load_agree($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('agree', 'agree.user_id = users.id');
        if($search_query['search']!=null){
             $name_query = '(users.username like "%'.$search_query['search'].'%" or users.realname like "%'.$search_query['search'].'%" or users.email like "%'.$search_query['search'].'%")';
             $this->db->where($name_query);
        }
        if($search_query['crt_date']==null){
            $this->db->order_by('agree.crt_date','desc');
        }else{
            $this->db->order_by('agree.crt_date',$search_query['crt_date']);
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
    function load_usearch($type = '', $offset = '', $limit = '', $search_query) {

        if($search_query['user_id']!=null){
            $this->db->where('user_id',$search_query['user_id']);
        }
        if($search_query['crt_date']==null){
            $this->db->order_by('crt_date','desc');
        }else{
            $this->db->order_by('crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('user_search');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
        }
        return $result;

    }

	function load_switch($type = '', $offset = '', $limit = '', $search_query) {

		if($search_query['user_id']!=null){
			$this->db->where('user_id',$search_query['user_id']);
		}
		if($search_query['crt_date']==null){
			$this->db->order_by('crt_date','desc');
		}else{
			$this->db->order_by('crt_date',$search_query['crt_date']);
		}
		if ($limit != '' || $offset != '') {
			$this->db->limit($limit, $offset);
		}

		$query = $this->db->get('user_switch');

		if ($type == 'count') {
			$result = $query -> num_rows();
		} else {
			$result = $query -> result_array();
		}
		return $result;

	}
    function load_verify($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('verify', 'verify.user_id = users.id');
        if($search_query['search']!=null){
            $name_query = '(users.username like "%'.$search_query['search'].'%" or users.email like "%'.$search_query['search'].'%" or users.realname like "%'.$search_query['search'].'%" or verify.phone like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);
        }
        if($search_query['crt_date']==null){
            $this->db->order_by('verify.crt_date','desc');
        }else{
            $this->db->order_by('verify.crt_date',$search_query['crt_date']);
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

    function load_transfer($offset='',$limit='',$type='',$search_query){


        $this->db->select('transfer.*, product.title');
        $this->db->join('product','product.prod_id=transfer.prod_id');
        if($search_query['search']!=null){

            $name_query = '(transfer.prod_id like "%'.$search_query['search'].'%" or transfer.user_id like "%'.$search_query['search'].'%" or product.title like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);
        }
        if($search_query['status']!=null){ //done, pending, deny, cancel

            $this->db->where('transfer.status', $search_query['status']);
        }

        if($search_query['crt_date']==null){
            $this->db->order_by('transfer.crt_date','desc');
        }else{
            $this->db->order_by('transfer.crt_date',$search_query['crt_date']);
        }


        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('transfer');

        if($type=='count'){

            $result = $query -> num_rows();
        }else{

            $result = $query -> result_array();
            foreach ($result as $key => $item){
                $result[$key]['status_kr']=$this->_set_transfer_status_kr($item['status']);
            }
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

    function load_payment($type = '',$payment_type='card', $offset = '', $limit = '', $search_query) {

        /*
         *
SELECT *
 FROM `danal`
  JOIN `danal_card` ON `danal_card`.`danal_id` = `danal`.`danal_id`
  JOIN `payment` ON `payment`.`payment_id` = `danal`.`payment_id`
  JOIN `product` ON `product`.`prod_id` = `payment`.`prod_id`

  ORDER BY `danal`.`crt_date` DESC
        */
        $this->db->select('*');
        $this->db->from('danal');
        $this->db->join('danal_'.$payment_type, 'danal_'.$payment_type.'.danal_id = danal.danal_id');
        $this->db->join('payment', 'payment.danal_id = danal.danal_id');
        $this->db->join('product', 'product.prod_id = payment.prod_id');
//        if($search_query['search']!=null){
//            $this->db->like('product.title',$search_query['search']); //판매자 이름
//        }
        if($search_query['crt_date']==null){
            $this->db->order_by('danal.crt_date','desc');
        }else{
            $this->db->order_by('danal.crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
            foreach ($result as $key=>$item){
                if($item['type']=='import'){

                    $this->db->flush_cache();
                    $this->db->where('import_prod_id', $item['prod_id']);
                    $query_import = $this->db->get('import_product');
                    $result_import = $query_import -> row_array();

                    $result[$key]['title'] = $result_import['title'];

                }
            }
        }
        return $result;

    }

    function load_sms_payment($type = '',$payment_type='card', $offset = '', $limit = '', $search_query) {

        $this->db->select('*');
        $this->db->from('danal');
        $this->db->join('danal_'.$payment_type, 'danal_'.$payment_type.'.danal_id = danal.danal_id');
        $this->db->join('payment', 'payment.payment_id = danal.payment_id');
        $this->db->join('users', 'users.id = payment.user_id');
        $this->db->where('payment.status','done'); //이미 완료된것만
        $this->db->where('payment.type','sms'); //도네이션만
//        if($search_query['search']!=null){
//            $this->db->like('product.title',$search_query['search']); //판매자 이름
//        }
        if($search_query['crt_date']==null){
            $this->db->order_by('danal.crt_date','desc');
        }else{
            $this->db->order_by('danal.crt_date',$search_query['crt_date']);
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
    function load_sms_log($type = '', $offset = '', $limit = '', $search_query) {
        $this->load->helper('smscode');

        $this->db->select('*'); //ticket도 있지만 대체적으로 prod기 떄문에 조인을prod로 했음 190816
        $this->db->join('product', 'product.prod_id = sms_log.unique_id');
        $this->db->join('users', 'users.id = sms_log.user_id');
        if($search_query['user_id']!=null){
            $this->db->where('sms_log.user_id',$search_query['user_id']);

        }
        if($search_query['search']!=null){
            $name_query = '(users.username like "%'.$search_query['search'].'%" or users.email like "%'.$search_query['search'].'%" or users.realname like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);
        }
        if($search_query['crt_date']==null){
            $this->db->order_by('sms_log.crt_date','desc');
        }else{
            $this->db->order_by('sms_log.crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('sms_log');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
            foreach ($result as $key =>$item){
                $result[$key]['code_kr'] = get_code_text($item['code']);
            }
        }
        return $result;

    }
    function load_sms_balance($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->select('*');
        $this->db->join('users', 'users.id = sms_balance.user_id');
        if($search_query['user_id']!=null){
            $this->db->where('sms_balance.user_id',$search_query['user_id']);

        }
        if($search_query['search']!=null){
            $name_query = '(users.username like "%'.$search_query['search'].'%" or users.email like "%'.$search_query['search'].'%" or users.realname like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);
        }
        if($search_query['crt_date']==null){
            $this->db->order_by('sms_balance.mod_date','desc');
        }else{
            $this->db->order_by('sms_balance.mod_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('sms_balance');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();
        }
        return $result;

    }
    function load_donation_payment($type = '',$payment_type='card', $offset = '', $limit = '', $search_query) {

        $this->db->select('*');
        $this->db->from('danal');
        $this->db->join('danal_'.$payment_type, 'danal_'.$payment_type.'.danal_id = danal.danal_id');
        $this->db->join('users', 'users.id = danal.user_id');
        $this->db->join('payment', 'payment.danal_id = danal.danal_id');
//        $this->db->join('users', 'users.id = payment.user_id');
        $this->db->where('payment.status','done'); //이미 완료된것만
        $this->db->where('payment.type','donation'); //도네이션만
//        if($search_query['search']!=null){
//            $this->db->like('product.title',$search_query['search']); //판매자 이름
//        }
        if($search_query['crt_date']==null){
            $this->db->order_by('danal.crt_date','desc');
        }else{
            $this->db->order_by('danal.crt_date',$search_query['crt_date']);
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
    function load_pricing() {

        $this->db->select('*');
        $this->db->order_by('crt_date','desc');

        $query = $this->db->get('pricing');
        $result = $query -> result_array();

        return $result;

    }

    function get_refund_by_form_id($form_id){

        $this->db->select('*,  refund.bank as refund_bank, refund.name as refund_name');
        $this->db->join('refund', 'form.user_id = refund.user_id');
        $this->db->where('refund.user_id !=', 0);
        $this->db->where('form.user_id !=', 0);
        $this->db->where('form_id', $form_id);
        $query = $this->db->get('form');

        $result = $query -> row_array();
        if($result['user_id']==0){
            $result = array();
        }

        return $result;
    }

    function set_pricing($pricing_id,$user_id){

        $pricing_data = array(
            'status' => 'done'
        );

        $this->db->where('pricing_id', $pricing_id);
        $this->db->update('pricing', $pricing_data);
        $this->db->flush_cache();

        //pricing_id로 promo_code가져와서 set해야함..
        $info = $this->get_pricing($pricing_id);
        switch ($info['code']){
            case 'pre-regi':
            case 1130:

                $user_data = array(
                    'level' => 5
                );
                break;
            default:

                $user_data = array(
                    'level' => 3
                );
            break;

        }

        $this->db->where('id', $user_id);
        $this->db->update('users', $user_data);


        return 0;

    }

    /*
    function make_user_profile($table,$user_id) {

        $this->db->where('id', $user_id);

        $query = $this->db->get('users');
        $user_ok = $query->row_array();
        if(count($user_ok)>0){ //회원 있으면

            $data = array(
                'user_id' => $user_id,
            );
            if($table=='sns_login'){
                $data['sns_type']=$user_ok['sns_type'];
            }

            $this->db->insert($table, $data);

            $latest_id = $this->db->insert_id();
            return $latest_id;
        }else{
            return false;
        }

    }
*/
    /*function make_payment(){
        //product있는것 중에서 fin, hidden이 아닌것들만 데려와서 이를 payment에서 free로 만든다.. // 그랫더니.. fin인것들이 다 사라져버렸어요..
    // payment에서 없는데 그게 fin이거나 hidden이면 만든다..
        $this->db->where(' (`status` != \'fin\' and `status` != \'hidden\') or `status` is null');
        $this->db->where('prod_id <',653);

        $query = $this->db->get('product');
        $result = $query -> result_array();

        foreach ($result as $key=>$item){

            $payment_data = array(
                'prod_id'=>$item['prod_id'],
                'type'=>'basic',
                'user_id' => $item['user_id'],
                'close_date' => $item['close_date'],
                'status' => 'free',
                'amount'=>0,
                'danal_id'=>null,
                'crt_date' => date('Y-m-d H:i:s'),
            );

            $this->Payment_model->make_payment($payment_data);

        }
    }*/

    function init_sns_login($user_id){
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        $user_ok = $query->row_array();
        if($query->num_rows()>0){ //회원 있으면

            $data = array(
                'user_id' => $user_id,
                'sns_type'=>$user_ok['sns_type'],
                'unique_id'=>null,
                'crt_date'=>$user_ok['sns_crt_date'],
            );

            $this->db->where('id', $user_id);
            $this->db->update('sns_login', $data);

        }else{

            $this->db->delete('sns_login', array('id' => $user_id));
            return false;
        }

    }
    function make_fin_payement(){
        $this->db->where(' (`status` = \'fin\' or `status` = \'hidden\')');
        $this->db->where('prod_id >',41);
        $this->db->where('prod_id <',653);

        $query = $this->db->get('product');
        $result = $query -> result_array();

        foreach ($result as $key=>$item){

            $payment_data = array(
                'prod_id'=>$item['prod_id'],
                'type'=>'basic',
                'user_id' => $item['user_id'],
                'status' => 'free',
                'amount'=>0,
                'danal_id'=>null,
                'crt_date' => date('Y-m-d H:i:s'),
            );

            $this->Payment_model->make_payment($payment_data);

        }

    }

    function make_draft($from, $to){
        $this->db->where('prod_id >=',$from);
        $this->db->where('prod_id <=',$to);

        $query = $this->db->get('product');
        $result = $query -> result_array();

        foreach ($result as $key=>$item){

            $draft_data = array(
                'user_id' => $item['user_id'],
                'prod_id'=>$item['prod_id'],
                'contents' => $item['contents'],
                'crt_date' => $item['crt_date']
            );

            $this->db->insert('draft', $draft_data);

        }

    }
    function set_payment_done($prod_id,$type=null){
        if($type==null||$type=='basic'){
            $type = 'basic';
            $this->db->where('prod_id',$prod_id);
            $query = $this->db->get('product');
        }else{ //import

            $this->db->where('import_prod_id',$prod_id);
            $query = $this->db->get('import_product');
        }
        $prod_info = $query -> row_array();

        $this->db->flush_cache();

        $this->db->where('type', $type);
        $this->db->where('user_id', $prod_info['user_id']);
        $this->db->where('prod_id', $prod_id);
        $query = $this->db->get('payment');
        $payment_info = $query -> row_array();

        if($payment_info['amount']==0){

            $payment_data = array(
                'status'=>'free',
                'amount'=>0,
                'danal_id'=>null
            );

            $this->db->where('type', $type);
            $this->db->where('prod_id', $prod_id);
            $this->db->update('payment', $payment_data);

        }else{ //결제 처리할 건이 있을 경우

            $today = date('Y-m-d H:i:s');
            //danal
            $danal_data= array(
                'user_id'=>$prod_info['user_id'],
                'payment_id'=>$payment_info['payment_id'],
                'method'=>'bank',
                'auth_date'=>$today,
                'TID'=>'-------tmm_bank',
                'amount'=>$payment_info['amount'],
                'result_code'=>'0000',
                'crt_date'=>$today
            );
            $this->db->insert('danal', $danal_data);

            $danal_id = $this->db->insert_id();

            $this->db->flush_cache();
            //payment


            $payment_data = array(
                'status'=>'done',
                'amount'=>$payment_info['amount'],
                'danal_id'=>$danal_id
            );

            $this->db->where('type', $type);
            $this->db->where('prod_id', $prod_id);
            $this->db->update('payment', $payment_data);

            $this->db->flush_cache();
            //nicebank

            $bank_data= array(
                'danal_id'=>$danal_id,
                'user_id'=>$prod_info['user_id'],
                'payment_id'=>$payment_info['payment_id'],
                'account'=>null,
                'bankcode'=>null,
                'bankname'=>null,
                'rcpttype'=>null,
                'rcptauthcode'=>null,
                'crt_date'=>$today
            );
            $this->db->insert('danal_bank', $bank_data);

            $bank_id = $this->db->insert_id();
        }

        return false;

    }

	function get_pricing($pricing_id){
	
        
        $this->db->where('pricing_id',$pricing_id);

        $query = $this->db->get('pricing');
        $result = $query -> row_array();

        return $result;
	}
	
	function load_refund($prod_id=null)
	{
		
        $this->db->select('refund.*,form.*, refund.bank as refund_bank,refund.name as refund_name');

        $this->db->from('refund');
		if($prod_id!=null){
			
            $this->db->join('form', 'form.user_id = refund.user_id');
            $this->db->where('form.prod_id', $prod_id);
		}
        $this->db->order_by('refund.crt_date','desc');

        $query = $this->db->get();
        $result = $query -> result_array();

        return $result;
		
	}

    function load_ticket_refund($ticket_id=null)
    {

        $this->db->select('refund.*,ticket_form.*, refund.bank as refund_bank,refund.name as refund_name');

        $this->db->from('refund');
        if($ticket_id!=null){

            $this->db->join('ticket_form', 'ticket_form.user_id = refund.user_id');
            $this->db->where('ticket_form.ticket_id', $ticket_id);
        }
        $this->db->order_by('refund.crt_date','desc');

        $query = $this->db->get();
        $result = $query -> result_array();

        return $result;

    }

    function comment_delete($id,$table){
        $this->db->delete($table, array('id' => $id));
    }
    function comment_ok($id,$table){

        $data = array(
            'status' => 1
        );

        $this->db->where('id', $id);
        $this->db->update($table, $data);

    }

    function this_week_trans($type='basic'){

        $this->db->select('*');
        if($type=='ticket'){
        	$close_table = 'ticket';
            $this->db->join('payment' ,'`payment`.`prod_id` = `ticket`.`ticket_id` and `payment`.`type` = \'ticket\'');
        }else{
			$close_table = 'product';
            $this->db->join('payment', 'product.prod_id = payment.prod_id');
        }
        $this->db->where('payment.status', 'pending');
        $this->db->where('(date(`close_date`) BETWEEN subdate(curdate(),date_format(curdate(),\'%w\')-1)  AND subdate(curdate(),date_format(curdate(),\'%w\')-7))');
        $query = $this->db->get($close_table);
        $result = $query -> result_array();
        foreach ($result as $key => $item){
            if($type=='ticket'){

                $form_list = $this->Ticket_form_model->load_form($item['ticket_id']);
            }else{

                $form_list = $this->Form_model->load_form($item['prod_id']);
            }

            $sum = 0;
            foreach ($form_list as $item){
                $sum = $sum + $item['money']; //얼마인지..근데 각각에 대해서는.. 따로 구해얗ㅁ.
            }
            $result[$key]['bare_sum'] = $sum;
            $user_level = $this->User_model-> get_user_level($item['user_id']);
            if(count($form_list)>12){

                if($user_level==5||$user_level==1){

                    if($sum*0.03<10000){
                        $result_money = 10000;
                    }else{
                        $result_money = $sum*0.03;
                    }
                }else{ //일반 사용자

                    if($sum*0.05<10000){
                        $result_money = 15000;
                    }else{
                        $result_money = $sum*0.05;
                    }
                }
            }else{ //12개 이하면 무료
                $result_money = 0;
            }
            $result[$key]['result_sum'] = $result_money;
        }


        return $result;
    }



    function get_transaction($prod_id=null,$type='basic'){

        if($type=='ticket'){

            $sum = 0; // 966까지의 총 거래액 (약 15억)
            $table = 'ticket_form';
            if($prod_id!=null){
                $this->db->where('ticket_id',$prod_id);
            }
        }else{ //기본 prod

            $table = 'form';
            if($prod_id!=null){
                $this->db->where('prod_id',$prod_id);
                $sum = 0; //특정 상품에 대해서는..
            }else{

                $this->db->where('prod_id >',966);
                $sum = 1530577134; // 966까지의 총 거래액 (약 15억)
            }
        }

        $this->db->where('user_id !=',42345); // 현서
        $this->db->where('user_id !=',1); // 운영자
        $query = $this->db->get($table);
        $result = $query -> result_array();
        foreach ($result as $item){
            $sum = $sum + $item['money'];
        }
        return $sum;
    }


    function set_prod_username($prod_username, $org_username){
        if($prod_username!=null || $prod_username!=''){
            $result  = $prod_username;
        }else{
            $result = $org_username;
        }
        return $result;
    }

    function update_prod_username($prod_id){

		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('product', 'product.user_id = users.id');
		$this->db->where('prod_id',$prod_id);

		$query = $this->db->get();
		if($query->num_rows()>0){

			$result = $query -> row_array();
			$username = $result['username'];
			$prod_username = $result['prod_username'];
			//prod_username있는지 확인

			if($prod_username==null || $prod_username==''){

				$this->db->flush_cache();
				//없으면 해당에 prod_id 에업데이트 한다.
				$name_data = array(
					'prod_username' => $username
				);

				$this->db->where('prod_id', $prod_id);
				$this->db->update('product', $name_data);

				return 'ok: '.$prod_id;

			}else{

				return 'has prod_username: '.$prod_id;
			}
		}else{
			return 'no prod_id: '.$prod_id;
		}
	}

	function fin_prod(){ //이건 오직 prod만.. ticket 은 나중에 한다
        //상품 지난것들 가쟈오기..

		echo '[free]<br>';
        $this->db->where('((`product`.`open_date` < current_timestamp() and `product`.`close_date` < current_timestamp()) and (`product`.`status` is null or `product`.`status` = \'on\'  ))');

        $query = $this->db->get('product');
        $result = $query->result_array();
        foreach ($result as $key=>$item){

        	echo 'prod_id: '.$item['prod_id'].' - ';
            $this->db->flush_cache();
            $data = array(
                'status'=>'fin',
            );

            $this->db->where('prod_id',$item['prod_id']);
            $this->db->update('product', $data);
            //얘네 가격..근데 자동으로 했다가 내가 잘못하는게 있으면 내 손해인데.. 일단 해본다..
			//일단 0 얘네 중에서 가격 계산을 또 해야됨..
			$is_free=false; // 둘 중 하나라도 돈 내야하면 ㅇㅋ로
			//폼 수 계산
			$this_form_cnt = $this->load_form_count($item['prod_id'], 'prod');
            $transaction = 0;
			if($this_form_cnt<13){
				echo '<br>폼 수 적음 - 개수: '.number_format($this_form_cnt).'<br> 폼 수 적어서 가격 계산 안 함<br>';
				$is_free = true;
			}else{ // 12개 이상일 때에만 transaction
				//가격 계산
				$transaction = $this->get_transaction($item['prod_id']);
				echo $transaction;
				if($transaction<150000){
					$is_free = true;
					echo '<br>15만원 이하: '.number_format($transaction);
				}
			}

			if($is_free==true){ // 무료라면 얘들을 대상으로 무료로 해준다

				echo '<br>prod_id: '.$item['prod_id'].'<br> 폼 개수: '.number_format($this_form_cnt).'개<br>가격: '.number_format($transaction).'원<br>';
				echo '--------<br>';

				$payment_data = array(
					'status'=>'free',
					'amount'=>0,
					'danal_id'=>null
				);

				$this->db->where('type', 'basic');
				$this->db->where('prod_id', $item['prod_id']);
				$this->db->update('payment', $payment_data);

			}else{

				echo '<br>유료<br>--------<br>';
			}


		}

        return false;

    }

	function update_thumbs($prod_id){

        $this->db->where('prod_id',$prod_id);
        $query = $this->db->get('product');
        if($query->num_rows()>0) { //thumbs잇는지 확인
            $this->db->flush_cache();

            $this->db->where('prod_id',$prod_id);
            $query_thumbs = $this->db->get('thumbs');
            if($query_thumbs->num_rows()>0){ //넘어간
                return 'has thumbs: '.$prod_id;
            }else{ //없으면 업데이트
                $this->db->flush_cache();
                //없으면 thumbs 새로 쓰기
                $thumbs_data = array(
                    'prod_id'=>$prod_id,
                    'url' => '/www/img/basic_thumbs.jpg',
                    'crt_date' => date('Y-m-d H:i:s')
                );

                $this->db->insert('thumbs', $thumbs_data);

                return 'ok: '.$prod_id;
            }
        }else{
            return 'no prod_id: '.$prod_id;

        }
    }

    function set_prod_detail($prod_id){

        $this->db->select('*');
        $this->db->where('prod_id',$prod_id);
        $query = $this->db->get('product');
        if($query->num_rows()>0) { //thumbs잇는지 확인
            $result = $query->row_array();
            $this->db->flush_cache();

            $this->db->where('prod_id',$prod_id);
            $query_detail = $this->db->get('product_detail');
            if($query_detail->num_rows()>0){ //넘어간

                $this->db->flush_cache();
                $this->db->select('status');
                $this->db->where('prod_id',$prod_id);
                $query_form = $this->db->get('form');
                $result_form = $query_form->result_array();
                $result_form['all_count'] = $query_form -> num_rows();
                $result_form['done_count'] =0;
                $result_form['pend_count'] =0;

                foreach ($result_form as $item){
                    if($item['status']=='done'){
                        $result_form['done_count'] = $result_form['done_count'] +1;
                    }else if($item['status']=='pending'){

                        $result_form['pend_count'] = $result_form['pend_count'] +1;
                    }
                }

                $detail_data = array(
                    'form_cnt' => $result_form['all_count'],
                    'done_cnt' => $result_form['done_count'],
                    'pending_cnt' => $result_form['pend_count'],
                );

                $this->db->where('prod_id',$prod_id);
                $this->db->update('product_detail', $detail_data);

                return 'has detail: '.$prod_id;
            }else{ //없으면 업데이트
                $this->db->flush_cache();
                //없으면 detail 새로 쓰기
                $prod_array = explode(',',$result['product_name']);
                $new_array = array();
                //foreach 돌리면서 전부 0으로 만든다..
                for ($i = 0; $i<count($prod_array); $i++){
                    array_push($new_array,0);
                }
                $result_amount = implode(',',$new_array);


                $this->db->flush_cache();
                $this->db->select('status');
                $this->db->where('prod_id',$prod_id);
                $query_form = $this->db->get('form');
                $result_form = $query_form->result_array();
                $result_form['all_count'] = $query_form -> num_rows();
                $result_form['done_count'] =0;
                $result_form['pend_count'] =0;

                foreach ($result_form as $item){
                    if($item['status']=='done'){
                        $result_form['done_count'] = $result_form['done_count'] +1;
                    }else if($item['status']=='pending'){

                        $result_form['pend_count'] = $result_form['pend_count'] +1;
                    }
                }

                $detail_data = array(
                    'prod_id'=>$prod_id,
                    'user_id'=>$result['user_id'],
                    'form_cnt' => $result_form['all_count'],
                    'done_cnt' => $result_form['done_count'],
                    'pending_cnt' => $result_form['pend_count'],
                    'limit_amount' => $result_amount,
                    'crt_date' => $result['crt_date'],
                );

                $this->db->insert('product_detail', $detail_data);

                return 'ok: '.$prod_id;
            }
        }else{
            return 'no prod_id: '.$prod_id;

        }
    }

    function load_block($type = '', $offset = '', $limit = '', $search_query) {

        $this->db->join('users','users.id = block.block_user_id');//판매자가 아니라 차단 당한 사람의 block _user_id 와 join

        //조건문
        if($search_query['search']!=null){

            $name_query = '(users.email like "%'.$search_query['search'].'%" or users.username like "%'.$search_query['search'].'%" or users.id like "%'.$search_query['search'].'%")';
            $this->db->where($name_query);

        }
        if($search_query['crt_date']==null){
            $this->db->order_by('crt_date','desc');
        }else{
            $this->db->order_by('crt_date',$search_query['crt_date']);
        }
        if ($limit != '' || $offset != '') {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get('block');

        if ($type == 'count') {
            $result = $query -> num_rows();
        } else {
            $result = $query -> result_array();

        }
        return $result;

    }

    function _set_transfer_status_kr($status){

        switch ($status){
            case 'done':
                $status_kr='완료';
                break;
            case 'cancel':
                $status_kr='취소';
                break;
            case 'deny':
                $status_kr='거절';
                break;
            case 'pending':
            default:
                $status_kr='대기';
                break;
        }
        return $status_kr;

    }
}
