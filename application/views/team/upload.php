<?php

$write_type = $this->input->get('type');
$action_url = '/team/upload';
$btn_text = '등록하기';
$checked = null;
if(!is_null($write_type)){
    $team_id = $this->uri->segment(3);
    $btn_text = '수정하기';
    $action_url = '/team/upload/'.$team_id.'?type=modify';
}
if($result['status']=='on'){
    $checked = 'checked';
}

/* 팀 등록 */?>


<h1>팀 등록</h1>

<form method="post" name="team_form" action="<?=$action_url?>" id="team_form" enctype="multipart/form-data">
    <div class="">
        팀 소개 제목

        <input type="text" name="title" value="<?=$result['title']?>" class="form-control" id="team_title">
    </div>
    <div class="">
        팀 이름

        <input type="text" name="name" value="<?=$result['name']?>" class="form-control" id="team_name">
    </div>
    <div class="">
        팀 URL

        <input type="text" name="url" value="<?=$result['url']?>" class="form-control" id="team_url">
        <div id="url_dup_error"></div>
    </div>

    <div class="">
        외부링크

        <input type="text" name="external_link" value="<?=$result['external_link']?>" class="form-control" id="team_external_link">
        <span class="editor_desc">팀을 설명하는 웹페이지가 있다면 적어주세요.</>
    </div>

    <div class="">
        내용
        <div id="editor">
            <?php if($write_type=='modify'){ echo $result['contents'];}?>
        </div>
        <div class="contents_end"></div>
    </div>

    <div class="form_module" style="clear: both;">
        <h2 class="form_p_title ">썸네일</h2>
        <div class="prod_upload_guide"><i class="fas fa-info-circle"></i> 어쩌구 저쩌구</div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12">
                <?php if($write_type=='modify'){
                    if($result['thumb_url']!=null){?>
                        <img  class="upload_thumbs_sm" src="../../<?=$result['thumb_url']?>" alt="팀 섬네일">
                    <?php }else{ ?>
                        <img class="upload_thumbs_sm" src="/www/img/basic_thumbs.jpg" alt="팀 섬네일">
                    <?php }
                }else{?>
                    <img class="upload_thumbs_sm" src="/www/img/basic_thumbs.jpg" alt="팀 섬네일">
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
    <input type="hidden" id="input_mirror" name="contents"/>

<?php if(!is_null($write_type)){?>
    <input type="hidden" name="team_id" value="<?=$result['team_id']?>">
    <?php }?>
    <input type="button" class="upload_ok btn btn-action" value="<?=$btn_text?>" onclick="submit_team();">
</form>



<div class="modal fade" id="GuideModal" tabindex="-1" role="dialog" aria-labelledby="GuideModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="">이미지 업로드 방법</div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <h5 class="">
                    1. 직접 올리기
                </h5>
                <p class="guide_modal_p">
                    툴바 오른쪽 끝에 있는 '이미지' 버튼을 눌러서 이미지 주소를 넣어주세요.
                </p>
                <p class="guide_modal_p">
                    계정 받기: 이미지의 계정을 받는 방법 및 html 이미지 태그는 검색하시면 도움을 받으실 수 있습니다. 외부 계정은 구글 블로거(blogger)혹은 imgur.com에서 계정을 받아주세요. imgur는 로딩 속도가 느릴 수 있습니다.
                    추천사이트는 <a href="https://www.blogger.com" target="_blank" rel="noopener">https://www.blogger.com</a>입니다.</p>

                <p class="guide_modal_p">
                    자주 묻는 질문에도 자세한 가이드가 있습니다. <a href="/info/faq/manage/view/7" target="_blank" rel="noopener">자주 묻는 질문 - 상품 관리 - 등록 및 삭제 -'6. 이미지 업로드 하는 방법을 자세히 알려주세요.' (새 창)</a> 항목을 참고해주세요.
                </p>
                <h5>2. 관리자에게 이미지 보내기</h5>

                <p style="margin-bottom: 10px;">관리자에게 이미지를 보내주시면 관리자가 이미지를 첨부해드립니다. 시간이 소요되오니 급하신 분들께서는 1번 방법을 이용해주세요.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-action " data-dismiss="modal">확인</button>
            </div>
        </div>
    </div>
</div>
