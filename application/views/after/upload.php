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

<form method="post" action="<?=$action_url?>" id="after_form" enctype="multipart/form-data">
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
        참여 프로그램

        <input type="text" name="program" value="<?=$result['program']?>"  class="form-control">
    </div>
    <div class="">
        내용
        <div id="editor">
            <?php if($write_type=='modify'){ echo $result['contents'];}?>
        </div>
        <div class="contents_end"></div>
    </div>

    <input type="hidden" id="input_mirror" name="contents"/>

    <div class="form_module" style="clear: both;">
        <h2 class="form_p_title ">썸네일</h2>
        <div class="prod_upload_guide"><i class="fas fa-info-circle"></i> 어쩌구 저쩌구</div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12">
                <?php if($write_type=='modify'){
                    if($result['thumb_url']!=null){?>
                        <img  class="upload_thumbs_sm" src="../../<?=$result['thumb_url']?>" alt="후기 섬네일">
                    <?php }else{ ?>
                        <img class="upload_thumbs_sm" src="/thumbs/after/basic_thumbs.jpg" alt="후기 섬네일">
                    <?php }
                }else{?>
                    <img class="upload_thumbs_sm" src="/thumbs/after/basic_thumbs.jpg" alt="후기 섬네일">
                <?php }?>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-12">

                <?php  if(isset($error)){ echo $error; }?>
                <input type="file" name="thumbs" class="form-control"/>

                <?php if($write_type=='modify'){
                    if($result['thumb_url']!=null){?>

                        <div class="prod_upload_guide"><i class="fas fa-info-circle"></i> 지정된 섬네일이 있습니다. 다시 올리고 싶으시면 파일을 다시 선택해주세요.</div>
                    <?php }else{ ?>

                        <div class="prod_upload_guide"><i class="fas fa-exclamation-circle"></i> 섬네일을 업로드 하지 않았습니다. 섬네일을 올려주세요.</div>
                    <?php }
                }?>
            </div>
        </div>

    </div>
    <div class="">
        공개여부
        <input type="checkbox" name="status" checked="<?=$checked?>"> 공개여부 (체크하시면 목록에 공개됩니다.)
    </div>

    <?php if($write_type=='modify'){ ?>
        <input type="hidden" name="after_id" value="<?=$result['after_id']?>">
    <?php }?>

        <input type="hidden" name="team_id" id="team_id" value="<?=$result['team_id']?>">
    <input type="button" class="btn btn-action upload_ok" value="<?=$btn_text?>" onclick="submit_after();">
</form>

