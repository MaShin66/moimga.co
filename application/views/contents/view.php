<?php

//cv = contents_view

$date = explode(' ',$result['crt_date']);
$new_date = $date[0];
?>

	<div class="row justify-content-md-center">

		<div class="col-lg-8 col-md-12 col-sm-12">
            <div class="list_top">

                <h1 class="top_title"><?=$result['title']?></h1>
            </div>
            <div class="cv_top">
                <div class="cvt_img">
                    <img src="<?=$result['thumb_url']?>">
                </div>

                <div class="cvt_info">

                    <span class="cv_date"><?=$new_date?></span>
                    <span class="cv_author"><?=$result['author']?></span>
                    <span class="cv_hit"><?=number_format($result['hit'])?> 읽음</span>
                </div>

            </div>


            <div class="cv_line"></div>
			<div class="cv_contents">
				<?=$result['contents']?>
			</div>
			<div class="magazine_bottom">

				<a href="/contents" class="btn btn-round btn-outline-action ">목록</a>
				<?php if($user['user_id']==$result['user_id']){?>
					<a href="/contents/upload?write=modify&id=<?=$result['contents_id']?>" class="btn btn-round btn-outline-action">수정</a>
				<?php }?>
			</div>
		</div>
	</div>
