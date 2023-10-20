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
	    		<?php foreach($errors as $error) { ?>
	    			<p><?php echo $error; ?></p>
	    		<?php } ?>
			</div>
		<?php } ?>
		
		<?php if(isset($success)) { ?>
			<div class='alert alert-success'><?php echo $success; ?></div>
		<?php } ?>
	</div>
	
	<?php if(isset($user) && !empty($user)) { ?>
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
	<?php } ?>
	
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-edit"></i><span class="break"></span>Account Details</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content">
				<form class="form-horizontal">
				  <fieldset>
					<div class="control-group">
						<label class="control-label" for="email_address">Email</label>
						<div class="controls">
							<input type="text" class="input-large focused" id="email_address" name="email_address" value="<?php echo isset($user['email_address'])? $user['email_address']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Last Active Session</label>
						<div class="controls">
							2014-01-01
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="first_name">Name</label>
						<div class="controls">
							<input type="text" class="input-large" id="first_name" name="first_name" value="<?php echo isset($user['first_name'])? $user['first_name']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Body Mesurements</label>
						<div class="controls">
							2014-01-01
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Sign Up Date</label>
						<div class="controls">
							2014-01-01
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="user_name">Username</label>
						<div class="controls">
							<input type="text" class="input-large" id="user_name" name="user_name" value="<?php echo isset($user['user_name'])? $user['user_name']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="password">Password</label>
						<div class="controls">
							<input type="password" class="input-large" id="password" name="password" value="" placeholder="Password">
							<input type="password" class="input-large" id="confirm_password" name="confirm_password" value="" placeholder="Confirm Password">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="status">Status</label>
						<div class="controls">
							<input type="text" class="input-large" id="status" name="status" value="<?php echo isset($user['status'])? $user['status']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="location">Location</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="location" name="location" value="<?php echo isset($user['location'])? $user['location']: ""; ?>">
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
					<a href="<?php echo base_url('/post/edit'); ?>" class="btn-add"><i class="icon-plus"></i> Create a Post as this user</a>
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content">
				<table class="table table-striped bootstrap-datatable datatable">
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
			<div class="box-content">
				<div class="btn-group">
					<button class="btn">Simple BMS</button>
					<button class="btn">Upper BMS</button>
					<button class="btn">Lower BMS</button>
					<button class="btn">Full BMS</button>
				</div>
				<table class="table table-striped bootstrap-datatable datatable">
				  <tbody>
				  <?php foreach($similar_users as $user) { ?>
					<tr>
						<td><a href="<?php echo base_url('/user/detail/'. $user['user_id']); ?>"><?php echo $user['user_name']; ?></a></td>
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
				<h2><i class="icon-check"></i><span class="break"></span>Body Measurements</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content">
				<table class="table table-striped bootstrap-datatable datatable">
				  <tbody>
				  <?php foreach($similar_users as $user) { ?>
					<tr>
						<td><a href="<?php echo base_url('/user/detail/'. $user['user_id']); ?>"><?php echo $user['user_name']; ?></a></td>
					</tr>
				  <?php } ?>
				  </tbody>
			  	</table>
			</div>
		</div><!--/span-->
	</div><!--/row-->

</div>

<script type="text/javascript">
    $(function() {

    });
</script>
