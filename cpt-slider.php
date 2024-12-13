<?php

function register_cpt_slider() {

    $labels = array( 
        'name' => _x( 'Slider Posts', 'slider' ),
        'singular_name' => _x( 'Slider Post', 'slider' ),
        'add_new' => _x( 'Add New', 'slider' ),
        'add_new_item' => _x( 'Add New Slider Post', 'slider' ),
        'edit_item' => _x( 'Edit Slider Post', 'slider' ),
        'new_item' => _x( 'New Slider Post', 'slider' ),
        'view_item' => _x( 'View Slider Post', 'slider' ),
        'search_items' => _x( 'Search Slider Posts', 'slider' ),
        'not_found' => _x( 'No slider posts found', 'slider' ),
        'not_found_in_trash' => _x( 'No slider posts found in Trash', 'slider' ),
        'parent_item_colon' => _x( 'Parent Slider Post:', 'slider' ),
        'menu_name' => _x( 'Slider', 'slider' )
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        
        'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'has_archive' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'slider', $args );

}

add_action( 'init', 'register_cpt_slider' );