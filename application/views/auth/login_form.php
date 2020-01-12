<?php
$login = array(
    'name'	=> 'login',
    'id'	=> 'login',
    'value' => set_value('login'),
    'maxlength'	=> 80,
    'size'	=> 30,
    'class'=>'form-control'
);

$password = array(
    'name'	=> 'password',
    'id'	=> 'password',
    'size'	=> 30,
    'class'=>'form-control'
);
$remember = array(
    'name'	=> 'remember',
    'id'	=> 'remember',
    'value'	=> 1,
    'checked'	=> set_value('remember'),
    'style' => 'margin:0;padding:0',
);
$captcha = array(
    'name'	=> 'captcha',
    'id'	=> 'captcha',
    'maxlength'	=> 8,
);
?>
<div class="auth_logo_wrap">
    <a href="<?php echo base_url()?>"><img src="/www/img/logo.png" class="auth_logo" alt="moimga logo"></a>
</div>

<?php echo form_open($this->uri->uri_string()); ?>
        <div class="login_id_wrap">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="login_form_left" id="login_email"><?php echo form_label('이메일', $login['id']); ?></span>
                </div>
                <?php echo form_input($login); ?>
            </div>
            <div class="login_error"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></div>

        </div>
        <div class="login_id_wrap">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="login_form_left" id="login_pw"><?php echo form_label('비밀번호', $password['id']); ?></span>
                </div>
                <?php echo form_password($password); ?>
            </div>
            <div class="login_error"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></div>


        </div>
        <div class="form-group" style="margin: 10px 0;">

            <input type="checkbox" id="login_remember" class="input_check" name="remember" value="1">
            <label for="login_remember">로그인 기억</label>
            <a class="login_link" href="/auth/forgot_password">비밀번호 찾기</a>
        </div>

            <?php echo form_submit('submit', '로그인', array('class'=>'btn btn-full btn-action')); ?>
<?php echo form_close(); ?>
            <div class="login_else">
                <a href="/auth/register" class="btn btn-outline-action btn-full">이메일로 회원가입</a>
            </div>

<h2 class="social_title">SNS 계정으로 로그인</h2>

<div class="social_btn_wrap">

    <div id="kakaoIdLogin">
        <a id="custom-login-btn" href="javascript:loginWithKakao()" class="kakao_btn social_btn">
            <img src="/www/img/kakao_logo.png" class="kakao_btn_img" alt="Kakao login">
        </a>
    </div>

    <div id="naverIdLogin">
        <a id="naverIdLogin_loginButton" href="#" role="button" class="naver_btn social_btn">
            <img src="/www/img/naver_logo.png" alt="Naver login"></a>
    </div>
    <div id="gSignInWrapper" onclick="startApp();" class="">
        <div id="customGoogleBtn" class="customGPlusSignIn google_btn social_btn">
            <img src="/www/img/google_logo.png" alt="Google login">
        </div>
    </div>

    <div id="FacebookIdLogin" onclick="facebook_login()" class="facebook_btn social_btn">
        <img src="/www/img/fb_logo.png" class="fb_btn_img" alt="Facebook login">
    </div>
</div>

<div class="social_desc">
    회원 가입 시 개인정보 승인 팝업에서 모든 내용 체크 후 승인 버튼을 눌러주세요.<br>
    소셜 로그인 버튼으로 회원가입을 함으로써 '모임가'의 <a href="/info/terms" target="_blank" rel="noopener">이용 약관</a>, <a href="/info/privacy" target="_blank" rel="noopener">개인정보보호정책</a>에 동의하는 것으로 간주됩니다.
</div>


<div class="fade_form">
    <div class="fade_desc">로그인 중입니다.<br>잠시만 기다려주세요.</div>

    <div class="spinner_wrap">
        <span class="inner-circles-loader">Loading&#8230;</span>
    </div>
</div>

