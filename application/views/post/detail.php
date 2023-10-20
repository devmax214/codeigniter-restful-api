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
    	<?php if(isset($msg_error)) { ?>
			<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>x</button><?php echo $msg_error; ?></div>
		<?php } ?>
		
		<?php if(isset($msg_success)) { ?>
			<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>x</button><?php echo $msg_success; ?></div>
		<?php } ?>
	</div>
	
	<div class="row-fluid">
		<div class="span6">
			<h2>Post</h2>
		</div>
		<div class="span6 text-right">
			<a class="btn btn-danger delete-post" rel="<?php echo base_url('/post/delete/'. $post['post_id']); ?>" href="#">
				<i class="icon-trash icon-white"></i> Delete Post
			</a>
		</div>
	</div>
	
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-picture"></i></h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content">
				<div class="profile-photo">
					<?php if($post['photo_url'] != "") { ?>
						<img src="<?php echo $post['photo_url']; ?>" />
					<?php } else { ?>
						<div class="empty-photo"></div>
					<?php } ?>
				</div>            
			</div>
		</div><!--/span-->
	</div>
	
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-list-alt"></i><span class="break"></span>Description</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content post-detail">
				<p><?php echo nl2br($post['content']); ?></p>
			</div>
		</div><!--/span-->
	</div>
		
	<div class="row-fluid sortable">
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-comment"></i><span class="break"></span>Comments</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content user-detail">
				<table class="table">
				  <tbody>
				  <?php foreach($comments as $comment) { ?>
					<tr>
						<td><?php echo $comment['user_name']; ?></td>
						<td><?php echo $comment['created']; ?></td>
						<td><p><?php echo nl2br($comment['comment']); ?></p></td>
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
				<h2><i class="icon-th-list"></i><span class="break"></span>Fitting Report (5 indicates the most; 1 indicates the least)</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content user-detail">
				<form class="form-horizontal">
				  <fieldset>
					<div class="control-group">
						<label class="control-label" for="spec_height">Overall Rating</label>
						<div class="controls">
							<span><?php echo $post['overall_rating']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_weight">Brand</label>
						<div class="controls">
							<span><?php echo $post['brand_name']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_chest">Model</label>
						<div class="controls">
							<span><?php echo $post['cloth_model']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_waist">Size</label>
						<div class="controls">
							<span><?php echo $post['sizing']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_hip">Recommended?</label>
						<div class="controls">
							<span><?php echo $post['is_recommended']; ?></span>
						</div>
					</div>
					<div class="control-group">
					
					<?php 
						$toe_rating = '';
						$heel_rating = '';
						$width_rating = '';
						$length_rating = '';
						
						if($post['fitting_report'] != "") {
							$fitting_reports = json_decode($post['fitting_report']);
							$toe_rating = isset($fitting_reports->Toe)? $fitting_reports->Toe: '';
							$heel_rating = isset($fitting_reports->Heel)? $fitting_reports->Heel: '';
							$width_rating = isset($fitting_reports->Width)? $fitting_reports->Width: '';
							$length_rating = isset($fitting_reports->Length)? $fitting_reports->Length: '';
						}
					?>
					<label class="control-label" for="spec_foot">Length</label>
						<div class="controls">
							<span><?php echo $length_rating; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_neck">Toe</label>
						<div class="controls">
							<span><?php echo $toe_rating; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_shoulder">Heel</label>
						<div class="controls">
							<span><?php echo $heel_rating; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_arm_length">Width</label>
						<div class="controls">
							<span><?php echo $width_rating; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_torso_height">Comfort</label>
						<div class="controls">
							<span><?php echo $post['comfort_rating']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_upper_arm_size">Quality</label>
						<div class="controls">
							<span><?php echo $post['quality_rating']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_belly">Style</label>
						<div class="controls">
							<span><?php echo $post['style_rating']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_leg_length">Ease of Care</label>
						<div class="controls">
							<span><?php echo $post['ease_rating']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_thigh">Durability Occassion (casual / formal)</label>
						<div class="controls">
							<span><?php echo $post['durability_rating']; ?></span>
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
				<h2><i class="icon-check"></i><span class="break"></span>Post Details</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content user-detail">
				<form class="form-horizontal">
				  <fieldset>
					<div class="control-group">
						<label class="control-label" for="spec_height">Clothing Type</label>
						<div class="controls">
							<span><?php echo $post['clothing_type']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_neck">Likes</label>
						<div class="controls">
							<span><?php echo $post['post_likes']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_shoulder">Re-shares</label>
						<div class="controls">
							<span><?php echo $post['post_shares']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_arm_length">Comments</label>
						<div class="controls">
							<span><?php echo $post['post_comments']; ?></span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="spec_torso_height">Post Date</label>
						<div class="controls">
							<span><?php echo $post['created']; ?></span>
						</div>
					</div>
				  </fieldset>
				</form>
			</div>
		</div><!--/span-->
	</div><!--/row-->
	
</div>

<script type="text/javascript">
    $(function() {
        $('.delete-post').click(function(){
            if(!confirm("<?php echo translate("Are you sure you want to delete?"); ?>")) {
                return false;
            }

            location.href = $(this).attr("rel");
        });
    });
</script>