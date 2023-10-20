<!-- Middle Section Start -->
<div id="middle-sec3">
  <div id="middle-sec-in3">
      <h5 class="txt-alg"><?php echo (isset($cmd) && $cmd=='add')? 'Add Product': 'Edit Product'; ?></h5>

		<div class="admin-account-box">
			<hr />
			
			<form method="post" name="frm_product" id="frm_product" action="<?php echo base_url('/admin/editProduct/'. $product_id); ?>" enctype="multipart/form-data">
				<input type="hidden" name="cmd" value="<?php echo $cmd; ?>" />
				<div class="msgbox">
					<?php if(isset($msg_error)) { ?>
			            <div class="alert alert-error">
							<?php echo $msg_error; ?>
						</div>
			        <?php } ?>
				</div>
				
			    
		     	<div class="design-tab-wrap">
		        	<label>Product Name</label>
		            <div class="design-tab-right">
		            	<input type="text" name="product_name" id="product_name" value="<?php echo isset($product['product_name'])? htmlspecialchars($product['product_name'], ENT_QUOTES): ''; ?>"/>
			        </div>
		        </div>
		    
		     	<div class="design-tab-wrap">
		        	<label>Description</label>
		            <div class="design-tab-right">
		            	<textarea name="description" id="description"><?php echo isset($product['description'])? htmlspecialchars($product['description'], ENT_QUOTES): ''; ?></textarea>
			        </div>
		        </div>
		    
		     	<div class="design-tab-wrap">
		        	<label>Photos<br />(Select multiple files)</label>
		            <div class="design-tab-right upload-photo">
		            	
	            		<input type="file" name="product_photo[]" id="product_photo" multiple/>
	            		<input type="hidden" name="upload" id="upload" value="0"/><br/>
		            	
		            	<?php if(isset($product_photos) && count($product_photos) > 0) { ?>
		            		<?php foreach($product_photos as $product_photo) { ?>
		            			<a href="javascript:void(0)" class="remove-photo" data-photo_id="<?php echo $product_photo['product_photo_id']; ?>">Remove</a> 
		            			<img src="<?php echo $product_photo['photo_url']; ?>" id="photo_<?php echo $product_photo['product_photo_id']; ?>" class="product-photo"/><br/>
		            		<?php } ?>
		            	<?php } ?>
		            	
		            	<input type="hidden" name="product_photo_ids" id="product_photo_ids" value=""/><br/>
			        </div>
		        </div>
		    
			    <div class="design-tab-wrap edit-page-buttons">
			     	<a href="javascript:void(0)" id="btn_back" class="popup-btn-blue pull-right">Back</a>
			     	<a href="javascript:void(0)" id="btn_save_product" class="popup-btn-blue pull-right">Save</a>
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

		$("a.remove-photo").click(function() {
			if(confirm("Are you sure to delete this photo?")) {
				var photo_id = $(this).data("photo_id");
				$("img#photo_" + photo_id).remove();
				$(this).remove();
			}

			return false;
		});
		
		$("#btn_save_product").click(function(){
			var error = "";
			
			if($("#product_name").val().trim() == "") {
				error += "<p><?php echo translate("You must enter Product name"); ?>.</p>";
			}
			
			if(error != "") {
				$(".msgbox").html("<div class='alert alert-error'>" + error + "</div>");
				return false;
			}
			else {
				if($("#product_photo").val() != "")
					$("#upload").val("1");

				var product_photo_ids = "";
				$(".product-photo").each(function() {
					product_photo_ids += "," + $(this).attr("id").substring(6);
				});

				if(product_photo_ids.length > 0)
					product_photo_ids = product_photo_ids.substring(1);

				$("#product_photo_ids").val(product_photo_ids);
				
				$("#frm_product").submit();
			}
			
			return true;
		});
	});
</script>