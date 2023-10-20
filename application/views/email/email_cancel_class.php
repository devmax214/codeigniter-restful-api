<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// --------------------------------------------------------------------------
?>
<h1>Class Canceled</h1>
<p>Dear <?php echo $student['first_name']. ' '. $student['last_name']; ?>, 
<br/>
<br/>
You class <strong><?php echo $class['class_name']; ?></strong> has been canceled by teacher, <?php echo $class['first_name']. ' '. $class['last_name']; ?> (<?php echo $class['email_address']; ?>).
<br/>
<br/>
You will received refund about it automatically. 
</p>
<br/>
<br/>
<?php
