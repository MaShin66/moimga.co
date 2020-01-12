<?php
//print_r($contents);
?>
<?php foreach ($contents as $key=>$item){ ?>
<div class="cont_item">
    <div class="cont_img">

        <img src="<?=$item['program'][0]['thumb_url']?>">
    </div>
    <div class="cont_info">
        <a class="cont_title" href="">
             <span class="cont_maintitle"><?=$item['title']?></span>
            <span class="cont_subtitle"><?=$item['desc']?></span>
        </a>

        <div class="cont_subitem">
            <?php foreach ($item['program'] as $p_key=>$p_item){?>

                <a href="/contents/view/<?=$p_item['contents_id']?>">
                    <span class="csi_num"><?=$item['program_count']-$p_key?></span>
                    <span class="csi_title"><?=$p_item['title']?></span>
                </a>
            <?php }?>

        </div>
        <?php if($item['program_count']>3){?>
            <a class="cont_more" href="">
                <span class="cont_more_icon"><i class="fas fa-plus"></i></span>
                <span class="">그 외 <?=$item['program_count']-3?>개 큐레이션 더 보기</span>
            </a>
        <?php }?>

       
    </div>
</div>
<?php }?>