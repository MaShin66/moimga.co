<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-10
 * Time: 오후 3:38
 */?>

<h1>후기 등록하기</h1>
<?php echo form_open();?>
후기 제목 <input type="text" class="form-control" name="title" value="<?=$after_info['title']?>">
지원서 이름  <?=$app_info['title']?>
내용 <textarea name="contents" class="form-control"><?=$after_info['contents']?></textarea>
<input type="hidden" name="application_id" value="<?=$app_info['application_id']?>">

<?php echo form_submit('submit', '만들기', array('class'=>'btn btn-full btn-action')); ?>
<?php echo form_close()?>
