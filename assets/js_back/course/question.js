$(function() {

	var saveCourseComment = function(e) {
		var $target = $(e.target);
		if(e.keyCode == 13 && $target.val() != "") {
			var question_id = $target.attr("id").substring(16);
			$.ajax({
				type : "post",
				url : base_url + "course/addComment/",
				data : {
					"question_id": question_id, 
					"message": $target.val()
				},
				dataType: "json",
				error: function(response) {
					$(".post-question-form .msgbox").html("<div class='alert alert-error'>" + response.responseText + "</div>");
				},
				success: function(data) {
					
					if(data && data.id) {
						var comment_html = '<hr id="hr_course_comment_' + data.id + '"/><li id="course_comment_' + data.id + '"><div class="user-comment-pic"><a href="javascript:void(0)">';
						
						if(data.photo_url != "")
							comment_html += '<img src="' + data.photo_url + '" alt="" />';
						
						comment_html += '</a></div>'
							+ '<div class="fullwidth">'
							+ ' <div class="user-comment-info">'
							+ '  <h5><a href="javascript:void(0)">' + data.first_name + ' ' + data.last_name + '</a>';
						
						if(course_instructor == "1")
							comment_html += '<a href="javascript:void(0)" class="remove-link" onclick="javascript:removeComment(' + data.id + ')">X</a>';
						
						comment_html += '</h5>' 
							+ '  <p>' + data.message + '</p>'
							+ '  <p><span class="time">' + data.time_format + '</span></p>'
	                        + ' </div>'
	                        + '</div></li>';
							
						$("#user_comments_count_" + question_id).after(comment_html);
						$("#user_comments_count_" + question_id + " a").html(data.comments_count + ' Comments');
						
						$target.val("");
					}
					else if(data && data.error) {
						$(".post-question-form .msgbox").html("<div class='alert alert-error'>" + data.error + "</div>");
					}
				}
			});
		}
	};
	
	$("#btn_post_question").click(function(){
		var error = "";
		
		$(".post-question-form .msgbox").html("");
		
		if($("#question_message").val().trim() == "" || $("#question_message").val().trim() == "Enter your Message...") {
			error += "<p>Please enter your message.</p>";
		}
		
		if(error != '') { 
			$(".post-question-form .msgbox").html("<div class='alert alert-error'>" + error + "</div>");
			return false;
		}
		else {
			$.ajax({
				type : "post",
				url : base_url + "course/addQuestion/",
				data : {
					"course_id": $(".post-question-form [name=course_id]").val(), 
					"message": $("#question_message").val(),
					"type": $("#question_type").val()
				},
				dataType: "json",
				error: function(response) {
					$(".post-question-form .msgbox").html("<div class='alert alert-error'>" + response.responseText + "</div>");
				},
				success: function(data) {
					if(data && data.id) {
						var question_html = '<hr id="hr_course_question_' + data.id + '" data-type="' + data.type + '"/>'
							+ '<li id="course_question_' + data.id + '" data-type="' + data.type + '"><div class="user-chat-pic"><a href="javascript:void(0)">';
						
						if(data.photo_url != "")
							question_html += '<img src="' + data.photo_url + '" alt="" />';
						
						question_html += '</a></div>'
							+ '<div class="fullwidth">'
							+ ' <div class="user-chat-info">'
							+ '  <h5><a href="javascript:void(0)">' + data.first_name + ' ' + data.last_name + '</a>';
						
						if(course_instructor == "1")
							question_html += '<a href="javascript:void(0)" class="remove-link" onclick="javascript:removeQuestion(' + data.id + ')">X</a>';
						
						question_html += '</h5>'
							+ '  <p>' + data.message + '</p>'
							+ '  <p><span class="time">' + data.time_format + '</span></p>'
	                        + '  <ul class="user-comment-box" id="user_comments_' + data.id + '">'
	                        + '   <li id="user_comments_count_' + data.id + '"><a href="javascript:void(0)" onclick="javascript:toggleCommentsBox(' + data.id + ');">0 Comments</a></li>'
	                        + '   <hr />'
	                        + '   <li><div class="user-comment-pic"><a href="javascript:void(0)">';
	                    
						if(data.photo_url != "")
							question_html += '<img src="' + data.photo_url + '" alt="" />';
						
						question_html += '</a></div>'
							+ '    <div class="fullwidth">'
	                        + '     <div class="user-comment-info">'
	                        + '      <textarea name="comment_message_' + data.id + '" id="comment_message_' + data.id + '" class="course-comment-message" onfocus="if(this.value == \'Enter your text here\') { this.value = \'\'; }" onblur="if(this.value == \'\') { this.value = \'Enter your text here\'; }">Enter your text here</textarea>'
	                        + '     </div>'
	                        + '    </div>'
	                        + '   </li>'
	                        + '  </ul>'
	                        + ' </div>'
	                        + '</div></li>';
	                        
						$("#course_questions").prepend(question_html);
						
						$("#questions_count_completed").html(data.questions_count_completed);
						$("#questions_count_out").html(data.questions_count_out);
						
						$("#comment_message_" + data.id).keydown(saveCourseComment);
						
						$("#question_message").val("Enter your Message...");
					}
					else if(data && data.error) {
						$(".post-question-form .msgbox").html("<div class='alert alert-error'>" + data.error + "</div>");
					}
				}
			});
		}
	});
	
	$(".course-comment-message").keydown(saveCourseComment);
	
	$("#course_questions_more").click(function(){
		var type = $("#questions_filter").val();
		var i=0;
		
		$("#course_questions li[id^=course_question_]").each(function() {
			
			if($(this).data("type") == type || type == "all") {
				if($("#course_questions_more a").hasClass("opened")) {
					if(i>1) {
						$(this).hide();
						$("#hr_" + $(this).attr("id")).hide();
					}
				}
				else {
					$(this).fadeIn(300);
					$("#hr_" + $(this).attr("id")).fadeIn(300);
				}
				i++;
			}
			else {
				$(this).hide();
				$("#hr_" + $(this).attr("id")).hide();
			}
			
		});
		
		if($("#course_questions_more a").hasClass("opened")) {
			$("#course_questions_more a").html("+ More");
			$("#course_questions_more a").removeClass("opened");
		}
		else {
			$("#course_questions_more a").html("- Less");
			$("#course_questions_more a").addClass("opened");
		}
	});
	
	$("#questions_filter").change(function(){
		var type = $(this).val();
		var i=0;
		
		$("#course_questions li[id^=course_question_]").each(function(){
			
			if($(this).data("type") == type || type == "all") {
				if(i<2) {
					$(this).fadeIn(300);
					$("#hr_" + $(this).attr("id")).fadeIn(300);
				}
				else {
					$(this).hide();
					$("#hr_" + $(this).attr("id")).hide();
				}
				i++;
			}
			else {
				$(this).hide();
				$("#hr_" + $(this).attr("id")).hide();
			}
		});
		
		$("#course_questions_more a").html("+ More");
		$("#course_questions_more a").removeClass("opened");
	});
});

