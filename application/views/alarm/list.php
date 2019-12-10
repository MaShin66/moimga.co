<div class="cont-padding">
    <div class="header_box header_space"></div>
    <h1 class="top_title">
        <?=$this->lang->line('alarm')?>
    </h1>

    <div class="alarm_wrap">
        <?php if(count($alarms)==0){?>

            <div class="item_empty">
                <div class="item_empty_icon">
                    <i class="fas fa-exclamation"></i>
                </div>
                아직 받은 알람이 없습니다.<br>
                댓글, 폼 작성, 입금확인 상태 등을 알람 창에서 확인할 수 있어요.
            </div>
        <?php }?>

        <?php foreach ($alarms as $key => $item){   ?>
            <div class="alarm_item <?=$item['status']?>" id="<?=$key?>">
                <a href="<?=$item['url']?>" class="alarm_go">
                    <div class="alarm_thumb_wrap">
                        <?php if($item['thumb_url']!=null){?>
                            <img class="alarm_thumb" src="<?=$item['thumb_url']?>"  alt="상품 미리보기 이미지">
                        <?php }else{?>
                            <img class="alarm_thumb" src="../www/img/basic_thumbs.jpg"  alt="상품 미리보기 이미지">
                        <?php  }?>
                    </div>
                    <div class="alarm_info">
                        <div class="alarm_text"><?=$item['text']?></div>
                        <div class="alarm_icon"><?=$item['icon']?></div>
                        <div class="alarm_date"><?=$item['crt_date']?></div>
                    </div>
                </a>
            </div>
        <?php }?>
        <div class="divider_dot text-center">
            <span class="ddot"></span>
            <span class="ddot"></span>
            <span class="ddot"></span>
            <span class="ddot"></span>
            <span class="ddot"></span>
            <span class="ddot"></span>
        </div>
        <div class="alarm_notice">
            오늘로부터 7일 전까지의 알람만 보입니다.
        </div>
        <!--<div class="alarm_more">
            더 보기 <i class="fas fa-chevron-down"></i>
        </div>-->
    </div>

</div>