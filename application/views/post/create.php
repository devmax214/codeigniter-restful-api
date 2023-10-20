<div id="content" class="span10">
<!-- start: Content -->

	<div>
		<ul class="breadcrumb">
			<li>
				<a href="<?php echo base_url(); ?>">Home</a> <span class="divider">/</span>
			</li>
			<li>
				<a href="<?php echo base_url('/post/'); ?>">Posts</a>
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
			<h2>Create a Post</h2>
		</div>
	</div>
	
	<form method="post" name="create-form" action="<?php echo base_url('/post/create/'. $user['user_id']); ?>" enctype="multipart/form-data" class="form-horizontal">
	
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-edit"></i><span class="break"></span>Account Details</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content user-detail">
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
			
			</div>
		</div><!--/span-->
	</div>
		
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-picture"></i><span class="break"></span>Photo</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content">
			    <div class="control-group photo_url_group">
				  <label class="control-label" for="photo_url">Upload Photo</label>
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
				<h2><i class="icon-check"></i><span class="break"></span>Description</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content">
				<fieldset>
					<div class="control-group">
						<textarea class="input-large span11" id="content" name="content"><?php echo isset($post['content'])? $post['content']: ""; ?></textarea>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
	
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-check"></i><span class="break"></span>Post detail</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content">
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="clothing_id">Clothing Type</label>
						<div class="controls">
							<select name="clothing_id" id="clothing_id">
							<?php foreach($clothings as $clothing) { ?>
								<option value="<?php echo $clothing['clothing_id']; ?>"><?php echo $clothing['clothing_type']; ?></option>
							<?php } ?>
							</select>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-check"></i><span class="break"></span>Fitting Report (5 indicates the most; 1 indicates the least)</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content">
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="overall_rating">Overall Rating</label>
						<div class="controls">
							<select name="overall_rating" id="overall_rating">
								<option value="">Select Rating (1-5)</option>
								<?php for($i=1; $i<6; $i++) { ?>
									<option value="<?php echo $i; ?>" <?php echo (isset($post['overall_rating']) && $post['overall_rating'] == $i)? "selected": ""; ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="brand_name">Brand</label>
						<div class="controls">
							<select name="brand_name" id="brand_name">
								<option value="">Select Brand</option>
								<?php foreach($brands as $brand) { ?>
									<option value="<?php echo $brand['brand_name']; ?>"><?php echo $brand['brand_name']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="cloth_model">Model</label>
						<div class="controls">
							<input type="text" class="input-large" id="cloth_model" name="cloth_model" value="<?php echo isset($post['cloth_model'])? $post['cloth_model']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="sizing">Size</label>
						<div class="controls">
							<input type="text" class="input-large" id="sizing" name="sizing" value="<?php echo isset($post['sizing'])? $post['sizing']: ""; ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="is_recommended">Recommended?</label>
						<div class="controls">
							<select name="is_recommended" id="is_recommended">
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="length_rating">Length</label>
						<div class="controls">
							<select name="length_rating" id="length_rating">
								<option value="">Select Rating (1-5)</option>
								<?php for($i=1; $i<6; $i++) { ?>
									<option value="<?php echo $i; ?>" <?php echo (isset($post['length_rating']) && $post['length_rating'] == $i)? "selected": ""; ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="toe_rating">Toe</label>
						<div class="controls">
							<select name="toe_rating" id="toe_rating">
								<option value="">Select Rating (1-5)</option>
								<?php for($i=1; $i<6; $i++) { ?>
									<option value="<?php echo $i; ?>" <?php echo (isset($post['toe_rating']) && $post['toe_rating'] == $i)? "selected": ""; ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="heel_rating">Heel</label>
						<div class="controls">
							<select name="heel_rating" id="heel_rating">
								<option value="">Select Rating (1-5)</option>
								<?php for($i=1; $i<6; $i++) { ?>
									<option value="<?php echo $i; ?>" <?php echo (isset($post['heel_rating']) && $post['heel_rating'] == $i)? "selected": ""; ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="width_rating">Width</label>
						<div class="controls">
							<select name="width_rating" id="width_rating">
								<option value="">Select Rating (1-5)</option>
								<?php for($i=1; $i<6; $i++) { ?>
									<option value="<?php echo $i; ?>" <?php echo (isset($post['width_rating']) && $post['width_rating'] == $i)? "selected": ""; ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="comfort_rating">Comfort</label>
						<div class="controls">
							<select name="comfort_rating" id="comfort_rating">
								<option value="">Select Rating (1-5)</option>
								<?php for($i=1; $i<6; $i++) { ?>
									<option value="<?php echo $i; ?>" <?php echo (isset($post['comfort_rating']) && $post['comfort_rating'] == $i)? "selected": ""; ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="quality_rating">Quality</label>
						<div class="controls">
							<select name="quality_rating" id="quality_rating">
								<option value="">Select Rating (1-5)</option>
								<?php for($i=1; $i<6; $i++) { ?>
									<option value="<?php echo $i; ?>" <?php echo (isset($post['quality_rating']) && $post['quality_rating'] == $i)? "selected": ""; ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="style_rating">Style</label>
						<div class="controls">
							<select name="style_rating" id="style_rating">
								<option value="">Select Rating (1-5)</option>
								<?php for($i=1; $i<6; $i++) { ?>
									<option value="<?php echo $i; ?>" <?php echo (isset($post['style_rating']) && $post['style_rating'] == $i)? "selected": ""; ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="ease_rating">Ease of Care</label>
						<div class="controls">
							<select name="ease_rating" id="ease_rating">
								<option value="">Select Rating (1-5)</option>
								<?php for($i=1; $i<6; $i++) { ?>
									<option value="<?php echo $i; ?>" <?php echo (isset($post['ease_rating']) && $post['ease_rating'] == $i)? "selected": ""; ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="durability_rating">Durability Occassion (casual / formal)</label>
						<div class="controls">
							<select name="durability_rating" id="durability_rating">
								<option value="">Select Rating (1-5)</option>
								<?php for($i=1; $i<6; $i++) { ?>
									<option value="<?php echo $i; ?>" <?php echo (isset($post['durability_rating']) && $post['durability_rating'] == $i)? "selected": ""; ?>><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</fieldset>
			</div>
		</div><!--/span-->
	</div><!--/row-->
	
	<div class="row-fluid">
		<div class="span12 text-right">
			<button class="btn btn-large btn-primary btn-create-post">Create Post</button>
		</div>
	</div>
	
		<input type="hidden" name="upload_photo" id="upload_photo" value="1" />
		<input type="hidden" name="cmd" id="cmd" value="create" />
	</form>

</div>

<script type="text/javascript">
    $(function() {
		$(".btn-create-post").click(function(e) {
			e.preventDefault();
			
			var error = false;
			$(".control-group").removeClass("error");
			$(".control-group .error-text").hide();
			
			if($("#photo_url").val() == "") {
				$(".photo_url_group").addClass("error");
				$(".photo_url_group .error-text").show();
				error = true;
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
