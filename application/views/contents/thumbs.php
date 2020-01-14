<?php
//print_r($contents);
?>
<?php foreach ($contents as $key=>$item){ ?>
    <div class="col-lg-4 col-md-3 col-sm-12">

        <div class="cont_item">

            <a class="cont_title" href="/<?=$meta_array['location']?>/category/1/q?category=<?=$item[$meta_array['location'].'_category_id']?>">
                <span class="cont_maintitle"><?=$item['title']?></span>
                <span class="cont_subtitle"><?=$item['desc']?></span>
            </a>

            <div class="cont_img">
                <a href="/<?=$meta_array['location']?>/view/<?=$item['sub_cont'][0][$meta_array['location'].'_id']?>"><img src="<?=$item['sub_cont'][0]['thumb_url']?>"></a>
            </div>
            <div class="cont_info">

                <ul class="cont_subitem">
                    <?php foreach ($item['sub_cont'] as $p_key=>$p_item){?>

                        <li>
                            <a href="/<?=$meta_array['location']?>/view/<?=$p_item[$meta_array['location'].'_id']?>">
                                <span class="csi_num">
                                <?php
                                $this_num =$item['sub_cont_count']-$p_key;
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


            <a class="cont_more" href="/<?=$meta_array['location']?>/category/1/q?category=<?=$item[$meta_array['location'].'_category_id']?>">
                <span class="cont_more_icon"><i class="fas fa-plus"></i></span>
                <span class="">
    <?php if($item['sub_cont_count']>3){?>
        그 외 <?=$item['sub_cont_count']-3?>개 큐레이션 더 보기
    <?php }else{?>
        모두 보기
    <?php }?></span>


            </a>

        </div>
    </div>
<?php }?>