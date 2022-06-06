=== Switch Post Status ===

Description:        Switch post status from draft to publish and back.
Version:            1.0.1
Requires PHP:       5.6
Requires:           1.1.0
Tested:             4.9.99
Author:             Gieffe edizioni
Author URI:         https://www.gieffeedizioni.it
Plugin URI:         https://software.gieffeedizioni.it
Download link:      https://github.com/xxsimoxx/switch-post-status/releases/download/1.0.1/switch-post-status-1.0.1.zip
License:            GPLv2
License URI:        https://www.gnu.org/licenses/gpl-2.0.html

Switch post status from draft to publish and back.

== Description ==
# Plugin description

This plugin allows to switch post status from draft to publish and back using a link in row actions.

### Configuring other actions

Use the `xsxswitch_lookup` filter.

Example:

```php
add_filter ('xsxswitch_lookup', 'myprefix_custom_sps_actions');
function myprefix_custom_sps_actions($lookup) {

	// Let it handle private posts and convert them to published.
	$lookup['private'] = [
		'dst' => 'publish',
		'msg' => 'Switch to a great public post',
	];
	
	// Change the text to a custom one for draft posts.
	$lookup['draft'] = [
		'dst' => 'publish',
		'msg' => 'My custom lovely message',
	];
	
	return $l;
}
```

== Frequently asked questions ==

> Do you track plugin usage?

To help us know the number of active installations of this plugin, we collect and store anonymized data when the plugin check in for updates. The date and unique plugin identifier are stored as plain text and the requesting URL is stored as a non-reversible hashed value. This data is stored for up to 28 days.


== Screenshots ==
1. How it looks.

== Changelog ==

= 1.0.1 =
* Added readme.txt
* Updated UpdateClient.class.php

= 1.0.0 =
* Initial release