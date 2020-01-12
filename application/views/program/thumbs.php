<?php foreach ($program as $key=>$item){
    $number = $key+1;
    ?>
    <li class="list_item">
        <a href="/@<?=$item['team_url']?>/program/<?=$item['program_id']?>">

            <span class="pi_title"><?=$item['title']?></span>
            <span class="pi_info">
                <span class="pii_img">
                    <img src="<?=$item['thumb_url']?>" alt="<?=$item['title']?>" >
                </span>
                <span class="pii_cont">
                    <span class="pii_desc"><?=$item['contents']?></span>
                    <span class="pii_date"><?=$item['event_date']?> (<?=$item['weekday']?>) <?=$item['time']?>:00부터 총 <?=$item['round']?>회</span>
                    <span class="pii_price">
                        <span class="pii_cate">참가비</span>
                        <span class="pii_value"><?=number_format($item['price'])?>won</span>
                    </span>
                    <span class="pii_venue">

                        <span class="pii_cate">장소</span>
                        <span class="pii_value"><?=$item['district']?> @<?=$item['venue']?></span>
                    </span>
                </span>

            </span>
            <span class="item_sub_info">
                <span class="item_heart">
                    <?php if($item['heart_on']=='on'){ ?>
                        <span class="item_heart_on"><i class="fas fa-heart"></i></span>
                    <?php }else{ ?>
                        <span class="item_heart_off"><i class="far fa-heart"></i></span>
                    <?php }?>
                    <span class="item_heart_count"><?=$item['heart_count']?></span>
                </span>
                <span class="item_team_name"> - <?=$item['team_name']?></span>
            </span>

        </a>
    </li>
<?php }?>
