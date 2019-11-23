<?php

class Alarm_model extends CI_Model

{
    function __construct()

    {
        parent::__construct();
    }


    function get_alarm($alarm_id){

        $this->db->where('alarm_id',$alarm_id);

        $query = $this->db->get('alarm');
        $result = $query -> row_array();

        return $result;
    }


    function find_alarm_id($alarm_type=null, $prod_id=null, $form_id=null, $user_id=null){

        $this->db->where('type',$alarm_type);
        $this->db->where('prod_id',$prod_id);
        $this->db->where('user_id',$user_id);
        $this->db->where('form_id',$form_id);

        $query = $this->db->get('alarm');
        $result = $query -> row_array();

        return $result;
    }
    function get_alarm_count($user_id){

        $this->db->where('user_id',$user_id);
        $this->db->where('status','unread');
        $this->db->where('crt_date between subdate(now(), interval 7 DAY) and now()');
        $query = $this->db->get('alarm');
        $result = $query -> num_rows();

        return $result;
    }

    function insert_alarm($data) {

        $this->db->insert('alarm', $data);

        $latest_id = $this->db->insert_id();
        return $latest_id;
    }

    function delete_alarm($alarm_id){

        $this->db->where('alarm_id', $alarm_id);
        $this->db->order_by('crt_date', 'desc');
        $this->db->limit(1);
        $this->db->delete('alarm');
    }


    function update_alarm($alarm_id, $data){

        $this->db->where('alarm_id', $alarm_id);
        $this->db->update('alarm', $data);
        return $alarm_id;
    }


    function set_alarm_read($alarm_id){
        $data = array(
            'status'=>'read',
            'read_date'=>date('Y-m-d H:i:s'),
        );
        $this->db->where('alarm_id', $alarm_id);
        $this->db->update('alarm', $data);
        return $alarm_id;
    }


    function set_alarm_read_all($user_id){
        $data = array(
            'status'=>'read',
            'read_date'=>date('Y-m-d H:i:s'),
        );
        $this->db->where('status', 'unread');
        $this->db->where('user_id', $user_id);
        $this->db->update('alarm', $data);
        return $user_id;
    }

    function delete_unread_alarm($alarm_type=null, $prod_id=null){

        $this->db->where('type', $alarm_type);
        $this->db->where('prod_id', $prod_id);
        $this->db->where('status', 'unread');
        $this->db->delete('alarm');
    }



    function get_alarm_specific($type='', $prod_id, $from_user_id='', $user_id=''){

        $this->db->where('type',$type);
        $this->db->where('prod_id',$prod_id);
        $this->db->where('from_user_id',$from_user_id);
        $this->db->where('user_id',$user_id);
        $this->db->order_by('crt_date','desc');
        $this->db->limit(1);

        $query = $this->db->get('alarm');
        $result = $query -> row_array();

        return $result;
    }

