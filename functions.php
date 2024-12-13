<?php

// Enable Post Thumbnails
add_theme_support( 'post-thumbnails' );

// Add support for excerpts on pages
add_post_type_support('page', 'excerpt');

// Comments!
require_once( TEMPLATEPATH . '/library/comments.php' );

// Include filters
require_once(TEMPLATEPATH.'/library/filters.php');

// Custom post types
require_once(TEMPLATEPATH.'/library/cpt-slider.php');
require_once(TEMPLATEPATH.'/library/cpt-testimonial.php');

/* Site Fixes Post Hack */
function register_menu() {
    register_nav_menu('main-menu', __('Main Menu'));
}
add_action('init', 'register_menu'); 

function custom_button_shortcode($atts, $content = null) {
    // Extract shortcode attributes
    extract(shortcode_atts(array(
        'link'   => '#',
        'color'  => '#990000',
        'size'   => '16',
        'style'  => 'default',
        'target' => '_self'
    ), $atts));

    // Sanitize attribute values
    $link    = esc_url($link);
    $color   = esc_attr($color);
    $size    = intval($size); // Convert size to integer
    $style   = esc_attr($style);
    $target  = in_array($target, array('_blank', '_self', '_parent', '_top')) ? $target : '_self';

    // If the size is less than 16, set it to 16
    $size = max($size, 16);

    // Construct the button HTML
    $output = '<a href="' . $link . '" class="su-button su-button-style-default" style="color:' . $color . ';background-color:#2D89EF;border-color:#246ec0;border-radius:7px;-moz-border-radius:7px;-webkit-border-radius:7px" target="' . $target . '">';
    $output .= '<span style="color:' . $color . ';padding:0px 20px;font-size:' . $size . 'px;line-height:32px;border-color:#6cadf4;border-radius:7px;-moz-border-radius:7px;-webkit-border-radius:7px;text-shadow:none;-moz-text-shadow:none;-webkit-text-shadow:none">';
    $output .= do_shortcode($content); // Render nested shortcodes within content
    $output .= '</span></a>';

    return $output;
}
add_shortcode('button', 'custom_button_shortcode');


function custom_spoiler_shortcode($atts, $content = null) {
    // Extract shortcode attributes
    extract(shortcode_atts(array(
        'title' => '',
        'open' => '0',
        'style' => '1'
    ), $atts));

    // Sanitize attribute values
    $title = esc_html($title);
    $open = $open == '1' ? 'true' : 'false';
    $style = intval($style);

    // Construct the spoiler HTML
    $output = '<div class="su-spoiler su-spoiler-style-default su-spoiler-icon-plus" data-scroll-offset="0" data-anchor-in-url="no">';
    $output .= '<div class="su-spoiler-title" tabindex="0" role="button"><span class="su-spoiler-icon"></span>' . $title . '</div>';
    $output .= '<div class="su-spoiler-content su-u-clearfix su-u-trim">' . do_shortcode($content) . '</div>';
    $output .= '</div>';

    return $output;
}
add_shortcode('spoiler', 'custom_spoiler_shortcode');


function custom_subpages_shortcode($atts) {
    // Extract shortcode attributes
    extract(shortcode_atts(array(
        'depth' => 1,
    ), $atts));

    // Get current page ID
    global $post;
    $parent_id = $post->ID;

    // Get subpages of current page
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_parent'    => $parent_id,
        'order'          => 'ASC',
        'orderby'        => 'menu_order',
        'depth'          => intval($depth),
    );
    $subpages = get_pages($args);

    // Start output
    $output = '<ul class="su-subpages">';
    foreach ($subpages as $subpage) {
        // Check if the subpage is a direct child
        if ($subpage->post_parent == $parent_id) {
            $output .= '<li class="page_item page-item-' . $subpage->ID . '">';
            $output .= '<a href="' . get_permalink($subpage->ID) . '">' . $subpage->post_title . '</a>';
            $output .= '</li>';
        }
    }
    $output .= '</ul>';

    return $output;
}
add_shortcode('subpages', 'custom_subpages_shortcode');

/* End Site Fixes */

// Image sizes
add_image_size( 'slider', 940, 375, true );
add_image_size( 'subpage', 940, 260, true );

// Create widget areas
register_sidebar(array(
	'name' 			=> 'Sidebar',
	'id'			=> 'sidebar',
	'description' 	=> '',
	'before_widget' => '<div class="module">',
	'after_widget'	=> '</div><div class="module_bottom"></div></div>',
	'before_title' 	=> '<h2>',
	'after_title' 	=> '</h2><div class="module_text">'
));

