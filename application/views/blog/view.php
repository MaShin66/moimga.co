<?php

$date = explode(' ',$result['crt_date']);
$new_date = $date[0];
?>

<div class="cont-padding">
	<div class="row justify-content-md-center">

		<div class="col-lg-8 col-md-12 col-sm-12">

            <div class="header_box header_space"></div>
            <h1 class="top_title"><?=$result['title']?></h1>
			<span class="blog_date"><?=$new_date?></span>
			<div class="blog_contents">
				<?=$result['contents']?>
			</div>
			<div class="blog_bottom">

				<a href="/blog" class="btn btn-round btn-outline-action ">목록</a>
				<?php if($user['user_id']==$result['user_id']){?>
					<a href="/blog/upload?write=modify&id=<?=$result['blog_id']?>" class="btn btn-round btn-outline-action">수정</a>
				<?php }?>
			</div>
		</div>
	</div>
</div>