    function load_alarm($user_id,$status='unread'){

        $this->db->where('user_id',$user_id);
        //오늘부터 7일까지의 내용만 가져온다.. // 이 코드 필요함
        $this->db->where('crt_date between subdate(now(), interval 7 DAY) and now()'); // 오늘부터 7일.. 되는지 확인해보기

        $this->db->where('status',$status);
        $this->db->order_by('crt_date','desc');
        $query = $this->db->get('alarm');

        $alarm_result = $query -> result_array();
        //read 된것 중  unread 가 새로 생기면 그건 또 새로운걸로 보여줘야됨 꼭 이 코드 해ㅎ야해!!!!!!!!!

        $result_copy = $alarm_result;
        $id_array=array();
        foreach ($result_copy as $key => $item){
            $this_type = $item['type'];
            $this_key = $key;
            $result_copy[$this_key]['count'] = 0;
            $this_prod= $item['prod_id'];

            for( $i=0; $i<count($result_copy); $i++){
                //합치는게 필요한것만 여기로 오도록..
                //C1,2, / D1, /F1
                if($result_copy[$i]['type']=='C1'||$result_copy[$i]['type']=='C2'||$result_copy[$i]['type']=='D1'
                    ||$result_copy[$i]['type']=='F1'||$result_copy[$i]['type']=='T1'){

                    if(($result_copy[$i]['type']==$this_type) &&($result_copy[$i]['prod_id']==$this_prod)){

                        $result_copy[$this_key]['count'] = $result_copy[$this_key]['count']+1;
                        if($result_copy[$this_key]['count']>1 && !array_search($result_copy[$i]['alarm_id'], $id_array)){
                            array_push($id_array, $result_copy[$i]['alarm_id']);

                        }
                    }
                }

            }

        }

        $id_array = array_unique($id_array);
        //돌면서.. 지우기..
        foreach ($result_copy as $real_key => $real_item){
            if(in_array($real_item['alarm_id'], $id_array)){
                unset($result_copy[$real_key]);
            }
        }
        foreach ($result_copy as $text_key => $text_item){
            $result_copy[$text_key]['text'] = $this->set_alarm_text($text_item['type'], $text_item['prod_id'], $text_item['count'], $text_item['form_id']);

            switch ($text_item['type'][0]){
                case 'T':

                    $this->db->flush_cache();
                    $this->db->select('url');
                    $this->db->where('ticket_id', $text_item['prod_id']);
                    $this->db->order_by('crt_date', 'desc');
                    $this->db->limit(1);

                    $query = $this->db->get('ticket_thumbs');
                    $result = $query->row_array();
                    break;
                case 'S': //검색 허용
                case 'D': //수요조사
                case 'U': //쿠폰
                case 'A': //양도인 경우에는 섬네일 기본으로 설정
                    $result['url'] = '/www/img/basic_thumbs.jpg';
                    break;
                default:

                    $this->db->flush_cache();
                    $this->db->select('url');
                    $this->db->where('prod_id', $text_item['prod_id']);
                    $this->db->order_by('crt_date', 'desc');
                    $this->db->limit(1);

                    $query = $this->db->get('thumbs');
                    $result = $query->row_array();
                    break;

            }

            $result_copy[$text_key]['thumb_url'] = $result['url'];
            $result_copy[$text_key]['url'] ='/prod/view/'.$text_item['prod_id'];
            $result_copy[$text_key]['icon'] ='<i class="fas fa-comments"></i>';
            switch ($text_item['type']){
                case 'C1':
                case 'C2':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-comments"></i>';
                    break;
                case 'C3':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-bullhorn"></i>';
                    break;
                case 'D1':
                    $result_copy[$text_key]['icon'] ='<i class="far fa-paper-plane"></i>';
                    $result_copy[$text_key]['url'] ='/manage/demand/forms/'.$text_item['prod_id'];
                    break;
                case 'D2':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-check-circle"></i>';
                    break;
                case 'D3':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-exclamation-circle"></i>';
                    $result_copy[$text_key]['url'] ='/demand/view/'.$text_item['prod_id'];
                    break;
                case 'F1':
                    $result_copy[$text_key]['icon'] ='<i class="far fa-paper-plane"></i>';
                    $result_copy[$text_key]['url'] ='/manage/prod/forms/'.$text_item['prod_id'];
                    break;
                case 'F2':
                case 'F12':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-check-circle"></i>';
                    $result_copy[$text_key]['url'] ='/mypage/form/'.$text_item['form_id'];
                    break;
                case 'F4':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-hourglass-end"></i>';
                    $result_copy[$text_key]['url'] ='/manage/prod/detail/'.$text_item['prod_id'];
                    break;
                case 'F3':
                case 'F6':
                case 'F11':
                case 'F13':
                case 'W3':
                case 'W4':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-exclamation-circle"></i>';
                    $result_copy[$text_key]['url'] ='/mypage/form/'.$text_item['form_id'];
                    break;
                case 'F7':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-exclamation-circle"></i>';
                    $result_copy[$text_key]['url'] ='/manage/prod/forms/'.$text_item['prod_id'];
                    break;
                case 'F8':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-exclamation-circle"></i>';
                    break;
                case 'F9':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-hand-holding-heart"></i>';
                    $result_copy[$text_key]['url'] ='/manage/prod/forms/'.$text_item['prod_id'];
                    break;
                case 'F10':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-exclamation-circle"></i>';
                    $result_copy[$text_key]['url'] ='/manage/prod/forms/'.$text_item['prod_id'];
                    break;
                case 'P1':
                case 'W5':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-check-circle"></i>';
                    $result_copy[$text_key]['url'] ='/manage/prod/detail/'.$text_item['prod_id'];
                    break;
                case 'P2':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-check-circle"></i>';
                    $result_copy[$text_key]['url'] ='/manage/import/detail/'.$text_item['prod_id'];
                    break;
                case 'P3'://후원
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-hand-holding-heart"></i>';
                    $result_copy[$text_key]['url'] ='/';
                    break;
                case 'P4': //프로모션
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-check-circle"></i>';
                    $result_copy[$text_key]['url'] ='/manage/prod';
                    break;
                case 'P5': //티켓 결제
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-check-circle"></i>';
                    $result_copy[$text_key]['url'] ='/manage/ticket/detail/'.$text_item['prod_id'];
                    break;
                case 'P6': //문자메시지 충전 결제
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-check-circle"></i>';
                    $result_copy[$text_key]['url'] ='/manage/sms/';
                    break;
                case 'V1':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-truck"></i>';
                    $result_copy[$text_key]['url'] ='/mypage/form/'.$text_item['form_id'];
                    break;
                case 'M1':
                    $result_copy[$text_key]['icon'] ='<i class="far fa-envelope"></i>';
                    $result_copy[$text_key]['url'] ='/mypage/form/'.$text_item['form_id'];
                    break;
                case 'T1':
                    $result_copy[$text_key]['icon'] ='<i class="far fa-paper-plane"></i>';
                    $result_copy[$text_key]['url'] ='/manage/ticket/forms/'.$text_item['prod_id'];
                    break;
                case 'T2':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-check-circle"></i>';
                    $result_copy[$text_key]['url'] ='/mypage/ticket/'.$text_item['form_id'];
                    break;
                case 'T3':
                case 'T5':
                case 'T10':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-exclamation-circle"></i>';
                    $result_copy[$text_key]['url'] ='/mypage/ticket/'.$text_item['form_id'];
                    break;
                case 'T4':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-hourglass-end"></i>';
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-hourglass-end"></i>';
                    $result_copy[$text_key]['url'] ='/manage/ticket/detail/'.$text_item['prod_id'];
                    break;
                case 'T6':
                case 'T11':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-exclamation-circle"></i>';
                    $result_copy[$text_key]['url'] ='/manage/ticket/forms';
                    break;
                case 'T7':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-exclamation-circle"></i>';
                    $result_copy[$text_key]['url'] ='/ticket/view/'.$text_item['prod_id'];
                    break;
                case 'T8':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-hand-holding-heart"></i>';
                    $result_copy[$text_key]['url'] ='/manage/ticket/detail/'.$text_item['prod_id'];
                    break;
                case 'T9':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-check-circle"></i>';
                    $result_copy[$text_key]['url'] ='/mypage/ticket/'.$text_item['form_id'];
                    break;
                case 'S1':
                case 'W1':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-check-circle"></i>';
                    $result_copy[$text_key]['url'] ='/mypage/';
                    break;
                case 'S2':
                case 'W2':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-exclamation-circle"></i>';
                    $result_copy[$text_key]['url'] ='/mypage/';
                    break;
                case 'A1':
                case 'A2':
                case 'A3':
                case 'A4':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-check-circle"></i>';
                    $result_copy[$text_key]['url'] ='/mypage/transfer'; //이건 연결 못해서 어쩔 수 없네요..
                    break;
                case 'A5':
                case 'A6':
                case 'A7':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-exclamation-circle"></i>';
                    $result_copy[$text_key]['url'] ='/mypage/transfer';//이건 연결 못해서 어쩔 수 없네요..
                    break;
                case 'U1':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-ticket-alt"></i>';
                    $result_copy[$text_key]['url'] ='/manage/coupon';
                    break;
                default:
                    break;

            }

        }

        return array_values($result_copy);
    }

