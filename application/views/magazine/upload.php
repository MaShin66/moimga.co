<?php

$write_type=$this->input->get('write');

?>
<div class="cont-padding">

	<h1 class="faq_top" style="border-bottom: transparent;">
		글 쓰기
	</h1>

	<form method="post" action="/magazine/upload<?php if($write_type=='modify') {echo '?write=modify&id='.$result['magazine_id'];}?>" name="board" id="magazine_form" enctype="multipart/form-data">

		<div class="">
			<h2 class="form_p_title">제목</h2>

			<input type="text" class="form-control" value="<?php if($write_type=='modify'){echo $result['title'];} ?>" name="title">
		</div>
		<div class="">
			<hr>
			<h2 class="form_p_title">내용</h2>
			<div class="prod_upload_guide"><i class="fas fa-info-circle"></i> 이미지 추가는 툴바 오른쪽 끝의 '이미지 <i class="far fa-image"></i>' 아이콘을 누르신 후, 이미지 주소를 붙여넣으시면 됩니다. <a href="#" data-toggle="modal" data-target="#GuideModal">이미지 업로드 가이드</a></div>

			<div id="editor" style="min-height: 150px;">
				<?php if($write_type=='modify'){ echo $result['contents'];}?>
			</div>

			<input type="hidden" id="input_mirror" name="contents"/>
		</div>
        <div class="contents_end"></div>

		<hr>
		<h2 class="form_p_title">업로드 옵션</h2>

		<div class="form-inline" style="padding: 10px;">
			<input class=""  name="status" type="checkbox" id="inlineCheckbox4"
				   value="on" <?php if($write_type=='modify'){
				if($result['status']=='on'){ echo 'checked'; } }?>>
			<label class="upload_label" for="inlineCheckbox4">공개 여부</label>
			<small class="text-muted" style="margin-left: 10px;"></small>
		</div>

		<?php if($write_type=='modify'){?>
			<input type="hidden" value="<?=$result['magazine_id']?>" name="magazine_id">
		<?php } ?>

		<input type="hidden" value="<?=$user['user_id']?>" name="user_id">
		<input type="submit" value="등록하기" class="btn  btn-lg btn-full btn-primary upload_ok">
	</form>
</div>
