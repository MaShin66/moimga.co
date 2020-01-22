<?php

$blog_count = count($team_blog);
$program_count = count($programs);

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-07
 * Time: 오후 5:11
 */?>


<?php if($as_member){?>

    <div class="cv_manage">
        <a href="/manage/team/detail/<?=$team_info['team_id']?>" class="btn btn-outline-action">관리</a>
        <a href="/team/upload/<?=$team_info['team_id']?>?type=modify" class="btn btn-outline-secondary">수정</a>
    </div>
<?php }?>

<div class="list_top">

    <h1 class="top_title"><?=$team_info['name']?></h1>
    <h2 class="top_desc"><?=$team_info['title']?></h2>
</div>
<div class="team_top">
    <div class="tt_item">
        <span class="tt_title">구독하기</span>
        <span class="tt_cont subscribe_btn" onclick="set_subscribe(<?=$team_info['team_id']?>)">
            <?php if(!is_null($check_bookmark)){?>
                <i class="fas fa-bookmark subscribe_active"></i>
            <?php }else{?>
                <i class="far fa-bookmark"></i>
            <?php }?>

        </span>
    </div>

    <div class="tt_item">
        <span class="tt_title">하트로 응원</span>

        <span class="tt_cont heart_btn"  onclick="heart('team',<?=$team_info['team_id']?>)">
            <?php if(!is_null($check_heart)){?>
                <i class="fas fa-heart heart_active"></i>
            <?php }else{?>
                <i class="far fa-heart"></i>
            <?php }?>
        </span>
        <span class="team_heart_count" id="heart_cnt"><?=$team_info['heart_count']?></span>
    </div>

    <?php if($team_info['external_link']!=null || $team_info['external_link']!=''){?>
        <div class="tt_item">
            <span class="tt_title">공식 채널</span>
            <a href="<?=$team_info['external_link']?>" target="_blank" rel="noopener" class="tt_cont"><i class="fas fa-link"></i></a>
        </div>
    <?php } ?>

</div>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="team_img">
            <img src="<?=$team_info['thumb_url']?>">
        </div>
    </div>
    <div class="col-lg-8 col-md-6 col-sm-12">
        <div class="team_box">
            <h3 class="sub_title">
                <a href="/program/lists/1/q?team_id=<?=$team_info['team_id']?>">프로그램</a>
            </h3>
            <?php
            if($program_count==0){?>
                <div class="team_box_empty">
                    아직 프로그램이 없습니다.
                </div>
            <?php }else{?>
                <div class="team_box_sub">
                    <div class="team_count">
                        총 <?=$program_count?>개의 모임
                    </div>
                    <div class="team_view_more">
                        <a href="/program/lists/1/q?team_id=<?=$team_info['team_id']?>">모두 보기 <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>
                <div class="team_box">
                    <div class="row">
                        <?php foreach ($programs as $key=>$item){?>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <a class="team_program_item" href="/<?=$at_url?>/program/<?=$item['program_id']?>">
                                    <span class="tpi_title"><?=$item['title']?></span>
                                    <span class="tpi_info">
                                    <span class="tpi_cont">
                                        <span class="tpi_desc"><?=$item['contents']?></span>
                                        <span class="il_info">
                                            <span class="ili_box">

                                            </span>
                                            <span class="ili_date">
                                                <?=$item['event_date']?> <?=$item['time']?>:00  (<?=$item['weekday']?>)
                                            </span>
                                            <span class="ili_price">
                                                <?=number_format($item['price'])?>won
                                            </span>
                                        </span>
                                        <span class="tpi_venue">@<?=$item['venue']?></span>
                                    </span>
                                    <span class="tpi_img">
                                        <img src="<?=$item['thumb_url']?>">
                                    </span>
                                </span>
                                </a>
                            </div>
                        <?php }?>
                    </div>
                </div>

            <?php }?>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-lg-8 col-md-6 col-sm-12">

        <div class="cv_line"></div>
        <div class="cv_contents">
            <?=$team_info['contents']?>
        </div>
        <div class="team_box">
            <h3 class="sub_title">
                <a href="/<?=$at_url?>/blog/lists"><?=$team_info['name']?>가 만든 콘텐츠</a>
            </h3>
            <?php  if($blog_count==0){?>
                <div class="team_box_empty">
                    아직 콘텐츠가 없습니다.
                </div>
            <?php }else{?>
                <div class="team_box_sub">
                    <div class="team_count">
                        총 <?=$blog_count?>개의 콘텐츠
                    </div>
                    <div class="team_view_more">
                        <a href="/<?=$at_url?>/blog/lists">모두 보기 <i class="fas fa-chevron-right"></i></a>
                    </div>
                </div>

                <ol class="list_wrap">
                    <?php $this->load->view('team/blog/thumbs', array('post'=>$team_blog)); ?>
                </ol>

            <?php }?>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="team_box">

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

                <ol class="list_wrap">
                    <?php  $this->load->view('/after/list_thumbs', array('after_list'=>$after_list)); ?>
                </ol>
            <?php }?>
        </div>
    </div>

</div>