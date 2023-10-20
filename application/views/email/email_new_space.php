<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// --------------------------------------------------------------------------
?>
<h1>New Space</h1>
<p>Dear <?php echo $this->session->userdata('owner_name'); ?>, 
<br/>
<br/>
New space has been added by <?php echo $space['first_name']. ' '. $space['last_name']; ?>.
<br/>
<br/>
Address: <?php echo $space['address']; ?>
<br/>
<br/>
Description: <?php echo $space['description']; ?>
<br/>
<br/>
Available Time: <?php echo $space['available_time']; ?>
<br/>
<br/>
Please review <a href="<?php echo $space_url; ?>">hear</a>
<br/>
<br/>
</p>
<?php
