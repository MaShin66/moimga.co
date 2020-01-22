<?php foreach ($team as $key=>$item){
    
    $number = $key+1;
    ?>
    <div class="col-lg-4 col-md-4 col-sm-12 item_card">
        <a href="/@<?=$item['url']?>" class="item_link">
            <img src="<?=$item['thumb_url']?>" class="item_img" alt="<?=$item['title']?>" >
            <div class="item_info">
                <span class="item_title"><?=$item['title']?></span>
                <div class="item_cont"><?=$item['contents']?></div> <!--cont를 정리해야하는구나..-->
            </div>
            <div class="item_sub_info">
                <span class="item_heart">
                    <?php if($item['heart_on']=='on'){ ?>
                        <span class="item_heart_on"><i class="fas fa-heart"></i></span>
                    <?php }else{ ?>
                        <span class="item_heart_off"><i class="far fa-heart"></i></span>
                    <?php }?>
                    <span class="item_heart_count"><?=$item['heart_count']?></span>
                </span>
                <span class="item_subscribe">
                   <i class="far fa-bookmark"></i> <span class="item_subscribe_count"><?=$item['subscribe_count']?></span>
                </span>
                <span class="item_team_name"> - <?=$item['name']?></span>
            </div>
            <div class="item_latest">
                <?php if(!is_null($item['program'])){?>
                    <div class="il_img">
                        <img src="<?=$item['program']['thumb_url']?>">
                    </div>
                    <div class="il_cont">
                        <div class="il_title"><?=$item['program']['title']?></div>
                        <div class="il_desc"><?=$item['program']['contents']?></div>
                        <div class="il_info">
                            <span class="ili_box"></span>
                            <span class="ili_date">
                                <?=$item['program']['date']?> <?=$item['program']['time']?>:00 (<?=$item['program']['weekday']?>)
                            </span>
                            <span class="ili_price">
                                <?=number_format($item['program']['price'])?>won
                            </span>
                        </div>
                    </div>
                <?php }?>

            </div>
        </a>
    </div>
<?php }?>
