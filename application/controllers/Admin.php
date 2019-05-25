<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();

        $this->load->library('tank_auth');
        $this->load->library('layout', 'layouts/admin_layout');
        $this->layout->setLayout("layouts/admin_layout");

    }


    public function index()
    {


        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );
        $search_query = array(
            'crt_date'=>null
        );
        $user_cnt = $this->User_model->load_users('count','','',$search_query);

        $all_transaction = $this->Admin_model->get_transaction();
        $this_week = $this->Admin_model->this_week_trans();
        $sum = 0;
        foreach ($this_week as $key=> $item){
            $sum = $item['result_sum'] + $sum;
        }

        $this->layout->view('admin/main', array('user' => $user_data,'all_transaction'=>$all_transaction,'this_week'=>$sum,'user_cnt'=>$user_cnt));
    }

    function product($type = 'list',$prod_id=null)
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_prod_list('list',$user_data);
                        break;
                    case 'view':
                        $this->_prod_view($prod_id);
                        break;
                    default:
                        $this->_prod_list();
                        break;
                }

            }

        }
    }
    function demand($type = 'list',$demand_id=null)
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_demand_list('list',$user_data);
                        break;
                    case 'view':
                        $this->_demand_view($demand_id);
                        break;
                    default:
                        $this->_demand_list();
                        break;
                }

            }

        }
    }
    function users($type = 'list',$this_user_id=null)
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_user_list('list',$user_data);
                        break;
                    case 'view':
                        $this->_user_view($this_user_id,$user_data);
                        break;
                    case 'level':
                        $this->_user_level($this_user_id,$user_data);
                        break;
                    default:
                        $this->_user_list();
                        break;
                }

            }

        }
    }
    function payment($payment_type='card',$type = 'list',$payment_id=null)
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_payment_list($payment_type,'list',$user_data);
                        break;
                    case 'view':
                        $this->_payment_view($payment_id);
                        break;
                    default:
                        $this->_payment_list();
                        break;
                }

            }

        }
    }
    function comment($type = 'list',$comment_id=null)
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_comment_list('list',$user_data);
                        break;
                    case 'view':
                        $this->_comment_view($comment_id);
                        break;
                    default:
                        $this->_comment_list();
                        break;
                }

            }

        }
    }

    function pricing()
    {

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );
        $result = $this->Admin_model->load_pricing();
        $this->layout->view('admin/pricing', array('user' => $user_data, 'result' => $result));

    }

    function pricing_ok($pricing_id, $user_id)
    {
        $this->Admin_model->set_pricing($pricing_id, $user_id); //설정

        $result = $this->Admin_model->get_pricing($pricing_id);


        $this->_send_email('pricing_ok', $result['email'], $result, '[moimga] 상품 관리자 안내 메일');

        alert('완료되었습니다.');

    }
    function refund($prod_id = null)
    {

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );
        $result = $this->Admin_model->load_refund($prod_id);
        $form_info = $this->Form_model->load_form($prod_id);
        $this->layout->view('admin/refund', array('user' => $user_data, 'result' => $result, 'form_info' => $form_info));

    }

    function down_refund($prod_id = null)
        {

            $prod_info = $this->Prod_model->get_product_info($prod_id);

            $result = $this->Admin_model->load_refund($prod_id);

            $file_name = urldecode($prod_info['title']);
            //[moimga] 상품이름_폼.xls
            header("Content-type: application/vnd.ms-excel; charset=utf-8");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
            header("Pragma: no-cache");
            header("Content-Disposition: attachment; filename='moimga_" . $file_name . "_환불목록.xls");


            echo "
    <table>
    <tr>
        <td>폼번호</td>
        <td>회원번호</td>
        <td>계좌번호</td>
        
        <td>은행</td>
        <td>예금주</td>
        <td>입금액</td>
        
        <td>날짜</td>
         </tr> "; // 테이블 상단
            $result_count = count($result);


            for ($i = 0; $i < $result_count; $i++) {


                echo "<tr>";
                echo "<td>" . $result[$i]['form_id'] . "</td>";
                echo "<td>" . $result[$i]['user_id'] . "</td>";
                echo "<td style=mso-number-format:'\@'>" . $result[$i]['account'] . "</td>";

                echo "<td>" . $result[$i]['refund_bank'] . "</td>";
                echo "<td>" . $result[$i]['refund_name'] . "</td>";
                echo "<td>" . $result[$i]['money'] . "</td>";
                echo "<td>" . $result[$i]['crt_date'] . "</td>";

                echo "</tr>";
            }

            echo "</table>";


        }

    function download_xls($prod_id){

        $prod_info = $this->Prod_model->get_product_info($prod_id);
        $products_name = explode(',',$prod_info['product_name']);

        $delivery_name= explode(',',$prod_info['del_name']);

        $file_name = urldecode($prod_info['title']);
        //[moimga] 상품이름_폼.xls
        header( "Content-type: application/vnd.ms-excel; charset=euc-kr" );
        header( "Expires: 0" );
        header( "Cache-Control: must-revalidate, post-check=0,pre-check=0" );
        header( "Pragma: no-cache" );
        header( "Content-Disposition: attachment; filename='moimga_".$file_name."_폼.xls" );

        $list = $this->Form_model->load_form($prod_id); //정보

        echo "
    <table>
    <tr>
        <td>번호</td>
        <td>입금인</td>
        <td>수령인</td>
        
        <td>이메일</td>
        <td>전화번호</td>
        <td>입금일</td>
        
        <td>주소</td>
        <td>우편번호</td>";
        foreach ($products_name as $key => $name_item){
            echo "<td>".$name_item."</td>";
        }

        echo "<td>입금액</td>
        <td>은행</td>
        <td>메모</td>
        
        <td>타입</td>";
        if($prod_info['online']==1){
            echo '<td>배송 방법</td>';
        }
        echo "<td>상태</td>
         <td>폼 작성 시간</td>
         </tr> "; // 테이블 상단
        $list_count = count($list);

        for($i=0; $i<$list_count; $i++) {

            $volume = explode(',',$list[$i]['volume']);
            $del_method_orig = $list[$i]['del_method'];
            if($del_method_orig==null || $del_method_orig=='') {
                $del_method = '기본';
            }else{
                $del_method = $delivery_name[$del_method_orig];
            }
            if($list[$i]['type']=='online'){
                $type = '통판';
            }else{
                $type = '현장판매';
            }
            if($list[$i]['status']=='done'){
                $status= '확인 완료';
            }else{

                $status = '대기';
            }

            echo "<tr>";
            echo "<td>".$list[$i]['form_id']."</td>";
            echo "<td>".$list[$i]['name']."</td>";
            echo "<td>".$list[$i]['rec_name']."</td>";
            echo "<td>".$list[$i]['email']."</td>";
            echo "<td style=mso-number-format:'\@'>".$list[$i]['phone']."</td>";

            echo "<td>".$list[$i]['date']."</td>";
            echo "<td>".$list[$i]['address'].' '.$list[$i]['address2']."</td>";
            echo "<td style=mso-number-format:'\@'>".$list[$i]['zipcode']."</td>";

            foreach ($volume as $key => $volume_item){
                echo "<td>".$volume_item."</td>";
            }
            echo "<td>".$list[$i]['money']."</td>";
            echo "<td>".$list[$i]['bank']."</td>";
            echo "<td>".$list[$i]['memo']."</td>";
            echo "<td>".$type."</td>";
            if($list[$i]['type']=='online'){
                echo "<td>".$del_method."</td>";
            }
            echo "<td>".$status."</td>";
            echo "<td>".$list[$i]['crt_date']."</td>";

            echo "</tr>";
        }

        echo "</table>";
    }

    function _send_email($type, $email, &$data, $title)
    {

        $config = array(
            'protocol' => "smtp",
            'smtp_host' => "ssl://smtp.gmail.com",
            'smtp_port' => "465",//"587", // 465 나 587 중 하나를 사용
            'smtp_user' => "admin@takemm.com",
            'smtp_pass' => "fortis53",
            'charset' => "utf-8",
            'newline' => "\r\n",
            'mailtype' => "html",
            'smtp_timeout' => 10,
        );


        $this->load->library('email', $config);


        $this->email->set_newline("\r\n");
        $this->email->clear();

        $this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->to($email);
        $this->email->subject($title, $this->config->item('website_name', 'tank_auth'));
        $this->email->message($this->load->view('email/' . $type . '-txt', $data, TRUE));
        $this->email->set_alt_message($this->load->view('email/' . $type . '-html', $data, TRUE));
        if ($this->email->send()) {
            echo "성공";
        } else {
            echo "실패";
        }


    }

    function delete_import_form($import_prod_id)
    {
        $this->Form_model->delete_import_form($import_prod_id);
    }

    function transaction($prod_id=null){


        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );
        if($prod_id==null){ //null이면 모든 상품

            $result = $this->Admin_model->get_transaction();
        }else{ //아니면 전체 거래액

            $result = $this->Admin_model->get_transaction($prod_id);
        }


        $this->layout->view('admin/transaction', array('user' => $user_data, 'result' => $result));

    }

