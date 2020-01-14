<?php

$date = explode(' ',$result['crt_date']);
$new_date = $date[0];
?>

<div class="cont-padding">
	<div class="row justify-content-md-center">

		<div class="col-lg-8 col-md-12 col-sm-12">

            <div class="header_box header_space"></div>
            <h1 class="top_title"><?=$result['title']?></h1>
			<span class="store_date"><?=$new_date?></span>
			<div class="store_contents">
				<?=$result['contents']?>
			</div>
			<div class="store_bottom">

				<a href="/store" class="btn btn-round btn-outline-action ">목록</a>
				<?php if($user['user_id']==$result['user_id']){?>

                    <div class="cv_manage">
                        <a href="/store/upload?write=modify&id=<?=$result['store_id']?>" class="btn btn-round btn-outline-action">수정</a>
                    </div>

				<?php }?>
			</div>
		</div>
	</div>
</div>
