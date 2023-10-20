$(function(){
	$(".alert button.close").click(function(){
		$alert_box = $(this).parent();
		$alert_box.fadeOut(300, function(){
			$(this).remove();
		});
	});
});