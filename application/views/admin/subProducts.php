<!-- Middle Section Start -->
<div id="middle-sec3">
  <div id="middle-sec-in3">
      <h5 class="txt-alg">Product - <?php echo $product['product_name']; ?></h5>

		<div class="admin-account-box">
			<div class="row-add">
				<a href="<?php echo base_url('/admin/editSubProduct/'. $product['product_id']); ?>">New Sub Product</a>
			</div>
			<hr />
			
			<div class="msgbox"></div>
			
			<table border="0" cellpadding="4" cellspacing="0" align="center" width="95%" class="account-table">
			  <tr>	
			    <th><?php echo translate("#", 'admin'); ?></th>
			    <th><?php echo translate("Name"); ?></th>
			    <th><?php echo translate("Price", 'admin'); ?></th>
			    <th><?php echo translate("Photo count", 'admin'); ?></th>
			    <th></th>
			  </tr>
			 <?php if(count($sub_products) == 0) { ?>
			  <tr><td colspan="4">No data found</td></tr>
			 <?php } ?>
			 <?php foreach($sub_products as $i=>$sub_product) { ?>
			  <tr>
			    <td><?php echo $i + 1; ?></td>
			    <td><?php echo $sub_product['sub_product_name']; ?></td>
			    <td><?php echo '$'.$sub_product['price']; ?></td>
			    <td><?php echo $sub_product['photo_count']; ?></td>
			    <td class="actions">
			    	<a href="<?php echo base_url('/admin/editSubProduct/'. $product['product_id']. '/'. $sub_product['sub_product_id']); ?>">Edit</a>
			    	<a href="javascript:void(0)" class="delete-link" rel="<?php echo base_url('/admin/deleteSubProduct/'. $product['product_id']. '/'. $sub_product['sub_product_id']); ?>">Delete</a>
			    </td>
			   </tr>
			 <?php } ?>
		
			</table>
			
		</div>

    <!-- Left Section --> 
  </div>
</div>
<!-- Middle Section End --> 

<script type="text/javascript">
	$(document).ready(function() {

		$('.delete-link').click(function(){
            if(!confirm("<?php echo translate("Are you sure to delete this sub product?"); ?>")) {
                return false;
            }

            location.href = $(this).attr("rel");
        });
	});
	
</script>