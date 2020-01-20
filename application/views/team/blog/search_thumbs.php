<?php
//print_r($contents);
?>
<?php foreach ($team_blog as $key=>$item){ ?>
<div class="col-lg-6 col-md-6 col-sm-12">
    <div class="list_item list_item_padding">
        <a href="/@<?=$item['url']?>/blog/<?=$item['team_blog_id']?>">

            <span class="pi_title"><?=$item['title']?></span>
            <span class="pi_info">
                <span class="pii_img">
                    <img src="<?=$item['thumb_url']?>" alt="<?=$item['title']?>" >
                </span>
                <span class="pii_cont">
                    <span class="pii_desc"><?=$item['contents']?></span>
                    <span class="ati_team_name"> - <?=$item['author']?></span>
                </span>
            </span>
        </a>
    </div>

</div>
<?php }?>