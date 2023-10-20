<?php 
	$role = $this->session->userdata('user_role');
?>
<!-- Top Section Start -->
<div id="top-sec">
	<div id="top-sec-in">
    	<a href="<?php echo base_url(); ?>">
    		<h1 class="logo"><?php echo $title; ?></h1>
    	</a>
        <ul class="menu-top-logged">
        	<li><a href="<?php echo base_url('/admin/products'); ?>"><?php echo translate("Products"); ?></a></li>
            <li><a href="<?php echo base_url('/admin/orders'); ?>"><?php echo translate("Orders"); ?></a></li>
            <li><a href="<?php echo base_url('/admin/accounts'); ?>"><?php echo translate("Accounts"); ?></a></li>
            <li><a href="<?php echo base_url('/index/logout'); ?>"><?php echo translate("Logout"); ?></a></li>
        </ul>
        <div class="menu-top-logged-dd">
            <div class="menu-top-logged-dd-in">
                <div class="menu-top-logged-dd-click">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </div>
            </div>
            <div class="dd-list-logged">
                <span class="arrow-logged"></span>
                <ul class="menu-top-logged-dd-list">
                    <li><a href="<?php echo base_url('/admin/products'); ?>"><?php echo translate("Products"); ?></a></li>
		            <li><a href="<?php echo base_url('/admin/orders'); ?>"><?php echo translate("Orders"); ?></a></li>
		            <li><a href="<?php echo base_url('/admin/accounts'); ?>"><?php echo translate("Accounts"); ?></a></li>
		            <li class="border"></li>
                    <li><a href="<?php echo base_url('/index/logout'); ?>"><?php echo translate("Logout"); ?></a></li>
                </ul>
            </div>    
        </div><!-- End Menu-top-logged-dd -->
    </div>
</div>
<!-- Top Section End -->

<div id="under-header"></div>