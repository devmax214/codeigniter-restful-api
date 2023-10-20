<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// --------------------------------------------------------------------------
?>
<h1>Class Registered</h1>
<p>Dear <?php echo $student['first_name']. ' '. $student['last_name']; ?>, 
<br/>
<br/>
Thanks for registering for <a href="<?php echo $class['share_link']; ?>" target="_blank"><?php echo $class['class_name']; ?></a>! 
<br/>
<br/>
Here are the class details
<br/>
<br/>
Class Name: <?php echo $class['class_name']; ?>
<br/>
<br/>
Description:<br/>
<?php echo $class['details']; ?>
<br/>
<br/>
Difficulty: <?php echo ($class['difficulty']==0)? 'Low': (($class['difficulty']==1)? 'Medium': 'High'); ?>
<br/>
<br/>
Class Time: <?php echo $class['start_time']. ' ~ '. $class['end_time']; ?>
<br/>
<br/>
Teacher Name: <?php echo $class['first_name']. ' '. $class['last_name']; ?>
<br/>
<br/>
Teacher Email: <?php echo $class['email_address']; ?>
<br/>
<br/>
Teacher Phone: <?php echo $class['phone']; ?>
<br/>
<br/>
Place: <?php echo $place['address']. ', '. $place['city']. ', '. $place['state']; ?>
<br/>
<br/>
<?php
