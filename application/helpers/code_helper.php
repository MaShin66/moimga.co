<?php
/**
 * Created by IntelliJ IDEA.
 * User: kangmin
 * Date: 2019. 11. 26.
 * Time: PM 4:25
 */

function auth_code_to_text($code){
    switch ($code){
        case 0:
            $text = 'admin';
            break;
        case 1:
            $text = 'representative';
            break;
        case 2:
            $text = 'member';
            break;
        default:
            $text = 'normal';
            break;
    }

    return $text;
}

function generate_random_code($length=6){
    $list = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $list_count = strlen($list);
    $return = '';
    for ($i = 0; $i < $length; $i++) {
        $return .= $list[rand(0, $list_count - 1)];
    }
    return $return;
}

function tag_strip($contents){
    $text =  iconv_substr($contents, 0, 200, "utf-8");
    $text = addslashes($text);
    $content = strip_tags($text);
    $result = str_replace("&nbsp;", "", $content);


    return $result;
}

function get_kr_date($date){

    $week = array("일" , "월"  , "화" , "수" , "목" , "금" ,"토") ;
    $weekday = $week[ date('w'  , strtotime($date)  ) ] ;

    $return = array(
        'kr_date' => null,
        'weekday' => $weekday,
    );

    $date_array = explode('-',$date);
    $this_year = date('Y');
    $return['kr_date'] = $date_array[1].'월 '.$date_array[2].'일';
    if($this_year!==$date_array[0]){
        $return['kr_date'] = $date_array[0].'년 '.$return['kr_date'];
    }
    return $return;
}
