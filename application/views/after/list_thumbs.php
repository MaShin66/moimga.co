<?php foreach ($after_list as $a_key=>$a_item){?>
    <li>
        <a class="after_list_item" href="/after/view/<?=$a_item['after_id']?>">
            <span class="ali_img">
                <img src="<?=$a_item['thumb_url']?>">
            </span>
            <span class="ali_info">
                <span class="ali_title"><?=$a_item['title']?></span>
                <span class="ali_desc"><?=$a_item['contents']?></span>
            </span>
        </a>
    </li>
<?php }?>
