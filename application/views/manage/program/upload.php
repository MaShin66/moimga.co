<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-10
 * Time: 오후 3:38
 */?>

<h1>모임 만들기</h1>
<?php echo form_open();?>
모임 이름 <input type="text" class="form-control" name="title">
모임 url <input type="text" class="form-control" name="url_name">
카테고리
<select name="category_id">
    <?php foreach ($cate_list as $key=> $item) {?>

        <option value="<?=$item['category_id']?>"><?=$item['title']?></option>
    <?php }?>
</select>

설명 <textarea name="description"></textarea>
지역 <input type="text" class="form-control" name="district">

<?php echo form_submit('submit', '만들기', array('class'=>'btn btn-full btn-action')); ?>
<?php echo form_close()?>
