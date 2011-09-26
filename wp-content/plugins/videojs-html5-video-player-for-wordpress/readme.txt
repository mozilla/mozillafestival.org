=== VideoJS - HTML5 Video Player for Wordpress ===
Contributors: Steve Heffernan
Donate link: http://videojs.com/
Tags: html5, video, player, javascript
Requires at least: 2.6
Tested up to: 3.0.3
Stable tag: 2.0.2

VideoJS is the most widely used HTML5 Video Player available. It allows you to embed video in your post or page using HTML5.

== Description ==

A video plugin for WordPress built on the VideoJS HTML5 video player library. Allows you to embed video in your post or page using HTML5 with Flash fallback support for non-HTML5 browsers.

View <a href="http://videojs.com/">VideoJS.com</a> for additional information.

== Installation ==

View [VideoJS.com](http://videojs.com/) for more information.

This section describes how to install the plugin and get it working.

1. Upload the `videojs-html5-video-player-for-wordpress` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use the [video] shortcode in your post or page using the following options.

Video Shortcode Options
-----------------------

### mp4
The location of the h.264/MP4 source for the video.
    
    [video mp4="http://video-js.zencoder.com/oceans-clip.mp4"]

### ogg
The location of the Theora/Ogg source for the video.

    [video ogg="http://video-js.zencoder.com/oceans-clip.ogg"]

### webm
The location of the VP8/WebM source for the video.

    [video ogg="http://video-js.zencoder.com/oceans-clip.webm"]

### poster
The location of the poster frame for the video.

    [video poster="http://video-js.zencoder.com/oceans-clip.png"]

### width
The width of the video.

    [video width="640"]

### height
The height of the video.

    [video height="264"]

### preload
Start loading the video as soon as possible, before the user clicks play.

    [video preload="true"]

### autoplay
Start playing the video as soon as it's ready.

    [video autoplay="true"]

### All Attributes Example

    [video mp4="http://video-js.zencoder.com/oceans-clip.mp4" ogg="http://video-js.zencoder.com/oceans-clip.ogg" webm="http://video-js.zencoder.com/oceans-clip.webm" poster="http://video-js.zencoder.com/oceans-clip.png" preload="true" autoplay="true" width="640" height="264"]


== Changelog ==

= 2.0.2 = 

* Upgraded to version 2.0.2 of VideoJS. See videojs.com for specific version updates.

= 1.1.3 = 

* Added poster to Flash fallback

= 1.1.2 = 

* Updated to VideoJS 1.1.2
* Fixed the default plugin folder name.
* Fixed an issue with autoplay not working correctly.

= 1.0.1 =

* Updated to latest version of VideoJS

= 1.0 =

* First release.

== Updating Info (notes to self) ==

http://wordpress.org/extend/plugins/about/svn/

- Copy new version of VideoJS, not including skins directory (would overwrite svn info).
- Copy new skin versions to skins dir.
- Update version numbers. Readme (Top & Changelog), PHP (2 places)
- Test.
- svn up (update)
- svn stat / svn diff
- svn ci -m "fancy new feature: now you can foo *and* bar at the same time"
- svn cp trunk tags/2.0
- svn ci -m "tagging version 2.0"