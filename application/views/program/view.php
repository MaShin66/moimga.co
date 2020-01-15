<?php
$qna_count = count($qna_info);
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-07
 * Time: 오후 5:11
 */?>
<!--내가 멤버면 수정 가능-->
<?php if($as_member){?>

    <div class="cv_manage">
        <a href="/manage/program/detail/<?=$program_info['program_id']?>" class="btn btn-outline-action">관리</a>
        <a href="/@<?=$team_info['url']?>/program/upload/<?=$program_info['program_id']?>?type=modify" class="btn btn-outline-secondary">수정</a>
    </div>
<?php }?>

<div class="list_top">

    <h1 class="top_title"><?=$program_info['title']?></h1>
    <h2 class="top_desc"><?=$team_info['name']?></h2>
</div>

<input type="hidden" value="<?=$program_info['program_id']?>" id="program_id">


<div class="team_top">
    <div class="tt_item">
        <span class="tt_title">하트로 응원</span>

        <span class="tt_cont heart_btn"  onclick="heart('program',<?=$program_info['program_id']?>)">
            <?php if(!is_null($check_heart)){?>
                <i class="fas fa-heart heart_active"></i>
            <?php }else{?>
                <i class="far fa-heart"></i>
            <?php }?>
        </span>
        <span class="team_heart_count" id="heart_cnt"><?=$program_info['heart_count']?></span>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="team_img">
            <img src="<?=$team_info['thumb_url']?>">
        </div>
    </div>

    <div class="col-lg-8 col-md-6 col-sm-12">
        <div class="team_box">
            <ol class="program_info">
                <li>
                    <span class="program_info_title">팀</span>
                    <a class="program_info_cont info_link" href="/program/lists/1/q?team_id=<?=$team_info['team_id']?>"><?=$team_info['name']?></a>

                </li>
                <li>
                    <span class="program_info_title">일시</span>
                    <span class="program_info_cont">
                        <ol>
                            <?php foreach ($date_info as $date_key => $date_value){ ?>
                                <li><?=$date_value['date']?> (<?=$date_value['weekday']?>) <?=$date_value['time']?>:00</li>
                            <?php }?>
                        </ol>
                    </span>
                </li>
                <li>
                    <span class="program_info_title">인원</span>
                    <span class="program_info_cont"><?=$program_info['participant']?>명</span>
                </li>
                <li>
                    <span class="program_info_title">지역</span>
                    <span class="program_info_cont"><?=$program_info['district']?></span>
                </li>
                <li>
                    <span class="program_info_title">장소</span>
                    <span class="program_info_cont"><?=$program_info['venue']?> (<?=$program_info['address']?>)</span>
                </li>
                <li>
                    <span class="program_info_title">참가비</span>
                    <span class="program_info_cont"><?=number_format($program_info['price'])?>원</span>
                </li>
            </ol>

        </div>


        <?php if($program_info['external_link']!=null || $program_info['external_link']!=''){?>

            <div class="">
                <a href="<?=$program_info['external_link']?>" target="_blank" rel="noopener" class="btn btn-full btn-action">신청하기</a>
            </div>

        <?php } ?>
    </div>
</div>


<div class="row">
    <div class="col-lg-8 col-md-6 col-sm-12">
        <div class="team_box">
            <div class="cv_line"></div>
            <div class="cv_contents">
                <?=$program_info['contents']?>
            </div>

        </div>
        <div class="team_box">
            <h3 class="sub_title">질문과 답변</h3>
            <?php  if($qna_count==0){?>
                <div class="team_box_empty">
                    아직 콘텐츠가 없습니다.
                </div>
            <?php }else{?>

            <div class="team_box">

                <ol class="team_box_list">
                    <?php foreach ($qna_info as $akey => $avalue){ ?>
                        <li class="team_box_item">
                            <span class="tbi_question">Q. <?=$avalue['question']?></span>
                            <span class="tbi_answer">A. <?=$avalue['answer']?></span>
                        </li>
                    <?php }?>
                </ol>
            </div>

            <?php }?>
        </div>

        <div class="team_box">
            <h3 class="sub_title">이런 분들이 오셨으면 좋겠어요</h3>
            <div class="team_box">

                <ol>
                    <?php foreach ($qualify_info as $qkey => $qvalue){ ?>
                        <li><?=$qvalue['contents']?></li>
                    <?php }?>
                </ol>
            </div>

        </div>
        <div class="map_wrap">
            <h3 class="sub_title">지도</h3>
            <div class="team_box">
                <div id="map" style="width:100%;height:400px;" data-role="content" ></div>
            </div>


        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="team_box team_box_border">

            <h3 class="sub_title">
                <a href="/after/lists/1/q?team_id=<?=$team_info['team_id']?>">후기</a>
            </h3>
            <?php

            $after_count = count($after_list);
            if($after_count==0){?>
                <div class="team_box_empty">
                    아직 후기가 없습니다.
                </div>

            <?php }else{?>
                <div class="team_box_sub">
                    <div class="team_count">
                        총 <?=$after_count?>개의 콘텐츠
                    </div>
                    <div class="team_view_more">
                        <a href="/after/lists/1/q?team_id=<?=$team_info['team_id']?>">모두 보기 <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>

                <ol class="team_box_list">
                    <?php  $this->load->view('/after/list_thumbs', array('after_list'=>$after_list)); ?>
                </ol>
            <?php }?>
        </div>
    </div>
</div>
<div class="hidden-md-up program_bottom">
    <a href="<?=$program_info['external_link']?>" target="_blank" rel="noopener" class="btn btn-full btn-action">신청하기</a>

    <a href="/program/lists/1/q?team_id=<?=$team_info['team_id']?>" class="btn btn-full btn-outline-action">이 팀의 다른 프로그램</a>

</div>