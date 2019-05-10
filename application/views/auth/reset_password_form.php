<?php
$new_password = array(
	'name'	=> 'new_password',
	'class'=>'form-control',
	'id'	=> 'new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_new_password = array(
	'name'	=> 'confirm_new_password',
    'class'=>'form-control',
	'id'	=> 'confirm_new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size' 	=> 30,
);
?>
    <div class="auth_top">
        <h1 class="page_title" style="margin-bottom: 20px;">비밀번호 초기화</h1>
    </div>
<?php echo form_open($this->uri->uri_string()); ?>
    <div class="login_id_wrap">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="login_form_left" id="login_email"><?php echo form_label('새 비밀번호', $new_password['id']); ?></span>
            </div>
            <?php echo form_password($new_password); ?>
        </div>
        <div class="login_error"><?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?></div>

    </div>
    <div class="login_id_wrap">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="login_form_left" id="login_email"><?php echo form_label('비밀번호 확인', $confirm_new_password['id']); ?></span>
            </div>
            <?php echo form_password($confirm_new_password); ?>
        </div>
        <div class="login_error"><?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?></div>

    </div>
<?php echo form_submit('change', '비밀번호 변경', array('class'=>'btn btn-full btn-lg btn-action btn-auth-lg')); ?>

<?php echo form_close(); ?>