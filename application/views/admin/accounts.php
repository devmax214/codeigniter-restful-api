<!-- Middle Section Start -->
<div id="middle-sec3">
  <div id="middle-sec-in3">
      <h5 class="txt-alg">Accounts</h5>

		<?php 
			$page_links = $pagination->create_links();
			$cur_page = ($pagination->cur_page == 0)? 1: $pagination->cur_page;
		?>
		<div class="admin-account-box">
			<hr />
			
			<div class="msgbox"></div>
			
			<table border="0" cellpadding="4" cellspacing="0" align="center" width="95%" class="account-table">
			  <tr>	
			    <th><?php echo translate("No", 'admin'); ?></th>
			    <th><?php echo translate("E-mail"); ?></th>
			    <th><?php echo translate("Name"); ?></th>
			    <th><?php echo translate("Password", 'admin'); ?></th>
			    <th><?php echo translate("Delete", 'admin'); ?></th>
			  </tr>
			 <?php if(count($users) == 0) { ?>
			  <tr><td colspan="5">No data found</td></tr>
			 <?php } ?>
			 <?php foreach($users as $i=>$user) { ?>
			  <tr>
			    <td><?php echo ($cur_page - 1) * $pagination->per_page + $i + 1; ?></td>
			    <td><a href="#"><?php echo $user['email_address']; ?></a></td>
			    <td><?php echo $user['first_name']. ' ' . $user['last_name']; ?></td>
			    <td><a href="javascript:void(0)" data-user_id="<?php echo $user['user_id']; ?>" class="reset_pass_link">Reset</a></td>
			    <td><a href="javascript:void(0)" class="delete-link" rel="<?php echo base_url('/admin/deleteAccount/'. $user['user_id']); ?>">Delete</a></td>
			   </tr>
			 <?php } ?>
		
			</table>
			<div class="pager-wrap centered">
				<ul class="pager-container">
			    	<?php echo $page_links; ?>
			    </ul>
			    
			</div>
			
			<div id="dialog_reset_pass" class="" title="Change Password">
		    	<div class="tc-popup">
		    		<form id="frm_reset_pass" method="post">
			            <div class="tc-popup-in">
			            	<div class="msgbox"></div>
			                <input type="password" placeholder="Password" name="password" id="password" value="">
		                    <input type="password" placeholder="Re-enter Password" name="confirm_password" id="confirm_password" value="">
		                </div>
			        </form>
			    </div>
		        <div class="centered"><a href="javascript:void(0)" class="popup-btn-blue btn-reset-pass"><?php echo translate("Save"); ?></a></div>
		    </div>
		</div>

    <!-- Left Section --> 
  </div>
</div>
<!-- Middle Section End --> 

<script type="text/javascript">
    $(function() {
		$("#dialog_reset_pass").dialog({
            autoOpen: false,
            draggable: false,
			resizable: false,
			width: 400,
			minHeight: false,
			modal: true
        });

        $('.delete-link').click(function(){
            if(!confirm("<?php echo translate("Are you sure to delete this account?"); ?>")) {
                return false;
            }

            location.href = $(this).attr("rel");
        });
        
 		$( ".reset_pass_link").click(function() {
 			$("#frm_reset_pass .msgbox").html("");
 			$("#password").val("");
 			$("#confirm_password").val("");
 			$("#dialog_reset_pass").dialog("open");

            var user_id = $(this).data("user_id");
            $(".btn-reset-pass").click(function(){
            	$("#frm_reset_pass .msgbox").html("");
            	var error = "";
				if($("#password").val() == "") {
					error += "<p><?php echo translate("Password is required", 'admin'); ?>.</p>";
				}
				if($("#password").val().length<6) {
					error += "<p><?php echo translate("You must enter password with 6 characters at least", 'admin'); ?>.</p>";
				}
				if($("#password").val() != $("#confirm_password").val()) {
					error += "<p><?php echo translate("You must re-type password", 'admin'); ?>.</p>";
				}
				if(error != "") 
					$("#frm_reset_pass .msgbox").html("<div class='alert alert-error'>" + error + "</div>");
				else {
					$.ajax({
						"type": "post",
						"url": "<?php echo base_url('/admin/resetPass'); ?>/" + user_id,
						"data": {"password": $("#password").val()},
						"dataType": "json",
						"error": function(res) {
							$("#frm_reset_pass .msgbox").html("<div class='alert alert-error'>" + res.responseText + "</div>");
						},
						"success": function(data) {
							if(data.error)
								$("#frm_reset_pass .msgbox").html("<div class='alert alert-error'>" + data.error + "</div>");
							else if(data.success) {
								$("#frm_reset_pass .msgbox").html("<div class='alert alert-success'><?php echo translate("Password has been reset successfully", 'admin'); ?>!</div>");
								setTimeout(function() {
									$("#dialog_reset_pass").dialog("close");
								}, 3000);
							}
						}
					});
				}
            });
            
            return false;
        });

    });

    function disableTeacher(user_id) {
		$(".msgbox").html("");
		
		$.ajax({
			"url": "<?php echo base_url('/admin/disableTeacher'); ?>/" + user_id,
			"dataType": "json",
			"error": function(res) {
				$(".msgbox").html("<div class='alert alert-error'>" + res.responseText + "</div>");
			},
			"success": function(data) {
				if(data.error)
					$(".msgbox").html("<div class='alert alert-error'>" + data.error + "</div>");
				else if(data.success) {
					$(".msgbox").html("<div class='alert alert-success'>Data has been changed successfully!</div>");
					$("#teacher_cell_" + user_id).removeClass("mention-active");
					//$("#teacher_cell_" + user_id).html('Student <a href="javascript:void(0)" onclick="enableTeacher(' + user_id + ')">Enable</a>');
					$("#teacher_cell_" + user_id).html('Student');
				}
			}
		});
	}

	function enableTeacher(user_id) {
		$(".msgbox").html("");
		
		$.ajax({
			"url": "<?php echo base_url('/admin/enableTeacher'); ?>/" + user_id,
			"dataType": "json",
			"error": function(res) {
				$(".msgbox").html("<div class='alert alert-error'>" + res.responseText + "</div>");
			},
			"success": function(data) {
				if(data.error)
					$(".msgbox").html("<div class='alert alert-error'>" + data.error + "</div>");
				else if(data.success) {
					$(".msgbox").html("<div class='alert alert-success'>Data has been changed successfully!</div>");
					$("#teacher_cell_" + user_id).addClass("mention-active");
					$("#teacher_cell_" + user_id).html('Teacher <a href="javascript:void(0)" onclick="disableTeacher(' + user_id + ')">Disable</a>');
				}
			}
		});
	}

	function changeViewBy() {
		var view_by = $("#view_by").val();
		location.href = '<?php echo base_url('/admin/accounts'); ?>/' + view_by;
	}
</script>