<?php
/*
* Version 2.2.1
* The base class for the storm twitter feed for developers.
* This class provides all the things needed for the wordpress plugin, but in theory means you don't need to use it with wordpress.
* What could go wrong?
*/


if (!class_exists('TwitterOAuth')) {
  require_once('oauth/twitteroauth.php');
} else {
  define('TFD_USING_EXISTING_LIBRARY_TWITTEROAUTH',true);
}

class StormTwitter {

  private $defaults = array(
    'directory' => '',
    'key' => '',
    'secret' => '',
    'token' => '',
    'token_secret' => '',
    'screenname' => '',
    'cache_expire' => 3600      
  );
  
  public $st_last_error = false;
  
  function __construct($args = array()) {
    $this->defaults = array_merge($this->defaults, $args);
  }
  
  function __toString() {
    return print_r($this->defaults, true);
  }
  
  function getTweets($screenname = false,$count = 20,$options = false) {
    // BC: $count used to be the first argument
    if (is_int($screenname)) {
      list($screenname, $count) = array($count, $screenname);
    }
    
    if ($count > 20) $count = 20;
    if ($count < 1) $count = 1;
    
    $default_options = array('trim_user'=>true, 'exclude_replies'=>true, 'include_rts'=>false);
    
    if ($options === false || !is_array($options)) {
      $options = $default_options;
    } else {
      $options = array_merge($default_options, $options);
    }
    
    if ($screenname === false || $screenname === 20) $screenname = $this->defaults['screenname'];
  
    $result = $this->checkValidCache($screenname,$options);
    
    if ($result !== false) {
      return $this->cropTweets($result,$count);
    }
    
    //If we're here, we need to load.
    $result = $this->oauthGetTweets($screenname,$options);
    
    if (is_array($result) && isset($result['errors'])) {
      if (is_array($result) && isset($result['errors'][0]) && isset($result['errors'][0]['message'])) {
        $last_error = $result['errors'][0]['message'];
      } else {
        $last_error = $result['errors'];
      }
      return array('error'=>'Twitter said: '.json_encode($last_error));
    } else {
      if (is_array($result)) {
        return $this->cropTweets($result,$count);
      } else {
        $last_error = 'Something went wrong with the twitter request: '.json_encode($result);
        return array('error'=>$last_error);
      }
    }
    
  }
  
  private function cropTweets($result,$count) {
    return array_slice($result, 0, $count);
  }
  
  private function getCacheLocation() {
    return $this->defaults['directory'].'.tweetcache';
  }
  
  private function getOptionsHash($options) {
    $hash = md5(serialize($options));
    return $hash;
  }
  
  private function checkValidCache($screenname,$options) {
    $file = $this->getCacheLocation();
    if (is_file($file)) {
      $cache = file_get_contents($file);
      $cache = @json_decode($cache,true);
      
      if (!isset($cache)) {
        unlink($file);
        return false;
      }
      
      // Delete the old cache from the first version, before we added support for multiple usernames
      if (isset($cache['time'])) {
        unlink($file);
        return false;
      }
      
      $cachename = $screenname."-".$this->getOptionsHash($options);
      
      //Check if we have a cache for the user.
      if (!isset($cache[$cachename])) return false;
      
      if (!isset($cache[$cachename]['time']) || !isset($cache[$cachename]['tweets'])) {
        unset($cache[$cachename]);
        file_put_contents($file,json_encode($cache));
        return false;
      }
      
      if ($cache[$cachename]['time'] < (time() - $this->defaults['cache_expire'])) {
        $result = $this->oauthGetTweets($screenname,$options);
        if (!isset($result['errors'])) {
          return $result;
        }
      }
      return $cache[$cachename]['tweets'];
    } else {
      return false;
    }
  }
  
  private function oauthGetTweets($screenname,$options) {
    $key = $this->defaults['key'];
    $secret = $this->defaults['secret'];
    $token = $this->defaults['token'];
    $token_secret = $this->defaults['token_secret'];
    
    $cachename = $screenname."-".$this->getOptionsHash($options);
    
    $options = array_merge($options, array('screen_name' => $screenname, 'count' => 20));
    
    if (empty($key)) return array('error'=>'Missing Consumer Key - Check Settings');
    if (empty($secret)) return array('error'=>'Missing Consumer Secret - Check Settings');
    if (empty($token)) return array('error'=>'Missing Access Token - Check Settings');
    if (empty($token_secret)) return array('error'=>'Missing Access Token Secret - Check Settings');
    if (empty($screenname)) return array('error'=>'Missing Twitter Feed Screen Name - Check Settings');
    
    $connection = new TwitterOAuth($key, $secret, $token, $token_secret);
    $result = $connection->get('statuses/user_timeline', $options);
    
    if (is_file($this->getCacheLocation())) {
      $cache = json_decode(file_get_contents($this->getCacheLocation()),true);
    }
    
    if (!isset($result['errors'])) {
      $cache[$cachename]['time'] = time();
      $cache[$cachename]['tweets'] = $result;
      $file = $this->getCacheLocation();
      file_put_contents($file,json_encode($cache));
    } else {
      if (is_array($results) && isset($result['errors'][0]) && isset($result['errors'][0]['message'])) {
        $last_error = '['.date('r').'] Twitter error: '.$result['errors'][0]['message'];
        $this->st_last_error = $last_error;
      } else {
        $last_error = '['.date('r').'] Twitter returned an invalid response. It is probably down.';
        $this->st_last_error = $last_error;
      }
    }
    
    return $result;
  
  }
}
