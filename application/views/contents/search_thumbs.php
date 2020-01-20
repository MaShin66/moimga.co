<?php
//print_r($contents);
?>
<?php foreach ($contents as $key=>$item){ ?>
<div class="col-lg-6 col-md-6 col-sm-12">
    <div class="list_item">
        <a href="/<?=$type?>/view/<?=$item[$type.'_id']?>">

            <span class="pi_title">
                <span class="pi_num"><?=$item[$type.'_id']?></span>
                <span class=""><?=$item['title']?></span>
            </span>
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