function _prod_list($type='list',$user_data){

    $search = $this->uri->segment(4);

    if($search==null){
        $search_query = array(
            'crt_date' => '',
            'type' => 'all',
            'search' => '',
            'status'=>null,
        );

    }else{
        $sort_date = $this->input->get('crt_date');
        $sort_search = $this->input->get('search');
        $sort_type = $this->input->get('type');

        $search_query = array(
            'crt_date' => $sort_date,
            'search' => $sort_search,
            'type' => $sort_type,
            'status'=>null,
        );

    }
    $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&type='.$search_query['type'];

    $this->load->library('pagination');
    $config['suffix'] = $q_string;
    $config['base_url'] = '/admin/product/' . $type; // 페이징 주소
    $config['total_rows'] = $this -> Admin_model -> load_prod('count','','',$search_query); // 게시물 전체 개수

    $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
    $config['uri_segment'] = 4; // 페이지 번호가 위치한 세그먼트
    $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
    $config = $this->_pagination_config($config);
    // 페이지네이션 초기화
    $this->pagination->initialize($config);
    // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
    $data['pagination'] = $this->pagination->create_links();

    // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
    $page = $this->uri->segment(4);
    if($page==null||$page=='q'){
        $start=0;
    }else{

        $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
    }


    $limit = $config['per_page'];

    $data['result'] = $this->Admin_model->load_prod('', $start, $limit, $search_query);
    $data['total']=$config['total_rows'];

    $this->layout->view('admin/product', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

}

    function _demand_list($type='list',$user_data){

        $search = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'type' => 'all',
                'search' => '',
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_type = $this->input->get('type');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'type' => $sort_type,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&type='.$search_query['type'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/demand/' . $type; // 페이징 주소
        $config['total_rows'] = $this -> Admin_model -> load_demand('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 4; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = $this->_pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this->uri->segment(4);


        if($page==null){
            $start=0;
        }else{

            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }

        $limit = $config['per_page'];

        $data['result'] = $this->Admin_model->load_demand('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/demand', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }

    function _payment_list($payment_type='card',$type='list',$user_data=null){

        $search = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'type' => 'all',
                'search' => '',
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_type = $this->input->get('type');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'type' => $sort_type,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&type='.$search_query['type'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/payment/' . $type; // 페이징 주소
        $config['total_rows'] = $this -> Admin_model -> load_payment('count',$payment_type,'','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 13; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 4; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = $this->_pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this->uri->segment(4);


        if($page==null){
            $start=0;
        }else{

            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }

        $limit = $config['per_page'];

        $data['result'] = $this->Admin_model->load_payment('',$payment_type, $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/payment', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }
    function _user_list($type='list',$user_data){

        $search = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'type' => 'all',
                'search' => '',
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_type = $this->input->get('type');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'type' => $sort_type,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&type='.$search_query['type'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/users/' . $type; // 페이징 주소
        $config['total_rows'] = $this -> Admin_model -> load_users('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 4; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = $this->_pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this->uri->segment(4);


        if($page==null){
            $start=0;
        }else{

            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }

        $limit = $config['per_page'];

        $data['result'] = $this->Admin_model->load_users('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/users/list', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }
    function _user_view($user_id,$user_data){

        $result = $this->User_model->get_user_info($user_id);
        $this->layout->view('admin/users/view', array('user' => $user_data, 'result' => $result));
    }

    function _user_level($user_id){

        $level = $this->input->get('level');
        if($level==0){
            $level = null;
        }
        $this->User_model->set_user_level($user_id,$level);

        alert('선택하신 회원의 레벨이 '.$level.'로 조정되었습니다.');
    }
    function _comment_list($type='list',$user_data){

        $search = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'type' => 'all',
                'search' => '',
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_type = $this->input->get('type');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'type' => $sort_type,
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&type='.$search_query['type'];

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/comment/' . $type; // 페이징 주소
        $config['total_rows'] = $this -> Admin_model -> load_comment('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 4; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = $this->_pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this->uri->segment(4);
        if($page==null){
            $start=0;
        }else{

            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }


        $limit = $config['per_page'];

        $data['result'] = $this->Admin_model->load_comment('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/comment', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }

    function _pagination_config($config){

        $config['first_link'] = '≪';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '≫';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '＞';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '＜';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="page-item active"><a href="" class="page-link">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        $config['use_page_numbers'] = TRUE;

        return $config;
    }

    function refund_find(){
        $form_id = $this->input->get('form_id');
        $result = array();
        if($form_id!=null){

            $result = $this -> Admin_model -> get_refund_by_form_id($form_id); // 게시물 전체 개수

        }

        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );
        $this->layout->view('admin/refund_find', array('user' => $user_data, 'result' => $result));

    }

    function pending($type = 'list',$prod_id=null)
    {
        $status = $this->data['status'];
        $user_id = $this->data['user_id'];
        $level = $this->data['level'];
        $user_data = array(
            'status' => $status,
            'user_id' => $user_id,
            'level' => $level
        );

        if (!$this->tank_auth->is_logged_in()) {

            show_error('접근이 불가능합니다.');
        } else {
            if ($user_data['level'] != 9) {

                show_error('접근이 불가능합니다.');
            } else {

                switch ($type){
                    case 'list':
                        $this->_pending_list('list',$user_data);
                        break;
                    case 'view':
                        $this->_pending_view($prod_id);
                        break;
                    default:
                        $this->_pending_list();
                        break;
                }

            }

        }
    }

    function _pending_list($type='list',$user_data){

        $search = $this->uri->segment(4);

        if($search==null){
            $search_query = array(
                'crt_date' => '',
                'type' => 'all',
                'search' => '',
                'status'=>'pending',
            );

        }else{
            $sort_date = $this->input->get('crt_date');
            $sort_search = $this->input->get('search');
            $sort_type = $this->input->get('type');

            $search_query = array(
                'crt_date' => $sort_date,
                'search' => $sort_search,
                'type' => $sort_type,
                'status'=>'pending',
            );

        }
        $q_string = '/q?search='.$search_query['search'].'&crt_date='.$search_query['crt_date'].'&type='.$search_query['type'].'&status=pending';

        $this->load->library('pagination');
        $config['suffix'] = $q_string;
        $config['base_url'] = '/admin/pending/' . $type; // 페이징 주소
        $config['total_rows'] = $this -> Admin_model -> load_prod('count','','',$search_query); // 게시물 전체 개수

        $config['per_page'] = 16; // 한 페이지에 표시할 게시물 수
        $config['uri_segment'] = 4; // 페이지 번호가 위치한 세그먼트
        $config['first_url'] = $config['base_url'].'/1/'.$config['suffix']; // 첫페이지에 query string 에러나서..
        $config = $this->_pagination_config($config);
        // 페이지네이션 초기화
        $this->pagination->initialize($config);
        // 페이지 링크를 생성하여 view에서 사용하 변수에 할당
        $data['pagination'] = $this->pagination->create_links();

        // 게시물 목록을 불러오기 위한 offset, limit 값 가져오기
        $page = $this->uri->segment(4);
        if($page==null){
            $start=0;
        }else{

            $start = ($page  == 1) ? 0 : ($page * $config['per_page']) - $config['per_page'];
        }


        $limit = $config['per_page'];

        $data['result'] = $this->Admin_model->load_prod('', $start, $limit, $search_query);
        $data['total']=$config['total_rows'];

        $this->layout->view('admin/pending', array('user' => $user_data, 'data' => $data,'search_query'=>$search_query));

    }

    /*
        function update_prod_username($start,$end){
            //시작, 끝 아이디 지정해서
            //이 번호부터 끝까지
            //1. 해당 prod_id 있는지 확인
            //2. 있으면  prod_username 있는지 확인
            //3. 없으면 username을 prod_username 으로 사용하도록 함
            for($i=$start; $i<=$end; $i++){
                $result = $this->Admin_model->update_prod_username($i);
                echo $result.'<br>';
            }

        }*/

    /*function make_user_profile($table,$start, $end){
        for($i=$start; $i<=$end; $i++){
            $result = $this->Admin_model->make_user_profile($table,$i);
            echo $result.'<br>';
        }
    }

    function make_payment(){

        $this->Admin_model->make_payment();
    }*/
    /*
    function make_fin_payment(){

        $this->Admin_model->make_fin_payement();
    }*/
    /*

    function set_thumbs($start,$end){
        //thumb없으면 기본 섬네일로 지정해버린다..
        for($i=$start; $i<=$end; $i++){
            $result = $this->Admin_model->update_thumbs($i);
            echo $result.'<br>';
        }
    }*/

    function set_prod_detail($start,$end){
        for($i=$start; $i<=$end; $i++){
            $result = $this->Admin_model->set_prod_detail($i);
            echo $result.'<br>';
        }
    }

    function set_payment_done($prod_id,$type=null){

        $this->Admin_model->set_payment_done($prod_id,$type);
        alert('결제처리 되었습니다.');
    //    print_r($result);
    }

    function fin_prod(){
        //시간 지난것들 가져와서 걔들만..
        $this->Admin_model->fin_prod();
        alert('시간이 지난 상품이 종료되었습니다.');
    }
    /*
    function init_sns_login($start,$end){

        for($i=$start; $i<=$end; $i++){
            $result = $this->Admin_model->init_sns_login($i);
            echo $result.'<br>';
        }
    }*/

}
