<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-21
 * Time: 오후 10:14
 */

function thumbs_upload($type='team', $unique_id, $ratio='basic'){

    $CI =& get_instance();
    
    $config['upload_path'] = './www/thumbs/'.$type.'/'; //team path
    $config['allowed_types'] = '*';
    $config['overwrite'] = true;
    $config['remove_spaces'] = TRUE;
    $config['max_size'] = 3 * 1024; //이미지 최대크기

    $config['file_name'] = $unique_id.'_'.strtotime("now");

    $CI->load->library('upload', $config);
    $CI->upload->initialize($config);

    if ($CI->upload->do_upload('thumbs')){ //있을 때만 업로드 하도록

        $data = array('upload_data' => $CI->upload->data());

        $width = $data['upload_data']['image_width'];
        $height = $data['upload_data']['image_height'];

        $CI->load->library('image_lib');
        $config_gd2['image_library'] = 'gd2';
        $config_gd2['source_image'] = FCPATH . '/www/thumbs/'.$type.'/'.$data['upload_data']['file_name'];
        $config_gd2['new_image'] = FCPATH . '/www/thumbs/'.$type.'/resize';
        $config_gd2['thumb_marker'] = '';
        /*ratio에 따라서*/

        if($ratio!='basic'){ //6:4
            if($width<$height){ //세로 기준으로

                $config_gd2['max_height'] = '240';
                $config_gd2['height'] = 240;
            }else{ //가로를 320으로 고정했을때 새로운 height는?

                $config_gd2['max_width'] = '360';
                $config_gd2['width'] = 360;
            }
        }else{ //basic은 1:1

            $config_gd2['max_width'] = '360';
            $config_gd2['width'] = 360;
            $config_gd2['max_height'] = '360';
            $config_gd2['height'] = 360;
        }


        $CI->image_lib->clear();
        $CI->image_lib->initialize($config_gd2);

        // 이미지 썸네일 만들기
        if (!$CI->image_lib->resize()) {
            $data['thumb_error'] = $CI->image_lib->display_errors();
        }

        //자르기 환경 설정

        $CI->image_lib->clear();
        //바뀐것의 height
        $config_gd_crop['source_image'] = FCPATH . '/www/thumbs/'.$type.'/resize/'.$data['upload_data']['file_name'];
        $config_gd_crop['new_image'] = FCPATH . '/www/thumbs/'.$type;
        $config_gd_crop['maintain_ratio'] = FALSE;
        //바뀐 값을 어케 알지?
        $config_gd_crop['width'] = 360;

        if($ratio!='basic'){
            $config_gd_crop['height'] = 240;
        }else{ //basic은 1:1
            $config_gd_crop['height'] = 360;
        }


        $CI->image_lib->initialize($config_gd_crop);

        // 이미지 자르기
        if (!$CI->image_lib->crop()) {
            $data['crop_error'] =  $CI->image_lib->display_errors();
        }

        $thumbs_url =  '/www/thumbs/'.$type.'/'.$data['upload_data']['file_name'];
        unlink($config_gd_crop['source_image']);   //이 resize폴더에있는거 지우기..

    }else{
        $thumbs_url = null;
    }
    return $thumbs_url;

}