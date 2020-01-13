<?php
//print_r($contents);
?>
<?php foreach ($contents as $key=>$item){ ?>
    <div class="col-lg-4 col-md-3 col-sm-12">

        <div class="cont_item">

            <a class="cont_title" href="/contents/category/1/q?category=<?=$item['contents_category_id']?>">
                <span class="cont_maintitle"><?=$item['title']?></span>
                <span class="cont_subtitle"><?=$item['desc']?></span>
            </a>

            <div class="cont_img">
                <a href="/contents/view/<?=$item['program'][0]['contents_id']?>"><img src="<?=$item['program'][0]['thumb_url']?>"></a>
            </div>
            <div class="cont_info">

                <ul class="cont_subitem">
                    <?php foreach ($item['program'] as $p_key=>$p_item){?>

                        <li>
                            <a href="/contents/view/<?=$p_item['contents_id']?>">
                                <span class="csi_num">
                                <?php
                                $this_num =$item['program_count']-$p_key;
                                if($this_num<10){

                                    echo '0'.$this_num;
                                }else{
                                    echo $this_num;
                                } ?></span>
                                <span class="csi_info">

                                    <span class="csi_title"><?=$p_item['title']?></span>
                                    <span class="csi_author"><?=$p_item['author']?></span>
                                </span>
                            </a>
                        </li>

                    <?php }?>

                </ul>

            </div>


            <a class="cont_more"  href="/contents/category/1/q?category=<?=$item['contents_category_id']?>">
                <span class="cont_more_icon"><i class="fas fa-plus"></i></span>
                <span class="">
    <?php if($item['program_count']>3){?>
        그 외 <?=$item['program_count']-3?>개 큐레이션 더 보기
    <?php }else{?>
        모두 보기
    <?php }?></span>


            </a>

        </div>
    </div>
<?php }?>