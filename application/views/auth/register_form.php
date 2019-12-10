<?php

$realname = array(
    'name'	=> 'realname',
    'id'	=> 'realname',
    'class' => 'form-control',
    'value' => set_value('realname'),
    'size'	=> 30,
);
$email = array(
	'name'	=> 'email',
    'class' => 'form-control',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
    'class' => 'form-control',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_password = array(
    'class' => 'form-control',
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'value' => set_value('confirm_password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);

//print_r($errors);
?>
<div class="auth_top">
    <h1 class="page_title"><?=$this->lang->line('registration')?></h1>
</div>
<?php echo form_open($this->uri->uri_string(), array('class'=>'register_form')); ?>
<div class="login_id_wrap">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="login_form_left" id="login_email"><?php echo form_label($this->lang->line('email'), $email['id']); ?></span>
        </div>
        <?php echo form_input($email); ?>
    </div>
    <div class="login_error " id="email_error"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></div>
    <div class="login_error " id="email_dup_error"></div>
    <div class="regi_guide"><?=$this->lang->line('email_guide')?></div>
</div>
<div class="login_id_wrap">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="login_form_left" id="login_realname"><?php echo form_label($this->lang->line('realname'), $realname['id']); ?></span>
        </div>
        <?php echo form_input($realname); ?>
    </div>
    <div class="login_error"><?php echo form_error($realname['name']); ?><?php echo isset($errors[$realname['name']])?$errors[$realname['name']]:''; ?></div>
    <div class="regi_guide"><?=$this->lang->line('realname_guide')?></div>
</div>
<div class="login_id_wrap">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="login_form_left" id="login_pw"><?php echo form_label($this->lang->line('password'), $password['id']); ?></span>
        </div>
        <?php echo form_password($password); ?>
    </div>
    <div class="login_error"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></div>

</div>
<div class="login_id_wrap">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="login_form_left" id="login_pw_confirm"><?php echo form_label($this->lang->line('confirm_password'), $confirm_password['id']); ?></span>
        </div>
        <?php echo form_password($confirm_password); ?>
    </div>
    <div class="login_error" id="pw_error"><?php echo form_error($confirm_password['name']); ?><?php echo isset($errors[$confirm_password['name']])?$errors[$confirm_password['name']]:''; ?></div>

</div>
<div class="regi_agree_wrap">
    <h4 class="auth_agree_title">개인정보 수집·이용 동의(필수)</h4>
    <p class="agree_guide" style="margin-bottom: 10px">모임가 서비스 이용자의 개인정보를 수집하는 목적은 다음과 같습니다. 표가 보이지 않는다면 화면을 옆으로 밀어주세요.</P>
    <table class="table-bordered table table table-responsive">
        <thead>
        <tr>
            <th width="200px">수집목적</th>
            <th>수집항목</th>
            <th width="134px">보유/이용기간</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>회원제 서비스 제공 및<br>유지·관리</td>
            <td>이메일, 비밀번호, 로그인ID, 이름,<br> 서비스 이용 기록, 접속 로그, 쿠키, 접속 IP 정보, 방문일시</td>
            <td>회원탈퇴시</td>
        </tr>
        </tbody>
    </table>
    <p class="agree_guide">- 동의를 거부할 권리가 있으나, 동의거부에 따른 서비스 이용에 제한이 있을 수 있습니다.</p>
    <p class="agree_guide">- 당사는 계약 및 서비스 이행을 위해 개인정보 처리업무를 위탁할 수 있으며, 개인정보처리방침에 그 내용을 고지합니다.</p>
    <div>
        <label for="agree_ok">
            <input type="radio" name="regi_agree_radio" class="regi_agree" id="agree_ok" value="ok">
            <span>(필수) 개인정보 수집 및 이용에 동의합니다.</span>
        </label>
        <label for="agree_no">
            <input type="radio" name="regi_agree_radio" class="regi_agree" id="agree_no" value="no">
            <span>동의하지 않습니다.</span>
        </label>
    </div>
</div>
<div class="regi_agree">
    <?=$this->lang->line('agreement')?>
</div>
<?php echo form_submit('register', $this->lang->line('registration'), array('id'=>'register_ok','class'=>'btn btn-full btn-lg btn-action btn-auth-lg')); ?>

<?php echo form_close(); ?>
