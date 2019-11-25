<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-11-13
 * Time: 오후 4:47
 */?>

<div class="cont-padding">
    <div class="header_box header_space"></div>
    <h1 class="top_title">프로그램 목록</h1>
    <div class="prod_list">
        <div class="row">
            <?php $this->load->view('program/thumbs', array('program'=>$data['result'])); ?>
        </div>

    </div>
    <nav class="page-navigation">
        <ul class="pagination justify-content-center">
            <?php echo $data['pagination'];?>
        </ul>
    </nav>
</div>
