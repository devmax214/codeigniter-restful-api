
<!-- Search Section Start -->
<div id="search-sec">
	<div id="search-sec-in">
    	<h3><?php echo translate("Browse Popular Courses"); ?></h3>
        <div class="centered">
        	<div class="search-box">
            	<form name="frm_index_search" id="frm_index_search" method="post" action="<?php echo base_url(); ?>">
	            	<select name="category_id" id="category_id">
	                	<option value=""><?php echo translate("All Category"); ?></option>
	                	<?php foreach($categories as $category) { ?>
	                    	<option value="<?php echo $category['id']; ?>" <?php echo ($category['id']==$category_id)? 'selected': ''; ?>><?php echo $category['name']; ?></option>
	                    <?php } ?>
	                </select>
	                <select name="review" id="review">
	                	<option value=""><?php echo translate("All review rates"); ?></option>
	                    <option value="10" <?php echo ($review=="10")? 'selected': ''; ?>>5<?php echo translate("Star"); ?></option>
	                    <option value="8" <?php echo ($review=="8")? 'selected': ''; ?>>4<?php echo translate("Star and Up"); ?></option>
	                    <option value="6" <?php echo ($review=="6")? 'selected': ''; ?>>3<?php echo translate("Star and Up"); ?></option>
	                    <option value="4" <?php echo ($review=="4")? 'selected': ''; ?>>2<?php echo translate("Star and Up"); ?></option>
	                </select>
	                <select name="sort_by" id="sort_by">
	                	<option value=""><?php echo translate("Sort courses by"); ?></option>
	                    <option value="recommandation" <?php echo ($sort_by=="recommandation")? 'selected': ''; ?>><?php echo translate("Sort by"); ?> <?php echo translate("Recommandation"); ?></option>
                    	<option value="most_views" <?php echo ($sort_by=="most_views")? 'selected': ''; ?>><?php echo translate("Sort by"); ?> <?php echo translate("views"); ?></option>
	                    <option value="most_ratings" <?php echo ($sort_by=="most_ratings")? 'selected': ''; ?>><?php echo translate("Sort by"); ?> <?php echo translate("ratings"); ?></option>
	                    <option value="most_likes" <?php echo ($sort_by=="most_likes")? 'selected': ''; ?>><?php echo translate("Sort by"); ?> <?php echo translate("likes"); ?></option>
	                </select>
	                <input type="text" id="course_name" name="course_name" onfocus="if(this.value == 'Course Name') { this.value = ''; }" onblur="if(this.value == '') { this.value = 'Course Name'; }" value="<?php echo ($course_name=="")? 'Course Name': $course_name; ?>" />
	                <a href="javascript:void(0);" class="search-btn" onclick="javascript:submitSearchForm()"><span class="icon-search"></span><span class="search-txt"><?php echo translate("Search"); ?></span></a>
	            </form>
            </div>
        </div>
    </div>
</div>
<!-- Search Section End -->

<!-- Middle Section Start -->
<div id="middle-sec">
	<div id="middle-sec-in">
    	<div class="centered">
            <ul class="course-list">
            	<?php foreach($courses as $i=>$course) { ?>
	            	<li>
	            		<a href="<?php echo isset($course['link'])? $course['link']: base_url('/course/courseinfo/'.$course['id']); ?>">
		                	<div class="course-img">
		                		<?php if($course['attach_type'] == "image" && $course['attachment'] != "") { ?>
		                    		<img src="<?php echo $course['attachment']; ?>" alt="" />
		                    	<?php } elseif($course['attach_type'] == "video" && $course['video_youtube_key'] != "") { ?>
		                    		<iframe scrolling="no" style="border:none;" src="http://www.youtube.com/embed/<?php echo $course['video_youtube_key']; ?>?rel=1&showinfo=0&wmode=transparent"></iframe>
		                    	<?php } ?>
		                    </div>
		                    <!-- <div class="course-tag-main"><div class="course-tag">Course of the month</div><span class="tag-right"></span></div> -->
		                </a>
	                    <a href="<?php echo isset($course['link'])? $course['link']: base_url('/course/courseinfo/'.$course['id']); ?>"><h5><?php echo substr($course['title'], 0, 50); ?></h5></a>
	                    <p class="desc"><?php echo $course['description']; ?></p>
	                    <div class="instructor-box">
	                        <div class="instructor-box-left">
	                            <div class="instructor-img">
	                            	<a href="<?php echo base_url('/user/instructor/'.$course['user_id']); ?>">
		                            	<?php if($course['photo_url']!="") { ?>
				                            <img src="<?php echo $course['photo_url']; ?>" alt="" />
				                    	<?php } ?>
				                    </a>
	                            </div>
	                            <div class="instructor-txt">
	                            	<a href="<?php echo base_url('/user/instructor/'.$course['user_id']); ?>">
	                                	<h6><?php echo $course['first_name']. ' '. $course['last_name']; ?><br /><small><?php echo $course['designation']; ?></small></h6>
	                                </a>
	                            </div>
	                        </div>
	                        <div class="instructor-box-right">
	                            <!-- <span><?php echo ($course['is_free'])? 'Free': number_format($course['price']). ' '. translate('Won'); ?></span> -->
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
		            
	            <?php if($i==11) break; } ?>
	            
            </ul>
        </div>    
        <?php if(count($courses) > 12) { ?>
        	<div class="centered"><a href="javascript:void(0)" class="discover-btn" ref="<?php echo base_url('/course'); ?>"><?php echo translate("Discover Courses"); ?></a></div>
        <?php } ?>
    </div>
</div>
<!-- Middle Section End -->

<script type="text/javascript">
	$(function() {
		$('input[type=radio].star').rating({split:2});

		<?php foreach($courses as $i=>$course) { ?>
			$("input[name=course_rating_<?php echo $course['id']; ?>]").rating('select', "<?php echo intval($course['course_rating']); ?>").rating('readOnly', true);
		<?php if($i==11) break; } ?>
	});
	
</script>