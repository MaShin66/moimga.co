<?php

?>

<h2>store 카테고리 <?=$data['submit_txt']?></h2>

<form action="/admin/store_category/upload/" method="post">
    <div class="form-group row">
        <label for="title" class="col-sm-2 col-form-label">이름</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="title" id="title" value="<?=$data['title']?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="title" class="col-sm-2 col-form-label">설명</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="desc" id="desc" value="<?=$data['desc']?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="order" class="col-sm-2 col-form-label">순서</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" name="order" id="order" value="<?=$data['order']?>">
        </div>
    </div>

    <?php if(!is_null($data['store_category_id'])){?>
        <input type="hidden" name="store_category_id" value="<?=$data['store_category_id']?>">

    <?php }?>
    <input type="hidden" name="write_type" value="<?=$data['write_type']?>">
    <input type="submit"  class="btn btn-action" value="<?=$data['submit_txt']?>">
</form>