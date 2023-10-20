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
    	<?php if(isset($msg_error)) { ?>
			<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>x</button><?php echo $msg_error; ?></div>
		<?php } ?>
		
		<?php if(isset($msg_success)) { ?>
			<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>x</button><?php echo $msg_success; ?></div>
		<?php } ?>
	</div>
	
	<div class="row-fluid">
		<div class="span6">
			<h2><?php echo $user['user_name']; ?></h2>
		</div>
		<div class="span6 text-right">
			<a class="btn btn-danger delete-user" rel="<?php echo base_url('/user/delete/'. $user['user_id']); ?>" href="#">
				<i class="icon-trash icon-white"></i> Delete User
			</a>
			<a class="btn change-password" rel="<?php echo base_url('/user/changePassword/'); ?>" href="#">
				<i class="icon-wrench"></i> Set the password for this user 
			</a>
		</div>
	</div>
	
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-edit"></i><span class="break"></span>Account Details</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content user-detail">
				<form class="form-horizontal">
				  <fieldset>
					<div class="control-group">
						<label class="control-label" for="email_address">Email</label>
						<div class="controls">
							<span><?php echo $user['email_address']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Last Active Session</label>
						<div class="controls">
							<span><?php echo ($user["last_activity"]) ? date('Y-m-d H:i:s', $user["last_activity"]): ''; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="first_name">Name</label>
						<div class="controls">
							<span><?php echo $user['first_name']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Body Mesurements</label>
						<div class="controls">
							<span><?php echo ($spec['last_changed'] != '' && $spec['last_changed'] != '0000-00-00 00:00:00')? $spec['last_changed']: ''; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Sign Up Date</label>
						<div class="controls">
							<span><?php echo $user['created']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="status">Status</label>
						<div class="controls">
							<span><?php echo $user['status']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="user_name">Username</label>
						<div class="controls">
							<span><?php echo $user['user_name']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="location">Location</label>
						<div class="controls">
							<span><?php echo $user['location']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="gender">Gender</label>
						<div class="controls">
						  	<span class="text-capital"><?php echo $user['gender']; ?></span>
						</div>
					</div>
				  </fieldset>
				</form>   

			</div>
		</div><!--/span-->
	</div>
		
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-picture"></i><span class="break"></span>Posts</h2>
				<div class="box-icon">
					<a href="<?php echo base_url('/post/create/'. $user['user_id']); ?>" class="btn-add"><i class="icon-plus"></i> Create a Post as this user</a>
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content user-detail">
				<table class="table">
				  <tbody>
				  <?php foreach($posts as $post) { ?>
					<tr>
						<td><a href="<?php echo base_url('/post/detail/'. $post['post_id']); ?>"><?php echo $post['created']; ?></a></td>
					</tr>
				  <?php } ?>
				  </tbody>
			  	</table>            
			</div>
		</div><!--/span-->
	</div>
		
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-check"></i><span class="break"></span>Body Doubles</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content user-detail">
				<div class="btn-group bsi-types">
					<button class="btn btn-success" data-bsi="simple">Simple BMS</button>
					<button class="btn" data-bsi="upper">Upper BMS</button>
					<button class="btn" data-bsi="lower">Lower BMS</button>
					<button class="btn" data-bsi="full">Full BMS</button>
				</div>
				<?php foreach($bsi_types as $bsi_type) { ?>
					<table class="table table-bsi table-<?php echo $bsi_type; ?>" <?php echo ($bsi_type=='simple')? '': 'style="display:none"'; ?>>
					  <tbody>
					  <?php foreach($similar_users[$bsi_type] as $user2) { ?>
						<tr>
							<td><a href="<?php echo base_url('/user/detail/'. $user2['user_id']); ?>"><?php echo $user2['user_name']; ?></a></td>
							<td><?php echo $user2['similar_percent']. '%'; ?></td>
						</tr>
					  <?php } ?>
					  </tbody>
				  	</table>
				 <?php } ?>
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
			<div class="box-content user-detail">
				<form class="form-horizontal">
				  <fieldset>
					<div class="control-group">
						<label class="control-label" for="spec_height">Height</label>
						<div class="controls">
							<span><?php echo $spec['height']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_weight">Weight</label>
						<div class="controls">
							<span><?php echo $spec['weight']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_chest">Chest</label>
						<div class="controls">
							<span><?php echo $spec['chest']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_waist">Waist</label>
						<div class="controls">
							<span><?php echo $spec['waist']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_hip">Hip</label>
						<div class="controls">
							<span><?php echo $spec['hip']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_foot">Foot</label>
						<div class="controls">
							<span><?php echo $spec['foot']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_neck">Neck</label>
						<div class="controls">
							<span><?php echo $spec['neck']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_shoulder">Shoulder</label>
						<div class="controls">
							<span><?php echo $spec['shoulder']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_arm_length">Arm Length</label>
						<div class="controls">
							<span><?php echo $spec['arm_length']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_torso_height">Torso Height</label>
						<div class="controls">
							<span><?php echo $spec['torso_height']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_upper_arm_size">Upper Arm Size</label>
						<div class="controls">
							<span><?php echo $spec['upper_arm_size']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_belly">Abdomen</label>
						<div class="controls">
							<span><?php echo $spec['belly']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_leg_length">Leg Length</label>
						<div class="controls">
							<span><?php echo $spec['leg_length']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_thigh">Thigh</label>
						<div class="controls">
							<span><?php echo $spec['thigh']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_calf">Calf</label>
						<div class="controls">
							<span><?php echo $spec['calf']; ?></span>
						</div>
					</div>
				  </fieldset>
				</form>
			</div>
		</div><!--/span-->
	</div><!--/row-->
	
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-picture"></i><span class="break"></span>Profile Photo</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content">
				<div class="profile-photo">
					<?php if($user['photo_url'] != "") { ?>
						<img src="<?php echo $user['photo_url']; ?>" />
					<?php } else { ?>
						<div class="empty-photo"></div>
					<?php } ?>
				</div>            
			</div>
		</div><!--/span-->
	</div>

