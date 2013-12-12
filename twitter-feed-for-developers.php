<?php
/*
Plugin Name: oAuth Twitter Feed for Developers
Description: Twitter API 1.1 compliant plugin that provides a function to get an array of tweets from the auth'd users Twitter feed for use in themes.
Version: 2.0.4
License: MIT
License URI: http://opensource.org/licenses/MIT
Author: Storm Consultancy (Liam Gladdy)
Author URI: http://www.stormconsultancy.co.uk
*/


require('StormTwitter.class.php');
require('twitter-feed-for-developers-settings.php');
add_shortcode('twitterfeed', 'tdf_twitter_feed');
add_action('widgets_init', 'tdf_register_widgets');
//add filter to add a setup link for the plugin on the plugin page
add_filter('plugin_action_links_'. plugin_basename( __FILE__ ), 'tdf_base_plugin_link', 10, 4);
/* implement getTweets */
function getTweets($count = 20, $username = false, $options = false) {

  $config['key'] = get_option('tdf_consumer_key');
  $config['secret'] = get_option('tdf_consumer_secret');
  $config['token'] = get_option('tdf_access_token');
  $config['token_secret'] = get_option('tdf_access_token_secret');
  $config['screenname'] = get_option('tdf_user_timeline');
  $config['cache_expire'] = intval(get_option('tdf_cache_expire'));
  if ($config['cache_expire'] < 1) $config['cache_expire'] = 3600;
  $config['directory'] = plugin_dir_path(__FILE__);
  
  $obj = new StormTwitter($config);
  $res = $obj->getTweets($count, $username, $options);
  update_option('tdf_last_error',$obj->st_last_error);
  return $res;
  
}
function tdf_register_widgets() {
	register_widget('TwitterFeedWidget');
}
function tdf_process_tweets($tweet, $time=false) {
	
			$the_tweet = $tweet['text'];
			$the_error = $tweet['error'];
			if ($the_error) {
				   $the_tweet = '';
			 		__return_false();
		 	}
			else {
					//use the display url
				if(is_array($tweet['entities']['urls'])){
            		foreach($tweet['entities']['urls'] as $key => $link){
                			$the_tweet = preg_replace('`'.$link['url'].'`','<a href="'.$link['url'].'" target="_blank">'.$link['url'].'</a>', $the_tweet);
           			}
        		}
					//  Hashtags must link to a twitter.com search with the hashtag as the query.
        		if(is_array($tweet['entities']['hashtags'])){
            		foreach($tweet['entities']['hashtags'] as $key => $hashtag){
                			$the_tweet = preg_replace('/#'.$hashtag['text'].'/i','<a href="https://twitter.com/search?q=%23'.$hashtag['text'].'&src=hash" target="_blank">#'.$hashtag['text'].'</a>',
                    $the_tweet);
            		}
        		}
					// User_mentions must link to the mentioned user's profile.
        		if(is_array($tweet['entities']['user_mentions'])){
            		foreach($tweet['entities']['user_mentions'] as $key => $user_mention){
                			$the_tweet = preg_replace('/@'.$user_mention['screen_name'].'/i','<a href="http://www.twitter.com/'.$user_mention['screen_name'].'" target="_blank">@'.$user_mention['screen_name'].'</a>',
                    $the_tweet);
            		}
        		}
				if ($time ) {
			$the_tweet .= '<p class="timestamp">
            	<a href="https://twitter.com/YOURUSERNAME/status/'.$tweet['id_str'].'" target="_blank">
 '.date('h:i A M d',strtotime($tweet['created_at']. '- 8 hours')).'
            </a></p>';// -8 GMT for Pacific Standard Time
				}
					
  		 	}
				

		 
		 return $the_tweet;
}
function tdf_add_twitter_Link($instance)
{
	if ('' == trim($instance['linktext'])) {
		 	$linktext = 'View all tweets';
	}
	else {
		$linktext = $instance['linktext'];
	}
	if ('#' != $instance['link'] && '' != trim($instance['link'])) {
		echo '<p><a class="twitterlink" href="'.$instance['link'].'" target="blank">'.$linktext.'</a></p>';
	}
}
//add setup link to plugin so you can go directly to setup after activation
function tdf_base_plugin_link($actions, $plugin_file) {
		static $this_plugin;
		if( !$this_plugin ) {
			 $this_plugin = plugin_basename( __FILE__ );
		}
		if( $plugin_file == $this_plugin ){
			$settingsLink = '<a href="'. admin_url('options-general.php?page=tdf_settings'). '">Setup</a>';
				return array_merge(
				array(
					'settings' => $settingsLink
				),
				$actions
			);
		}
	}
