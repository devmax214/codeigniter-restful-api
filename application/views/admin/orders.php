<!-- Middle Section Start -->
<div id="middle-sec3">
  <div id="middle-sec-in3">
      <h5 class="txt-alg">Orders</h5>

		<?php 
			$page_links = $pagination->create_links();
			$cur_page = ($pagination->cur_page == 0)? 1: $pagination->cur_page;
		?>
		<div class="admin-account-box">
			<?php if(count($orders) > 0) { ?>
				<a href="javascript:void(0)" id="download_orders_xml">Export Orders to XML</a>
			<?php } ?>
			<hr />
			
			<div class="msgbox">
				<?php if(isset($msg_success)) { ?>
					<div class="alert alert-success">
						<?php echo $msg_success; ?>
					</div>
				<?php } ?>
				
				<?php if(isset($msg_error)) { ?>
		            <div class="alert alert-error">
						<?php echo $msg_error; ?>
					</div>
		        <?php } ?>
			</div>
			
			<form method="post" name="frm_orders" id="frm_ordes" action="">
			
			<div class="search-area">
				View by : <select name="view_by" id="view_by" onchange="changeViewBy()">
					<option value="all">All</option>
					<option <?php echo ($view_by=='confirmed')? 'selected': ''; ?> value="confirmed">Confirmed</option>
					<option <?php echo ($view_by=='not-confirmed')? 'selected': ''; ?> value="not-confirmed">Not confirmed</option>
					<option <?php echo ($view_by=='completed')? 'selected': ''; ?> value="completed">Completed</option>
				</select>
			</div>
			
			<table border="0" cellpadding="4" cellspacing="0" align="center" width="95%" class="account-table">
			  <tr>	
			    <th><input type="checkbox" name="chk_all" id="chk_all" /></th>
			    <th><?php echo translate("#", 'admin'); ?></th>
			    <th><?php echo translate("Product"); ?></th>
			    <th><?php echo translate("Price"); ?></th>
			    <th><?php echo translate("Name"); ?></th>
			    <th><?php echo translate("Address"); ?></th>
			    <th><?php echo translate("Date"); ?></th>
			    <th><?php echo translate("Status"); ?></th>
			    <th></th>
			  </tr>
			 <?php if(count($orders) == 0) { ?>
			  <tr><td colspan="9">No data found</td></tr>
			 <?php } ?>
			 <?php foreach($orders as $i=>$order) { ?>
			  <tr>
			    <td><input type="checkbox" name="export_order[]" class="export-order" id="chk_order_<?php echo $order['order_id']; ?>" value="<?php echo $order['order_id']; ?>"/></td> 
			    <td><?php echo ($cur_page - 1) * $pagination->per_page + $i + 1; ?></td>
			    <td><?php echo $order['product_name']; ?></td>
			    <td><?php echo '$'.$order['total_price']; ?></td>
			    <td><?php echo $order['first_name']. ' '. $order['last_name']; ?></td>
			    <td><?php echo $order['address']. ' '. $order['unit_number']. ' '. $order['postal_code']. ' '. $order['city']. ' '. $order['country_name']; ?></td>
			    <td><?php echo $order['created']; ?></td>
			    <td class="status-cell-<?php echo $order['order_id']; ?>"><?php
			    	if($order['order_status'] == ORDER_STATUS_PUBLIC) 
			    		echo 'Not confirmed';
			    	elseif($order['order_status'] == ORDER_STATUS_CONFIRMED) 
			    		echo '<span class="blue">Confirmed</span>';
			    	elseif($order['order_status'] == ORDER_STATUS_SUCCESSED) 
			    		echo '<span class="green">Completed</span>';
			    ?></td>
			    <td>
			    	<!-- <a href="<?php echo base_url('/admin/order/'. $order['order_id']); ?>" class="edit-link">View</a> -->
			    	<?php if($order['order_status'] == ORDER_STATUS_CONFIRMED) { ?>
			    		<a href="javascript:void(0)" data-order_id="<?php echo $order['order_id']; ?>" class="complete-link" rel="<?php echo base_url('/admin/completeOrder/'. $order['order_id']); ?>">Make complete</a>
			    	<?php } ?>
			    	<a href="javascript:void(0)" class="delete-link" rel="<?php echo base_url('/admin/deleteOrder/'. $order['order_id']); ?>">Delete</a>
			    </td>
			   </tr>
			 <?php } ?>
		
			</table>
			
			</form>
			
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
            if(!confirm("<?php echo translate("Are you sure to delete this order?"); ?>")) {
                return false;
            }

            location.href = $(this).attr("rel");
        });

		$('.complete-link').click(function(){
			var order_id = $(this).data("order_id");
			
            $.ajax({
                url: $(this).attr("rel"),
                type: "POST",
                dataType: 'json',
            	erorr: function(res) {
                	console.log(res.responseText);
            	},
            	success: function(data) {
                	if(data.success) {
                    	$("td.status-cell-" + order_id).html('<span class="green">Completed</span>');
                	}
            	}
            });
        });

        $("#chk_all").change(function() {
            var checked = this.checked;
            $(".export-order").attr("checked", checked);
        });

        $("#download_orders_xml").click(function() {
            var checked_ids = "";

            $(".export-order").each(function() {
                if(this.checked)
                	checked_ids += "," + $(this).attr("id").substring(10);
            });

            if(checked_ids.length > 0) {
                $("#frm_ordes").attr("action", '<?php echo base_url('/admin/exportOrders'); ?>');
                $("#frm_ordes").submit();
            }
            else {
                alert("Please check orders");
                return false;
            }
        });
	});
	
	function changeViewBy() {
		var view_by = $("#view_by").val();
		location.href = '<?php echo base_url('/admin/orders'); ?>/' + view_by;
	}
</script>