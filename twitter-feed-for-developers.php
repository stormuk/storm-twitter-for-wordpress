<?php
/*
 * Plugin Name: oAuth Twitter Feed for Developers
 * Description: Twitter API 1.1 compliant plugin that provides a function to get an array of tweets from the auth'd users Twitter feed for use in themes.
 * Contributors: stormuk, lgladdy
 * Donate link: http://www.stormconsultancy.co.uk/
 * Tags: twitter, oauth, feed, tweets
 * Requires at least: 3.4
 * Tested up to: 3.5
 * Stable tag: 1.0.4
 * Version: 1.0.4
 * License: MIT
 * License URI: http://opensource.org/licenses/MIT
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