//shortcode function for TwitterFeed
function tdf_twitter_feed($attr){
	echo '<ul>';
		 
	$tweets = getTweets($attr['limit'], $attr['username']);
	if(is_array($tweets)){
		foreach($tweets as $tweet){
			$the_tweet = tdf_process_tweets($tweet, true);
			if ($the_tweet) {
		 		echo '<li>'.$the_tweet.'</li>';
			}
			else {
				echo '<li class="notweets">No tweets found</li>';
				break;
			}
		}
	}		 
		 
	echo '</ul>';
}
function tdf_show_twitter_link($instance){
}

class TwitterFeedWidget extends WP_Widget {
	function TwitterFeedWidget() {
		//processes the widget
		$widget_ops = array (
			'classname' => 'twitter_widget',
			'description' => 'Twitter Widget'
		);
		$this->WP_Widget('TwitterFeedWidget', 'Twitter Widget', $widget_ops);
	}
	function form($instance) {
		//displays widget form in admin dashboard
		$defaults = array (
			'title' => 'Tweets',
			'username' => '',
			'link' => '#',
			'linktext' => 'View all tweets',
            'limit' => 3
		);
		$instance = wp_parse_args((array) $instance, $defaults );?>
		
		<p>Title: <input class="widefat" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($instance['title']) ?>"/></p>
        <p>Username: <input class="widefat" name="<?php echo $this->get_field_name('username');?>" type="text" value="<?php echo esc_attr($instance['username']); ?>"/></p>
        <p>Twitter Link: <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'C' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo esc_url($instance['link']); ?>" /></p>
        <p>Twitter Link Text: <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'linktext' ); ?>" name="<?php echo $this->get_field_name( 'linktext' ); ?>" value="<?php echo esc_attr($instance['linktext']); ?>" /></p>
</p>
        <p><?php $linkbefore = $instance['linkbefore'] ;?>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'tlinkbeforeime' ); ?>" name="<?php echo $this->get_field_name( 'linkbefore' ); ?>" <?php checked($linkbefore, 'on');?> />
				Show Link before Tweets</p>
        <p>Limit:
				<select id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>">
					
					<?php for( $i = 1; $i <= 20; $i++ ) : $selected = ( $instance['limit'] == $i ) ? ' selected="selected"' : '' ?>
					<option value="<?php echo $i ?>"<?php echo $selected ?>><?php echo $i ?></option>
					<?php endfor ?>
				
				</select></p>
                
         <p><?php $time = $instance['time'] ;?>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'time' ); ?>" name="<?php echo $this->get_field_name( 'time' ); ?>" <?php checked($time, 'on');?> />
				Show Time</p>
                
     
<?php	}

	function widget($args, $instance) {
		//displays the widget
		 extract ($args);
		 //var_dump($instance);
		 $title = 	$instance['title'];
		
		 $time = empty( $instance['time']) ? 0 : 1;
         
		 $linkbefore = empty( $instance['linkbefore']) ? 0 : 1;
         
		 echo $before_widget;
		 if (!empty($title)) {
			echo $before_title . $title .$after_title;
		 }
		 if ($linkbefore ) {
		 	tdf_add_twitter_Link($instance);
		 }
		 echo '<ul>';
		 
		 $tweets = getTweets($instance['limit'], $instance['username']);
			if(is_array($tweets)){
				foreach($tweets as $tweet){
					$the_tweet = tdf_process_tweets($tweet, $time);
					if ($the_tweet) {
		 				echo '<li>'.$the_tweet.'</li>';
					}
					else {
						echo '<li class="notweets">No tweets found</li>';
						break;
					}
				}
			}
		 
		 
		 echo '</ul>';
		 if (!$linkbefore ) {
		 	tdf_add_twitter_Link($instance);
		 }
		 echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		//save widdget settings
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['link'] =  $new_instance['link'];
		$instance['linktext'] = strip_tags( $new_instance['linktext'] );
		$instance['linkbefore'] = strip_tags( $new_instance['linkbefore'] );
		$instance['time'] = strip_tags( $new_instance['time'] );
		$instance['limit'] = strip_tags( $new_instance['limit'] );


		return $instance;
		
	}
}