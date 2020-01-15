<?php
$list_txt = '큐레이션';
if($type=='store'){
    $list_txt = '셀렉션';
}

foreach ($list as $key=>$item){ ?>
<div class="col-lg-3 col-md-6 col-sm-6 col-6">
    <a class="main_cont_item"  href="/<?=$type?>/category/1/q?category=<?=$item['category_id']?>">

        <span class="cont_title">
            <span class="cont_maintitle"><?=$item['title']?></span>
            <span class="cont_subtitle"><?=$item['desc']?></span>
        </span>

        <span class="main_cont_img">
            <img src="<?=$item['thumb_url']?>">
        </span>

        <span class="main_cont_more main_more_<?=$type?>">
            <span class="cont_more_icon"><i class="fas fa-plus"></i></span>
            <span class=""><?=$item['count']?>개 <?=$list_txt?> 모두 보기</span>
        </span>

    </a>
</div>
<?php }?>