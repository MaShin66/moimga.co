<h2>FAQ <?=$data['submit_txt']?></h2>

<form action="/admin/faq/upload/" method="post">
    <div class="form-group row">
        <label for="category" class="col-sm-2 col-form-label">카테고리</label>
        <div class="col-sm-10">
            <select name="faq_category_id">
                <?php foreach ($cate_list as $key=>$item){?>
                    <option value="<?=$item['faq_category_id']?>"
                    <?php if($data['faq_category_id']==$item['faq_category_id']){ echo 'selected';}?>>
                        <?=$item['name']?>
                    </option>
                <?php }?>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label for="title" class="col-sm-2 col-form-label">제목</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="title" id="title" value="<?=$data['title']?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="contents" class="col-sm-2 col-form-label">내용</label>
        <div class="col-sm-10">
            <textarea name="contents" id="contents" class="form-control" ><?=str_replace("<br />", "\r\n", $data['contents']);?></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="order" class="col-sm-2 col-form-label">순서</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="order" id="order" value="<?=$data['order']?>">
        </div>
    </div>

    <?php if(!is_null($data['faq_id'])){?>
        <input type="hidden" name="faq_id" value="<?=$data['faq_id']?>">

    <?php }?>
    <input type="hidden" name="write_type" value="<?=$data['write_type']?>">
    <input type="submit"  class="btn btn-primary" value="<?=$data['submit_txt']?>">
</form>