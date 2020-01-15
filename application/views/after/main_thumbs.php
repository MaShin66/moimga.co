<?php foreach ($after as $key=>$item){ ?>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="list_item list_item_padding">
            <a href="/after/view/<?=$item['after_id']?>">

                <span class="pi_title"><?=$item['title']?></span>
                <span class="pi_info">
                <span class="pii_img">
                    <img src="<?=$item['thumb_url']?>" alt="<?=$item['title']?>" >
                </span>
                <span class="pii_cont">
                    <span class="pii_desc"><?=$item['contents']?></span>
                    <span class="ati_program"><?=$item['program']?></span>
                    <span class="ati_team_name"><span class="ili_box"></span> <?=$item['team_name']?></span>
                </span>
            </span>
            </a>
        </div>
    </div>
<?php }?>
