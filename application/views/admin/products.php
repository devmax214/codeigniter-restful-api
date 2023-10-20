<!-- Middle Section Start -->
<div id="middle-sec3">
  <div id="middle-sec-in3">
      <h5 class="txt-alg">Products</h5>

		<?php 
			$page_links = $pagination->create_links();
			$cur_page = ($pagination->cur_page == 0)? 1: $pagination->cur_page;
		?>
		<div class="admin-account-box">
			<div class="row-add">
				<a href="<?php echo base_url('/admin/editProduct'); ?>">New Product</a>
			</div>
			<hr />
			
			<div class="msgbox"></div>
			
			<table border="0" cellpadding="4" cellspacing="0" align="center" width="95%" class="account-table">
			  <tr>	
			    <th><?php echo translate("#", 'admin'); ?></th>
			    <th><?php echo translate("Name"); ?></th>
			    <th><?php echo translate("Created", 'admin'); ?></th>
			    <th></th>
			  </tr>
			 <?php if(count($products) == 0) { ?>
			  <tr><td colspan="4">No data found</td></tr>
			 <?php } ?>
			 <?php foreach($products as $i=>$product) { ?>
			  <tr>
			    <td><?php echo ($cur_page - 1) * $pagination->per_page + $i + 1; ?></td>
			    <td><?php echo $product['product_name']; ?></td>
			    <td><?php echo $product['created']; ?></td>
			    <td class="actions">
			    	<a href="<?php echo base_url('/admin/editProduct/'. $product['product_id']); ?>">Edit</a>
				    <a href="<?php echo base_url('/admin/subProducts/'. $product['product_id']); ?>">Sub Products</a>
			    	<?php if($product['not_editable']) { ?>
			    		
			    	<?php } else { ?>
				    	<a href="javascript:void(0)" class="delete-link" rel="<?php echo base_url('/admin/deleteProduct/'. $product['product_id']); ?>">Delete</a>
			    	<?php } ?>
			    </td>
			   </tr>
			 <?php } ?>
		
			</table>
			<div class="pager-wrap centered">
				<ul class="pager-container">
			    	<?php echo $page_links; ?>
			    </ul>
			    
			</div>
			
		</div>

    <!-- Left Section --> 
  </div>
</div>
<!-- Middle Section End --> 

<script type="text/javascript">
	$(document).ready(function() {

		$('.delete-link').click(function(){
            if(!confirm("<?php echo translate("Are you sure to delete this product?"); ?>")) {
                return false;
            }

            location.href = $(this).attr("rel");
        });
	});
	
	function changeViewBy() {
		var view_by = $("#view_by").val();
		location.href = '<?php echo base_url('/admin/products'); ?>/' + view_by;
	}
</script>