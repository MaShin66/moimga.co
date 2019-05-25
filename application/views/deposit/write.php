<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-10
 * Time: 오후 3:38
 */?>

<h1>입금 정보 입력</h1>
<?php echo form_open();?>
지원서 이름 <?=$app_info['title']?>
<input type="hidden" name="form_id" value="<?=$form_info['form_id']?>">
<input type="hidden" name="application_id" value="<?=$app_info['application_id']?>">

입금 은행 <input type="text" class="form-control" name="bank">

입금 금액 <input type="text" class="form-control" name="money" value="<?=$app_info['money']?>">
입금 날짜 <input type="text" class="form-control" name="deposit_date">

입금 시간  (24시간제)

<select name="time">
    <?php for($i=0; $i<=23; $i++){?>
        <option value="<?=$i?>"><?=$i?>시</option>
    <?php }?>
</select>

<?php echo form_submit('submit', '입력', array('class'=>'btn btn-full btn-action')); ?>
<?php echo form_close()?>
