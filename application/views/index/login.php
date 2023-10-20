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
			</div>
			<!-- end: Header Menu -->
			
		</div>
	</div>
</div>
<div id="under-header"></div>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="row-fluid">
			<div class="login-box">
				<div class="icons">
				</div>
				
				<h2>Login to your account</h2>
				
				<div class="msgbox">
			    	<?php if(isset($msg_error)) { ?>
			            <div class="alert alert-error">
			            	<button type='button' class='close' data-dismiss='alert'>x</button>
							<?php echo $msg_error; ?>
						</div>
			        <?php } ?>
				</div>
				
				<form class="form-horizontal" name="login-form" action="<?php echo base_url(); ?>" method="post">
					<fieldset>
						
						<div class="input-prepend" title="Username">
							<span class="add-on"><i class="icon-user"></i></span>
							<input class="input-large span10" type="text" id="login_email" name="login_email" value="<?php echo isset($login_email)? $login_email: ''; ?>" placeholder="Email"/>
						</div>
						<div class="clearfix"></div>

						<div class="input-prepend" title="Password">
							<span class="add-on"><i class="icon-lock"></i></span>
							<input class="input-large span10" type="password" id="login_pass" name="login_pass" value="<?php echo isset($login_pass)? $login_pass: ''; ?>" placeholder="Password"/>
						</div>
						<div class="clearfix"></div>
						
						<div class="btn-group button-login">	
							<button type="button" class="btn btn-primary btn-login"><i class="icon-off icon-white"></i></button>
							<button type="button" class="btn btn-primary btn-login">Login</button>
						</div>
						<div class="clearfix"></div>
					
				</form>
			</div><!--/span-->
		</div><!--/row-->
	</div><!--/fluid-row-->
</div><!--/.fluid-container-->

<script type="text/javascript">
	$(function() {
		/**
		 * Check for validation
		 */
		/* Login form */
		$(".btn-login").click(function(e){
			e.preventDefault();
			
			var error = "";
			
			if($("#login_email").val().trim() == "") {
				error += "<?php echo translate("You must enter email"); ?>.";
			}
			else if(!checkEmail($("#login_email").val().trim())) {
				error += "<?php echo translate("You must enter valid email"); ?>.";
			}
			else if($("#login_pass").val() == "") {
				error += "<?php echo translate("You must enter password"); ?>.";
			}
			
			if(error != "") {
				$(".msgbox").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>x</button>" + error + "</div>");
				return false;
			}
			else {
				$("form[name=login-form]").submit();
			}
			
			return false;
		});
	});
</script>