// Editor stylesheet
// ** 09/17/16 - This is causing weird issues with the WYSIWYG Editor - GW
//add_editor_style('css/editor-style.css');



// Custom Protected Page Message	
	add_filter( 'the_password_form', 'custom_password_form' );
function custom_password_form() {
	global $post;
	$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );?>
	<form class="protected-post-form" action="<?php bloginfo('url'); ?>/wp/wp-pass.php" method="post">
	<p>To view this page please enter the password below:</p>
	<label for="<?php $label ?>"><b>Password:</b></label><input name="post_password" id="<?php $label ?>" type="password" size="30" /><input type="submit" name="Submit" value="Submit" />
	</form> <p style="margin-top:20px;">or to obtain the password please fill out the Password Request Form below:</p>
	<?php gravity_form(2, false, false, false, '', true);
}

function the_title_trim($title)
{
$pattern[0] = '/Protected: /';
$pattern[1] = '/Private: /';
$replacement[0] = ''; // Enter some text to put in place of Protected:
$replacement[1] = ''; // Enter some text to put in place of Private:

return preg_replace($pattern, $replacement, $title);
}
add_filter('the_title', 'the_title_trim');





// Get the page number
function get_page_number() {
    if (get_query_var('paged')) {
        print ' | ' . __( 'Page ' , 'shape') . get_query_var('paged');
    }
}

function headerimg() {
 if ( is_home() || is_single() || is_archive() ) { 
 		$blogimg = wp_get_attachment_image_src(get_field('blog_header_image', 63), 'subpage');?>
		<img src="<?php echo $blogimg[0]; ?>" alt="<?php get_the_title(get_field('blog_header_image')) ?>" />
	  <?php } else { 
			if ( has_post_thumbnail()) {
				 the_post_thumbnail('subpage', array('title' => ''));
				 } else { 
				 $headerimg = wp_get_attachment_image_src(get_field('default_header_image', 63), 'subpage');?>
				 <img src="<?php echo $headerimg[0]; ?>" alt="<?php get_the_title(get_field('default_header_image')) ?>" />
				 <?php }
		     } 
}
// Check to see if the current page is in the specifed "tree"
function in_tree($pid) {
	global $post;
	
	if(!is_numeric($pid)) {
		$page = get_page_by_title($pid);
		$pid = $page->ID;
	}

	$ancestors = get_post_ancestors($post->$pid);
	$root = count($ancestors) - 1;
	$parent = $ancestors[$root];

	if(is_page() && (is_page($pid) || $post->post_parent == $pid || in_array($pid, $ancestors))) {
		return true;
	} else {
		return false;
	}
}

// Custom excerpt length
function the_excerpt_custom_length($charlength) {
	global $post;
	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo '... <a class="read-more" href="'.get_permalink($post->ID).'">Read More &raquo;</a>';
	} else {
		echo $excerpt;
	}
}

define('EMPTY_TRASH_DAYS', 5 );
	
function enable_more_buttons($buttons) {
  $buttons[] = 'hr';
  $buttons[] = 'sub';
  $buttons[] = 'sup';
  //$buttons[] = 'fontselect';
  //$buttons[] = 'fontsizeselect';
  //$buttons[] = 'cleanup';

  //$buttons[] = 'styleselect'; 
  return $buttons;
}
add_filter("mce_buttons_2", "enable_more_buttons");

		
add_filter('user_contactmethods','hide_profile_fields',10,1);

function hide_profile_fields( $contactmethods ) {
  unset($contactmethods['aim']);
  unset($contactmethods['jabber']);
  unset($contactmethods['yim']);
  return $contactmethods;
}

/*
 * Remove senseless dashboard widgets for non-admins. (Un)Comment or delete as you wish.
 */
function remove_dashboard_widgets() {
	global $wp_meta_boxes;

	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // Plugins widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // WordPress Blog widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // Other WordPress News widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); // Right Now widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // Quick Press widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // Incoming Links widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // Recent Drafts widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Recent Comments widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['w3tc_latest']); // w3tc widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['blc_dashboard_widget']); // broken link checker widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']); // yoast checker widget
	
}
if (!current_user_can('manage_options')) {
	add_action('wp_dashboard_setup', 'remove_dashboard_widgets', 20 );
} 

/**
 * Remove meta boxes from Post and Page Screens
 */
