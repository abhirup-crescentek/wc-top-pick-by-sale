<?php

if (!defined('ABSPATH'))
    exit;

/**
 * @author 	
 * @version   1.0.0
 */


echo esc_html__($email_heading) . "\n\n";

echo sprintf( esc_html__( "Hi there!", 'wc-top-pick-by-sale' ) ) . "\n\n";

echo "\n****************************************************\n\n";

echo sprintf( esc_html__( "This is to inform you that our system has automatically assigned products to categories based on predefined criteria.", 'wc-top-pick-by-sale' ) ) . "\n\n";

echo sprintf( esc_html__( "Kindly download the CSV of all previously unassign products.", 'wc-top-pick-by-sale' ) ) . "\n\n";

echo "\n****************************************************\n\n";

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );