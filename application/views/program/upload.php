<?php

$write_type = $this->input->get('type');
$action_url = '/@'.$team_info['url'].'/program/upload';
$btn_text = '등록하기';
$checked = null;
if(!is_null($write_type)){
    $program_id = $this->uri->segment(4);
    $btn_text = '수정하기';
    $action_url = '/@'.$team_info['url'].'/program/upload/'.$program_id.'?type=modify';
}
if($result['status']=='on'){
    $checked = 'checked';
}

/* 프로그램 등록 */?>
<h1>프로그램 <?=$btn_text?></h1>

<h2><?=$team_info['title']?></h2>
<h3><?=$team_info['name']?></h3>
<form method="post" action="<?=$action_url?>" id="program_form" enctype="multipart/form-data">
    <div class="">
        프로그램 제목

        <input type="text" name="title" value="<?=$result['title']?>" class="form-control" id="program_title">
    </div>
    <div class="">
        신청링크

        <input type="text" name="external_link" value="<?=$result['external_link']?>" class="form-control" id="external_link">
        <span class="editor_desc">프로그램 참여 신청페이지가 있다면 적어주세요. 입력하지 않으면 출력되지 않습니다.</span>
    </div>
    <div class="">
        참가인원

        <input type="number" min="0" max="999" name="participant" value="<?=$result['participant']?>" class="form-control" id="participant">
    </div>
    <div class="">
       지역

        <input type="text" name="district" value="<?=$result['district']?>" class="form-control" id="district">
    </div>
    <div class="">
        장소 이름

        <input type="text" name="venue" value="<?=$result['venue']?>" class="form-control" id="venue">
    </div>
    <div class="">
        주소

        <input type="text" name="address" value="<?=$result['address']?>" class="form-control" id="address" onclick="open_postcode()" >
    </div>
    <div class="">
        가격

        <input type="number" min="0" max="9999999" name="price" value="<?=$result['price']?>" class="form-control" id="price">
    </div>
    <hr>
    <ul class="prod_upload_guide prod_upload_list">
        <li>시간은 24시간제입니다. 예를 들어, 1시는 오전 1시, 13시는 오후 1시입니다.</li>
        <li>행사 날짜에 따른 티켓 종류는 따로 지정할 수 없습니다. 만약 다른 티켓 종류를 제공하신다면, 모든 티켓 종류를 입력하시고 구매자들에게 이에 대해 안내해주세요.</li>
    </ul>
    <div class="event_date_wrap" style="clear: both;">

        <?php if($write_type=='modify'){
            foreach ($date_info as $key => $item){?>
                <div class="form-row event_date_add" id="event_date_<?=$key+1?>" data-event-date-id="<?=$item['pdate_id']?>">
                    <div class="form-group col-md-6">
                        <label for="input_event_date<?=$key+1?>">행사 날짜</label>
                        <input type="text" class="form-control event_date calendar calendar-input" name="event_date[<?=$item['pdate_id']?>]" id="input_event_date<?=$key+1?>" placeholder="행사 날짜를 선택해주세요." value="<?=$item['date']?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="input_event_time<?=$key+1?>">행사 시간</label>
                        <div class="">

                            <select name="event_time[<?=$item['pdate_id']?>]" class="form-control custom-select" id="input_event_time<?=$key+1?>" >
                                <?php  for($i=0; $i<24; $i++){ ?>

                                    <option value="<?=$i?>" <?php if($write_type=='modify') {if($item['time']==$i) echo 'selected';}?>><?=$i?></option>
                                <?php } ?>
                            </select>시
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-6 col-6">
                        <div class="btn btn-outline-action  btn-adjust" onclick="javascript:copy_event_date(<?=$key+1?>);">+ 복사</div>
                    </div>
                    <div class="col-md-1 col-sm-6 col-6">
                        <div class="btn btn-outline-red  btn-delete btn-adjust" onclick="javascript:delete_event_date(<?=$key+1?>);">- 삭제</div>
                    </div>
                </div>

            <?php }
        }else{   //새 글 ?>

            <div class="form-row event_date_add" id="event_date_1">
                <div class="form-group col-md-6">
                    <label for="input_event_date1">행사 날짜</label>
                    <input type="text" class="form-control event_date input_basic calendar calendar-input" name="input_event_date[]" id="input_event_date1" placeholder="행사 날짜를 선택해주세요.">
                </div>
                <div class="form-group col-md-4">
                    <label for="input_event_time1">행사 시간</label>
                    <div class="">
                        <select name="input_event_time[]" class="custom-select form-control"  id="input_event_time1">
                            <?php  for($i=0; $i<24; $i++){ ?>
                                <option value="<?=$i?>"><?=$i?></option>
                            <?php } ?>
                        </select>시
                    </div>

                </div>
                <div class="col-md-1 col-sm-6 col-6">
                    <div class="btn btn-outline-action  btn-adjust" onclick="javascript:copy_event_date(1);">+ 복사</div>
                </div>
                <div class="col-md-1 col-sm-6 col-6">
                    <div class="btn btn-outline-red  btn-delete  btn-adjust" onclick="javascript:delete_event_date(1);">- 삭제</div>
                </div>
            </div>
        <?php }?>
    </div>
    <?php if($write_type!='modify'||($write_type=='modify')){?>
        <div class="add_btn_wrap">
            <div class="btn  btn-outline-action" onclick="javascript:add_event_date();">+ 행사 날짜 추가</div>
        </div>
    <?php }?>
    <hr>

    <div class="">
        내용
        <div id="editor">
            <?php if($write_type=='modify'){ echo $result['contents'];}?>
        </div>
        <div class="contents_end"></div>
    </div>
    <hr>
    <div class="">
        이런분들이 오셨으면 좋겠어요
    </div>
    <div class="" id="qualify_wrap">

        <?php if($write_type=='modify') {
            foreach ($qualify_info as $qu_key => $qu_item) {
                ?>
                <div class="form-row qualify_add" id="qualify_<?=$qu_key+1?>" data-qualify-option-id="<?=$qu_item['qualify_id']?>"> <!--아무것도 하지 않았을 경우-->
                    <div class="form-group col-md-11">

                        <label for="input_qualify1">내용</label>
                        <input type="text" name="qualify[<?=$qu_item['qualify_id']?>]"  class="form-control" id="input_qualify<?=$qu_key+1?>" value="<?=$qu_item['contents']?>">
                        <div class="form_guide_gray">이런 분들이 오셨ㅇ면 좋겠는거..</div>
                    </div>

                    <div class="col-md-1">
                        <div class="btn btn-outline-danger  btn-delete" onclick="javascript:delete_qualify(<?=$qu_key+1?>);">- 삭제</div>
                    </div>

                </div>

            <?php }
        }else{ //새 글 ?>
            <div class="form-row qualify_add" id="qualify_1" data-qualify-option-id="1"> <!--아무것도 하지 않았을 경우-->
                <div class="form-group col-md-11">

                    <label for="input_qualify1">내용</label>
                    <input type="text" name="input_qualify[]"  class="form-control" id="input_qualify1">
                    <div class="form_guide_gray">이런 분들이 오셨ㅇ면 좋겠는거..</div>
                </div>

                <div class="col-md-1">
                    <div class="btn btn-outline-danger  btn-delete" onclick="javascript:delete_qualify(1);">- 삭제</div>
                </div>

            </div>
        <?php }?>
    </div>
    <!--여러개 있는 경우에는foreach-->
    <div class="add_btn_wrap">
        <div class="btn  btn-outline-action" onclick="javascript:add_qualify();">+ 추가</div>
    </div>

    <hr>

    <div class="">
        QNA
    </div>

    <div class="" id="question_wrap">


        <?php if($write_type=='modify') {
        foreach ($qna_info as $qna_key => $qna_item) {
        ?>
            <div class="form-row question_add" id="question_<?=$qna_key+1?>" data-question-option-id="<?=$qna_item['pqna_id']?>">
                <div class="form-group col-md-11">

                    <label for="input_question1">질문</label>
                    <input type="text" name="question[<?=$qna_item['pqna_id']?>]"  class="form-control" id="input_question<?=$qna_key+1?>" value="<?=$qna_item['question']?>">
                    <div class="form_guide_gray">질문 내용을 입력해주세요</div>
                </div>

                <div class="col-md-1">
                    <div class="btn btn-outline-danger  btn-delete" onclick="javascript:delete_question(<?=$qna_key+1?>);">- 삭제</div>
                </div>
                <div class="form-group col-md-12">

                    <label for="input_question1">답변</label>
                    <input type="text" name="answer[<?=$qna_item['pqna_id']?>]"  class="form-control" id="input_answer<?=$qna_key+1?>" value="<?=$qna_item['answer']?>">
                    <div class="form_guide_gray">질문에 대한 답변을 입력해주세요</div>
                </div>

            </div>
        <?php }
        }else{ //새 글 ?>
            <div class="form-row question_add" id="question_1"> <!--아무것도 하지 않았을 경우-->
                <div class="form-group col-md-11">

                    <label for="input_question1">질문</label>
                    <input type="text" name="input_question[]"  class="form-control" id="input_question1">
                    <div class="form_guide_gray">질문 내용을 입력해주세요</div>
                </div>

                <div class="col-md-1">
                    <div class="btn btn-outline-danger  btn-delete" onclick="javascript:delete_question(1);">- 삭제</div>
                </div>
                <div class="form-group col-md-12">

                    <label for="input_question1">답변</label>
                    <input type="text" name="input_answer[]"  class="form-control" id="input_answer1">
                    <div class="form_guide_gray">질문에 대한 답변을 입력해주세요</div>
                </div>

            </div>
        <?php }?>

    </div>
    <!--여러개 있는 경우에는foreach-->
    <div class="add_btn_wrap">
        <div class="btn  btn-outline-action" onclick="javascript:add_question();">+ 추가</div>
    </div>
    <hr>
    <div class="form_module" style="clear: both;">
        <h2 class="form_p_title ">썸네일</h2>
        <div class="prod_upload_guide"><i class="fas fa-info-circle"></i> 어쩌구 저쩌구</div>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12">
                <?php if($write_type=='modify'){
                    if($result['thumb_url']!=null){?>
                        <img  class="upload_thumbs_sm" src="../../<?=$result['thumb_url']?>" alt="프로그램 섬네일">
                    <?php }else{ ?>
                        <img class="upload_thumbs_sm" src="/www/img/basic_thumbs.jpg" alt="프로그램 섬네일">
                    <?php }
                }else{?>
                    <img class="upload_thumbs_sm" src="/www/img/basic_thumbs.jpg" alt="프로그램 섬네일">
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
    <input type="hidden" name="team_id" value="<?=$result['team_id']?>">
    <input type="hidden" name="write_type" id="write_type" value="<?=$write_type?>">
    <input type="hidden" name="latitude" value="<?=$result['latitude']?>" id="latitude">
    <input type="hidden" name="longitude" value="<?=$result['longitude']?>" id="longitude">

<?php if(!is_null($write_type)){?>
    <input type="hidden" name="program_id" id="program_id" value="<?=$result['program_id']?>">
    <?php }?>
    <input type="button" class="btn btn-action" value="<?=$btn_text?>" onclick="submit_program();">
</form>


