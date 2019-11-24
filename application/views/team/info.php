<?php
print_r($moim_info);
print_r($app_list);
/***
 *
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-10
 * Time: 오후 4:28
 */?>
<div class="">
    <!--열려있는 지원서 목록을 출력한다..-->

<!--    <a href="" class="btn btn-outline-secondary">지원서 작성</a>-->
    <?php if($app_list){ ?>
        //열려있고 말고와 상관없이 출력하고 작성 가능한것만 위로 올린다..
        // 현재 가능한 모임을 따로 출력함
        <?php foreach ($app_list['open'] as $o_key=>$o_item){?>
            <?=$o_item['title']?>
        <?php }?>

        //지난 모임
        <?php foreach ($app_list['close'] as $c_key=>$c_item){?>
            <?=$c_item['title']?>
        <?php }?>
    <?php }else{?>
아직 지원서가 없습니다.
    <?php }?>
</div>
