<?php
$login = array(
    'name'	=> 'login',
    'id'	=> 'login',
    'value' => set_value('login'),
    'maxlength'	=> 80,
    'size'	=> 30,
    'class'=>'form-control'
);
if ($login_by_username AND $login_by_email) {
    $login_label = '닉네임';
} else if ($login_by_username) {
    $login_label = 'Login';
} else {
    $login_label = 'Email';
}
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
<?php echo form_open($this->uri->uri_string()); ?>
        <div class="auth_logo_wrap">
            <a href="<?php echo base_url()?>"><img src="/www/img/logo.png" class="auth_logo" alt="moimga logo"></a>
        </div>
            <div id="kakaoIdLogin">
                <a id="custom-login-btn" href="javascript:loginWithKakao()" class="btn btn-kakao btn-full">
                    <img src="/www/img/kakao_icon.png" class="kakao_btn_img" alt="Kakao login">카카오 계정으로 로그인
                </a>
            </div>

            <div id="naverIdLogin">
                <a id="naverIdLogin_loginButton" href="#" role="button" class="btn btn-circle">
                    <img src="/www/img/sprite/icon-naver.png" alt="Naver login">네이버 아이디로 로그인</a>
            </div>

            <div id="gSignInWrapper" onclick="startApp();">
                <div id="customGoogleBtn" class="customGPlusSignIn">
                    <img src="/www/img/google_icon.png" class="google_icon" alt="Google login">
                    <span class="buttonText">구글 계정으로 로그인</span>
                </div>
            </div>
            <div id="FacebookIdLogin">
                페이스북으로 로그인
                <fb:login-button
                        scope="public_profile,email"
                        onlogin="checkLoginState();">
                </fb:login-button>
            </div>
            <small class="text-muted auth_desc">소셜 로그인 버튼으로 회원가입을 함으로써 '모임가'의 <a href="/info/terms" target="_blank" rel="noopener">이용 약관</a>, <a href="/info/privacy" target="_blank" rel="noopener">개인정보보호정책</a>에 동의하는 것으로 간주됩니다.</small>

            <div class="login_or">

                <div class="login_or_line"></div>
                <div class="login_or_text">또는</div>
                <div class="login_or_line"></div>
            </div>
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

                <div class="form-group" style="margin-top: 10px; margin-bottom: 0">

                    <input type="checkbox" id="login_remember" class="input_check" name="remember" value="1">
                    <label for="login_remember">자동 로그인</label>
                </div>
            </div>
            <?php echo form_submit('submit', '로그인', array('class'=>'btn btn-full btn-action')); ?>
            <div class="login_line"></div>
            <div class="login_else">
                <a href="/auth/register" class="btn btn-outline-action btn-full">이메일로 회원가입</a>
            </div>
            <div class="login_bottom">
                <div class="login_else">
                    <a href="/auth/forgot_password">비밀번호를 잊으셨나요?</a>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>

<div class="fade_form">
    <div class="fade_desc">로그인 중입니다.<br>잠시만 기다려주세요.</div>

    <div class="spinner_wrap">
        <span class="inner-circles-loader">Loading&#8230;</span>
    </div>
</div>

