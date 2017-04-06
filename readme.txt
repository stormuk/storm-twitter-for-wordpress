=== oAuth Twitter Feed for Developers ===
Contributors: lgladdy, stormuk
Donate link: https://stormconsultancy.co.uk
Tags: twitter, oauth, feed, tweets
Requires at least: 3.4
Tested up to: 4.7.3
Stable tag: 2.3.0
Version: 2.3.0
License: MIT
License URI: http://opensource.org/licenses/MIT

Twitter API 1.1 compliant plugin that provides a function to get an array of tweets from the auth'd users Twitter feed for use in themes.

== Description ==

A Twitter API 1.1 compliant wordpress plugin that provides an array of a users timeline from Twitter for use by theme developers.

The new Twitter API requires you be oAuth'd before you can request a list of tweets, this means that all of the existing Twitter plugins that simply make an AJAX request for to the JSON API endpoint broke in March 2013. 

This wass a major problem for the vast majority of websites that are currently using twitter, so we built a PHP class that implements all the new requirements for authentication and gives you an array of tweets out of the other end, for you to use in your PHP applications, or WordPress theme.  You can find the stand-alone [StormTwitter](https://github.com/stormuk/storm-twitter) class on GitHub

This plugin wraps our Twitter class and provides a settings screen for easy integration into WordPress.  However, it's definitely for developers - you only get a PHP array out of it that contains Twitter tweet objects. You'll still need to style the output and make it comply with the new display requirements.

This plugin does not provide sidebar widgets, shortcodes or any other form of drop-in functionality.  You still need to do the styling, we've just done the heavy lifting for you!

Here's some example code for outputting the tweets in HTML:

https://github.com/stormuk/storm-twitter-for-wordpress/wiki/Example-code-to-layout-tweets

== Installation ==

Install the plugin using the plugin manager, or upload the files to your wp-content/plugins directory.

Navigate to the Settings > Twitter Feed Auth.

Here you'll find settings fields to authenticate with Twitter.  You'll need to create a new Application on http://dev.twitter.com/apps.

Once you've create the app, scroll down the app's details page to find the oAuth section.  Copy the consumer secret and consumer key into the settings page for the plugin.  Then click the Create Access Token button at the bottom of the Twitter app page.  Copy the Access token and Access token secret into the plugin's settings page.  Finally, enter the Twitter username of the feed you want to access.  Save the settings.

Now, anywhere in your theme files you can call the `getTweets()` function to retrieve an array of tweets.

You can then loop over the array and do whatever you want with it.

    `<?php
      $tweets = getTweets($number_of_tweets, $twitter_screenname_to_load, $optional_array_of_any_additional_twitter_api_parameters);
      var_dump($tweets);

      foreach($tweets as $tweet){
        var_dump($tweet);
      }
    ?>`

You can specify a number of tweets to return (up to 20) by passing a parameter to the function.  For example, to display just the latest tweet you'd request `getTweets(1)`

The following default options are used unless you override them in the optional array of additional parameters.

* Trim the user object ("trim_user" => true)
* Exclude replies ("exclude_replies" => true)
* Exclude retweets ("include_rts" => false)

Results are cached for 1 hour (by default) to help you avoid hitting the API limits.

== Credits ==

Uses Abraham Williams's Twitter OAuth class.

== About ==

Version: 2.3.0

Written by Liam Gladdy of Storm Consultancy - <http://www.stormconsultancy.co.uk>

Storm Consultancy are a web design and development agency based in Bath, UK.

If you are looking for a [Bath WordPress Developer](http://www.stormconsultancy.co.uk), then [get in touch](http://www.stormconsultancy.co.uk/contact)!

== License ==

Copyright (c) 2016 Liam Gladdy and Storm Consultancy (EU) Ltd, 
<https://gladdy.uk/>, <http://www.stormconsultancy.co.uk/>

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

== Changelog ==

= 2.3.0 =
* Support for WordPress's new internationalization system.

= 2.2.1 =
* Grah! Fixes a fairly major issue where if not loading from a cache, the tweet cache is an object, rather than an array. This should be configurable (maybe in 2.3), but for now - fix it. 

= 2.2.0 =
* Check if any of our libraries are loaded by other plugins. If so, don't load them and produce a warning in our settings. This will prevent fatal errors when any other plugin uses OAuth or TwitterOAuth.

= 2.1.3 =
* Fixes 2.1 for people using the very old getTweets($int) syntax. You should still change to the new version, but this will at least not be broken!

= 2.1.2 =
* Just a version bump - I'm the worst at remembering to update all the right places.

= 2.1.1 =
* Add support for a proxy server, as defined in wp-settings.php (Thanks, josmeer)

= 2.1 =
* Change default and prefered method of calling to username, then count (For backwards compatibility, both will work)
* Only include OAuth if an OAuthRequest class isn't already defined. This should stop some errors some folks have with other plugins.
* Bug Fixes

= 2.0.3 =
* Further defensive code against twitter abnormalities 

= 2.0.2 =
* Be a touch more graceful when Twitter is down or returning invalid data.
* Please make sure twitter is online before you upgrade - The update invalidates your cache and will display an error if twitter is offline.

= 2.0.1 =
* Please make sure twitter is online before you upgrade - The update invalidates your cache and will display an error if twitter is offline.
* Fix an issue with upgrading from 1.0.6, which turned out to not be an issue at all, and is actually because twitter went down and scared me very much.

= 2.0.0 =
* Support multiple screennames
* Support additional parameters to pass on to twitter (for excluding RTs, etc)
* Support custom cache expiry

= 1.0.4 =
* Make the plugin actually work properly!
* Correct documentation files for inclusion by wordpress

= 1.0 =
* First version