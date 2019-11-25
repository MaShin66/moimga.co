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
