/**
 * course create scripts
 * Created by: arangde
 * Date: 11/24/13
 */
$(function() {
	/**
	 * Check for validation
	 */
	$("a.btn-save").click(function(){

		$(".msgbox").html("");
			
		var error = "";
		if($("#title").val().trim() == "" || $("#title").val().trim() == "Title") {
			error += "<p>Title is required.</p>";
		}
		if($("#sub_title").val().trim() == "" || $("#sub_title").val() == "Sub Title") {
			error += "<p>Sub title is required.</p>";
		}
		
		if(error != "") {
			$(".msgbox").html("<div class='alert alert-error'>" + error + "</div>");

			$('html, body').animate({
		        scrollTop: $(".alert.alert-error").first().offset().top - 60
		    }, 600);
			
			return false;
		}
		else {
			if($("#description").val() == "Course Description")
				$("#description").val("");
			
			$(".section-group").each(function() {
				var num = parseInt($(this).attr("id").substring(8), 10);
				$("#section_title_" + num).val($(this).find(".section-title").html());
			});
			
			$(".lecture-tab-block").each(function() {
				var num = $(this).attr("id").substring(8);
				$("#lecture_title_" + num).val($(this).find(".lecture-title").html());
			});
			
			$("#cmd").val("add");
			$("form[name=form-course]").submit();
		}
		
	});
});

/* sections */

function addSectionBox() {
	var num=0;
	if($(".section-group").length>0)
		num = parseInt($(".section-group").last().attr("id").substring(8), 10);
	num = num>0 ? num+1: 1;
	
	var section_html = '<div id="section_' + num + '" class="section-group">'
		+ '<div class="section-tab-block">'
		+ 	'<div class="view-head-s hide">'
	    +		'<span>Section' + num + ':</span> <span class="section-title"></span>'
	    +		'<a href="javascript:void(0)" onclick="javascript:openSectionBox(' + num + ')"><span class="icon-edit"></span></a>'
	    + 	'</div>'
	    + 	'<div class="view-head-s-edit hide">'
	    +		'<div class="edit-left">Section' + num + ':</div>'
	    +		'<div class="fullwidth">'
	    +			'<div class="edit-right">'
	    +				'<input type="text" class="section_title_input" id="section_title_' + num + '" name="section_title[]" value="Section Name" onfocus="if(this.value == \'Section Name\') { this.value = \'\'; }" onblur="if(this.value == \'\') { this.value = \'Section Name\'; }" />'
	    +			'</div>'
	    +		'</div>'
	    +		'<div class="edit-bottom">'
	    +			'<a href="javascript:void(0)" class="btn-blue-cls" onclick="javascript:saveSectionBox(' + num + ')">Save</a>' 
	    + 			'<span class="cancel-txt">or <a href="javascript:void(0)" onclick="javascript:closeSectionBox(' + num + ')">Cancel</a></span>'        
	    +			'<a href="javascript:void(0)" onclick="javascript:deleteSectionBox(' + num + ')" class="btn-delete-section hide"><span class="icon-delete"></span></a>'
	    +		'</div>'
	    + 	'</div>'
	    + '</div></div>';

	$(".section-tab-block").find(".view-head-s").show();
	$(".section-tab-block").find(".view-head-s-edit").hide();
	
	$(".section-buttons").before(section_html);
	
	$("#section_" + num).find(".view-head-s").fadeIn(300);
	
	$(".section-group.opened").removeClass("opened");
	$("#section_" + num).addClass("opened");
}

function openSectionBox(num) {
	$(".section-tab-block").find(".view-head-s").show();
	$(".section-tab-block").find(".view-head-s-edit").hide();
	
	$(".lecture-tab-block").find(".view-head-l").show();
	$(".lecture-tab-block").find(".view-head-l-edit").hide();
	
	$("#section_" + num).find(".view-head-s").hide();
	$("#section_" + num).find(".view-head-s-edit").fadeIn(300);
	
	$(".section-group.opened").removeClass("opened");
	$("#section_" + num).addClass("opened");
}

