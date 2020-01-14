<?php foreach ($post as $key=>$item){ ?>
    <li class="list_item list_item_padding">
        <a href="/@<?=$item['url']?>/blog/<?=$item['team_blog_id']?>">

            <span class="pi_title"><?=$item['title']?></span>
            <span class="pi_info">
                <span class="pii_img">
                    <img src="<?=$item['thumb_url']?>" alt="<?=$item['title']?>" >
                </span>
                <span class="pii_cont">
                    <span class="pii_desc"><?=$item['contents']?></span>
                </span>
            </span>
        </a>
    </li>
<?php }?>
