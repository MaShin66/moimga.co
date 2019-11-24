<?php
$write_type = $this->input->get('type');
$action_url = '/after/write';
$btn_text = '등록하기';
$checked = null;
if(!is_null($write_type)){
    $btn_text = '수정하기';
    $action_url = '/after/write/'.$after_id.'?type=modify';
}
if($result['status']=='on'){
    $checked = 'checked';
}?>
<h1>후기 쓰기</h1>

<form method="post" action="<?=$action_url?>" id="after_form">
    <div class="">
        제목

        <input type="text" name="title" value="<?=$result['title']?>">
    </div>
    <div class="">
프로그램 검색
        <input type="text" name="program_title" id="program_title" value="<?=$result['program_title']?>">

        <input type="hidden" name="program_id" id="program_id" value="<?=$result['program_id']?>"><br>
        <div class="btn btn-outline-secondary" onclick="search_program()">검색하기</div>
        <div class="" id="search_list">


        </div>
    </div>
    <div class="">
        내용

        <input type="text" name="contents" value="<?=$result['contents']?>">
    </div>

    <div class="">
        공개여부
        <input type="checkbox" name="status" checked="<?=$checked?>"> 공개여부 (체크하시면 목록에 공개됩니다.)
    </div>


    <input type="hidden" name="after_id" value="after_id">
    <input type="button" class="btn btn-primary" value="<?=$btn_text?>" onclick="submit_after();">
</form>

