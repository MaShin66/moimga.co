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


    function find_alarm_id($alarm_type=null, $team_id=null, $program_id=null, $user_id=null){

        $this->db->where('type',$alarm_type);
        $this->db->where('team_id',$team_id);
        $this->db->where('user_id',$user_id);
        $this->db->where('program_id',$program_id);

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

    function delete_unread_alarm($alarm_type=null, $team_id=null){

        $this->db->where('type', $alarm_type);
        $this->db->where('team_id', $team_id);
        $this->db->where('status', 'unread');
        $this->db->delete('alarm');
    }



    function get_alarm_specific($type='',$user_id='', $from_user_id='', $team_id, $program_id=null){

        $this->db->where('type',$type);
        $this->db->where('user_id',$user_id);
        $this->db->where('from_user_id',$from_user_id);
        $this->db->where('team_id',$team_id);
        if($program_id!=null){
            $this->db->where('program_id',$program_id);

        }
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
            $this_team= $item['team_id'];

            for( $i=0; $i<count($result_copy); $i++){
                //합치는게 필요한것만 여기로 오도록..
                //C1,2, / D1, /F1
                if($result_copy[$i]['type']=='T1'){

                    if(($result_copy[$i]['type']==$this_type) &&($result_copy[$i]['team_id']==$this_team)){

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
            $result_copy[$text_key]['text'] = $this->set_alarm_text($text_item['type'], $text_item['team_id'], $text_item['count'], $text_item['program_id']);

            $this->db->flush_cache();

            switch ($text_item['type'][0]){
                case 'P': //프로그램

                    $this->db->select('program.thumb_url, team.url as url');
                    $this->db->join('team','team.team_id = program.team_id');
                    $this->db->where('program.program_id', $text_item['program_id']);
                    $query = $this->db->get('program');
                    break;
                case 'B':
                default: //T

                    $this->db->select('url, thumb_url');
                    $this->db->where('team_id', $text_item['team_id']);
                    $query = $this->db->get('team');
                    break;

            }

            $result = $query->row_array();
            $result_copy[$text_key]['thumb_url'] = $result['thumb_url'];
            $result_copy[$text_key]['url'] ='/manage/team/detail/'.$text_item['team_id']; //T3,8,10은 기본
            $result_copy[$text_key]['icon'] ='<i class="fas fa-user-plus"></i>';
            switch ($text_item['type']){
                case 'T1':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-bookmark"></i>';
                    break;
                case 'T2':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-pen-nib"></i>';
                    break;
                case 'T3':
                    $result_copy[$text_key]['url'] ='/manage/member/lists/'.$text_item['team_id']; //T3,8,10은 기본
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-pen-nib"></i>';
                    break;
                case 'T5':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-eye"></i>';
                    break;
                case 'T6':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-eye-slash"></i>';
                    break;
                case 'T9':
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-medal"></i>';
                    break;
                case 'P1':
                    $result_copy[$text_key]['url'] ='/manage/program/detail/'.$text_item['program_id'];
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-eye"></i>';
                    break;
                case 'P2':
                    $result_copy[$text_key]['url'] ='/manage/program/detail/'.$text_item['program_id'];
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-eye-slash"></i>';
                    break;
                case 'T12':
                    $result_copy[$text_key]['url'] ='/@'.$result['url'].'/program/'.$text_item['program_id'];
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-plus"></i>';
                    break;
                case 'T13':
                    $result_copy[$text_key]['url'] ='/manage/program/lists/'.$text_item['team_id'];
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-plus"></i>';
                    break;
                case 'B1':
                    $result_copy[$text_key]['url'] ='/@'.$result['url'].'/blog/'.$text_item['program_id']; //blog_id = program_id 로 사용한다
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-file-alt"></i>';
                    break;
                case 'B2':
                    $result_copy[$text_key]['url'] ='/manage/blog/lists/'.$text_item['team_id'];
                    $result_copy[$text_key]['icon'] ='<i class="fas fa-file-alt"></i>';
                    break;
                default:
                    break;

            }

        }

        return array_values($result_copy);
    }

    function _set_alarm_broad_type($start_char){
        switch ($start_char){
            case 'T':
                $type = 'team';
                break;
            case 'B':
                $type = 'blog'; //post
                break;
            case 'P':
                $type = 'program';
                break;
            default:
                $type = 'team';
                break;
        }
        return $type;

    }

    function set_alarm_text($type, $team_id, $count='', $program_id=null){
        
        switch ($type[0]){
          case  'P':
              $this->db->select('title');
              $this->db->where('program_id',$program_id);
              $query = $this->db->get('program');
              $result = $query -> row_array();
              $title = $result['title'];
                break;
        case  'B':
        default:
            $this->db->select('name');
            $this->db->where('team_id',$team_id);
            $query = $this->db->get('team');
            $result = $query -> row_array();
            $title = $result['name'];
            break;

        }

        if(strlen($title)>20){
            $title =  iconv_substr($title, 0, 18, "utf-8").'...';
        }

        $text = sprintf($this->lang->line($type), $title, $program_id, $count);
        return $text;
    }



}
