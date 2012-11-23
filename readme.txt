=== oAuth Twitter Feed for Developers ===
Contributors: stormuk, lgladdy
Donate link: http://www.stormconsultancy.co.uk/
Tags: twitter, oauth, feed, tweets
Requires at least: 3.4
Tested up to: 3.5
Stable tag: 1.0.5
Version: 1.0.5
License: MIT
License URI: http://opensource.org/licenses/MIT

Twitter API 1.1 compliant plugin that provides a function to get an array of tweets from the auth'd users Twitter feed for use in themes.

== Description ==

A Twitter API 1.1 compliant wordpress plugin that provides an array of a users timeline from Twitter for use by theme developers.

The new Twitter API requires you be oAuth'd before you can request a list of tweets, this means that all of the existing Twitter plugins that simply make an AJAX request for to the JSON API endpoint will break as of March 2013. 

This is a major problem for the vast majority of websites that are currently using twitter, so we built a PHP class that implements all the new requirements for authentication and gives you an array of tweets out of the other end, for you to use in your PHP applications, or WordPress theme.  You can find the stand-alone [StormTwitter](https://github.com/stormuk/storm-twitter) class on GitHub

This plugin wraps our Twitter class and provides a settings screen for easy integration into WordPress.  However, it's definitely for developers - you only get a PHP array out of it that contains Twitter tweet objects. You'll still need to style the output and make it comply with the new display requirements.

This plugin does not provide sidebar widgets, shortcodes or any other form of drop-in functionality.  You still need to do the styling, we've just done the heavy lifting for you!

== Installation ==

Install the plugin using the plugin manager, or upload the files to your wp-content/plugins directory.

Navigate to the Settings > Twitter Feed Auth.

Here you'll find settings fields to authenticate with Twitter.  You'll need to create a new Application on http://dev.twitter.com/apps.

Once you've create the app, scroll down the app's details page to find the oAuth section.  Copy the consumer secret and consumer key into the settings page for the plugin.  Then click the Create Access Token button at the bottom of the Twitter app page.  Copy the Access token and Access token secret into the plugin's settings page.  Finally, enter the Twitter username of the feed you want to access.  Save the settings.

Now, anywhere in your theme files you can call the `getTweets()` function to retrieve an array of tweets.

You can then loop over the array and do whatever you want with it.

    `<?php
      $tweets = getTweets();
      var_dump($tweets);

      foreach($tweets as $tweet){
        var_dump($tweet);
      }
    ?>`

You can specify a number of tweets to return (up to 20) by passing a parameter to the function.  For example, to display just the latest tweet you'd request `getTweets(1)`

Results are cached for 1 hour to help you avoid hitting the API limits.

== TODO ==

* Move the screen name from the settings page to a function parameter so you can use the plugin to request different timelines
* Make the cache duration configurable

== Credits ==

Uses Abraham Williams's Twitter OAuth class.

== About ==

Version: 1.0.5

Written by Liam Gladdy of Storm Consultancy - <http://www.stormconsultancy.co.uk>

Storm Consultancy are a web design and development agency based in Bath, UK.

If you are looking for a [Bath WordPress Developer](http://www.stormconsultancy.co.uk/services/bath-wordpress-developers), then [get in touch](http://www.stormconsultancy.co.uk/contact)!

== License ==

Copyright (c) 2012 Storm Consultancy (EU) Ltd, 
<http://www.stormconsultancy.co.uk/>

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

= 1.0.4 =
* Make the plugin actually work properly!
* Correct documentation files for inclusion by wordpress

= 1.0 =
* First version