<section>
<div class="container">
<?php /*<div class="row">
<div class="span16"> */ ?>
<?php echo form_open('auth/login', array('id' => 'login_form', 'class' => 'form-horizontal'))?>
        <div class="page-header">
          <h1>Dashboard Login</h1>
          </div><!--page-header-->
<div class="alert_wrap">
<?php echo $this->ci_alerts->display()?>
<?php
// validation errors notification
if (validation_errors() != ''):
?>
<div class="alert alert-error fade in" data-dismiss="alert"><a class="close" href="#">&times;</a>Please correct the highlighted errors.</div>
<?php endif; ?>

</div><!--alert-wrap-->
<div class="control-group form_item <?php echo(form_error('email_address') != '' ? 'error' : '')?>">
            <?php echo form_label('Email Address: *', 'email_address_field', array('class' => 'control-label'))?>
            <div class="controls">
<?php
// form value
if (set_value('email_address') != '') {$value = set_value('email_address');}
else {$value = get_cookie('email_address');}
?>
              <?php echo form_input(array('name' => 'email_address', 'id' => 'email_address_field', 'class' => 'span3', 'value' => $value))?>
              <?php echo form_error('email_address')?>
            </div><!--controls-->
          </div><!--control-group-->
          
<div class="control-group form_item <?php echo (form_error('password') != '' ? 'error' : '')?>">
            <?php echo form_label('Password: *', 'password_field', array('class' => 'control-label'))?>
            <div class="controls">
<?php
// form value
if (set_value('password') != '') {$value = set_value('password');}
else {$value = get_cookie('password');}
?>
              <?php echo form_password(array('name' => 'password', 'id' => 'password_field', 'class' => 'span3', 'value' => $value))?>
              <?php echo form_error('password')?>
            </div><!--controls-->
</div><!--control-group-->

<div class="control-group form_item">
<div class="controls">
<?php
// checked or not
if ($this->input->post('remember_me') !== false) {$checked = $this->input->post('remember_me');}
else {$checked = get_cookie('remember_me');}
?>
<?php
$checkbox = form_checkbox(array('name' => 'remember_me', 'id' => 'remember_me_field', 'value' => '1', 'checked' => (boolean)$checked));
?>
<?php echo form_label($checkbox . ' <span>Remember Me</span>', 'remember_me_field', array('class' => 'checkbox'))?>
</div><!--controls-->
</div><!--control-group-->

          <fieldset class="form-actions">
          <button type="submit"  class="btn btn-primary">Login</button>
          </fieldset><!--form-actions-->
</form>

<?php /* </div><!--span-->
</div><!--row--> */ ?>
</div><!--container-->
</section>
</body>
</html>