<?php
$login = array(
    'name'	=> 'email',
    'id'	=> 'email',
    'value' => set_value('email'),
    'maxlength'	=> 80,
    'size'	=> 30,
    'class'=>'form-control'
);?>

    <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-12">
    <div class="auth_top">
        <h1 class="page_title">이메일 변경</h1>
    </div>

<?php echo form_open($this->uri->uri_string(), array('class'=>'register_form')); ?>
<div class="login_id_wrap">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="login_form_left" id="login_email"><?php echo form_label('이메일', $login['id']); ?></span>
        </div>
        <?php echo form_input($login); ?>
    </div>
    <div class="login_error"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></div>
    <input type="hidden" name="user_id" value="<?=$this->session->userdata('user_id')?>">

</div>
<?php echo form_submit('submit', '변경하기', array('class'=>'btn btn-full btn-action')); ?>
<?php echo form_close(); ?>
    </div>
    </div>
