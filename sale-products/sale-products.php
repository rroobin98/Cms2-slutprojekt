<?php
/*
Plugin Name: saleproducts
Description: Visar 8 produkter som är på rea
Version: 1.0
Author: Robin Lundström
*/

add_shortcode( 'saleproducts', 'saleproducts' );
function saleproducts($atts){
	global $woocommerce_loop;


	extract(shortcode_atts(array(
		'tax' => 'product_cat',
		'per_cat' => '4',
		'columns' => '4',
	), $atts));


	// startar output buffering
	ob_start();


  $query_args = array(
      'posts_per_page'    => 8,
      'no_found_rows'     => 1,
      'post_status'       => 'publish',
      'post_type'         => 'product',
      'meta_query'        => WC()->query->get_meta_query(),
      'post__in'          => array_merge( array( 0 ), wc_get_product_ids_on_sale() )
  );
  $products = new WP_Query( $query_args );

  	// Loopen som visar bestsälljarna
	if ( $products->have_posts() ) : ?>
		<?php woocommerce_product_loop_start(); ?>
			<?php while ( $products->have_posts() ) : $products->the_post(); ?>
				<?php wc_get_template_part( 'content', 'product' ); ?>
			<?php endwhile;  ?>
		<?php woocommerce_product_loop_end(); ?>
	<?php endif;
	wp_reset_postdata();

  //  html output - buffer output
	return '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';
}
?>
