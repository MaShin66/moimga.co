<?php foreach ($team as $key=>$item){
    
    $number = $key+1;
    ?>
    <div class="col-lg-3 col-md-4 col-sm-6 col-6 item_card">
        <a href="/@<?=$item['url']?>" class="item_link">
            <img src="<?=$item['thumb_url']?>" class="item_img" alt="<?=$item['title']?>" >
            <div class="item_info">
                <span class="item_title"><?=$item['title']?></span>
                <div class="item_cont"><?=$item['contents']?></div> <!--cont를 정리해야하는구나..-->
            </div>
            <div class="item_subinfo">
                <div class="item_team_name">
                    <span><?=$item['name']?></span>

                </div> <!--팀 이름-->
                <div class="item_heart">
                    <!--내가 눌렀으면 색칠 돼있음-->
                    <?php if($item['heart_on']=='on'){ ?>
                        <span class="item_heart_on"><i class="fas fa-heart"></i></span>
                    <?php }else{ ?>
                        <span class="item_heart_off"><i class="far fa-heart"></i></span>
                    <?php }?>
                    <div class=""><?=$item['heart_count']?></div>
                </div> <!--팀 이름-->
            </div>
        </a>
    </div>
<?php }?>
