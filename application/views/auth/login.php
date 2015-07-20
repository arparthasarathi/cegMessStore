<div class='row'>
	<div class='col s6 offset-s8 login_form'>
		<div class='row'>
		<h1>Login</h1>
		</div>

		<div class='row'>
		<span class='white-text text-darken-2'>Enter Username & Password to login ! </span>
		</div>

		<div class='row'>
		<span class='red-text text-darken-2'>
		<div id="infoMessage"><?php echo $message;?></div>
		</span>
		
		<?php echo form_open("auth/login");?>
	
  		<div class='row'>
		<?php echo lang('login_identity_label', 'identity');?>
		<?php echo form_input($identity);?>
		</div>
  
		<div class='row'>
		<?php echo lang('login_password_label', 'password');?>
		<?php echo form_input($password);?>
		</div>
  

		<div class="input-field row">
		<input type='checkbox' value=1 name='remember' id='remember'/>
		<label for='remember'> Remember Me! </label>
		</div>
    
		<div class = 'row'>
		<button class="btn waves-effect waves-light btn-large" 
                                        value="submit" type="submit" name="submit">
                Sign in
                <i class="glyphicon glyphicon-chevron-right"></i>
                </button>

		</div>

		<?php echo form_close();?>
		<div class='row'>
		<p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>
		</div>
	</div>
</div>
