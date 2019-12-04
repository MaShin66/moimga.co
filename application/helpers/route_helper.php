<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-24
 * Time: 오후 5:28
 */

function get_team_id($at_url){

    $CI =& get_instance();

    $org_url = explode('@',$at_url);
    $team_url = $org_url[1];
    $team_id = $CI->team_model->get_team_id_by_url($team_url);

    return $team_id;
}

function is_team_url($segment){ //첫번째 segment가 @로 시작하면.. url 타입이 뭔지 던져준다.

    $CI =& get_instance();

    $location = 'team'; //기본으로 설정
    $org_url = explode('@',$segment);

    if($org_url!=$segment){ //team url임
        $section = $CI->uri->segment(2);
        if($section=='program'){
            $location = 'team';
        }
    }

    return $location;
}