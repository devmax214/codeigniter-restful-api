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
	
	<div class="row-fluid sortable">		
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="icon-user"></i><span class="break"></span>Users</h2>
				<div class="box-icon">
					<a href="<?php echo base_url('/user/create'); ?>" class="btn-add"><i class="icon-plus"></i> Add User</a>
					<a href="#" class="btn-minimize"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			<div class="box-content users-table">
				<table class="table table-striped bootstrap-datatable datatable">
				  <thead>
					  <tr>
						  <th>Username</th>
						  <th>Name</th>
						  <th>Email</th>
						  <th></th>
					  </tr>
				  </thead>   
				  <tbody>
				  <?php foreach($users as $user) { ?>
					<tr>
						<td><a href="<?php echo base_url('/user/detail/'. $user['user_id']); ?>"><?php echo $user['user_name']; ?></a></td>
						<td><?php echo $user['first_name']; ?></td>
						<td><a href="mailTo:<?php echo $user['email_address']; ?>"><?php echo $user['email_address']; ?></a></td>
						<td class="text-right">
							<a class="btn btn-danger delete-user" rel="<?php echo base_url('/user/delete/'. $user['user_id']); ?>" href="#">
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
        $('.delete-user').click(function(){
            if(!confirm("<?php echo translate("Are you sure you want to delete?"); ?>")) {
                return false;
            }

            location.href = $(this).attr("rel");
        });
    });
</script>