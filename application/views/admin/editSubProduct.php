<!-- Middle Section Start -->
<div id="middle-sec3">
  <div id="middle-sec-in3">
      <h5 class="txt-alg"><?php echo (isset($cmd) && $cmd=='add')? 'Add Sub Product': 'Edit Sub Product'; ?></h5>

		<div class="admin-account-box">
			<hr />
			
			<form method="post" name="frm_product" id="frm_product" action="" enctype="multipart/form-data">
				<input type="hidden" name="cmd" value="<?php echo $cmd; ?>" />
				<div class="msgbox">
					<?php if(isset($msg_error)) { ?>
			            <div class="alert alert-error">
							<?php echo $msg_error; ?>
						</div>
			        <?php } ?>
				</div>
				
			    
		     	<div class="design-tab-wrap">
		        	<label>Sub Product Name</label>
		            <div class="design-tab-right">
		            	<input type="text" name="sub_product_name" id="sub_product_name" value="<?php echo isset($sub_product['sub_product_name'])? htmlspecialchars($sub_product['sub_product_name'], ENT_QUOTES): ''; ?>"/>
			        </div>
		        </div>
		    
		     	<div class="design-tab-wrap">
		        	<label>Price</label>
		            <div class="design-tab-right">
		            	$<input type="text" name="price" id="price" class="price" value="<?php echo isset($sub_product['price'])? $sub_product['price']: ''; ?>"/>
			        </div>
		        </div>
		    
				<div class="design-tab-wrap">
		        	<label>Photos count</label>
		            <div class="design-tab-right">
		            	<input type="text" name="photo_count" id="photo_count" class="price" value="<?php echo isset($sub_product['photo_count'])? $sub_product['photo_count']: ''; ?>"/>
			        </div>
		        </div>
		        
		        <div class="design-tab-wrap">
		        	<label>Description</label>
		            <div class="design-tab-right">
		            	<textarea name="description" id="description"><?php echo isset($sub_product['description'])? htmlspecialchars($sub_product['description'], ENT_QUOTES): ''; ?></textarea>
			        </div>
		        </div>
		        
		        <div class="design-tab-wrap">
		        	<label>Photo</label>
		            <div class="design-tab-right upload-photo">
		            	
	            		<input type="file" name="sub_product_photo" id="sub_product_photo" />
	            		<input type="hidden" name="upload" id="upload" value="0"/><br/>
		            	
		            	<?php if(isset($sub_product['sub_product_photo']) && $sub_product['sub_product_photo'] != "") { ?>
		            		<img src="<?php echo $sub_product['sub_product_photo']; ?>" class="product-photo"/><br/>
		            	<?php } ?>
		            </div>
		        </div>
		    
		        <div class="design-tab-wrap edit-page-buttons">
			     	<a href="javascript:void(0)" id="btn_back" class="popup-btn-blue pull-right">Back</a>
			     	<a href="javascript:void(0)" id="btn_save_sub_product" class="popup-btn-blue pull-right">Save</a>
			    </div>
		    
			</form>
		</div> 
    </div>
</div>

<script type="text/javascript">
	$(function() {
		$("#btn_back").click(function(){
			history.back();
		});

		$("#btn_save_sub_product").click(function(){
			var error = "";
			
			if($("#sub_product_name").val().trim() == "") {
				error += "<p><?php echo translate("You must enter Sub Product name"); ?>.</p>";
			}
			else if($("#price").val() == "") {
				error += "<p><?php echo translate("You must enter price"); ?>.</p>";
			}
			
			if(error != "") {
				$(".msgbox").html("<div class='alert alert-error'>" + error + "</div>");
				return false;
			}
			else {
				if($("#sub_product_photo").val() != "")
					$("#upload").val("1");
				
				$("#frm_product").submit();
			}
			
			return true;
		});
	});
</script>