function saveSectionBox(num) {
	if($("#section_title_" + num).val().trim() == "" || $("#section_title_" + num).val().trim() == "Section Name") {
		alert("Please enter Section Name");
		$("#section_title_" + num).focus();
		return false;
	}
	else {
		$("#section_" + num).find(".section-title").html($("#section_title_" + num).val().trim());
		$("#section_" + num).find(".view-head-s").fadeIn(300);
		$("#section_" + num).find(".view-head-s-edit").hide();
		$("#section_" + num).find(".btn-delete-section").show();
		$("#section_" + num).removeClass("opened");
		/*
		var id = ($("#section_" + num).data("id"))? $("#section_" + num).data("id"): "";
		
		$.ajax({
			type : "post",
			url : base_url + "course/saveSection/" + id,
			data : {
				"course_id": $("#time_id").val(), 
				"section_title": $("#section_title_" + num).val().trim(),
				"order_id": num
			},
			dataType: "json",
			error: function(response) {
				console.log(response.responseText);
			},
			success: function(data) {
				if(data && data.id) {
					$("#section_" + num).data("id", data.id);
					$("#section_" + num).find(".section-title").html(data.title);
					$("#section_" + num).find(".view-head-s").fadeIn(300);
					$("#section_" + num).find(".view-head-s-edit").hide();
					$("#section_" + num).find(".btn-delete-section").show();
					$("#section_" + num).removeClass("opened");
				}
				else if(data && data.error) {
					console.log(data.error);
				}
			}
		});
		*/
	}
}

function closeSectionBox(num) {
	$("#section_" + num).find(".view-head-s").fadeIn(300);
	$("#section_" + num).find(".view-head-s-edit").hide();
}

function deleteSectionBox(num) {
	
	if(confirm("Are you sure to delete this section?")) {
		$("#section_" + num).fadeOut(300, function() {
			$(this).remove();
		});
	}
	/*
	var id = ($("#section_" + num).data("id"))? $("#section_" + num).data("id"): "";
	
	if(id=="") {
		return false;
	}
	
	if(confirm("Are you sure to delete this section?")) {
		$.ajax({
			method : "post",
			url : base_url + "course/deleteSection/" + id,
			dataType: "json",
			error: function(response) {
				console.log(response.ResponseText);
			},
			success: function(data) {
				console.log(data);
				if(data && data.id) {
					$("#section_" + num).fadeOut(300, function() {
						$(this).remove();
					});
					
				}
			}
		});
	}
	*/
}


/* lectures */

function addLectureBox() {
	if($(".section-group.opened").length==0) {
		alert("Please select a section to add lecture");
		return false;
	}
	/*
	if($(".section-group.opened").data("id") == "" || $(".section-group.opened").data("id") == undefined) {
		alert("Please save the section, first");
		return false;
	}
	*/
	var section_num = parseInt($(".section-group.opened").last().attr("id").substring(8), 10), 
		lecture_num;
	
	if($("#section_" + section_num).find(".lecture-tab-block").length > 0) {
		lecture_num = $("#section_" + section_num).find(".lecture-tab-block").last().attr("id").substring(8);
		lecture_num = parseInt(lecture_num.substring(lecture_num.indexOf("_") + 1), 10);
		lecture_num = lecture_num>0 ? lecture_num+1: 1;
	}
	else {
		lecture_num = 1;
	}
	
	var num = section_num + "_" + lecture_num;
	
	var lecture_html = '<div class="lecture-tab-block" id="lecture_' + num + '">'
		+ '<div class="view-head-l hide">'
	    +	'<span>Lecture' + lecture_num + ':</span> <span class="lecture-title"></span>'
	    +	'<a href="javascript:void(0)" onclick="javascript:openLectureBox(\'' + num + '\')"><span class="icon-edit"></span></a>'
	    + '</div>'
	    + '<div class="view-head-l-edit hide">'
	    +	'<div class="edit-left">Lecture' + lecture_num + ':</div>'
	    +	'<div class="fullwidth">'
	    +		'<div class="edit-right">'
	    +			'<input type="text" class="lecture_title_input" id="lecture_title_' + num + '" name="lecture_title_' + section_num + '[]" value="Lecture Name" onfocus="if(this.value == \'Lecture Name\') { this.value = \'\'; }" onblur="if(this.value == \'\') { this.value = \'Lecture Name\'; }" />'
	    +		'</div>'
	    +	'</div>'
	    +	'<div class="edit-bottom">'
	    +		'<a href="javascript:void(0)" class="btn-blue-cls" onclick="javascript:saveLectureBox(\'' + num + '\')">Save</a>' 
	    + 		'<span class="cancel-txt">or <a href="javascript:void(0)" onclick="javascript:closeLectureBox(\'' + num + '\')">Cancel</a></span>'        
	    +		'<a href="javascript:void(0)" onclick="javascript:deleteLectureBox(\'' + num + '\')" class="btn-delete-lecture hide"><span class="icon-delete"></span></a>'
	    +	'</div>'
	    + '</div>'
	    + '</div>';
                            
	$(".lecture-tab-block").find(".view-head-l").show();
	$(".lecture-tab-block").find(".view-head-l-edit").hide();
	
	$("#section_" + section_num).append(lecture_html);
	
	$("#lecture_" + num).find(".view-head-l-edit").fadeIn(300);
}

