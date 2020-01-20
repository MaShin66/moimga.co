<?php
/** 실명, 닉네임, 이메일 (변경), sns_type
 * 본인인증
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-07
 * Time: 오후 5:23
 */?>
<div class="row justify-content-md-center">
    <div class="col-lg-6 col-md-6 col-sm-12">

        <div class="list_top">

            <h1 class="top_title">마이페이지</h1>
            <h2 class="top_desc">내 계정의 기본 정보</h2>
        </div>
        <ul class="mypage_menu">
            <li><a href="/mypage/info" class="btn btn-action btn-sm">내 정보</a> </li>
            <li><a href="/mypage/after" class="btn btn-action btn-sm">내 후기</a> </li>
            <li><a href="/mypage/subscribe" class="btn btn-action btn-sm">구독</a> </li>
        </ul>

        <div class="mypage_wrap">
            <div class="row mypage_row">
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <h2 class="mp-p-title">닉네임</h2>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <span class="mp-p-val"><?=$my_info['nickname']?></span>
                    <div class="mp-setting-icon" onclick="$('.change_nickname').toggle();"><i class="fas fa-cog"></i></div>
                    <div class="change_nickname">
                        <form class="" method="post" action="/mypage/change_name">
                            <input type="hidden" name="type" value="nickname">
                            <input type="hidden" name="user_id" value="<?=$my_info['id']?>">
                            <div class="input-group">
                                <input type="text" name="name" class="form-control open_input" placeholder="닉네임을 입력해주세요.">
                                <div class="input-group-append-border">
                                    <input type="submit" value="<?=$this->lang->line('save')?>" class="btn btn-action"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row mypage_row">
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <h2 class="mp-p-title">실명</h2>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <span class=""><?=$my_info['realname']?></span>
                    <div class="mp-setting-icon" onclick="$('.change_realname').toggle();"><i class="fas fa-cog"></i></div>
                    <div class="change_realname">
                        <form class="" method="post" action="/mypage/change_name">
                            <input type="hidden" name="type" value="realname">
                            <input type="hidden" name="user_id" value="<?=$my_info['id']?>">
                            <div class="input-group">
                                <input type="text" name="name" class="form-control open_input" placeholder="실명을 입력해주세요.">
                                <div class="input-group-append-border">
                                    <input type="submit" value="<?=$this->lang->line('save')?>" class="btn btn-action"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="row mypage_row">
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <h2 class="mp-p-title">이메일</h2>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-12">
                <span style="margin-right: 10px"><?=$my_info['email']?>
                    <?php if($my_info['sns_type']!=null){?>
                        <span class="badge badge-<?=$my_info['sns_type']?>">
                <?=$my_info['sns_type']?>
                </span>
                    <?php }?></span>
                    <a href="/auth/change_email" class="mp-setting-link">이메일 변경</a>
                </div>

            </div>
            <?php if($my_info['sns_type']==null){?>

                <div class="row mypage_row">
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <h2 class="mp-p-title">비밀번호</h2>
                    </div>
                    <div class="col-lg-9 col-md-8 col-sm-12"><a href="/auth/change_password" class="mp-setting-link">비밀번호 변경</a> </div>
                </div>
            <?php }?>

            <div class="row mypage_row">
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <h2 class="mp-p-title">본인인증</h2>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <?php if($my_info['verify']==1){ ?>
                        <span class="">인증 됨</span>
                    <?php }else{ ?>
                        <span style="margin-right: 10px">인증 안 됨</span>
                        <span class="btn btn-outline-action btn-sm"  onclick="$('.input_verify').toggle(); $(this).hide();">본인인증 하기</span>
                        <div class="input_verify">
                            <form class="verify_form" method="post" action="/mypage/verify/ready">
                                <div class="input-group">
                                    <input type="tel" id="verify_phone" name="phone" class="form-control open_input" placeholder="여기에 휴대폰 번호를 숫자만 입력해주세요.">
                                    <div class="input-group-append-border">
                                        <input type="submit" value="<?=$this->lang->line('go_adult_certi')?>" class="btn btn-action " onclick="phone_check();"/>
                                    </div>
                                </div>

                                <span class="mp-sm-desc">숫자만 입력해주세요.</span>
                            </form>
                        </div>

                    <?php }?>
                </div>
            </div>
            <?php if($my_info['verify']==1){ ?>

            <div class="row mypage_row">
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <h2 class="mp-p-title">실명</h2>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <span class=""><?=$my_info['realname']?></span>
                </div>
            </div>

            <div class="row mypage_row">
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <h2 class="mp-p-title">연락처</h2>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <span class="">수집 됨</span>
                </div>
            </div>

            <div class="row mypage_row">
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <h2 class="mp-p-title">성인</h2>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <span class=""><?php
                        if($my_info['adult']==1){
                            echo  '성인';
                        }else{
                            echo   '미성년자';
                        }?></span>
                </div>
            </div>
            <?php }else{ ?>

            <?php }?>
            <span class="mp-sm-desc">본인인증을 하는 것은 모임가.co의 약관에 동의해 이루어진 것으로 간주됩니다.</span>
            </div>



        <div class="mp_unregister">
            <a href="/auth/unregister" class="mp-setting-link">탈퇴</a>
        </div>



    </div>

</div>