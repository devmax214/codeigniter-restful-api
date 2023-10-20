<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// --------------------------------------------------------------------------
?>
<h1>Class Purchased</h1>
<p>Dear <?php echo $class['first_name']. ' '. $class['last_name']; ?>, 
<br/>
<br/>
You class <strong><?php echo $class['class_name']; ?></strong> has been phurchased by <?php echo count($students); ?> student(s).
<br/>
<br/>
<strong>Students:</strong> 
</p>
<ul>
<?php foreach($students as $student) { 
	if($student['email_address'] == '') 
		continue;
?>
<li>
<label><?php echo $student['email_address']; echo ($student['phone'] != '')? ('('. $student['phone']. ')'): ''; ?></label>
<br/>
</li>
<?php } ?>
</ul>
<br/>
<br/>
<?php
