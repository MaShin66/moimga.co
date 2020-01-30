<?php
$password = array(
    'name' => 'password',
    'id' => 'password',
    'size' => 30,
    'class' => 'form-control'
);
$email = array(
    'name' => 'email',
    'id' => 'email',
    'value' => set_value('email'),
    'maxlength' => 80,
    'size' => 30,
    'class' => 'form-control'
);
?>
<div class="row justify-content-md-center">
    <div class="col-lg-6 col-md-8 col-sm-12">
        <div class="list_top">

            <h1 class="top_title">이메일 주소 변경</h1>
        </div>
        <div style="margin: 40px 0;">

            <?php echo form_open($this->uri->uri_string()); ?>
            <div class="login_id_wrap">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="login_form_left"
                              id="login_pw"><?php echo form_label('비밀번호', $password['id']); ?></span>
                    </div>
                    <?php echo form_password($password); ?>
                </div>
                <div class="login_error"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']]) ? $errors[$password['name']] : ''; ?></div>
            </div>
            <div class="login_id_wrap">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="login_form_left"
                              id="login_email"><?php echo form_label('새 이메일 주소', $email['id']); ?></span>
                    </div>
                    <?php echo form_input($email); ?>
                </div>
                <div class="login_error"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']]) ? $errors[$email['name']] : ''; ?></div>


            </div>
            <?php echo form_submit('change', '확인 이메일 보내기', array('class' => 'btn btn-full btn-action')); ?>
            <?php echo form_close(); ?>
        </div>
    </div>

</div>