
<?php
$date = explode(' ',$post_info['crt_date']);
$new_date = $date[0];
?>

<div class="row justify-content-md-center">

    <div class="col-lg-8 col-md-12 col-sm-12">
        <div class="list_top">

            <h1 class="top_title"><?=$post_info['title']?></h1>
            <h2 class="top_desc"><?=$team_info['name']?></h2>
            <?php if($post_info['user_id']==$user['user_id']){?>
                <div class="cv_manage">
                    <a href="/<?=$at_url?>/blog/upload/<?=$post_info['team_blog_id']?>?type=modify" class="btn-outline-action btn">수정</a>
                </div>

            <?php }?>
        </div>
        <div class="cv_top">
            <div class="cvt_img">
                <img src="<?=$post_info['thumb_url']?>">
            </div>

            <div class="cvt_info">

                <span class="cv_date"><?=$new_date?></span>
                <span class="cv_hit"><?=number_format($post_info['hit'])?> 읽음</span>
            </div>

        </div>


        <div class="cv_line"></div>
        <div class="cv_contents">
            <?=$post_info['contents']?>
        </div>
        <div class="cv_bottom">

            <a href="/<?=$at_url?>/blog" class="btn-outline-action btn">목록으로</a>

        </div>
    </div>
</div>