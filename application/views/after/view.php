<?php

$date = explode(' ',$after_info['crt_date']);
$new_date = $date[0];
?>

<div class="row justify-content-md-center">

    <div class="col-lg-8 col-md-12 col-sm-12">
        <?php if($after_info['user_id']==$user['user_id']){?>
            <div class="cv_manage">
                <a href="/mypage/after/detail/<?=$after_info['after_id']?>" class="btn btn-outline-action">관리</a>
                <a href="/after/upload/<?=$after_info['after_id']?>?type=modify" class="btn btn-outline-secondary ">수정</a>
                <form action="/mypage/after/delete/" method="post">
                    <input type="hidden" name="after_id" value="<?=$after_info['after_id']?>">
                    <input type="submit"  class="btn btn-outline-danger  btn-delete" value="삭제">
                </form>
            </div>

        <?php }?>
        <div class="list_top">

            <h1 class="top_title"><?=$after_info['title']?></h1>
            <h2 class="top_desc"><?=$after_info['team_name']?></h2>

        </div>
        <div class="cv_top">
            <div class="cvt_img">
                <img src="<?=$after_info['thumb_url']?>">
            </div>

            <div class="cvt_info">

                <span class="cv_date"><?=$new_date?></span>
                <span class="cv_author"><?=$after_info['program']?></span>
                <span class="cv_hit"><?=number_format($after_info['hit'])?> 읽음</span>
            </div>

        </div>


        <div class="cv_line"></div>
        <div class="cv_contents">
            <?=$after_info['contents']?>
        </div>
        <div class="cv_bottom">

            <a href="/after" class="btn btn-round btn-outline-action ">목록</a>

        </div>
    </div>
</div>