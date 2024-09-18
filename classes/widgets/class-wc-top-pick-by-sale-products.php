<?php

if (!defined('ABSPATH'))
    exit;

/**
 * @version     1.0.0
 * @package     wc-top-pick-by-sale
 */

class WCTP_By_Sale_Widget_Products extends WC_Widget {

    public function __construct() {
        $this->widget_cssclass = 'wctpbs_widget_products';
        $this->widget_description = __( 'Displays a list of top pick by sale products.', 'wc-top-pick-by-sale' );
        $this->widget_id = 'wctpbs_widget_products';
        $this->widget_name = __( 'Top Pick By Sale Products', 'wc-top-pick-by-sale' );
        $this->settings = array(
            'title' => array(
                'type' => 'text',
                'std' => __( 'Top Pick Products', 'wc-top-pick-by-sale' ),
                'label' => __( 'Title', 'wc-top-pick-by-sale' ),
            ),
            'number' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => 1,
				'max'   => '',
				'std'   => 10,
				'label' => __( 'Number of Products to Show', 'wc-top-pick-by-sale' ),
			),
        );
        parent::__construct();
    }

    public function widget($args, $instance) {
        $category_id = wctpbs_get_plugin_settings( 'top_pick_category' ) ? wctpbs_get_plugin_settings( 'top_pick_category' )['value'] : '';
        if ( $category_id ) {
            $default_cat = get_term( $category_id, 'product_cat' );
            $cat_slug = $default_cat && !is_wp_error( $default_cat ) ? $default_cat->slug : '';
            $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : $this->settings['number']['std'];

            $query_args = array(
                'posts_per_page' => $number,
                'post_status'    => 'publish',
                'post_type'      => 'product',
                'no_found_rows'  => 1,
                'product_cat'    => $cat_slug,
                
            );
            
            $products = new WP_Query( apply_filters( 'woocommerce_products_widget_query_args', $query_args ) );
            
            if ( $products && $products->have_posts() ) {
                
                $this->widget_start( $args, $instance );
                
                do_action($this->widget_cssclass . '_top');

                echo wp_kses_post( apply_filters( 'woocommerce_before_widget_product_list', '<ul class="product_list_widget">' ) );

                $template_args = array(
                    'widget_id'   => $args['widget_id'],
                    //'show_rating' => true,
                );

                while ( $products->have_posts() ) {
                    $products->the_post();
                    wc_get_template( 'content-widget-product.php', $template_args );
                }

                echo wp_kses_post( apply_filters( 'woocommerce_after_widget_product_list', '</ul>' ) );
                
                do_action( $this->widget_cssclass . '_bottom' );

                $this->widget_end( $args );
            }
        }
    }
}