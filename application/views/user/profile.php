<!-- Middle Section Start -->
<div id="middle-sec">
	<div id="middle-sec-in2">
    	<ul class="box-left-full">
        	<li>
            	<h5 class="txt-alg"><?php echo $user['first_name']. ' '. $user['last_name']; ?></h5>
                <p><?php echo translate("There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc"); ?>.</p>
            </li>
            <li>
            	<div class="content-box box-style">
            	
                	<h3 class="head-sub top-radius"><?php echo translate("Basic"); ?></h3>
                    <div class="content-block">
                    	<div class="form-box">
                        	<div class="form-box-basic-left">
                            	<div class="form-box-basic-left-pic"><a href="javascript:void(0)">
                            		<?php if($user['photo_url']=="") { ?>
                            			<img src="<?php echo base_url(); ?>assets/images/no-avatar.png" alt="" />
                            		<?php } else { ?>
                            			<img src="<?php echo $user['photo_url']; ?>" alt="" />
                            		<?php } ?>
                            	</a></div>
                            </div>
                            <div class="form-box-basic-right">
                            	<div class="fullwidth">
                            		<p><?php echo $user['designation']; ?></p><br/>
                            		<p><?php echo $user['brief']; ?></p><br/>
                            		<p><?php echo $user['detail']; ?></p>
                            	</div>
                            </div>
                        </div>		
                    </div><!-- /Basic -->
                    <h3 class="head-sub"><?php echo translate("Instructors"); ?></h3>
                    <div class="content-block">
                    	<div class="form-box">
                    		<p><?php echo $user['welcome_message']; ?></p><br/>
                    		<p><?php echo $user['subtitle_message']; ?></p><br/>
                    	</div>		
                    </div><!-- /Instructors -->
                    
                    <h3 class="head-sub"><?php echo translate("Links"); ?></h3>
                    <div class="content-block">
                    	<div class="form-box">
                    		<?php for ($i=0; $i<4; $i++) { ?>
                    			<p><?php echo $links[$i]; ?></p><br/>
                    		<?php } ?>
                    	</div>		
                    </div><!-- /Links -->
                    
                </div>	
            </li>
        </ul><!-- Left Section -->
    </div>
</div>
<!-- Middle Section End -->

<script type="text/javascript">
    $(function() {
       
		
    });
</script>