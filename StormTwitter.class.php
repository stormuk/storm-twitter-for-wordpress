<?php
/*
* The base class for the storm twitter feed for developers.
* This class provides all the things needed for the wordpress plugin, but in theory means you don't need to use it with wordpress.
* What could go wrong?
*/

class StormTwitter {

  private $defaults = array(
    'directory' => '',
    'key' => '',
    'secret' => '',
    'token' => '',
    'token_secret' => '',
    'screenname' => ''      
  );
  
  public $st_last_cached = false;
  public $st_last_error = false;
  
  function __construct($args = array()) {
    $this->defaults = array_merge($this->defaults, $args);
  }
  
  function __toString() {
    return print_r($this->defaults, true);
  }
  
  function getTweets($count = 20) {
    if ($count > 20) $count = 20;
    if ($count < 1) $count = 1;
  
    $result = $this->checkValidCache();
    
    if ($result !== false) {
      return $this->cropTweets($result,$count);
    }
    
    //If we're here, we need to load.
    $result = $this->oauthGetTweets();
    
    if (isset($result['errors'])) {
      return array('error'=>'Twitter said: '.$result['errors'][0]['message']);
    } else {
      return $this->cropTweets($result,$count);
    }
    
  }
  
  function cropTweets($result,$count) {
    return array_slice($result, 0, $count);
  }
  
  function getCacheLocation() {
    return $this->defaults['directory'].'.tweetcache';
  }
  
  function checkValidCache() {
    $file = $this->getCacheLocation();
    if (is_file($file)) {
      $cache = file_get_contents($file);
      $cache = @json_decode($cache,true);
      if (count($cache) != 2) {
        unlink($file);
        return false;
      }
      if (!isset($cache['time']) || !isset($cache['tweets'])) {
        unlink($file);
        return false;
      }
      if ($cache['time'] < (time() - 1)) {
        $result = $this->oauthGetTweets();
        if (!isset($result['errors'])) {
          return $result;
        }
      }
      return $cache['tweets'];
    } else {
      return false;
    }
  }
  
  function oauthGetTweets() {
    $key = $this->defaults['key'];
    $secret = $this->defaults['secret'];
    $token = $this->defaults['token'];
    $token_secret = $this->defaults['token_secret'];
    $screenname = $this->defaults['screenname'];
    
    if (empty($key)) return array('error'=>'Missing Consumer Key - Check Settings');
    if (empty($secret)) return array('error'=>'Missing Consumer Secret - Check Settings');
    if (empty($token)) return array('error'=>'Missing Access Token - Check Settings');
    if (empty($token_secret)) return array('error'=>'Missing Access Token Secret - Check Settings');
    if (empty($screenname)) return array('error'=>'Missing Twitter Feed Screen Name - Check Settings');
    
    $connection = new TwitterOAuth($key, $secret, $token, $token_secret);
    $result = $connection->get('statuses/user_timeline', array('screen_name' => $screenname, 'count' => 20, 'trim_user' => true));
    
    if (!isset($result['errors'])) {
      $cache['time'] = time();
      $cache['tweets'] = $result;
      $file = $this->getCacheLocation();
      file_put_contents($file,json_encode($cache));
      $this->st_last_cached = $cache['time'];
    } else {
      $last_error = '['.date('r').'] Twitter error: '.$result['errors'][0]['message'];
      $this->st_last_error = $last_error;
    }
    
    return $result;
  
  }
}