
<div class="cont-padding">

	<div class="header_box header_space"></div>
	<h1 class="top_title">
		Magazine
	</h1>

	<div class="mp_form_list">
		<?php foreach ($data['result'] as $result){

			$text = substr($result['contents'], 0, 1050);
			$content = strip_tags ($text);
		?>
		<a class="magazine_list_item" href="/magazine/view/<?=$result['magazine_id']?>">
			<span class="magazine_list_title"><?=$result['title']?></span>
			<span class="magazine_list_cont"><?=str_replace("&nbsp;","",$content);?></span>
			<span class="magazine_list_info">
				<span class="magazine_list_read_more">Read more  →</span>
				<span class="magazine_list_date"><i class="far fa-clock"></i> <?=substr($result['crt_date'],0,10); ?></span>
			</span>
		</a>

		<?php }?>

	</div>
    <?php if($user['level']==9){?>
        <a href="/magazine/upload" class="btn btn-outline-primary">등록</a>
    <?php }?>

	<nav class="page-navigation">
		<ul class="pagination justify-content-center">
			<?php echo $data['pagination'];?>
		</ul>
	</nav>
</div>
