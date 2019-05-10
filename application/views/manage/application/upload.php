<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-10
 * Time: 오후 3:38
 */?>

<h1>지원서 만들기</h1>
<?php echo form_open();?>
지원서 이름 <input type="text" class="form-control" name="title">
지원서 소제목 <input type="text" class="form-control" name="subtitle">
정원 <input type="number" class="form-control" name="capacity" placeholder="정원을 숫자로만 입력해주세요.">
<?php if($moim_id){?>
모임 <?=$moim_info['title']?>
    <input type="hidden" class="form-control" name="moim_id" value="<?=$moim_info['moim_id']?>">
<?php }else{ //모임이 지정되어 있지 않다면 select?>

    모임 선택
    <select name="moim_id">
        <?php foreach ($moim_list as $key=> $item) {?>

            <option value="<?=$item['moim_id']?>"><?=$item['title']?></option>
        <?php }?>
    </select>

<?php }?>

카테고리
<select name="category_id">
    <?php foreach ($cate_list as $key=> $item) {?>

        <option value="<?=$item['category_id']?>"><?=$item['title']?></option>
    <?php }?>
</select>
<div class="upload_input">
    <label for="comment_name_input" class="comment_name">행사 시작 날짜</label>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-6">
            <input type="text" class="form-control event_date calendar" name="event_date" value="">
        </div>
        <div class="col-lg-2 col-md-8 col-sm-6 col-6 padding_zero">
            <select name="event_start_hour" class="custom-select" >
                <?php  for($i=0; $i<24; $i++){ ?>
                    <option value="<?=$i?>"><?=$i?></option>
                <?php } ?>
            </select>시
        </div>
        <div class="col-lg-2 col-md-8 col-sm-6 col-6 padding_zero">
            <select name="event_start_min" class="custom-select" >
                <?php  for($j=0; $j<60; $j++){ ?>
                    <option value="<?=$j?>"><?=$j?></option>
                <?php } ?>
            </select>분
        </div>
        부터
        <div class="col-lg-2 col-md-8 col-sm-6 col-6 padding_zero">
            <select name="event_end_hour" class="custom-select" >
                <?php  for($i=0; $i<24; $i++){ ?>
                    <option value="<?=$i?>"><?=$i?></option>
                <?php } ?>
            </select>시
        </div>
        <div class="col-lg-2 col-md-8 col-sm-6 col-6 padding_zero">
            <select name="event_end_min" class="custom-select" >
                <?php  for($j=0; $j<60; $j++){ ?>
                    <option value="<?=$j?>"><?=$j?></option>
                <?php } ?>
            </select>분
        </div>

    </div>
</div>

<div class="upload_input">
    <label for="comment_name_input" class="comment_name">제출 시작 시간</label>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-6">
            <input type="text" class="form-control open_date calendar" name="open_date" value="">
        </div>
        <div class="col-lg-2 col-md-8 col-sm-6 col-6 padding_zero">
            <select name="open_hour" class="custom-select" >
                <?php  for($i=0; $i<24; $i++){ ?>
                    <option value="<?=$i?>"><?=$i?></option>
                <?php } ?>
            </select>시
        </div>
        <div class="col-lg-2 col-md-8 col-sm-6 col-6 padding_zero">
            <select name="open_min" class="custom-select" >
                <?php  for($j=0; $j<60; $j++){ ?>
                    <option value="<?=$j?>"><?=$j?></option>
                <?php } ?>
            </select>분
        </div>
    </div>
</div>


<div class="upload_input">
    <label for="comment_name_input" class="comment_name">제출 종료 시간</label>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6 col-6">
            <input type="text" class="form-control close_date calendar" name="close_date" value="">
        </div>
        <div class="col-lg-2 col-md-8 col-sm-6 col-6 padding_zero">
            <select name="close_hour" class="custom-select" >
                <?php  for($i=0; $i<24; $i++){ ?>
                    <option value="<?=$i?>"><?=$i?></option>
                <?php } ?>
            </select>시
        </div>
        <div class="col-lg-2 col-md-8 col-sm-6 col-6 padding_zero">
            <select name="close_min" class="custom-select" >
                <?php  for($j=0; $j<60; $j++){ ?>
                    <option value="<?=$j?>"><?=$j?></option>
                <?php } ?>
            </select>분
        </div>
    </div>
</div>

설명 <textarea name="description"  class="form-control"></textarea>
내용 <textarea name="contents"  class="form-control"></textarea>

<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="postcode">우편번호</label>
    <div class="col-sm-10">
        <div class="input-group">
            <input type="text" class="form-control" size="13" id="postcode" name="zipcode" value=""  required placeholder="우편번호 찾기 버튼으로 주소를 입력해주세요.">
            <div class="input-group-append">
                <input class="btn btn-outline-action" type="button" onclick="open_postcode()" value="주소 찾기" required>
            </div>
        </div>
    </div>
</div>


<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="address">주소 <span class="form_star">*</span></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="address" name="address" value=""  required placeholder="">
    </div>
</div>


<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="address2">상세 주소 <span class="form_star">*</span></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="address2" name="address2" value=""  required placeholder="참가자들이 쉽게 찾아올 수 있도록 주소를 입력해주세요.">
    </div>
</div>


<div class="upload_input form-row">
    <div class="form-group col-md-4">
        <label for="input_bank">은행</label>
        <input type="text" class="form-control" name="bank" id="input_bank" placeholder="예) 국민" >
    </div>
    <div class="form-group col-md-6">
        <label for="input_bank_account">계좌번호</label>
        <input type="text" class="form-control" name="account"  id="input_bank_account" >
        <small class="text-muted">숫자만 입력해주세요. (- 혹은 띄어쓰기 제외)</small>
    </div>
    <div class="form-group col-md-2">
        <label for="input_account_name">예금주</label>
        <input type="text" class="form-control" name="holder" id="input_account_name" placeholder="자음만 입력하셔도 됩니다. 예) ㅇㅅㅇ" >
    </div>
</div>

<div class="prod_upload_guide">
    <ul class="prod_upload_list">
        <li>모임 관리 페이지에서 '종료'를 누르시면 계좌번호가 더이상 노출되지 않습니다.</li>
    </ul>
</div>
//지불 방식, 환불 방식은 나중에..

<?php echo form_submit('submit', '지원서 만들기', array('class'=>'btn btn-full btn-action')); ?>
<?php echo form_close()?>
