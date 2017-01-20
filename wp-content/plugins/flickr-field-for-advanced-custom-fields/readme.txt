=== Flickr field for Advanced Custom Fields ===
Contributors: phuisman
Tags: flickr, acf, custom, fields, photostream, photosets
Requires at least: 3.0.1
Tested up to: 4.4.1
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display items from your Flickr photostream or entire sets/galleries along with your WordPress content.

== Description ==

The Flickr Field will grant you the ability to include photos, sets and galleries from your Flickr account. After selecting which image formats you wish to use the plugin will generate the flickr image urls. This plugin is an add-on for the Advanced Custom Fields WordPress plugin (v4 and v5).

This plugins also grants you the ability to enable "Private Mode". Here you have to authenticate your account and generate a token which you can use to access your own private pictures. These private pictures are not available for the public on your Flickr.com account.

See the installation and/or screenshots tab for more info.

Looking for a clean and very easy to setup lightbox? I recommend Easy Fancybox: http://wordpress.org/plugins/easy-fancybox/

== Installation ==

1. Make sure you have Advanced Custom Fields **v4** or **v5** installed. Get it at http://www.advancedcustomfields.com/.
2. Upload the `flickr field` directory to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

Usage Example (in PHP):

**Getting the contents of a photostream and looping through the results**

	$flickr_photostream = get_field(FIELD_NAME);

	if (isset($flickr_photostream['items'])) {
		foreach ($flickr_photostream['items'] as $id => $photo) {
			echo '<a href="' . $photo['large'] . '" title="' . $photo['title'] . '"><img src="' . $photo['thumb'] . '" /></a>';
		}
	}

**Getting the contents of a set and looping through the results**

	$flickr_set = get_field(FIELD_NAME);

	if (isset($flickr_set['items'])) {
		foreach ($flickr_set['items'] as $id => $photos) {
			foreach ($photos as $photo) {
				echo '<a href="' . $photo['large'] . '" title="' . $photo['title'] . '"><img src="' . $photo['thumb'] . '" /></a>';
			}
		}
	}

== Screenshots ==

1. Setting up the field
2. Selecting a photo set
3. Selecting individual photos from your photostream

== Changelog ==

= 1.1.2 =
* Added seperate caching for the admin loading of flickr data. You now have a setting for front-end caching and admin (back-end) caching.

= 1.1.1 =
* You can now (optionally) use constants for flickr api key, secret and api token (for private mode). This could be useful in a multisite environment.

= 1.1.0 =
* Added Private Mode - you can now enable a mode where you can display private photos from Flickr (those that are not publicly available)

= 1.0.8 =
* Added possibility to set your own cache dir location with constant FLICKR_FIELD_CACHE_DIR

= 1.0.7 =
* Added feature so that you can control the amount of images that will be loaded in a photoset

= 1.0.6 =
* Small bugfix to get rid of notification

= 1.0.5 =
* Added feature to limit the maximum selectable amount of sets/photos

= 1.0.4 =
* Added possibility to display original format of the photo

= 1.0.3 =
* Fixed a bug where you could not deselect the flickr item and save correctly

= 1.0.2 =
* Fixed a bug where you could not include two flickr fields on the same post

= 1.0.1 =
* Updated Flickr Field to work with ACF5 most recent API changes

= 1.0.0 =
* First version of the plugin.
