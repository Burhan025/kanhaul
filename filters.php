<?php

/* Excerpt "read more"
 * ----------------------- */
function new_excerpt_more($more) {
	global $post;
	return '...<br /><a href="'.get_permalink($post->ID).'">Read More &raquo;</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

/* Remove title attribute from wp_list_categories
 * ----------------------- */
function wp_list_categories_remove_title_attributes($output) {
    $output = preg_replace('` title="(.+)"`', '', $output);
    return $output;
}
add_filter('wp_list_categories', 'wp_list_categories_remove_title_attributes');

/* Remove title attribute from wp_list_pages
 * ----------------------- */
function clean_wp_list_pages($menu) {
	$clean_page_list = preg_replace('/title=\"(.*?)\"/','',$menu);
	return $clean_page_list;
}
add_filter( 'wp_list_pages', 'clean_wp_list_pages' );