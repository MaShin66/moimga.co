
<?php
$number = count($contents);

foreach ($contents as $key=>$item){
    if($number<10){
        $number = '0'.$number;
    }  ?>
    <li class="list_item list_item_padding">
        <a href="/<?=$meta_array['location']?>/view/<?=$item[$meta_array['location'].'_id']?>">

            <span class="pi_title">
                <span class="pi_num"><?=$number?></span>
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
    </li>
<?php $number--; }?>

