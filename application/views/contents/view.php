<?php

$date = explode(' ',$result['crt_date']);
$new_date = $date[0];
?>

<div class="cont-padding">
	<div class="row justify-content-md-center">

		<div class="col-lg-8 col-md-12 col-sm-12">

            <div class="header_box header_space"></div>
            <h1 class="top_title"><?=$result['title']?></h1>
			<span class="magazine_date"><?=$new_date?></span>
			<div class="magazine_contents">
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
</div>