function customize_meta_boxes() {
  /* These remove meta boxes from POSTS */
  remove_meta_box('postcustom','post','normal'); // Hide Custom Fields
  remove_meta_box('trackbacksdiv','post','normal'); // Hide Trackbacks Box
  remove_meta_box('commentstatusdiv','post','normal'); // Hide Discussions Box
  remove_meta_box('commentsdiv','post','normal'); // Hide Comments Box
  remove_meta_box('tagsdiv-post_tag','post','normal'); // Hide Post Tags Box
  remove_meta_box('postexcerpt','post','normal'); // Hide Excerpt Box
  //remove_meta_box('categorydiv','post','normal'); // Hide Category Box
  remove_meta_box('authordiv','post','normal'); // Hide Author Box
  //remove_meta_box('revisionsdiv','post','normal'); // Hide Revisions Box
  //remove_meta_box(' postimagediv','post','normal'); // Hide Featured Image
  
  /* These remove meta boxes from PAGES */
  remove_meta_box('postcustom','page','normal');  // Hide Custom Fields Box
  remove_meta_box('trackbacksdiv','page','normal'); // Hide Trackbacks Box
  remove_meta_box('commentstatusdiv','page','normal'); // Hide Discussion Box
  remove_meta_box('commentsdiv','page','normal'); // Hide Comments Box
  remove_meta_box('authordiv','page','normal'); // Hide Authors Box
  //remove_meta_box('revisionsdiv','page','normal'); // Hide Revisions Box
  remove_meta_box('postaiosp','page','normal'); // Hide All in one SEO
  remove_meta_box('aiosp','page','normal'); // Hide All in one SEO
 //remove_meta_box(' postimagediv','page','normal'); // Hide Featured Image
   remove_meta_box('postexcerpt','page','normal'); // Hide Excerpt Box


}

if (!current_user_can('manage_options')) {
	add_action('admin_init','customize_meta_boxes');
} 

/**
 * Remove code from the <head>
 */
remove_action('wp_head', 'rsd_link'); // Might be necessary if you or other people on this site use remote editors.
remove_action('wp_head', 'wp_generator'); // Hide the version of WordPress you're running
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'index_rel_link'); // Displays relations link for site index
remove_action('wp_head', 'wlwmanifest_link'); // Might be necessary if you or other people on this site use Windows Live Writer.
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_filter( 'the_content', 'capital_P_dangit' ); // Get outta my Wordpress codez dangit!
remove_filter( 'the_title', 'capital_P_dangit' );
remove_filter( 'comment_text', 'capital_P_dangit' );

