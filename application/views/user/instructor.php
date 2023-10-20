<?php 
	$course_instructor = ($this->session->userdata("user_id") == $user['id'])? 1: 0;
?>

<!-- Header Section Start -->
<div id="header-sec">
	<div id="header-sec-in">
    	<div class="course-big-pic box-style"><?php if($user['photo_url'] != "") { ?><img src="<?php echo $user['photo_url']; ?>" alt="" /><?php } ?></div>
        <div class="centered">
            <div class="header-right-content">
            	<h3><?php echo $user['welcome_message']; ?></h3>
                <small><?php echo $user['subtitle_message']; ?></small> 
                <div class="average-box"><?php echo translate("Average Rating"); ?>: 
                	<div class="rating-control">
	                	<?php for($r=0; $r<10; $r++) { ?>
			                <input type="radio" class="star {split:2} review_avg_rating" name="review_avg_rating1" value="<?php echo $r+1; ?>" title="Star <?php echo $r+1; ?>"/>
			            <?php } ?>
			        </div>
                </div>
                <div class="social-plug">
                	<fb:like send="false" layout="button_count" width="68" height="17" show_faces="false"></fb:like>
                </div>   
            </div>
        </div>
    </div>
</div>
<!-- Header Section End -->

<!-- Middle Section Start -->
<div id="middle-sec">
	<div id="middle-sec-in2">
    	<ul class="box-left-big">
        	<li class='instructor-left' style="display:none;"></li>
            <li>
            	<div class="content-box box-style">
                	<h4 class="head-all"><?php echo translate("Learning Board"); ?></h4>	
                    <hr />
                    <div class="learning-board">
                    	<div class="board-meter"><?php echo translate("Completed Questions"); ?> 
                    		<span><a href="javascript:void(0)" id="questions_count_completed"><?php echo $questions_completed; ?></a></span> <?php echo translate("out of"); ?> 
                    		<span><a href="javascript:void(0)" id="questions_count_out"><?php echo count($questions); ?></a></span>
                    	</div>
                        <div class="msg-area-main">
                        	<div class="msg-area">
                            	<div class="spe-space1">
                                    <select name="questions_filter" id="questions_filter" class="select1">
                                        <option value="all"><?php echo translate("View"); ?> <?php echo translate("All"); ?></option>
		                                <!-- <option value="0"><?php echo translate("View by"); ?> <?php echo translate("Answer"); ?></option> -->
		                                <option value="1"><?php echo translate("View by"); ?> <?php echo translate("Questions"); ?></option>
		                                <option value="2"><?php echo translate("View by"); ?> <?php echo translate("Announcement"); ?></option>
                                    </select>
                                </div>
                                <hr />
                                <div class="spe-space2 post-question-form">
                                	<div class="msgbox"></div>
                                	
                                	<textarea name="question_message" id="question_message" onfocus="if(this.value == 'Enter your Message...') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Enter your Message...'; }">Enter your Message...</textarea>
                                    <div class="lb-bx">
										<select name="question_type" id="question_type" class="select2">
                                            <!-- <option value="0"><?php echo translate("Answer"); ?></option> -->
                                            <option value="1"><?php echo translate("Questions"); ?></option>
                                            <option value="2"><?php echo translate("Announcement"); ?></option>
                                        </select>
                                        <select name="course_id" class="select2">
                                            <?php foreach($courses as $course) { ?>
                                            	<option value="<?php echo $course['id']; ?>"><?php echo $course['title']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="button" id="btn_post_question" name="btn_post_question" value="<?php echo translate("Post"); ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <ul class="user-chat" id="course_questions">
                        	<?php foreach($questions as $i=>$question) { ?>
	                        	<hr class="<?php echo ($i>1)? 'hide': ''; ?>" id="hr_course_question_<?php echo $question['id']; ?>" data-type="<?php echo $question['type']; ?>"/>
	                        	<li class="<?php echo ($i>1)? 'hide': ''; ?>" id="course_question_<?php echo $question['id']; ?>" data-type="<?php echo $question['type']; ?>">
	                            	<div class="user-chat-pic"><a href="javascript:void(0)">
	                            		<?php if($question['photo_url']!="") { ?><img src="<?php echo $question['photo_url']; ?>" alt="" /><?php } ?>
	                            	</a></div>
	                                <div class="fullwidth">
	                                    <div class="user-chat-info">
	                                        <h5>
	                                        	<a href="javascript:void(0)"><?php echo $question['first_name']. ' '. $question['last_name']; ?></a>
	                                        	<?php if($course_instructor || $question['user_id']==$this->session->userdata("user_id")) { ?>
	                                        		<a href="javascript:void(0)" class="remove-link" onclick="javascript:removeQuestion(<?php echo $question['id']; ?>)">X</a>
	                                        	<?php } ?>
	                                        </h5>
	                                        <p><?php echo nl2br($question['message']); ?></p>
	                                        <p><span class="time"><?php echo date_diff_format($question['created']); ?></span></p>
	                                        <ul class="user-comment-box" id="user_comments_<?php echo $question['id']; ?>">
	                                        	<li id="user_comments_count_<?php echo $question['id']; ?>">
	                                        		<a href="javascript:void(0)" onclick="javascript:toggleCommentsBox(<?php echo $question['id']; ?>)"><?php echo count($question['comments']); ?> <?php echo translate("Comments"); ?></a>
	                                        	</li>
	                                        	<?php foreach($question['comments'] as $j=>$comment) { ?>
		                                            <hr class="<?php echo ($j>0)? 'hide': ''; ?>" id="hr_course_comment_<?php echo $comment['id']; ?>"/>
		                                            <li class="<?php echo ($j>0)? 'hide': ''; ?>" id="course_comment_<?php echo $comment['id']; ?>">
		                                            	<div class="user-comment-pic"><a href="javascript:void(0)">
		                                            		<?php if($comment['photo_url']!="") { ?><img src="<?php echo $comment['photo_url']; ?>" alt="" /><?php } ?>
		                                            	</a></div>
		                                                <div class="fullwidth">
		                                                    <div class="user-comment-info">
		                                                        <h5>
		                                                        	<a href="javascript:void(0)"><?php echo $comment['first_name']. ' '. $comment['last_name']; ?></a>
		                                                        	<?php if($course_instructor || $comment['user_id']==$this->session->userdata("user_id")) { ?>
		                                                        		<a href="javascript:void(0)" class="remove-link" onclick="javascript:removeComment(<?php echo $comment['id']; ?>)">X</a>
		                                                        	<?php } ?>
		                                                        </h5>
		                                                        <p><?php echo nl2br($comment['message']); ?></p>
		                                                        <p>
		                                                            <span class="time"><?php echo date_diff_format($comment['created']); ?></span>
		                                                        </p>
		                                                    </div>
		                                                </div>
		                                            </li>
		                                        <?php } ?>
	                                            <hr />
	                                            <li>
	                                            	<div class="user-comment-pic"><a href="javascript:void(0)">
	                                            		<?php if($user['photo_url']!="") { ?><img src="<?php echo $user['photo_url']; ?>" alt="" /><?php } ?>
	                                            	</a></div>
	                                                <div class="fullwidth">
	                                                    <div class="user-comment-info">
	                                                       <textarea name="comment_message_<?php echo $question['id']; ?>" id="comment_message_<?php echo $question['id']; ?>" class="course-comment-message"
	                                                        onfocus="if(this.value == 'Enter your text here') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Enter your text here'; }">Enter your text here</textarea>
	                                                    </div>
	                                                </div>
	                                            </li>
	                                        </ul>
	                                    </div>
	                                </div>
	                            </li>
	                        <?php } ?>
                        </ul>
                    </div>
                    <div class="more-btn <?php echo count($questions)<2? 'hide': ''; ?>" id="course_questions_more"><a href="javascript:void(0)">+ <?php echo translate("More"); ?></a></div>
                </div>
            </li>
            <li>
            	<div class="content-box box-style">
                	<h4 class="head-all"><?php echo translate("Reviews by Student"); ?><span class="rev-star-main"><?php echo translate("Average Rating"); ?>: 
                		<div class="rating-control">
		                	<?php for($r=0; $r<10; $r++) { ?>
				                <input type="radio" class="star {split:2} review_avg_rating" name="review_avg_rating2" value="<?php echo $r+1; ?>" title="Star <?php echo $r+1; ?>"/>
				            <?php } ?>
				        </div>
                	</span></h4>	
                    <hr />
                    <ul class="reviews" id="user_reviews">
                    	<?php foreach($reviews as $i=>$review) { ?>
                    		<li class="<?php echo ($i>1)? 'hide': ''; ?>" id="user_review_<?php echo $review['id']; ?>">
	                    		<div class="reviews-pic"><a href="javascript:void(0)">
	                    			<?php if($review['photo_url']!="") { ?><img src="<?php echo $review['photo_url']; ?>" alt="" /><?php } ?>
	                    		</a></div>
	                            <div class="fullwidth">
	                            	<div class="reviews-info">
	                                	<h5><span class="rev-name"><?php echo $review['first_name']. ' '. $review['last_name']; ?></span>
	                                	<span class="rev-star">
	                                		<?php for($r=0; $r<10; $r++) { ?>
			                        			<input type="radio" class="star {split:2}" name="review_rating_<?php echo $review['id']; ?>" value="<?php echo $r+1; ?>" title="Star <?php echo $r+1; ?>"/>
			                        		<?php } ?>
			                        	</span></h5>
	                                	<?php if(strlen($review['message'])>500) { ?>
	                                		<p class="full-message hide"><?php echo nl2br($review['message']); ?></p>
	                                		<p class="fixed-message"><?php echo nl2br(substr($review['message'], 0, 500)); ?></p>
	                                		<p><span class="time"><?php echo date_format2($review['created']); ?></span>
	                                		<span class="read-more"><a href="javascript:void(0)" onclick="javascript:toggleUserReview('<?php echo $review['id']; ?>')"><?php echo translate("Read More"); ?></a></span></p>
	                                    <?php } else { ?>
	                                    	<p class="fixed-message"><?php echo nl2br($review['message']); ?></p>
	                                		<p><span class="time"><?php echo date_format2($review['created']); ?></span></p>
	                                    <?php } ?>
	                                </div>
	                            </div>
	                        </li>
	                        <hr class="<?php echo ($i>1)? 'hide': ''; ?>" id="hr_user_review_<?php echo $review['id']; ?>"/>
	                    <?php } ?>
                    	<li>
                        	<div class="reviews-pic"></div>
                        	<div class="fullwidth">
                        		<form name="user-review-form" class="user-review-form" method="post">
	                        		<div class="msgbox"></div>
	                        		<input type="hidden" name="instructor_id" value="<?php echo $user['id']; ?>" />
	                        		<textarea name="review_message" id="review_message" class="" onfocus="if(this.value == 'Enter your Review...') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Enter your Review...'; }">Enter your Review...</textarea>
	                        		<div class="lb-bx">
	                        			<div class="rating-control">
			                        		<?php for($r=0; $r<10; $r++) { ?>
			                        			<input type="radio" class="star {split:2}" name="review_rating" value="<?php echo $r+1; ?>" title="Star <?php echo $r+1; ?>"/>
			                        		<?php } ?>
		                        		</div>
		                        		<input type="button" id="btn_post_user_review" name="btn_post_user_review" value="<?php echo translate("Post"); ?>"/>
	                        		</div>
                        		</form>
                        	</div>
                        </li>
                    </ul>
                    <div class="more-btn <?php echo count($reviews)<2? 'hide': ''; ?>" id="user_reviews_more"><a href="javascript:void(0)">+ <?php echo translate("More Reviews"); ?></a></div>
                </div>
            </li>    
        </ul><!-- Left Section -->
        <ul class="box-right-small">
        	<li class='instructor-right'>
            	<ul class="course-list">
                    <li class="none-blk"><h5><?php echo translate("Course by"); ?> <a href="javascript:void(0)"><?php echo $user['first_name']. ' '. $user['last_name']; ?></a></h5></li>
                    <?php foreach($courses as $course) { ?>
	                    <li>
	                    	<a href="<?php echo base_url('/course/courseinfo/'.$course['id']); ?>">
	                    		<div class="course-img">
			                		<?php if($course['attach_type'] == "image" && $course['attachment'] != "") { ?>
			                    		<img src="<?php echo $course['attachment']; ?>" alt="" />
			                    	<?php } elseif($course['attach_type'] == "video" && $course['video_youtube_key'] != "") { ?>
			                    		<iframe scrolling="no" style="border:none;" src="http://www.youtube.com/embed/<?php echo $course['video_youtube_key']; ?>?rel=1&showinfo=0&wmode=transparent"></iframe>
			                    	<?php } ?>
			                    </div>
			                </a>
		                    <a href="<?php echo base_url('/course/courseinfo/'.$course['id']); ?>"><h5><?php echo substr($course['title'], 0, 50); ?></h5></a>
		                    <p class="desc"><?php echo $course['description']; ?></p>
		                    <div class="instructor-box">
		                        <div class="instructor-box-left">
		                            <div class="instructor-img">
		                            	<a href="<?php echo base_url('/user/instructor/'.$course['user_id']); ?>">
			                            	<?php if($user['photo_url']!="") { ?>
					                            <img src="<?php echo $user['photo_url']; ?>" alt="" />
					                    	<?php } ?>
					                    </a>
		                            </div>
		                            <div class="instructor-txt">
		                            	<a href="<?php echo base_url('/user/instructor/'.$course['user_id']); ?>">
		                                	<h6><?php echo $user['first_name']. ' '. $user['last_name']; ?><br /><small><?php echo $user['designation']; ?></small></h6>
		                                </a>
		                            </div>
		                        </div>
		                        <div class="instructor-box-right">
		                            <!-- <span><?php echo ($course['is_free'])? 'Free': number_format($course['price']). ' '. translate("Won"); ?></span> -->
		                        </div>
		                    </div>
		                    <div class="course-fed">
		                        <div class="student-county"><span><?php echo $course['course_students']; ?></span> <?php echo translate("student"); ?></div>
		                        <div class="rating">
			                        <?php for($r=0; $r<10; $r++) { ?>
						                <input type="radio" class="star {split:2}" name="course_rating_<?php echo $course['id']; ?>" value="<?php echo $r+1; ?>" title="Star <?php echo $r+1; ?>"/>
						            <?php } ?>
		                        </div>
		                        <div class="fb-like">
		                        	<fb:like href="<?php echo base_url('/course/courseinfo/'.$course['id']); ?>" send="false" layout="button_count" width="68" height="17" show_faces="false"></fb:like>
		                        </div>
		                    </div>
			            </li>
	                    
	                <?php } ?>
                    
                </ul>    
            </li>
            </ul>
        </ul><!-- Right Section -->   
    </div>
</div>
<!-- Middle Section End -->

<?php 
	echo js('course/question.js');
	echo js('course/review.js');
?>

<script type="text/javascript">
	var course_instructor = "1";

	$(function() {

		$('input[type=radio].star').rating({split:2});

		<?php foreach($courses as $i=>$course) { ?>
			$("input[name=course_rating_<?php echo $course['id']; ?>]").rating('select', "<?php echo intval($course['course_rating']); ?>").rating('readOnly', true);
		<?php } ?>

		<?php foreach($reviews as $i=>$review) { ?>
			$("input[name=review_rating_<?php echo $review['id']; ?>]").rating('select', "<?php echo $review['rating']; ?>").rating('readOnly', true);
		<?php } ?>

		$(".review_avg_rating").rating('select', "<?php echo intval($user_rating);?>").rating('readOnly', true);
	});
</script>