<?php

?>

<h2>FAQ 카테고리 <?=$data['submit_txt']?></h2>

<form action="/admin/faq_category/upload/" method="post">
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">이름</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" id="name" value="<?=$data['name']?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="url_name" class="col-sm-2 col-form-label">url</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="url_name" id="url_name" value="<?=$data['url_name']?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="order" class="col-sm-2 col-form-label">순서</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" name="order" id="order" value="<?=$data['order']?>">
        </div>
    </div>

    <?php if(!is_null($data['faq_category_id'])){?>
        <input type="hidden" name="faq_category_id" value="<?=$data['faq_category_id']?>">

    <?php }?>
    <input type="hidden" name="write_type" value="<?=$data['write_type']?>">
    <input type="submit"  class="btn btn-action" value="<?=$data['submit_txt']?>">
</form>