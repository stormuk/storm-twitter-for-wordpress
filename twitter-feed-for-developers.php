<?php
/*
Plugin Name: oAuth Twitter Feed for Developers
Description: Twitter API 1.1 compliant plugin that provides a function to get an array of tweets from the auth'd users Twitter feed for use in themes.
Version: 2.3.0
Text Domain: oauth-twitter-feed-for-developers
License: MIT
License URI: http://opensource.org/licenses/MIT
Author: Liam Gladdy (Storm Consultancy)
Author URI: https://stormconsultancy.co.uk
*/


require('StormTwitter.class.php');
require('twitter-feed-for-developers-settings.php');

add_action('plugins_loaded', 'load_otffd_languages');
function load_otffd_languages() {
	load_plugin_textdomain('oauth-twitter-feed-for-developers', FALSE, basename(dirname( __FILE__ )).'/languages/');
}

/* implement getTweets */
function getTweets($username = false, $count = 20, $options = false) {

  $config['key'] = get_option('tdf_consumer_key');
  $config['secret'] = get_option('tdf_consumer_secret');
  $config['token'] = get_option('tdf_access_token');
  $config['token_secret'] = get_option('tdf_access_token_secret');
  $config['screenname'] = get_option('tdf_user_timeline');
  $config['cache_expire'] = intval(get_option('tdf_cache_expire'));
  if ($config['cache_expire'] < 1) $config['cache_expire'] = 3600;
  $config['directory'] = plugin_dir_path(__FILE__);
  
  $obj = new StormTwitter($config);
  $res = $obj->getTweets($username, $count, $options);
  update_option('tdf_last_error',$obj->st_last_error);
  return $res;
  
}