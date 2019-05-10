<?php
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
    'class' => 'form-control',
);
?>

    <div class="auth_top">
        <h1 class="page_title"><?=$this->lang->line('unregistration')?></h1>
    </div>

<?php echo form_open($this->uri->uri_string(), array('class'=>'register_form')); ?>
    <div class="login_id_wrap">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="login_form_left" id="login_email"><?php echo form_label('비밀번호', $password['id']); ?></span>
            </div>
            <?php echo form_password($password); ?>
        </div>
        <div class="login_error " id="email_error"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></div>
    </div>
<?php echo form_submit('cancel', '탈퇴', array('class'=>'btn btn-full btn-red')); ?>

<?php echo form_close(); ?>