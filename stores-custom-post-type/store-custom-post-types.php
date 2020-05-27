<?php
/*
Plugin Name: Anpassad Post Type Stores
Description: Plugin för anpassade post typer
Author: Robin Lundström
*/
// Register Custom Post Type

if ( ! function_exists('custom_post_type') ) {

// Register Custom Post Type
function custom_post_type() {

	$labels = array(
		'name'                  => _x( 'Affärer', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Affär', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Affärer', 'text_domain' ),
		'name_admin_bar'        => __( 'Affärer', 'text_domain' ),
		'archives'              => __( 'Alla affärer', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'Alla affärer', 'text_domain' ),
		'add_new_item'          => __( 'Ny affär', 'text_domain' ),
		'add_new'               => __( 'Ny affär', 'text_domain' ),
		'new_item'              => __( 'Ny affär', 'text_domain' ),
		'edit_item'             => __( 'Redigera affär', 'text_domain' ),
		'update_item'           => __( 'Uppdatera affär', 'text_domain' ),
		'view_item'             => __( 'Visa affär', 'text_domain' ),
		'view_items'            => __( 'Visa affärer', 'text_domain' ),
		'search_items'          => __( 'Sök efter affär', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Store', 'text_domain' ),
		'description'           => __( 'Post type for the stores', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail'),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-admin-home',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'store_post_type', $args );

}
add_action( 'init', 'custom_post_type', 0 );

}

?>
