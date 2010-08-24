<?php
/*
Plugin Name: oEmbed styling
Plugin URI: http://www.honza.info/category/wordpress/
Description: This plugins wraps oEmbed generated HTML with divs that allow for styling them with CSS (style sheet of the theme)
Version: 1.0
Author: Honza Skypala
Author URI: http://www.honza.info/
*/

define('OEMBED_STYLING_VERSION', '1.0');

function oembed_styling_activate() {
	oembed_styling_delete_whole_cache();
}

function oembed_styling_deactivate() {
	oembed_styling_delete_whole_cache();
}

function oembed_styling_delete_whole_cache() {
	// flush the complete oEmbed cache, so they are regenerated
	$allposts = get_posts('numberposts=-1');
	foreach ($allposts as $post) {
		$post_metas = get_post_custom_keys($post->ID);
		if (!empty($post_metas)) {
			foreach($post_metas as $post_meta_key) {
				if ('_oembed_' == substr($post_meta_key, 0, 8))
					delete_post_meta($post->ID, $post_meta_key);
			}
		}
	}
}

function oembed_styling_apply($html, $data, $url) {
	$result = "<div class=\"oembed oembed-$data->type";
	preg_match('#(http|ftp)s?://(www\.)?([a-z0-9\.\-]+)/?.*#i', $url, $matches);
	$server4css = str_replace(".", "-", $matches[3]);
	$result .= " oembed-".$server4css;
	$result .= " oembed-$data->type-".$server4css;
	$result .= "\">".$html."</div>";
	return $result;
}

add_filter('oembed_dataparse', 'oembed_styling_apply', 10, 3);

register_activation_hook(__FILE__, 'oembed_styling_activate');
register_deactivation_hook(__FILE__, 'oembed_styling_deactivate');

?>