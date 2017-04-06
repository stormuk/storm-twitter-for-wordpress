Storm Twitter Feed for WordPress
================================

A Twitter API 1.1 compliant wordpress plugin that provides an array of a users timeline from Twitter for use by theme developers.

The new Twitter API requires you be oAuth'd before you can request a list of tweets, this means that all of the existing Twitter plugins that simply make an AJAX request for to the JSON API endpoint broke in March 2013. 

This was a major problem for the vast majority of websites that are currently using twitter, so we built a PHP class that implements all the new requirements for authentication and gives you an array of tweets out of the other end, for you to use in your PHP applications, or WordPress theme.  You can find the stand-alone [StormTwitter](https://github.com/stormuk/storm-twitter) class on GitHub

This plugin wraps our Twitter class and provides a settings screen for easy integration into WordPress.  However, it's definitely for developers - you only get a PHP array out of it that contains Twitter tweet objects. You'll still need to style the output and make it comply with the new display requirements.

This plugin does not provide sidebar widgets, shortcodes or any other form of drop-in functionality.  You still need to do the styling, we've just done the heavy lifting for you!

Installation
============

Install the plugin using the plugin manager, or upload the files to your wp-content/plugins directory.

Navigate to the Settings > Twitter Feed Auth.

Here you'll find settings fields to authenticate with Twitter.  You'll need to create a new Application on http://dev.twitter.com/apps.

Once you've create the app, scroll down the app's details page to find the oAuth section.  Copy the consumer secret and consumer key into the settings page for the plugin.  Then click the Create Access Token button at the bottom of the Twitter app page.  Copy the Access token and Access token secret into the plugin's settings page.  Finally, enter the Twitter username of the feed you want to access.  Save the settings.

Now, anywhere in your theme files you can call the `getTweets()` function to retrieve an array of tweets.

You can then loop over the array and do whatever you want with it.

    `<?php
      $tweets = getTweets($twitter_screenname_to_load, $number_of_tweets, $optional_array_of_any_additional_twitter_api_parameters);
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

Output
======

As we said, this plugin does not provide sidebar widgets, shortcodes or any other form of drop-in functionality.  Here's some example code for outputting the tweets in HTML:

https://github.com/stormuk/storm-twitter-for-wordpress/wiki/Example-code-to-layout-tweets

Credits
=======

Uses Abraham Williams's Twitter OAuth class.

About
=====

Version: 2.3.0

Written by Liam Gladdy of Storm Consultancy - <http://www.stormconsultancy.co.uk>

Storm Consultancy are a web design and development agency based in Bath, UK.

If you are looking for a [Bath WordPress Developer](http://www.stormconsultancy.co.uk/services/bath-wordpress-developers), then [get in touch](http://www.stormconsultancy.co.uk/contact)!

License
=======

Copyright (c) 2016 Storm Consultancy (EU) Ltd and Liam Gladdy, 
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
