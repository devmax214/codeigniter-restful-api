<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// --------------------------------------------------------------------------
?>
<h1>Place booked</h1>
<?php if($place['is_public']==1) { ?>
<p>Dear <?php echo $place['first_name']. ' '. $place['last_name']; ?>, 
<br/>
<br/>
<strong><?php echo $class['first_name']. ' '. $class['last_name']; ?></strong> has booked your place, <strong><?php echo $place['address']; ?></strong> on <?php echo $class['start_time']; ?>.
<?php } else { ?>
<p>Dear <?php echo $this->session->userdata('owner_name'); ?>, 
<br/>
<br/>
<strong><?php echo $class['first_name']. ' '. $class['last_name']; ?></strong> has booked the place, <strong><?php echo $place['address']; ?></strong> on <?php echo $class['start_time']; ?>.
<?php } ?>
<br/>
<br/>
Teacher Email: <?php echo $class['email_address']; ?>
<br/>
<br/>
Phone: <?php echo $class['phone']; ?>
<br/>
<br/>
</p>
<?php