</div>

<div class="modal hide fade" id="modal_password">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">x</button>
		<h3>Change Password</h3>
	</div>
	<div class="modal-body">
		<form id="frm_reset_pass" method="post" class="form-horizontal">
	    	<div class="tc-popup-in">
	            <div class="msgbox"></div>
	            <input type="password" placeholder="Password" name="password" id="password" value="">
                <input type="password" placeholder="Re-enter Password" name="confirm_password" id="confirm_password" value="">
            </div>
	    </form>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>
		<a href="javascript:void(0)" class="btn btn-primary btn-reset-pass">Save changes</a>
	</div>
</div>

<script type="text/javascript">
    $(function() {
        $('.delete-user').click(function(){
            if(!confirm("<?php echo translate("Are you sure you want to delete?"); ?>")) {
                return false;
            }

            location.href = $(this).attr("rel");
        });

        $('.change-password').click(function(e){
    		e.preventDefault();
    		
    		$("#frm_reset_pass .msgbox").html("");
 			$("#password").val("");
 			$("#confirm_password").val("");
 			
 			$('#modal_password').modal('show');
 			
            var user_id = "<?php echo $user['user_id']; ?>";
            $(".btn-reset-pass").click(function(){
            	$("#frm_reset_pass .msgbox").html("");
            	var error = "";
				if($("#password").val() == "") {
					error += "<?php echo translate("Password is required", 'admin'); ?>.";
				}
				if($("#password").val().length<6) {
					error += "<?php echo translate("You must enter password with 6 characters at least", 'admin'); ?>.";
				}
				if($("#password").val() != $("#confirm_password").val()) {
					error += "<?php echo translate("You must re-type password", 'admin'); ?>.";
				}
				if(error != "") { 
					$("#frm_reset_pass .msgbox").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>x</button>" + error + "</div>");
				}
				else {
					$.ajax({
						"type": "post",
						"url": "<?php echo base_url('/admin/resetPass'); ?>/" + user_id,
						"data": {"password": $("#password").val()},
						"dataType": "json",
						"error": function(res) {
							$("#frm_reset_pass .msgbox").html("<div class='alert alert-error'>" + res.responseText + "</div>");
						},
						"success": function(data) {
							if(data.error)
								$("#frm_reset_pass .msgbox").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>x</button>" + data.error + "</div>");
							else if(data.success) {
								$("#frm_reset_pass .msgbox").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>x</button><?php echo translate("Password has been reset successfully", 'admin'); ?>!</div>");
								setTimeout(function() {
									$("#dialog_reset_pass").dialog("close");
								}, 3000);
							}
						}
					});
				}
            });
            
            return false;
        });

        $(".btn-group.bsi-types > .btn").click(function() {
            var bsi_type = $(this).data("bsi");
            $(".table.table-bsi").hide();
            $(".table.table-bsi.table-" + bsi_type).show();
            
            $(".btn-group.bsi-types > .btn").removeClass("btn-success");
            $(this).addClass("btn-success");
        });
    });
</script>