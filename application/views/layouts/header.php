<?php 
	$role = $this->session->userdata('user_role');
?>
<!-- start: Header -->
<div class="navbar">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="<?php echo base_url(); ?>"><span><?php echo $title; ?></span></a>
							
			<!-- start: Header Menu -->
			<div class="btn-group pull-right" >
				<a class="btn" href="#">
					<i class="icon-user"></i><span class="hidden-phone hidden-tablet"> admin</span>
				</a>
				<a class="btn" href="<?php echo base_url('/index/logout'); ?>">
					<i class="icon-off"></i><span class="hidden-phone hidden-tablet"> Logout</span>
				</a>
			</div>
			<!-- end: Header Menu -->
			
		</div>
	</div>
</div>
<div id="under-header"></div>

<div class="container-fluid">
	<div class="row-fluid">
			
		<!-- start: Main Menu -->
		<div class="span2 main-menu-span">
			<div class="nav-collapse sidebar-nav">
				<ul class="nav nav-tabs nav-stacked main-menu">
					<li class="nav-header hidden-tablet">Navigation</li>
					<li><a href="<?php echo base_url('/user'); ?>"><i class="icon-user"></i><span class="hidden-tablet"> Users</span></a></li>
					<li><a href="<?php echo base_url('/post'); ?>"><i class="icon-picture"></i><span class="hidden-tablet"> Posts</span></a></li>
				</ul>
			</div><!--/.well -->
		</div><!--/span-->
		<!-- end: Main Menu -->
		
		<noscript>
			<div class="alert alert-block span10">
				<h4 class="alert-heading">Warning!</h4>
				<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
			</div>
		</noscript>