    function _set_alarm_broad_type($start_char){
        switch ($start_char){
            case 'F':
                $type = 'form';
                break;
            case 'C':
                $type = 'comment';
                break;
            case 'D':
                $type = 'demand';
                break;
            case 'P':
                $type = 'payment';
                break;
            case 'V':
                $type = 'delivery';
                break;
            case 'M':
                $type = 'message';
                break;
            case 'T':
                $type = 'ticket';
                break;
            case 'A':
                $type = 'assignment'; //양도
                break;
			case 'W':
				$type = 'switch';
				break;
			case 'S':
				$type = 'search';
				break;
            default:
                $type = 'form';
                break;
        }
        return $type;

    }

    function set_alarm_text($type, $prod_id, $count='', $form_id=null){
        $this->db->select('title');
        
        switch ($type[0]){
          case  'D':
              $this->db->where('demand_id',$prod_id);
              $query = $this->db->get('demand');
              break;
          case  'T':
                $this->db->where('ticket_id',$prod_id);
                $query = $this->db->get('ticket');
                break;
            case  'P': //애앵 ㅇㅣ건 payment 인디..
                //P는 각각의 경우에 따라서 바꾼다..
                switch ($type){
                    case 'P1':
                        $this->db->where('prod_id',$prod_id);
                        $query = $this->db->get('product');
                        break;
                    case 'P2':
                        $this->db->where('import_prod_id',$prod_id);
                        $query = $this->db->get('import_product');
                        break;
                    case 'P5':
                        $this->db->where('ticket_id',$prod_id);
                        $query = $this->db->get('ticket');
                        break;
                    default: //p3, p4
                        break;
                }
                break;

            default: //c comment, f form, v delivery, m message, a assignment -- 모든 경우에 대해
                $this->db->where('prod_id',$prod_id);
                $query = $this->db->get('product');
                break;

        }
        
        $result = $query -> row_array();
        $title = $result['title'];
        if(strlen($title)>20){
            $title =  iconv_substr($title, 0, 18, "utf-8").'...';
        }

        $text = sprintf($this->lang->line($type), $title, $form_id, $count);
        return $text;
    }



}