function openLectureBox(num) {
	$(".lecture-tab-block").find(".view-head-l").show();
	$(".lecture-tab-block").find(".view-head-l-edit").hide();
	
	$("#lecture_" + num).find(".view-head-l").hide();
	$("#lecture_" + num).find(".view-head-l-edit").fadeIn(300);
}

function saveLectureBox(num) {
	if($("#lecture_title_" + num).val().trim() == "" || $("#lecture_title_" + num).val().trim() == "Lecture Name") {
		alert("Please enter Lecture Name");
		$("#lecture_title_" + num).focus();
		return false;
	}
	else {
		var lecture_num = num.substring(num.indexOf("_") + 1),
			section_num = parseInt(num.substring(num.indexOf("_") - 1), 10);
		
		$("#lecture_" + num).find(".lecture-title").html($("#lecture_title_" + num).val().trim());
		$("#lecture_" + num).find(".view-head-l").fadeIn(300);
		$("#lecture_" + num).find(".view-head-l-edit").hide();
		$("#lecture_" + num).find(".btn-delete-lecture").show();

		/*
		var id = ($("#lecture_" + num).data("id"))? $("#lecture_" + num).data("id"): "",
			lecture_num = num.substring(num.indexOf("_") + 1),
			section_id = $(".section-group.opened").data("id");
		
		$.ajax({
			type : "post",
			url : base_url + "course/saveLecture/" + id,
			data : {
				"section_id": section_id, 
				"lecture_title": $("#lecture_title_" + num).val().trim(),
				"order_id": lecture_num
			},
			dataType: "json",
			error: function(response) {
				console.log(response.responseText);
			},
			success: function(data) {
				if(data && data.id) {
					$("#lecture_" + num).data("id", data.id);
					$("#lecture_" + num).find(".lecture-title").html(data.title);
					$("#lecture_" + num).find(".view-head-l").fadeIn(300);
					$("#lecture_" + num).find(".view-head-l-edit").hide();
					$("#lecture_" + num).find(".btn-delete-lecture").show();
				}
				else if(data && data.error) {
					console.log(data.error);
				}
			}
		});
		*/
	}
}

function closeLectureBox(num) {
	$("#lecture_" + num).find(".view-head-l").fadeIn(300);
	$("#lecture_" + num).find(".view-head-l-edit").hide();
}

function deleteLectureBox(num) {
	if(confirm("Are you sure to delete this lecture?")) {
		$("#lecture_" + num).fadeOut(300, function() {
			$(this).remove();
		});
	}
	/*
	var id = ($("#lecture_" + num).data("id"))? $("#lecture_" + num).data("id"): "";
	
	if(id=="") {
		return false;
	}
	
	if(confirm("Are you sure to delete this lecture?")) {
		$.ajax({
			method : "post",
			url : base_url + "course/deleteLecture/" + id,
			dataType: "json",
			error: function(response) {
				console.log(response.ResponseText);
			},
			success: function(data) {
				console.log(data);
				if(data && data.id) {
					$("#lecture_" + num).fadeOut(300, function() {
						$(this).remove();
					});
					
				}
			}
		});
	}
	*/
}

function addLectureContent(num) {
	$("#lecture_" + num).find(".view-head-l").fadeIn(300);
	$("#lecture_" + num).find(".view-head-l-edit").hide();
}