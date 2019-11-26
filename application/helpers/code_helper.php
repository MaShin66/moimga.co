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