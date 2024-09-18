<?php

if (!defined('ABSPATH'))
    exit;

/**
 * @version		1.0.0
 * @package		wc-top-pick-by-sale
 */

class WC_Top_Pick_By_Sale_Shortcode {

	public function __construct() {
		// Register the shortcode
		add_shortcode( 'top_pick_by_sale_products', [ $this, 'wctpbs_products_shortcode' ] );
	}

    // Add custom WooCommerce product shortcode
    public function wctpbs_products_shortcode($atts) {
        $category_id = wctpbs_get_plugin_settings( 'top_pick_category' ) ? wctpbs_get_plugin_settings( 'top_pick_category' )['value'] : '';
		if ( $category_id ) {
            $default_cat = get_term( $category_id, 'product_cat' );
            $cat_slug = $default_cat && ! is_wp_error( $default_cat ) ? $default_cat->slug : '';
            ob_start();

            // Shortcode attributes
            $atts = shortcode_atts(
                array(
                    'category' => $cat_slug,
                ),
                $atts,
                'top_pick_by_sale_products'
            );

            // Get products based on shortcode attributes
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'product_cat' => $atts['category'],
                'orderby' => 'date', // Default sorting order
                'order' => 'desc',
            );

            // Check if sorting is specified in the URL
            $sort_order = isset( $_GET['sort'] ) ? sanitize_text_field( $_GET['sort'] ) : '';

            // Modify query for sorting
            if ( $sort_order ) {
                switch ( $sort_order ) {
                    case 'price_low_high':
                        $args['orderby'] = 'price';
                        $args['order'] = 'asc';
                        break;
                    case 'price_high_low':
                        $args['orderby'] = 'price';
                        $args['order'] = 'desc';
                        break;
                    // Add more cases for additional sorting options as needed
                }
            }

            $products = new WP_Query( $args );

            if ( $products->have_posts() ) {
                echo '<div class="top-pick-product-list">';
                    // Sort dropdown
                    echo '<form id="top-pick-sort-form">';
                        echo '<label for="sort-dropdown">Sort by:</label>';
                        echo '<select id="sort-dropdown" name="sort" onchange="this.form.submit()">';
                            echo '<option value="date" ' . selected( $sort_order, 'date', false ) . '>'. __( 'Latest', 'wc-top-pick-by-sale' ).'</option>';
                            echo '<option value="price_low_high" ' . selected( $sort_order, 'price_low_high', false ) . '>'. __( 'Price: Low to High', 'wc-top-pick-by-sale' ) .'</option>';
                            echo '<option value="price_high_low" ' . selected( $sort_order, 'price_high_low', false ) . '>'. __( 'Price: High to Low', 'wc-top-pick-by-sale' ) .'</option>';
                        echo '</select>';
                    echo '</form>';

                // Display products
                if ( $products->have_posts() ) : ?>
        
                    <?php woocommerce_product_loop_start(); ?>
        
                    <?php while ( $products->have_posts() ) : $products->the_post(); ?>
        
                        <?php wc_get_template_part( 'content', 'product' ); ?>
        
                    <?php endwhile; // end of the loop. ?>
        
                    <?php woocommerce_product_loop_end(); ?>
        
                    <?php
        
                endif;    

                echo '</div>';
            } else {
                echo 'No products found';
            }

            wp_reset_postdata();

            return ob_get_clean();
        }
    }
}