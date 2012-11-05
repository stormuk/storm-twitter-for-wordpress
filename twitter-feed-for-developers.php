<?php
/*
Plugin Name: Twitter Feed for Wordpress Developers
Plugin URI: https://github.com/lgladdy
Description: A twitter API 1.1 compliant plugin that provides a function that returns a number of tweets from the auth'd users twitter feed as an array for wordpress (theme) developers to use in their themes.
Author: Liam Gladdy
Version: 1.0
Author URI: http://www.gladdy.co.uk
*/


require('StormTwitter.class.php');
require('twitter-feed-for-developers-settings.php');
require('oauth/twitteroauth.php');

/* implement getTweets */
function getTweets($count = 20) {

  $config['key'] = get_option('tdf_consumer_key');
  $config['secret'] = get_option('tdf_consumer_secret');
  $config['token'] = get_option('tdf_access_token');
  $config['token_secret'] = get_option('tdf_access_token_secret');
  $config['screenname'] = get_option('tdf_user_timeline');
  $config['directory'] = plugin_dir_path(__FILE__);
  
  $obj = new StormTwitter($config);
  $res = $obj->getTweets($count);
  update_option('tdf_last_cached',$obj->st_last_cached);
  update_option('tdf_last_error',$obj->st_last_error);
  return $res;
  
}