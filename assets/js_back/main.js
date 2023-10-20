/**
 * main scripts
 * Created by: arangde
 * Date: 11/24/13
 */
$(function() {
	
	/*
	 * Remove alert messages
	 */
	setTimeout(function(){
		$("div.alert").fadeOut(600, function() {
			$(this).remove();
		})
	}, 5000);
	
	$(".discover-btn").click(function(){
		$("#frm_index_search").attr("action", $(this).attr("ref"));
		submitSearchForm();
	});
	
	
	
});

function submitSearchForm() {
	if($("#course_name").val() == "Course Name") {
		$("#course_name").val("");
	}
	
	$("#frm_index_search").submit();
}

function changeUserLanguage(language_id) {
	var return_url = location.href;
	location.href = base_url + 'index/language/' + language_id + '/';
}