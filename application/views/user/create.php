<div id="content" class="span10">
<!-- start: Content -->

	<div>
		<ul class="breadcrumb">
			<li>
				<a href="<?php echo base_url(); ?>">Home</a> <span class="divider">/</span>
			</li>
			<li>
				<a href="<?php echo base_url('/user/'); ?>">Users</a>
			</li>
		</ul>
	</div>

	<div class="msgbox all">
    	<?php if(isset($errors)) { ?>
			<div class='alert alert-error'>
	    		<button type='button' class='close' data-dismiss='alert'>x</button>
	    		<?php 
		    		foreach($errors as $error) {
		    			echo $error. '<br/>';
		    		} 
	    		?>
	    	</div>
		<?php } ?>
	</div>
	
	<div class="row-fluid">
		<div class="span6">
			<h2>Add a user</h2>
		</div>
	</div>
	
	<form method="post" name="create-form" action="<?php echo base_url('/user/create'); ?>" enctype="multipart/form-data" class="form-horizontal">
	
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-edit"></i><span class="break"></span>Account Details</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content">
				<fieldset>
					<div class="control-group email_address_group">
						<label class="control-label" for="email_address">Email</label>
						<div class="controls">
							<input type="text" class="input-large focused" id="email_address" name="email_address" value="<?php echo isset($user['email_address'])? $user['email_address']: ""; ?>">
							<span class="help-inline error-text required-email">You must input email</span>
							<span class="help-inline error-text valid-email">You must enter valid email</span>
						</div>
					</div>
					<div class="control-group first_name_group">
						<label class="control-label" for="first_name">Name</label>
						<div class="controls">
							<input type="text" class="input-large" id="first_name" name="first_name" value="<?php echo isset($user['first_name'])? $user['first_name']: ""; ?>">
							<span class="help-inline error-text">You must input name</span>
						</div>
					</div>
					<div class="control-group status_group">
						<label class="control-label" for="status">Status</label>
						<div class="controls">
							<input type="text" class="input-large" id="status" name="status" value="<?php echo isset($user['status'])? $user['status']: ""; ?>">
						</div>
					</div>
					<div class="control-group user_name_group">
						<label class="control-label" for="user_name">Username</label>
						<div class="controls">
							<input type="text" class="input-large" id="user_name" name="user_name" value="<?php echo isset($user['user_name'])? $user['user_name']: ""; ?>">
							<span class="help-inline error-text">You must input username</span>
						</div>
					</div>
					<div class="control-group password_group">
						<label class="control-label" for="password">Password</label>
						<div class="controls">
							<input type="password" class="input-large" id="password" name="password" value="" placeholder="Password">
							<span class="help-inline error-text required-password">You must input password</span><br />
							<input type="password" class="input-large" id="confirm_password" name="confirm_password" value="" placeholder="Confirm Password">
							<span class="help-inline error-text valid-password">You must input confirm password again</span>
						</div>
					</div>
					<div class="control-group">
					  <label class="control-label" for="gender">Gender</label>
					  <div class="controls">
						<select id="gender" name="gender">
						  <option value=""></option>
						  <option value="male" <?php echo (isset($user['gender']) && $user['gender'] == 'male')? "selected": ""; ?>>Male</option>
						  <option value="female" <?php echo (isset($user['gender']) && $user['gender'] == 'female')? "selected": ""; ?>>Female</option>
						</select>
					  </div>
					</div>
					<div class="control-group">
						<label class="control-label" for="location">Location</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="location" name="location" value="<?php echo isset($user['location'])? $user['location']: ""; ?>">
						</div>
					</div>
				</fieldset>
			</div>
		</div><!--/span-->
	</div>
		
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-picture"></i><span class="break"></span>Profile Photo</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content">
			    <div class="control-group photo_url_group">
				  <label class="control-label" for="photo_url">Upload Image</label>
				  <div class="controls">
					<input class="input-file uniform_on" id="photo_url" name="photo_url" type="file">
					<span class="help-inline error-text">You must upload profile photo with types *.png, *.jpg and *.gif</span>
				  </div>
				</div>
			</div>
		</div><!--/span-->
	</div>
	
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-check"></i><span class="break"></span>Body Measurements</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content">
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="height">Height</label>
						<div class="controls">
							<input type="text" class="input-large" id="height" name="height" value="<?php echo isset($spec['height'])? $spec['height']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="weight">Weight</label>
						<div class="controls">
							<input type="text" class="input-large" id="weight" name="weight" value="<?php echo isset($spec['weight'])? $spec['weight']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="chest">Chest</label>
						<div class="controls">
							<input type="text" class="input-large" id="chest" name="chest" value="<?php echo isset($spec['chest'])? $spec['chest']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="waist">Waist</label>
						<div class="controls">
							<input type="text" class="input-large" id="waist" name="waist" value="<?php echo isset($spec['waist'])? $spec['waist']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="hip">Hip</label>
						<div class="controls">
							<input type="text" class="input-large" id="hip" name="hip" value="<?php echo isset($spec['hip'])? $spec['hip']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="foot">Foot</label>
						<div class="controls">
							<input type="text" class="input-large" id="foot" name="foot" value="<?php echo isset($spec['foot'])? $spec['foot']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="neck">Neck</label>
						<div class="controls">
							<input type="text" class="input-large" id="neck" name="neck" value="<?php echo isset($spec['neck'])? $spec['neck']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="shoulder">Shoulder</label>
						<div class="controls">
							<input type="text" class="input-large" id="shoulder" name="shoulder" value="<?php echo isset($spec['shoulder'])? $spec['shoulder']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="arm_length">Arm Length</label>
						<div class="controls">
							<input type="text" class="input-large" id="arm_length" name="arm_length" value="<?php echo isset($spec['arm_length'])? $spec['arm_length']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="torso_height">Torso Height</label>
						<div class="controls">
							<input type="text" class="input-large" id="torso_height" name="torso_height" value="<?php echo isset($spec['torso_height'])? $spec['torso_height']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="upper_arm_size">Upper Arm Size</label>
						<div class="controls">
							<input type="text" class="input-large" id="upper_arm_size" name="upper_arm_size" value="<?php echo isset($spec['upper_arm_size'])? $spec['upper_arm_size']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="belly">Abdomen</label>
						<div class="controls">
							<input type="text" class="input-large" id="belly" name="belly" value="<?php echo isset($spec['belly'])? $spec['belly']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="leg_length">Leg Length</label>
						<div class="controls">
							<input type="text" class="input-large" id="leg_length" name="leg_length" value="<?php echo isset($spec['leg_length'])? $spec['leg_length']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="thigh">Thigh</label>
						<div class="controls">
							<input type="text" class="input-large" id="thigh" name="thigh" value="<?php echo isset($spec['thigh'])? $spec['thigh']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="calf">Calf</label>
						<div class="controls">
							<input type="text" class="input-large" id="calf" name="calf" value="<?php echo isset($spec['calf'])? $spec['calf']: ""; ?>">
						</div>
					</div>
				</fieldset>
			</div>
		</div><!--/span-->
	</div><!--/row-->
	
	<div class="row-fluid">
		<div class="span12 text-right">
			<button class="btn btn-large btn-primary btn-create-user">Create User</button>
		</div>
	</div>
	
		<input type="hidden" name="upload_photo" id="upload_photo" value="1" />
		<input type="hidden" name="cmd" id="cmd" value="create" />
	</form>

