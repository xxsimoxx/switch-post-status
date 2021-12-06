# Switch Post Status fom draft to publish and back.

This plugin allows to switch post status fom draft to publish and back using a link in row actions.

## Configuring other actions

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


## Privacy

To help us know the number of active installations of this plugin, we collect and store anonymized data when the plugin check in for updates. The date and unique plugin identifier are stored as plain text and the requesting URL is stored as a non-reversible hashed value. This data is stored for up to 28 days.

