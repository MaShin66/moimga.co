<?php
print_r($user);
/** 실명, 닉네임, 이메일 (변경), sns_type
 * 본인인증
 * Created by PhpStorm.
 * User: USER
 * Date: 2019-05-07
 * Time: 오후 5:23
 */?>
<h1>기본 정보</h1>
<ul>
    <li><a href="/mypage/info">내 정보</a> </li>
    <li><a href="/mypage/after">후기</a> </li>
</ul>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
        <h2>기본정보 </h2>

        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <h2>닉네임</h2>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <span class=""><?=$my_info['nickname']?></span>
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
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <h2>실명</h2>
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
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <h2>이메일</h2>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <div class="col-lg-9 col-md-8 col-sm-12">
                    <span class="">  <?=$my_info['email']?>

                        <?php if($my_info['sns_type']!=null){?>
                            <span class="badge badge-<?=$my_info['sns_type']?>">
                    <?=$my_info['sns_type']?>
                    </span>
                        <?php }?></span>
                    <a href="/auth/change_email">이메일 변경</a>
                </div>

            </div>
        </div>
        <?php if($my_info['sns_type']==null){?>

            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <h2>비밀번호</h2>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-12"><a href="/auth/change_password">비밀번호 변경</a> </div>
            </div>
        <?php }?>

    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="mp_title" style="display: block;">본인인증
            <?php if($my_info['verify']==1){ ?>
                <span class="mp_badge status_badge badge_done">인증 됨</span>
            <?php }else{ ?>
                <span class="mp_badge status_badge badge_pending">인증 안 됨</span>
            <?php }?>

        </div>
        <?php if($my_info['verify']==1){ ?>
            <div class="row mp-p-sec">
                <div class="col-sm-3 cont_left">실명</div>
                <div class="col-9 cont_right"><span><?=$my_info['realname']?></span></div>
            </div>
            <div class="row mp-p-sec">
                <div class="col-sm-3 cont_left">연락처</div>
                <div class="col-9 cont_right"><span>수집 됨</span></div>
            </div>

            <div class="row mp-p-sec">
                <div class="col-sm-3 cont_left">성인</div>
                <div class="col-9 cont_right"><span><?php
                        if($my_info['adult']==1){
                            echo  '성인';
                        }else{
                            echo   '미성년자';
                        }?></span>

                </div>
            </div>

        <?php }else{ ?>
            <div class="btn btn-outline-action btn-lg"  onclick="$('.input_verify').toggle(); $(this).hide();">본인인증 하기</div>
            <div class="input_verify">
                <form class="verify_form" method="post" action="/mypage/verify/ready">
                    <div class="input-group">
                        <input type="tel" id="verify_phone" name="phone" class="form-control open_input" placeholder="여기에 휴대폰 번호를 숫자만 입력해주세요.">
                        <div class="input-group-append-border">
                            <input type="submit" value="<?=$this->lang->line('go_adult_certi')?>" class="btn btn-action " onclick="phone_check();"/>
                        </div>
                    </div>

                    <small class="mp-sm-desc">숫자만 입력해주세요.</small>
                </form>
            </div>
        <?php }?>
        <small class="mp-sm-desc">본인인증 이후 구매하는 성인 상품은 <a href="#" data-toggle="modal" data-target="#agreeModal">해당 약관</a>에 동의하는 것으로 간주됩니다.</small>
    </div>

</div>


<a href="/auth/unregister">탈퇴</a>