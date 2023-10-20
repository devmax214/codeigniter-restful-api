<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title; ?></title>
	
	<!-- start: Favicon -->
	<!-- <link rel="shortcut icon" href="img/favicon.ico"> -->
	<!-- end: Favicon -->
	
	<!-- CSS -->
	<?php
		echo css('bootstrap.css');
		echo css('bootstrap-responsive.min.css');
		echo css('style.css');
		echo css('style-responsive.css');
		echo css('custom.css');
	?>
	
	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<?php
		echo js('jquery-1.8.3.min.js');
		echo js('jquery-ui-1.8.21.custom.min.js');
		echo js('bootstrap.js');
		echo js('common.js');
	?>
	
	<script type="text/javascript">
		var base_url = "<?php echo base_url(); ?>";
	</script>
</head>

<body>
<?php
	$user_id = $this->session->userdata("user_id");

	if($user_id) {
		echo $this->load->view('layouts/header');
		echo $main_content;
		echo $this->load->view('layouts/footer');
	}
	else {
		echo $main_content;
	}
?>

<!-- Scripts -->
<?php
	echo js('jquery.cookie.js');
	echo js('fullcalendar.min.js');
	echo js('jquery.dataTables.min.js');
	echo js('excanvas.js');
	echo js('jquery.flot.min.js');
	echo js('jquery.flot.pie.min.js');
	echo js('jquery.flot.stack.js');
	echo js('jquery.flot.resize.min.js');
	echo js('jquery.chosen.min.js');
	echo js('jquery.uniform.min.js');
	echo js('jquery.cleditor.min.js');
	echo js('jquery.noty.js');
	echo js('jquery.elfinder.min.js');
	echo js('jquery.raty.min.js');
	echo js('jquery.iphone.toggle.js');
	echo js('jquery.uploadify-3.1.min.js');
	echo js('jquery.gritter.min.js');
	echo js('jquery.imagesloaded.js');
	echo js('jquery.masonry.min.js');
	echo js('custom.js');
	echo js('main.js');
?>
</body>
</html>