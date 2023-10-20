$(function() {
	
	/*
	 * Course Reviews
	 */
	$("#btn_post_review").click(function(){
		var error = "";
		
		$(".course-review-form .msgbox").html("");
		
		if($("#review_message").val().trim() == "" || $("#review_message").val().trim() == "Enter your Review...") {
			error += "<p>Please enter your review message.</p>";
		}
		
		if(error != '') { 
			$(".course-review-form .msgbox").html("<div class='alert alert-error'>" + error + "</div>");
			return false;
		}
		else {
			$.ajax({
				type : "post",
				url : base_url + "course/addReview/",
				data : $("[name=course-review-form]").serialize(),
				dataType: "json",
				error: function(response) {
					$(".course-review-form .msgbox").html("<div class='alert alert-error'>" + response.responseText + "</div>");
				},
				success: function(data) {
					if(data && data.id) {
						var review_html = '<li id="course_review_' + data.id + '"><div class="reviews-pic"><a href="javascript:void(0)">';
						
						if(data.photo_url != "")
							review_html += '<img src="' + data.photo_url + '" alt="" />';
						
						review_html += '</a></div>'
							+ '<div class="fullwidth">'
							+ ' <div class="reviews-info">'
							+ '  <h5><span class="rev-name">' + data.first_name + ' ' + data.last_name + '</span><span class="rev-star"></span>'
							+ '  <span class="rev-star">';
						
						for(var i=0; i<10; i++) {
							review_html += '<input type="radio" class="star {split:2}" name="review_rating_' + data.id + '" value="' + (i+1) + '" title="Star ' + (i+1) + '"/>';
						}
						
						review_html += '</span></h5>';
							
						if(data.message.length>500) {
							review_html += '<p class="full-message hide">' + data.message + '</p><p class="fixed-message">' + data.message.substring(0, 500) + '</p>'
										+  '<p><span class="time">' + data.date_format + '</span><span class="read-more"><a href="javascript:void(0)" onclick="javascript:toggleFullReview(' + data.id + ')">Read More</a></span></p>';
						}
						else {
							review_html += '<p class="fixed-message">' + data.message + '</p>'
										+  '<p><span class="time">' + data.date_format + '</span></p>';
						}
						
						review_html += '</div></div></li><hr id="hr_course_review_' + data.id + '"/>';
	                        
						$("#course_reviews").prepend(review_html);
						$("input[name=review_rating_" + data.id + "]").rating({split:2}).rating('select', data.rating).rating('readOnly', true);
						
						$("#review_message").val("Enter your Review...");
						$("input[name=review_rating]").rating('select', null);
						
						$(".review_avg_rating").rating('readOnly', false).rating('select', data.course_rating).rating('readOnly', true);
					}
					else if(data && data.error) {
						$(".course-review-form .msgbox").html("<div class='alert alert-error'>" + data.error + "</div>");
					}
				}
			});
		}
	});
	
	$("#course_reviews_more").click(function(){
		var i=0;
		
		$("#course_reviews li[id^=course_review_]").each(function() {
			
			if($("#course_reviews_more a").hasClass("opened")) {
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
		});
		
		if($("#course_reviews_more a").hasClass("opened")) {
			$("#course_reviews_more a").html("+ More");
			$("#course_reviews_more a").removeClass("opened");
		}
		else {
			$("#course_reviews_more a").html("- Less");
			$("#course_reviews_more a").addClass("opened");
		}
	});
	
	/*
	 * User Review
	 */
	$("#btn_post_user_review").click(function(){
		var error = "";
		
		$(".user-review-form .msgbox").html("");
		
		if($("#review_message").val().trim() == "" || $("#review_message").val().trim() == "Enter your Review...") {
			error += "<p>Please enter your review message.</p>";
		}
		
		if(error != '') { 
			$(".user-review-form .msgbox").html("<div class='alert alert-error'>" + error + "</div>");
			return false;
		}
		else {
			$.ajax({
				type : "post",
				url : base_url + "user/addReview/",
				data : $("[name=user-review-form]").serialize(),
				dataType: "json",
				error: function(response) {
					$(".user-review-form .msgbox").html("<div class='alert alert-error'>" + response.responseText + "</div>");
				},
				success: function(data) {
					if(data && data.id) {
						var review_html = '<li id="user_review_' + data.id + '"><div class="reviews-pic"><a href="javascript:void(0)">';
						
						if(data.photo_url != "")
							review_html += '<img src="' + data.photo_url + '" alt="" />';
						
						review_html += '</a></div>'
							+ '<div class="fullwidth">'
							+ ' <div class="reviews-info">'
							+ '  <h5><span class="rev-name">' + data.first_name + ' ' + data.last_name + '</span><span class="rev-star"></span>'
							+ '  <span class="rev-star">';
						
						for(var i=0; i<10; i++) {
							review_html += '<input type="radio" class="star {split:2}" name="review_rating_' + data.id + '" value="' + (i+1) + '" title="Star ' + (i+1) + '"/>';
						}
						
						review_html += '</span></h5>';
							
						if(data.message.length>500) {
							review_html += '<p class="full-message hide">' + data.message + '</p><p class="fixed-message">' + data.message.substring(0, 500) + '</p>'
										+  '<p><span class="time">' + data.date_format + '</span><span class="read-more"><a href="javascript:void(0)" onclick="javascript:toggleUserReview(' + data.id + ')">Read More</a></span></p>';
						}
						else {
							review_html += '<p class="fixed-message">' + data.message + '</p>'
										+  '<p><span class="time">' + data.date_format + '</span></p>';
						}
						
						review_html += '</div></div></li><hr id="hr_user_review_' + data.id + '"/>';
	                        
						$("#user_reviews").prepend(review_html);
						$("input[name=review_rating_" + data.id + "]").rating({split:2}).rating('select', data.rating).rating('readOnly', true);
						
						$("#review_message").val("Enter your Review...");
						$("input[name=review_rating]").rating('select', null);
						
						$(".review_avg_rating").rating('readOnly', false).rating('select', data.user_rating).rating('readOnly', true);
					}
					else if(data && data.error) {
						$(".user-review-form .msgbox").html("<div class='alert alert-error'>" + data.error + "</div>");
					}
				}
			});
		}
	});
	
	$("#user_reviews_more").click(function(){
		var i=0;
		
		$("#user_reviews li[id^=user_review_]").each(function() {
			
			if($("#user_reviews_more a").hasClass("opened")) {
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
		});
		
		if($("#user_reviews_more a").hasClass("opened")) {
			$("#user_reviews_more a").html("+ More");
			$("#user_reviews_more a").removeClass("opened");
		}
		else {
			$("#user_reviews_more a").html("- Less");
			$("#user_reviews_more a").addClass("opened");
		}
	});
	
});

function toggleFullReview(review_id) {
	if($("#course_review_" + review_id + " .full-message").is(":visible")) {
		$("#course_review_" + review_id + " .full-message").hide();
		$("#course_review_" + review_id + " .fixed-message").fadeIn(300);
		$("#course_review_" + review_id + " .read-more a").html("Read More");
	}
	else {
		$("#course_review_" + review_id + " .fixed-message").hide();
		$("#course_review_" + review_id + " .full-message").fadeIn(300);
		$("#course_review_" + review_id + " .read-more a").html("Read Less");
	}
}

function toggleUserReview(review_id) {
	if($("#user_review_" + review_id + " .full-message").is(":visible")) {
		$("#user_review_" + review_id + " .full-message").hide();
		$("#user_review_" + review_id + " .fixed-message").fadeIn(300);
		$("#user_review_" + review_id + " .read-more a").html("Read More");
	}
	else {
		$("#user_review_" + review_id + " .fixed-message").hide();
		$("#user_review_" + review_id + " .full-message").fadeIn(300);
		$("#user_review_" + review_id + " .read-more a").html("Read Less");
	}
}