//This function removes the comment inline css
function twentyten_remove_recent_comments_style() {
	global $wp_widget_factory;
	//remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}

function hide_menus() {
global $post;
	echo '<style type="text/css">.widefat #override {display:none; !important;}';
	
if (current_user_can('editor')) {
	echo '#revisionsdiv {display:block !important;} #yoast_db_widget, #descriptiondiv, #linkcategorydiv, #linktargetdiv, #linkxfndiv, #linkadvanceddiv, .menu-icon-tools, .menu-icon-comments,';
	echo '#wpseo_meta, #postcustom, #postexcerpt, #acx_plugin_dashboard_widget, #pb_backupbuddy, .menu-icon-settings, .menu-icon-appearance, #cpt_info_box, #cart66_feature_level_meta, .tagcloud,';
	echo '#yoast_db_widget, .toplevel_page_rs-post-restrictions, .toplevel_page_rs-post-roles, #tribe-filters, #slidedeck-sidebar, #tribe_dashboard_widget, #event_cost, #eventBritePluginPlug, #event_organizer,';	
	echo '.overview-options, #callout-sidebar, .callout-button, .slide-convert-vertical, .slide-background-url, .mce_slidedeck, .menu_acf, #menu-plugins, #edit-box-ppr, #content_wp_more, #content_wp_help,';	
	echo '#content_wp_help, .mce_wp_more, #content_wp_more, #content_wp_help, #menu-links, #toplevel_page_edit-post_type-acf ';
	echo '{display:none; !important;}';
} else {
    echo '#revisionsdiv {display:block !important;} ';
	echo '#yoast_db_widget, #yoast_db_widget, #slidedeck-sidebar, #cpt_info_box, .widefat #override, #slidedeck-sidebar, #tribe_dashboard_widget,';
	echo '#content_wp_more, #content_wp_help, #menu-links '; 
	echo '{display:none;}';
}
	echo '</style>';
}
add_action('admin_head', 'hide_menus');


/*Hide unwanted profile info*/
add_action('admin_head', 'hide_profile_info');
function hide_profile_info() {
global $pagenow; // get what file we're on

if(!current_user_can('manage_options')) { // we want admins and editors to still see it
switch($pagenow) {
case 'profile.php':
$output = "\n\n" . '<script type="text/javascript">' . "\n";
$output .= 'jQuery(document).ready(function() {' . "\n";
$output .= 'jQuery("form#your-profile > h3:first").hide();' . "\n"; // hide "Personal Options" header
$output .= 'jQuery("form#your-profile > h3:eq(2)").hide();' . "\n"; // hide "Contact info" header
$output .= 'jQuery("form#your-profile > h3:eq(3)").hide();' . "\n"; // hide "About Yourself" header
$output .= 'jQuery("form#your-profile > table:first").hide();' . "\n"; // hide "Personal Options" table
$output .= 'jQuery("table.form-table:eq(1) tr:first").hide();' . "\n"; // hide "username"
$output .= 'jQuery("table.form-table:eq(1) tr:eq(3)").hide();' . "\n"; // hide "nickname"
$output .= 'jQuery("table.form-table:eq(1) tr:eq(4)").hide();' . "\n"; // hide "display name publicly as"
$output .= 'jQuery("table.form-table:eq(1)+h3").hide();' . "\n"; // hide "Contact Info" header
//$output .= 'jQuery("table.form-table:eq(2)").hide();' . "\n"; // hide "Contact Info" table
$output .= 'jQuery("table.form-table:eq(3) tr:eq(0)").hide();' . "\n"; // hide "Biographical Info"
$output .= '});' . "\n";
$output .= '</script>' . "\n\n";
break;

default:
$output = '';
}
}
echo $output;
}

// THIS INCLUDES THE THUMBNAIL IN OUR RSS FEED
function insertThumbnailRSS($content) {
global $post;
if ( has_post_thumbnail( $post->ID ) ){
$content = '<a href="' . get_permalink( $thumbnail->ID ) . '" title="' . esc_attr( $thumbnail->post_title ) . '">'. get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'alt' => get_the_title(), 'title' => get_the_title(), 'style' => 'float:right;margin:0 0 10px 15px;display:block !important;', 'align' => 'right' ) ) . '</a>' . $content;
}
return $content;
}
add_filter('the_excerpt_rss', 'insertThumbnailRSS');
add_filter('the_content_feed', 'insertThumbnailRSS'); 

//Replace Howdy in Admin
function replace_howdy( $wp_admin_bar ) {
    $my_account=$wp_admin_bar->get_node('my-account');
    $newtitle = str_replace( 'Howdy,', 'Welcome ', $my_account->title );
    $wp_admin_bar->add_node( array(
        'id' => 'my-account',
        'title' => $newtitle,
    ) );
}
add_filter( 'admin_bar_menu', 'replace_howdy',25 );

//Hide Help Tab in admin
function hide_help() {
    echo '<style type="text/css">
            #contextual-help-link-wrap { display: none !important; }
          </style>';
}
add_action('admin_head', 'hide_help');

function unhide_kitchensink( $args ) {
	$args['wordpress_adv_hidden'] = false;
	return $args;
}
add_filter( 'tiny_mce_before_init', 'unhide_kitchensink' );

//Remove items from admin bar	
function wps_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('about');
    $wp_admin_bar->remove_menu('wporg');
    $wp_admin_bar->remove_menu('documentation');
    $wp_admin_bar->remove_menu('support-forums');
    $wp_admin_bar->remove_menu('feedback');
	$wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'wps_admin_bar' );


// Enables the confirmation anchor on a specific form. In this case, form Id 1
add_filter( 'gform_confirmation_anchor_1', '__return_true' );


/*Hide unwanted profile info*/
function admin_color_scheme() {
   global $_wp_admin_css_colors;
   $_wp_admin_css_colors = 0;
}
add_action('admin_head', 'admin_color_scheme');

if(!function_exists('sp_remove_wpmandrill_dashboard'))
{
 function sp_remove_wpmandrill_dashboard() {

 if ( class_exists( 'wpMandrill' ) ) {
 
 remove_action( 'wp_dashboard_setup', array( 'wpMandrill' , 'addDashboardWidgets' ) );
 
 }
 
 }
 
 add_action( 'admin_init', 'sp_remove_wpmandrill_dashboard' );
}