</div>

<script type="text/javascript">
    $(function() {
		$(".btn-create-user").click(function(e) {
			e.preventDefault();
			
			var error = false;
			$(".control-group").removeClass("error");
			$(".control-group .error-text").hide();
			
			if($("#email_address").val().trim() == "") {
				$(".email_address_group").addClass("error");
				$(".email_address_group .required-email").show();
				error = true;
			}
			else if(!checkEmail($("#email_address").val().trim())) {
				$(".email_address_group").addClass("error");
				$(".email_address_group .valid-email").show();
				error = true;
			}
			if($("#user_name").val() == "") {
				$(".user_name_group").addClass("error");
				$(".user_name_group .error-text").show();
				error = true;
			}
			if($("#first_name").val() == "") {
				$(".first_name_group").addClass("error");
				$(".first_name_group .error-text").show();
				error = true;
			}
			if($("#password").val() == "") {
				$(".password_group").addClass("error");
				$(".password_group .required-password").show();
				error = true;
			}
			else if($("#password").val() != "" && $("#confirm_password").val() != $("#password").val()) {
				$(".password_group").addClass("error");
				$(".password_group .valid-password").show();
				error = true;
			}
			if($("#photo_url").val() == "") {
				// $("#photo_url_group").addClass("error");
				// error = true;
			}
			else {
				var types = ['image/png', 'image/gif', 'image/jpeg'];
				if(types.indexOf($("#photo_url")[0].files[0].type) === -1) {
					$(".photo_url_group").addClass("error");
					$(".photo_url_group .error-text").show();
					error = true;
				}
			}
					
			if(error) {
				$(".msgbox").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>x</button>You need to fix errors.</div>");
				return false;
			}
			else {
				if($("#photo_url").val() != "") {
					$("#upload_photo").val(1);
				}
				$("form[name=create-form]").submit();
			}
		});
    });
</script>
