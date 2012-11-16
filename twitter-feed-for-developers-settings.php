<?php

//Add an option page
if (is_admin()) {
  add_action('admin_menu', 'tdf_menu');
  add_action('admin_init', 'tdf_register_settings');
}

function tdf_menu() {
	add_options_page('Twitter Feed for Developers','Twitter Feed Auth','manage_options','tdf_settings','tdf_settings_output');
}

function tdf_settings() {
	$tdf = array();
	$tdf[] = array('name'=>'tdf_consumer_key','label'=>'Twitter Application Consumer Key');
	$tdf[] = array('name'=>'tdf_consumer_secret','label'=>'Twitter Application Consumer Secret');
	$tdf[] = array('name'=>'tdf_access_token','label'=>'Account Access Token');
	$tdf[] = array('name'=>'tdf_access_token_secret','label'=>'Account Access Token Secret');
	$tdf[] = array('name'=>'tdf_user_timeline','label'=>'Twitter Feed Screen Name');
	return $tdf;
}

function tdf_register_settings() {
	$settings = tdf_settings();
	foreach($settings as $setting) {
		register_setting('tdf_settings',$setting['name']);
	}
}


function tdf_settings_output() {
	$settings = tdf_settings();
	
	echo '<div class="wrap">';
	
		echo '<h2>Twitter Feed for Developers</h2>';
		
		echo '<p>This plugin requires five fields. Most of which are found on the application overview page on the <a href="http://dev.twitter.com/apps">http://dev.twitter.com</a> website</p>';
		echo '<p>When creating an application for this plugin, you don\'t need to set a callback location and you only need read access.</p>';
		echo '<p>You will need to generate an oAuth token once you\'ve created the application. The button for that is on the bottom of the application overview page.</p>';
		echo '<p>The \'Twitter Feed Screen Name\' setting is the timeline you wish you load when you call the function getTweets(), such as @stormuk</p>';
		echo '<p>getTweets($limit = 20) takes an optional limit on the number of tweets, up to a maximum of 20, and has default of 20.</p>';
		echo '<p>The format of the response from getTweets will either be an array of arrays containing tweet objects, as described on the official Twitter documentation <a href="https://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline">here</a>, or an 1D array containing an "error" key, with a value of the error that occurred.</p>';
		
		echo '<hr />';
		
		echo '<form method="post" action="options.php">';
		
    settings_fields('tdf_settings');
		
		echo '<table>';
			foreach($settings as $setting) {
				echo '<tr>';
					echo '<td>'.$setting['label'].'</td>';
					echo '<td><input type="text" style="width: 400px" name="'.$setting['name'].'" value="'.get_option($setting['name']).'" /></td>';
				echo '</tr>';
			}
		echo '</table>';
		
		submit_button();
		
		echo '</form>';
		
		echo '<hr />';
		
		echo '<h3>Debug Information</h3>';
		$last_cached = get_option('tdf_last_cached');
		if (empty($last_cached)) $last_cached = "Never"; else $last_cached = date('r',$last_cached);
		echo '<p>Last Cached: '.$last_cached.'<br />';
		$last_error = get_option('tdf_last_error');
		if (empty($last_error)) $last_error = "None";
		echo 'Last Error: '.$last_error.'</p>';
	
	echo '</div>';
	
}