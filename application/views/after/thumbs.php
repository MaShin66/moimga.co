<?php foreach ($after as $key=>$item){
    $number = $key+1;
    ?>
    <li class="list_item list_item_padding">
        <a href="/after/view/<?=$item['after_id']?>">

            <span class="pi_title"><?=$item['title']?></span>
            <span class="pi_info">
                <span class="pii_img">
                    <img src="<?=$item['thumb_url']?>" alt="<?=$item['title']?>" >
                </span>
                <span class="pii_cont">
                    <span class="pii_desc"><?=$item['contents']?></span>
                    <span class="ati_team_name"> - <?=$item['team_name']?></span>
                </span>
            </span>
        </a>
    </li>
<?php }?>
