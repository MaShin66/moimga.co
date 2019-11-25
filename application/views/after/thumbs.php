<?php foreach ($after as $key=>$item){
    $number = $key+1;
    ?>
    <div class="col-lg-3 col-md-4 col-sm-6 col-6 item_card">
        <a href="/after/view/<?=$item['after_id']?>" class="item_link">
            <img src="" class="item_img" alt="<?=$item['title']?>" >
            <div class="item_info">
                <span class="item_title"><?=$item['title']?></span>
                <span class="item_title"><?=$item['team_name']?></span>

                <span class="item_seller"><?=$item['nickname']?></span> <!--팀 이름-->

            </div>
        </a>
    </div>
<?php }?>
