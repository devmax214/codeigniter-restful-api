<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// --------------------------------------------------------------------------
?>
<h1>New Place</h1>
<p>Dear <?php echo $this->session->userdata('owner_name'); ?>, 
<br/>
<br/>
New place has been added by <?php echo $place['first_name']. ' '. $place['last_name']; ?>.
<br/>
<br/>
Address: <?php echo $place['address']; ?>
<br/>
<br/>
Available Time: <?php echo $place['available_time']; ?>
<br/>
<br/>
City: <?php echo $place['city']; ?>
<br/>
<br/>
State: <?php echo $place['state']; ?>
<br/>
<br/>
Zip: <?php echo $place['zip']; ?>
<br/>
<br/>
Please review <a href="<?php echo $place_url; ?>">hear</a>
<br/>
<br/>
</p>
<?php
