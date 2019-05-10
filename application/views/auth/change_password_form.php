<?php
$old_password = array(
	'name'	=> 'old_password',
	'id'	=> 'old_password',
    'class' => 'form-control',
	'value' => set_value('old_password'),
	'size' 	=> 30,
);
$new_password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password',
    'class' => 'form-control',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_new_password = array(
	'name'	=> 'confirm_new_password',
	'id'	=> 'confirm_new_password',
    'class' => 'form-control',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size' 	=> 30,
);
?>
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-12">

            <div class="auth_top">
                <h1 class="page_title"  style="margin-bottom: 20px;">비밀번호 변경</h1>
            </div>
            <?php echo form_open($this->uri->uri_string(), array('class'=>'register_form')); ?>

            <div class="login_id_wrap">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="login_form_left" id="login_pw_confirm"><?php echo form_label($this->lang->line('old_password'), $old_password['id']); ?></span>
                    </div>
                    <?php echo form_password($old_password); ?>
                </div>
                <div class="login_error" id="pw_error"><?php echo form_error($old_password['name']); ?><?php echo isset($errors[$old_password['name']])?$errors[$old_password['name']]:''; ?></div>

            </div>

            <div class="login_id_wrap">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="login_form_left" id="login_pw_confirm"><?php echo form_label($this->lang->line('new_password'), $new_password['id']); ?></span>
                    </div>
                    <?php echo form_password($new_password); ?>
                </div>
                <div class="login_error" id="pw_error"><?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?></div>

            </div>

            <div class="login_id_wrap">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="login_form_left" id="login_pw_confirm"><?php echo form_label($this->lang->line('confirm_password'), $confirm_new_password['id']); ?></span>
                    </div>
                    <?php echo form_password($confirm_new_password); ?>
                </div>
                <div class="login_error" id="pw_error"><?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?></div>

            </div>
            <?php echo form_submit('change', '비밀번호 변경', array('class'=>'btn btn-full btn-lg btn-action btn-auth-lg')); ?>

            <?php echo form_close(); ?>
        </div>
    </div>