<?php
$write_type = $this->input->get('type');
$action_url = '/after/upload';
$btn_text = '등록하기';
$checked = null;
if(!is_null($write_type)){
    $btn_text = '수정하기';
    $action_url = '/after/upload/'.$result['after_id'].'?type=modify';
}
if($result['status']=='on'){
    $checked = 'checked';
}?>
<h1>후기 <?=$btn_text?></h1>

<form method="post" action="<?=$action_url?>" id="after_form">
    <div class="">
        제목

        <input type="text" name="title" value="<?=$result['title']?>" class="form-control">
    </div>
    <div class="">
팀 검색

        <div class="input-group">
            <input type="text" name="team_title" id="team_title" value="<?=$result['team_name']?>" class="form-control">
            <div class="input-group-append">
                <input type="button" class="btn btn-outline-secondary" onclick="search_team()" value="검색하기">
            </div>
        </div>
        <?php if($write_type=='modify'){?>
            <?=$result['team_name']?>의 후기로 입력합니다. 변경하고 싶으시다면 다시 검색해주세요.
        <?php }?>
        <div class="">
            팀 검색 결과
        </div>
        <div class="" id="search_list">
            여기에 팀 검색 결과가 나옵니다.

        </div>
    </div>

    <div class="">
        내용
        <div id="editor">
            <?php if($write_type=='modify'){ echo $result['contents'];}?>
        </div>
        <div class="contents_end"></div>
    </div>

    <input type="hidden" id="input_mirror" name="contents"/>

    <div class="">
        공개여부
        <input type="checkbox" name="status" checked="<?=$checked?>"> 공개여부 (체크하시면 목록에 공개됩니다.)
    </div>

    <?php if($write_type=='modify'){ ?>
        <input type="hidden" name="after_id" value="<?=$result['after_id']?>">
    <?php }?>

        <input type="hidden" name="team_id" id="team_id" value="<?=$result['team_id']?>">
    <input type="button" class="btn btn-primary upload_ok" value="<?=$btn_text?>" onclick="submit_after();">
</form>

