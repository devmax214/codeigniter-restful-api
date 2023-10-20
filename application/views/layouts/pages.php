<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $title; ?></title>
<!-- CSS -->
<link href="http://fonts.googleapis.com/earlyaccess/nanumgothic.css" rel="stylesheet" type="text/css" />
<?php
	echo css('basic.css');
	echo css('style.css');
	echo css('style-responsive.css');
	echo css('jquery-ui.css');
	echo css('pages.css');
?>

<!-- Scripts -->
<?php
	echo js('jquery-1.8.3.js');
	echo js('jquery.easing.1.3.js');
	echo js('jquery.form.js');
	echo js('custom-form-elements.js');
	echo js('content-display.js');
	echo js('jquery-ui.js');
	echo js('elements.js');
	echo js('common.js');
	echo js('main.js');
?>

<!-- Menu Dropdown -->
<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";

	var csource=".menu-top-logged-dd-click", ctarget=".dd-list-logged";
	$(csource).live('click',function(event){
	    if($(ctarget).is(':visible')){
	        $(ctarget).fadeOut();
	    }else{
	        $(ctarget).fadeIn();
	        event.stopPropagation();
	    }
	});
	$('html').live('click',function(event){
	   $(ctarget).fadeOut();
	});

</script>

</head>

<?php 
	$body_img = $this->session->userdata('body_bg_img');
?>
<body style="<?php echo ($body_img!='')? 'background:url('. $body_img. ') repeat left top': ''; ?>" >
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php 
	if($this->session->userdata("loggedin")) {
		echo $this->load->view('layouts/header_logged');
	}
	else {
 		echo $this->load->view('layouts/header');
 	}
 	
 	echo $main_content;
 	
 	echo $this->load->view('layouts/footer');
?>
</body>
</html>