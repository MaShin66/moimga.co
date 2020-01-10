<?php

$date = explode(' ',$result['crt_date']);
$new_date = $date[0];
?>

<div class="cont-padding">
	<div class="row justify-content-md-center">

		<div class="col-lg-8 col-md-12 col-sm-12">

            <div class="header_box header_space"></div>
            <h1 class="top_title"><?=$result['title']?></h1>
			<span class="shop_date"><?=$new_date?></span>
			<div class="shop_contents">
				<?=$result['contents']?>
			</div>
			<div class="shop_bottom">

				<a href="/shop" class="btn btn-round btn-outline-action ">목록</a>
				<?php if($user['user_id']==$result['user_id']){?>
					<a href="/shop/upload?write=modify&id=<?=$result['shop_id']?>" class="btn btn-round btn-outline-action">수정</a>
				<?php }?>
			</div>
		</div>
	</div>
</div>
