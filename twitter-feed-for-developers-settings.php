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
	$tdf[] = array('name'=>'tdf_consumer_key','label'=>'Twitter Application API Key');
	$tdf[] = array('name'=>'tdf_consumer_secret','label'=>'Twitter Application API Secret');
	$tdf[] = array('name'=>'tdf_access_token','label'=>'Account Access Token');
	$tdf[] = array('name'=>'tdf_access_token_secret','label'=>'Account Access Token Secret');
	$tdf[] = array('name'=>'tdf_cache_expire','label'=>'Cache Duration (Default 3600)');
	$tdf[] = array('name'=>'tdf_user_timeline','label'=>'Twitter Feed Screen Name*');
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
	
		echo '<h2>oAuth Twitter Feed for Developers</h2>';
		
		if (defined('TFD_USING_EXISTING_LIBRARY_TWITTEROAUTH') && TFD_USING_EXISTING_LIBRARY_TWITTEROAUTH) {
      $reflector = new ReflectionClass('TwitterOAuth');
      $file = $reflector->getFileName();
      
      echo '<div id="message" class="error"><p><strong>oAuth Twitter Feed for Developers</strong> is using an existing version of the TwitterOAuth class library to provide compatibility with existing plugins.<br />This could lead to conflicts if the plugin is using an different version of the class.</p><p>The class is being loaded at <strong>'.$file.'</strong></p></div>';
		}
  
    if (defined('TFD_USING_EXISTING_LIBRARY_OAUTH') && TFD_USING_EXISTING_LIBRARY_OAUTH) {
      $reflector = new ReflectionClass('OAuthConsumer');
      $file = $reflector->getFileName();
      
      echo '<div id="message" class="error"><p><strong>oAuth Twitter Feed for Developers</strong> is using an existing version of the PHP OAuth library to provide compatibility with existing plugins or your PHP installation.<br />This could lead to conflicts if the plugin, or your PHP installed class is using an different version of the class.</p><p>The class is being loaded at <strong>'.$file.'</strong></p></div>';
    }
		
		echo '<p>Most of this configuration can found on the application overview page on the <a href="http://dev.twitter.com/apps">http://dev.twitter.com</a> website.</p>';
		echo '<p>When creating an application for this plugin, you don\'t need to set a callback location and you only need read access.</p>';
		echo '<p>You will need to generate an oAuth token once you\'ve created the application. The button for that is on the bottom of the application overview page.</p>';
		echo '<p>Once configured, you then need to call getTweets() anywhere in your template. getTweets supports 3 parameters - the username of the twitter feed you want to load, the number of tweets to load (max 20), and any additional parameters you want to send to Twitter. An example code usage is shown under the debug information below.</p>';
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
				if ($setting['name'] == 'tdf_user_timeline') {
  				echo '<tr>';
  				  echo '<td colspan="2" style="font-size:10px; font-style: italic">This option is no longer required and is deprecated. You should define the screen name to load as part of the getTweets() call as detailed above.</td>';
  				echo '</tr>';
				}
			}
		echo '</table>';
		
		submit_button();
		
		echo '</form>';
		
		echo '<hr />';
		
		echo '<h3>Debug Information</h3>';
		$last_error = get_option('tdf_last_error');
		if (empty($last_error)) $last_error = "None";
		echo 'Last Error: '.$last_error.'</p>';
	
	echo '</div>';
	
}