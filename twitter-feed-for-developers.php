<?php
/*
Plugin Name: oAuth Twitter Feed for Developers
Description: Twitter API 1.1 compliant plugin that provides a function to get an array of tweets from the auth'd users Twitter feed for use in themes.
Version: 2.2.1
License: MIT
License URI: http://opensource.org/licenses/MIT
Author: Storm Consultancy (Liam Gladdy)
Author URI: http://www.stormconsultancy.co.uk
*/


require('StormTwitter.class.php');
require('twitter-feed-for-developers-settings.php');

/* setup tweet request */
function requestTweets($method, $args) {
    
  $config['key'] = get_option('tdf_consumer_key');
  $config['secret'] = get_option('tdf_consumer_secret');
  $config['token'] = get_option('tdf_access_token');
  $config['token_secret'] = get_option('tdf_access_token_secret');
  $config['screenname'] = get_option('tdf_user_timeline');
  $config['cache_expire'] = intval(get_option('tdf_cache_expire'));
  if ($config['cache_expire'] < 1) $config['cache_expire'] = 3600;
  $config['directory'] = plugin_dir_path(__FILE__);
  
  $obj = new StormTwitter($config);
  $res = call_user_func_array(array($obj, $method), $args);
  
  update_option('tdf_last_error',$obj->st_last_error);
  return $res;
}

/* implement getTweets */
function getTweets($username = false, $count = 20, $options = false) {  
  return requestTweets('getTweets', array($username, $count, $options));
}

/* implement searchTweets */
function searchTweets($count = 20, $options = false) {
  return requestTweets('searchTweets', array($count, $options));
}

/* implement getTweetById */
function getTweetById($id) {
   return requestTweets('getTweetById', array($id));
}
