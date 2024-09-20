<?php

if (!defined('ABSPATH'))
    exit;

/**
 * @version		1.0.0
 * @package		wc-top-pick-by-sale
 */

class WC_Top_Pick_By_Sale_Frontend {

	public function __construct() {
		
		if ( wctpbs_get_plugin_settings( 'enable_show_order_count' ) ) {
			add_action( 'woocommerce_single_product_summary', [ $this, 'wctpbs_product_sold_count' ], 15 );
		}
	}
  
	public function wctpbs_product_sold_count() {
		global $product;
		$category_id = wctpbs_get_plugin_settings( 'top_pick_category' ) ? wctpbs_get_plugin_settings( 'top_pick_category' )['value'] : '';
		if ( $category_id ) {
            $default_cat = get_term( $category_id, 'product_cat' );
            $cat_slug = $default_cat && !is_wp_error($default_cat) ? $default_cat->slug : '';
			if ( has_term( $cat_slug, 'product_cat' ) ) {
				$days = wctpbs_get_plugin_settings( 'order_in_days' , '7' );
				$all_orders = wc_get_orders(
					array(
						'limit' => -1,
						'status' => wc_get_is_paid_statuses(),
						'date_after' => gmdate( 'Y-m-d', strtotime( '-'.$days.' days' ) ),
						'return' => 'ids',
					)
				);
				$count = 0;
				foreach ( $all_orders as $all_order ) {
					$order = wc_get_order( $all_order );
					$items = $order->get_items();
					foreach ( $items as $item ) {
						$product_id = $item->get_product_id();
						if ( $product_id == $product->get_id() ) {
							$count = $count + absint( $item['qty'] ); 
						}
					}
				}
				$minimum_order = wctpbs_get_plugin_settings( 'minimum_order_of_product', 0 );
					
				if ( $count >= $minimum_order ) {
					$default_massages = wctpbs_default_massages();
					$row_massage = wctpbs_get_plugin_settings( 'shown_order_count_text' , $default_massages['shown_order_count_text'] );
					$shown_order_text = str_replace( "%day_count%", $days, $row_massage );
					$shown_order_text = str_replace( "%order_count%", $count, $shown_order_text );
					echo "<p>" . esc_html($shown_order_text) . "</p>";
				}
			}
		}
	}
}