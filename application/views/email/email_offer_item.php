<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// --------------------------------------------------------------------------
?>
<h1><?php echo ($offer['trade_offer'] == 1)? 'Item offered trade': 'Item offered'; ?></h1>
<p>Dear <?php echo $owner['first_name']. ' '. $owner['last_name']; ?>, 
<br/>
<br/>
Your item <a href="<?php echo $item['item_url']; ?>" target="_blank"><?php echo $item['item_name']; ?></a> has been <?php echo ($offer['trade_offer'] == 1)? 'offered trade': 'offered'; ?> by <strong><?php echo $user['first_name']. ' '. $user['last_name']; ?></strong>
<br/>
<br/>
<?php if($offer['trade_offer'] == 1) { ?>
What would you like to offer as a trade?<br />
<?php echo $offer['comment']; ?>
<?php } else { ?>
Price: <?php echo '$'. $offer['price']; ?>
<?php } ?>
<br/>
<br/>
Zip: <?php echo $offer['zip_code']; ?>
<br/>
<br/>
Buyer Email: <?php echo $user['email_address']; ?>
<br/>
<br/>
</p>
<?php