function toggleCommentsBox(question_id) {
	$("#user_comments_" + question_id + " li[id^=course_comment_].hide").each(function(){
		if($(this).is(":visible")) {
			$(this).hide();
			$("#hr_" + $(this).attr("id")).hide();
		}
		else {
			$(this).fadeIn(300);
			$("#hr_" + $(this).attr("id")).fadeIn(300);
		}
	});
}

function removeQuestion(question_id) {
	if(!confirm("Are you sure to delete this queston and all comments of it?"))
		return false;
	
	$(".post-question-form .msgbox").html("");
	
	$.ajax({
		type : "post",
		url : base_url + "course/removeQuestion/" + question_id,
		data : {
			 
		},
		dataType: "json",
		error: function(response) {
			$(".post-question-form .msgbox").html("<div class='alert alert-error'>" + response.responseText + "</div>");
		},
		success: function(data) {
			if(data && data.error) {
				$(".post-question-form .msgbox").html("<div class='alert alert-error'>" + data.error + "</div>");
			}
			else {
				$("#course_question_" + question_id).remove();
				$("#hr_course_question_" + question_id).remove();
				
				$("#questions_count_completed").html(data.questions_count_completed);
				$("#questions_count_out").html(data.questions_count_out);
			}
		}
	});
}

function removeComment(comment_id) {
	if(!confirm("Are you sure to delete this comment?"))
		return false;
	
	$(".post-question-form .msgbox").html("");
	
	$.ajax({
		type : "post",
		url : base_url + "course/removeComment/" + comment_id,
		data : {
			 
		},
		dataType: "json",
		error: function(response) {
			$(".post-question-form .msgbox").html("<div class='alert alert-error'>" + response.responseText + "</div>");
		},
		success: function(data) {
			if(data && data.error) {
				$(".post-question-form .msgbox").html("<div class='alert alert-error'>" + data.error + "</div>");
			}
			else {
				$("#course_comment_" + comment_id).remove();
				$("#hr_course_comment_" + comment_id).remove();
				
				$("#user_comments_count_" + data.question_id + " a").html(data.comments_count + ' Comments');
			}
		}
	});
}
