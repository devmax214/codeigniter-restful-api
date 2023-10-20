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
	
	<div class="row-fluid sortable">		
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-picture"></i><span class="break"></span>Posts</h2>
				<div class="box-icon">
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content users-table">
				<table class="table table-striped bootstrap-datatable datatable">
				  <thead>
					  <tr>
						  <th>Name</th>
						  <th>Date Posted</th>
						  <th>Caption</th>
						  <th>Clothing Types</th>
						  <th>Comments</th>
						  <th>Likes</th>
						  <th>Re-shares</th>
						  <th>Brand</th>
						  <th>Last Comment</th>
						  <th> </th>
					  </tr>
				  </thead>   
				  <tbody>
				  <?php foreach($posts as $post) { ?>
					<tr>
						<td><a href="<?php echo base_url('/post/detail/'. $post['post_id']); ?>"><?php echo $post['user_name']; ?></a></td>
						<td><?php echo $post['created']; ?></td>
						<td><?php echo substr($post['content'], 0, 30); ?></td>
						<td><?php echo $post['clothing_type']; ?></td>
						<td><?php echo $post['post_comments']; ?></td>
						<td><?php echo $post['post_likes']; ?></td>
						<td><?php echo $post['post_shares']; ?></td>
						<td><?php echo $post['brand_name']; ?></td>
						<td><?php echo $post['last_comment']; ?></td>
						<td class="text-right">
							<a class="btn btn-danger delete-post" rel="<?php echo base_url('/post/delete/'. $post['post_id']); ?>" href="#">
								<i class="icon-trash icon-white"></i> Delete 
							</a>
						</td>
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
        $('.delete-post').click(function(){
            if(!confirm("<?php echo translate("Are you sure you want to delete?"); ?>")) {
                return false;
            }

            location.href = $(this).attr("rel");
        });
    });
</script>