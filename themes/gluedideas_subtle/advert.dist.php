<?php
/*
Filename: 		advert.php
Date: 			06-06-25
Copyright: 		2008, Frazier Media
Author: 		Christopher Frazier (cfrazier@fraziermedia.com)
Description: 	Multi-Author Template for WordPress (Subtle)
Requires:
*/

function displaySubtleAds ($iPosition) {
	switch ($iPosition) {
		case 0:
			echo('<!-- No Ads Displayed -->');
			break;
		case 1:
			echo('<div class="advert">');
?>
<!-- Ad Code Here -->
<?php
			echo('</div>');
			break;
		case 2:
			echo('<div class="advert">');
?>
<!-- Ad Code Here -->
<?php
			echo('</div>');
			break;
		case 3:
			echo('<div class="advert">');
?>
<!-- Ad Code Here -->
<?php
			echo('</div>');
			break;
	}
}

?>