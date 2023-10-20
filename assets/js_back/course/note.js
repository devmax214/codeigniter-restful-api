$(function() {

	$("#btn_post_note").click(function(){
		var error = "";
		
		$(".post-note-form .msgbox").html("");
		
		if($("#note_message").val().trim() == "" || $("#note_message").val().trim() == "Enter your Message...") {
			error += "<p>Please enter your message.</p>";
		}
		
		if(error != '') { 
			$(".post-note-form .msgbox").html("<div class='alert alert-error'>" + error + "</div>");
			return false;
		}
		else {
			$.ajax({
				type : "post",
				url : base_url + "course/addNote/",
				data : {
					"course_id": $(".post-note-form [name=course_id]").val(), 
					"note": $("#note_message").val()
				},
				dataType: "json",
				error: function(response) {
					$(".post-note-form .msgbox").html("<div class='alert alert-error'>" + response.responseText + "</div>");
				},
				success: function(data) {
					if(data && data.id) {
						var note_html = '<hr id="hr_course_note_' + data.id + '"/>'
							+ '<li id="course_note_' + data.id + '">'
							+ '<div class="fullwidth">'
							+ ' <div class="user-chat-info">'
							+ '  <p>' + data.note + '</p>'
							+ '  <p><span class="time">' + data.time_format + '</span></p>'
	                        + ' </div>'
	                        + '</div></li>';
	                        
						$("#course_notes").prepend(note_html);
						
						$("#note_message").val("Enter your Message...");
					}
					else if(data && data.error) {
						$(".post-note-form .msgbox").html("<div class='alert alert-error'>" + data.error + "</div>");
					}
				}
			});
		}
	});
	
	$("#course_notes_more").click(function(){
		var i=0;
		
		$("#course_notes li[id^=course_note_]").each(function() {
			if($("#course_notes_more a").hasClass("opened")) {
				if(i>2) {
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
		
		if($("#course_notes_more a").hasClass("opened")) {
			$("#course_notes_more a").html("+ More");
			$("#course_notes_more a").removeClass("opened");
		}
		else {
			$("#course_notes_more a").html("- Less");
			$("#course_notes_more a").addClass("opened");
		}
	});
	
	$("#search_note").keydown(function(e) {
		var $target = $(e.target);
		if(e.keyCode == 13 && $target.val() != "") {
			var search_note = $(this).val();
			
			if(search_note == "Search") {
				search_note = "";
			}
			
			$("#course_notes").html("");
			$("#course_notes_more").hide();
			
			$.ajax({
				type : "post",
				url : base_url + "course/filterNotes/",
				data : {
					"course_id": $(".post-note-form [name=course_id]").val(), 
					"search_note": search_note
				},
				dataType: "json",
				error: function(response) {
					$(".post-note-form .msgbox").html("<div class='alert alert-error'>" + response.responseText + "</div>");
				},
				success: function(data) {
					if(data && data.length>0) {
						for(var i=0; i<data.length; i++) {
							var note_html = '<hr id="hr_course_note_' + data[i].id + '"/>'
								+ '<li id="course_note_' + data[i].id + '">'
								+ '<div class="fullwidth">'
								+ ' <div class="user-chat-info">'
								+ '  <p>' + data[i].note + '</p>'
								+ '  <p><span class="time">' + data[i].time_format + '</span></p>'
		                        + ' </div>'
		                        + '</div></li>';
							$("#course_notes").append(note_html);
						}
	        			
						if(data.length>3)
							$("#course_notes_more").show();
						$target.val("");
					}
					else if(data && data.error) {
						$(".post-note-form .msgbox").html("<div class='alert alert-error'>" + data.error + "</div>");
					}
				}
			});
		}
	});
	

    $(".download").click(function() {
    	var note_ids = "";
    	var course_id = $(".post-note-form [name=course_id]").val();
    	
        $("#course_notes li").each(function() {
        	note_ids += $(this).attr("id").substring(12) + ",";
        });
        
        if(note_ids == "")
        	return false;
        else {
        	note_ids = note_ids.substring(0, note_ids.length-1);
        	
        	window.open(base_url + 'course/downloadNotes/'+ course_id + '/' + encodeURIComponent(note_ids), 'notes_download');
        }
    });
});
