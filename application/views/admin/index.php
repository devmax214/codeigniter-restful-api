
<!-- Middle Section Start -->
<div id="middle-sec3">
  <div id="middle-sec-in3">
      <h5 class="txt-alg"><?php echo translate("Admin","admin"); ?></h5>
      
      <div class="">
          <div id="tabs">
            <ul>
              <?php foreach($tabs as $tab_key=>$tab_name) { ?>
                <li><a href="#tabs-<?php echo $tab_key; ?>"><?php echo $tab_name; ?></a></li>
              <?php } ?>
            </ul>
            <?php foreach($tabs as $tab_key=>$tab_name) { ?>
	            <div id="tabs-<?php echo $tab_key; ?>" class="fullwidth">
	            <?php
	            	echo $this->load->view('admin/'. $tab_key);
	            ?>
				</div>
			<?php } ?>
            
           </div>
          </div>
        
      </li>
      
    </ul>
    <!-- Left Section --> 
  </div>
</div>
<!-- Middle Section End --> 
