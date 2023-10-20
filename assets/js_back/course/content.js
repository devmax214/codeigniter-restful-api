/**
 * course content scripts
 * Created by: arangde
 * Date: 11/24/13
 */
$(function() {
	/* 
	 * Dialog lecture content 
	 */
	$( "#dialog-lec-type-pu" ).dialog({
        autoOpen: false,
        draggable: false,
		resizable: false,
		width: 400,
		minHeight: false,
		modal: true,
		close: function() {
			$(".opener-lec-type-pu.modal-open").removeClass("modal-open");
		}
    });
	
	$( ".opener-lec-type-pu" ).click(function() {
 		$(".opener-lec-type-pu.modal-open").removeClass("modal-open");
 		$(this).addClass("modal-open");
 		
 		$("form[name=form-lecture-content]").find("input[name=lecture_content_url]").val("Links");
 		$("form[name=form-lecture-content]").find("input[name=lecture_content_file]").val("");
 		
 		if($(this).data("type") == "video") {
 			$("form[name=form-lecture-content]").find("input[name=lecture_content_file]").hide();
 		}
 		else {
 			$("form[name=form-lecture-content]").find("input[name=lecture_content_file]").show();
 		}
	 	
        $( "#dialog-lec-type-pu .msgbox" ).html("");
        $( "#dialog-lec-type-pu" ).dialog( "open" );
        return false;
    });

	$(window).resize(function(){
		$( "#dialog-lec-type-pu" ).dialog( "option", "position", { my: "center", at: "center", of: window } );
	});
	
	/*
	 * Date time picker
	 */
	$("#live_session_live_date").datepicker({
		dateFormat:"yy.mm.dd"
	});
	$("#live_session_time_from").timepicker({
		timeFormat:"HH:mm",
	});
	$("#live_session_time_to").timepicker({
		timeFormat:"HH:mm",
	});
	
	/**
	 * Save sections
	 */
	$("a.btn-save-course-content").click(function(){
			
		$(".section-group").each(function() {
			var num = parseInt($(this).attr("id").substring(8), 10);
			$("#section_title_" + num).val($(this).find(".section-title").html());
		});
		
		$(".lecture-tab-block").each(function() {
			var num = $(this).attr("id").substring(8);
			$("#lecture_title_" + num).val($(this).find(".lecture-title").html());
		});
		
		$("#cmd").val("update");
		$("form[name=form-course-content]").submit();
		
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
	    +				'<input type="text" id="section_title_' + num + '" name="section_title[]" value="Section Name" onfocus="if(this.value == \'Section Name\') { this.value = \'\'; }" onblur="if(this.value == \'\') { this.value = \'Section Name\'; }" />'
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
	
	$("#section_" + num).find(".view-head-s-edit").fadeIn(300);
	
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
	
}


/* lectures */

function addLectureBox() {
	if($(".section-group.opened").length==0) {
		alert("Please select a section to add lecture");
		return false;
	}
	
	var section_num = parseInt($(".section-group.opened").last().attr("id").substring(8), 10), 
		lecture_num;
	
	if($("#section_" + section_num).find(".lecture-tab-block").length > 0) {
		lecture_num = $("#section_" + section_num).find(".lecture-tab-block").last().attr("id").substring(8);
		lecture_num = parseInt(lecture_num.substring(lecture_num.indexOf("_") + 1), 10);
		lecture_num = lecture_num>0 ? lecture_num+1 : 1;
	}
	else {
		lecture_num = 1;
	}
	
	var num = section_num + "_" + lecture_num;
	
	var lecture_html = '<div class="lecture-tab-block" id="lecture_' + num + '">'
		+ '<div class="view-head-l hide">'
	    +	'<span>Lecture' + lecture_num + ':</span> <span class="lecture-title"></span>'
	    +	'<a href="javascript:void(0)" onclick="javascript:openLectureBox(\'' + num + '\')"><span class="icon-edit"></span></a>'
	    +	'<a href="javascript:void(0)" class="btn-add-lec" onclick="javascript:addLectureContent(\'' + num + '\')">+ Add Content</a>'
	    + '</div>'
	    + '<div class="view-head-l-edit hide">'
	    + 	'<div class="edit-l-title fullwidth">'
	    +		'<div class="edit-left">Lecture<?php echo $lecture_num + 1; ?>:</div>'
	    +		'<div class="fullwidth">'
	    +			'<div class="edit-right">'
	    +				'<input type="text" id="lecture_title_' + num + '" name="lecture_title_' + section_num + '[]" value="Lecture Name" onfocus="if(this.value == \'Lecture Name\') { this.value = \'\'; }" onblur="if(this.value == \'\') { this.value = \'Lecture Name\'; }" />'
	    +			'</div>'
	    +		'</div>'
	    +		'<div class="edit-bottom">'
	    +			'<a href="javascript:void(0)" class="btn-blue-cls" onclick="javascript:saveLectureBox(\'' + num + '\')">Save</a>' 
	    + 			'<span class="cancel-txt">or <a href="javascript:void(0)" onclick="javascript:closeLectureBox(\'' + num + '\')">Cancel</a></span>'        
	    +			'<a href="javascript:void(0)" onclick="javascript:deleteLectureBox(\'' + num + '\')" class="btn-delete-lecture hide"><span class="icon-delete"></span></a>'
	    +		'</div>'
	    + 	'</div>'
	    + 	'<div class="edit-l-type fullwidth hide">'
	    +		'<span>Lecture' + lecture_num + ':</span> <span class="lecture-title"></span>'
	    +		'<div class="lec-cont-type">'
	    +			'<h2>Select Content type</h2>'
	    +			'<a href="javascript:void(0)" class="lecture-type-icn opener-lec-type-pu" data-type="video"><span>Video</span></a>'
	    +			'<a href="javascript:void(0)" class="lecture-type-icn opener-lec-type-pu" data-type="audio"><span>Audio</span></a>'
	    +			'<a href="javascript:void(0)" class="lecture-type-icn opener-lec-type-pu" data-type="presentation"><span>Presentation</span></a>'
	    +			'<a href="javascript:void(0)" class="lecture-type-icn opener-lec-type-pu" data-type="document"><span>Document</span></a>'
	    +			'<a href="javascript:void(0)" class="lecture-type-icn opener-lec-type-pu" data-type="text"><span>Text</span></a>'
	    +			'<div class="clear"></div>'
	    +			'<div class="lecture-content-files hide pull-left"><h2>Content files</h2></div>'
	    +			'<div class="lecture-content-button pull-right">'
		+				'<a class="btn-blue-big btn-save-lecture-content" href="javascript:closeLectureContent(\'' + num + '\')">Save</a>'
		+		    '</div>'
	    +		'</div>'
	    +	'</div>'
	    + '</div>'
	    + '</div>';
	
	$(".lecture-tab-block").find(".view-head-l").show();
	$(".lecture-tab-block").find(".view-head-l-edit").hide();
	
	$("#section_" + section_num).append(lecture_html);
	
	$("#lecture_" + num).find(".view-head-l-edit").fadeIn(300);
	
	$("#lecture_" + num).find(".opener-lec-type-pu").click(function() {
 		$(".opener-lec-type-pu.modal-open").removeClass("modal-open");
 		$(this).addClass("modal-open");
 		
 		$("form[name=form-lecture-content]").find("input[name=lecture_content_url]").val("Links");
 		$("form[name=form-lecture-content]").find("input[name=lecture_content_file]").val("");
	 	
 		$("#dialog-lec-type-pu .msgbox").html("");
        $( "#dialog-lec-type-pu" ).dialog( "open" );
        
        return false;
    });
	
}

function openLectureBox(num) {
	$(".lecture-tab-block").find(".view-head-l").show();
	$(".lecture-tab-block").find(".view-head-l-edit").hide();
	$(".lecture-tab-block").find(".edit-l-title").show();
	$(".lecture-tab-block").find(".edit-l-type").hide();
	
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
	
}

function addLectureContent(num) {
	$(".lecture-tab-block").find(".view-head-l").show();
	$(".lecture-tab-block").find(".view-head-l-edit").hide();
	$(".lecture-tab-block").find(".edit-l-title").hide();
	$(".lecture-tab-block").find(".edit-l-type").show();
	
	$("#lecture_" + num).find(".view-head-l").hide();
	$("#lecture_" + num).find(".view-head-l-edit").fadeIn(300);
}

function saveLectureContent() {
	if($("#lecture_content_file").val() == "" && ($("#lecture_content_url").val() == "" || $("#lecture_content_url").val() == "Links")) {
		alert("Please select a file or file url");
		return false;
	}
	
	var target = $(".opener-lec-type-pu.modal-open").first(),
		target_id = target.parents(".lecture-tab-block").attr("id"),
		num = target_id.substring(8),
		content_type = target.data("type"),
		is_upload = ($("#lecture_content_file").val() == "")? "0": "1",
		content_url = ($("#lecture_content_url").val() == "Links")? "": $("#lecture_content_url").val();

	$("form[name=form-lecture-content]").ajaxSubmit({
		type : "post",
		url : base_url + "course/saveContentLecture/",
		data : {"content_type":content_type, "is_upload":is_upload, "content_url":content_url},
		dataType: "json",
		error: function(response) {
			$("#dialog-lec-type-pu .msgbox").html("<div class='alert alert-error'>" + response.responseText + "</div>");
		},
		success: function(data) {
			if(data.error) {
				$("#dialog-lec-type-pu .msgbox").html("<div class='alert alert-error'>" + data.error + "</div>");
			}
			else {
				var c_num = $("#" + target_id + " .lecture-content-url").length;
				
				var content_html = '<p class="lecture-content-url" id="lecture_content_' + num + '_' + c_num + '">'
					+ '<input type="hidden" name="lecture_content_' + num + '[]" value="' + data.content_type + '_' + data.content_url + '" />'
					+ '<span>[' + data.content_type + ']: </span>';
				
				if(data.content_type == 'video')
					content_html += '<span>' + data.content_url + '</span>';
				else
					content_html += '<a href="' + data.content_url + '" target="_blank">' + data.content_filename + '</a>';
				
				content_html += '<a href="javascript:void(0)" onclick="javascript:removeLectureContent(\'' + num + '_' + c_num + '\')" class="remove-link">X</a></p>';
				
				target.parents(".lecture-tab-block").find(".lecture-content-files").append(content_html);
				target.parents(".lecture-tab-block").find(".lecture-content-files").fadeIn(300);
				
				$("#dialog-lec-type-pu .msgbox").html("");
				$("#dialog-lec-type-pu").dialog("close");
			}
		}
	});
}

function removeLectureContent(c_num) {
	if(confirm("Are you sure to delete this content?")) {
		$("#lecture_content_" + c_num).fadeOut(300, function() {
			$(this).remove();
		});
	}
}

function closeLectureContent(num) {
	$("#lecture_" + num).find(".view-head-l").fadeIn(300);
	$("#lecture_" + num).find(".view-head-l-edit").hide();
}