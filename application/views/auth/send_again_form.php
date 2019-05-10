<?php
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
    'class' => 'form-control',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
?>

<div class="auth_top">
    <h1 class="page_title">인증 메일 재전송</h1>
</div>

<?php echo form_open($this->uri->uri_string(), array('class'=>'register_form')); ?>

<div class="login_id_wrap">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="login_form_left" id="login_email"><?php echo form_label($this->lang->line('email'), $email['id']); ?></span>
        </div>
        <?php echo form_input($email); ?>
    </div>
    <div class="login_error"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></div>
</div>

<?php echo form_submit('send', '인증메일 보내기', array('class'=>'btn btn-full btn-lg btn-action')); ?>

<?php echo form_close(); ?>