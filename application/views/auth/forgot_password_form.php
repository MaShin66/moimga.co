<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
    'class'=>'form-control',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email or login';
} else {
	$login_label = 'Email';
}
?>
    <div class="auth_top">
        <h1 class="page_title">비밀번호 찾기</h1>
    </div>
    <div class="forgot_pw_desc">

        <p>가입하실 때 입력한 이메일로 임시 비밀번호를 전송해드립니다. <br>
            소셜 로그인(카카오, 구글, 네이버, 페이스북)으로 가입하신 분들은 이 기능을 사용하실 수 없습니다.</p>
        <p style="margin-top: 10px;">이메일이 도착하지 않았다면 스팸메일함을 확인해주세요.</p>

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
<?php echo form_submit('reset', '새 비밀번호 받기', array('class'=>'btn btn-full btn-lg btn-action')); ?>

<?php echo form_close(); ?>