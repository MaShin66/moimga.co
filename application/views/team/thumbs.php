<?php foreach ($team as $key=>$item){
    $number = $key+1;
    ?>
    <div class="col-lg-3 col-md-4 col-sm-6 col-6 item_card">
        <a href="/team/@<?=$item['url']?>" class="item_link">
            <img src="<?=$item['thumb_url']?>" class="item_img" alt="<?=$item['title']?>" >
            <div class="item_info">
                <span class="item_title"><?=$item['title']?></span>
                <span class="item_seller"><?=$item['name']?></span> <!--팀 이름-->
                <span class="item_seller">/@<?=$item['url']?></span> <!--팀 이름-->


            </div>
        </a>
    </div>
<?php }?>
