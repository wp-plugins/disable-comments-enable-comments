<?php
/**
 Plugin Name: Disable Comments Enable Comments
 Plugin URI:
 Description: Deletes all tags
 Version: 1.0
 Author: <a href="http://www.seo101.net">Seo101</a>
 */

function disable_comment_enable_comments_page() {
	global $current_user, $wpdb;

	// Settings
	$limit = 250;
	$timeout = 4; // For refresh

	// Hash based on userid, userlevel and ip
	get_currentuserinfo();
	$ip = preg_replace( '/[^0-9a-fA-F:., ]/', '',$_SERVER['REMOTE_ADDR'] );
	$hash = md5($current_user->ID.$current_user->user_level.$ip);
	$url  = 'options-general.php?page=disable_comment_enable_comments&hash='.$hash;
	$stop =  false;
	$validated = false;
	if(isset($_POST['plugin_tag_action']) && isset($_POST['disableenableoption']))
	{
		$validated = true;
	}
	if(isset($_GET['hash']) && $_GET['hash'] = $hash)
	{
		$validated = true;
	}

	if ($validated) {
		$selectedoption = $_POST['disableenableoption'];
		if ($selectedoption==1) {
			$wpdb->query( "UPDATE `$wpdb->posts` SET `comment_status` = 'closed' WHERE `comment_status` = 'open'" );
			echo '<br/><br/><h2><b>All comments are now disabled</b></h2><br/>';
		} else if ($selectedoption==2) {
			$wpdb->query( "UPDATE `$wpdb->posts` SET `comment_status` = 'open' WHERE `comment_status` = 'closed'" );
			echo '<br/><br/><h2><b>All comments are now enabled</b></h2><br/>';
		} else if ($selectedoption==3) {
			$wpdb->query( "UPDATE `$wpdb->posts` SET `ping_status` = 'closed' WHERE `ping_status` = 'open'" );
			echo '<br/><br/><h2><b>All pingbacks are now disabled</b></h2><br/>';
		} else if ($selectedoption==4) {
			$wpdb->query( "UPDATE `$wpdb->posts` SET `ping_status` = 'open' WHERE `ping_status` = 'closed'" );
			echo '<br/><br/><h2><b>All pingbacks are now enabled</b></h2><br/>';
		}
		$stop = true;
	}

	if (!$stop) {
		?>

<h4>By clicking the button you will modify the comments or pingback settings on all posts and pages</h4>
<form action="options-general.php?page=disable_comment_enable_comments" method="post">
<input
	type="radio" name="disableenableoption" id="disableenableoption_1"
	value="1" checked="checked" /><label for="disableenableoption_1">Disable all comments</label><br />
<input
	type="radio" name="disableenableoption" id="disableenableoption_2"
	value="2" /><label for="disableenableoption_2">Enable all comments</label><br />
<input
	type="radio" name="disableenableoption" id="disableenableoption_3"
	value="3" /><label for="disableenableoption_3">Disable all pingbacks</label><br />
<input
	type="radio" name="disableenableoption" id="disableenableoption_4"
	value="4" /><label for="disableenableoption_4">Enable all pingbacks</label><br />
<br />

<br />
Always be very carefull when deleting data, it is always suggested to make a backup first.<BR><BR>
<br />
<input type="submit" name="plugin_tag_action" value="Execute" onclick="javascript:return(confirm('<?php _e("Are you sure you want to proceed?")?>'))" />

</form>
<?php
	} else {

	}
}


function disable_comment_enable_comments_menu() {
	if (is_admin()) {
		add_options_page('Disable/Enable Comments', 'Disable/Enable Comments', 'administrator','disable_comment_enable_comments', 'disable_comment_enable_comments_page');
	}
}

// Admin menu items
add_action('admin_menu', 'disable_comment_enable_comments_menu');

?>