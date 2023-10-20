<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta property="al:ios:url" content="<?php echo (isset($iphone_link)? $iphone_link: ''); ?>" />
    <meta property="al:ios:app_store_id" content="<?php echo (isset($appstore_link)? $appstore_link: ''); ?>" />
    <meta property="al:ios:app_name" content="<?php echo $title; ?>" />
    <!-- <meta property="al:android:url" content="applinks://docs" />
    <meta property="al:android:app_name" content="App Links" />
    <meta property="al:android:package" content="org.applinks" /> -->
    <meta property="al:web:url" content="<?php echo (isset($web_link)? $web_link: ''); ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title; ?></title>

<!-- CSS -->
<!-- <link href="http://fonts.googleapis.com/earlyaccess/nanumgothic.css" rel="stylesheet" type="text/css" /> -->
<?php
	echo css('basic.css');
	echo css('style.css');
	echo css('admin-style.css');
	echo css('style-responsive.css');
	echo css('jquery-ui.css');
	echo css('pages.css');
?>

<!-- Scripts -->
<?php
	echo js('jquery-1.8.3.js');
	echo js('jquery.easing.1.3.js');
	echo js('custom-form-elements.js');
	echo js('content-display.js');
	echo js('jquery-ui.js');
	echo js('elements.js');
	echo js('common.js');
	echo js('main.js');
?>

<!-- Menu Dropdown -->
<script type="text/javascript">

<?php if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'iphone') !== false) { ?>
	$(window).load(function() {
		//Invoke URL for existing users with app already installed on their device
		<?php if(isset($iphone_link) && $iphone_link != '') { ?>
	    	window.location.href = '<?php echo $iphone_link; ?>';
	    <?php } ?>
		//Downlaod URL (MAT link) for new users to download the app
	    <?php if(isset($appstore_link) && $appstore_link != '') { ?>
		    setTimeout(function() { 
			    window.location.href = '<?php echo $appstore_link; ?>'; }
		    , 1000);
	    <?php } ?>
	    
	});
<?php } ?>
 
	var base_url = "<?php echo base_url(); ?>";

	var csource=".menu-top-login-dd-click", ctarget=".dd-list-login";
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

<body>
<?php echo $this->load->view('layouts/header'); ?>
<?php echo $main_content; ?>
<?php echo $this->load->view('layouts/footer'); ?>
</body>
</html>