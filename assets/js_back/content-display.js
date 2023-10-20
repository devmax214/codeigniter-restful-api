var reArrangeAt=568;
var isReArrange=false;	
function reArrange(){
	var wid=$(document).width();
	if(isReArrange && wid>=reArrangeAt){
		var dhtml=$('.instructor-left').html();
		$('.instructor-right').html(dhtml);
		$('.instructor-right').show();
		$('.instructor-left').html('');
		$('.instructor-left').hide();
		isReArrange=false;
		return true;
	}else{
		if(!isReArrange && wid<reArrangeAt){
			var dhtml=$('.instructor-right').html();
			$('.instructor-left').html(dhtml);
			$('.instructor-left').show();
			$('.instructor-right').html('');
			$('.instructor-right').hide();
			isReArrange=true;
			return true;
		}
	}
	return false;
}
$(window).resize(function(){
	reArrange()	;
});

$(function(){reArrange();});