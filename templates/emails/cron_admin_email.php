<?php

if (!defined('ABSPATH'))
    exit;

/**
 * @author 	  
 * @version   1.0.0
 */

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php printf( esc_html__( "Hi there!",  'wc-top-pick-by-sale' ) ); ?></p>

<p><?php printf( esc_html__( "This is to inform you that our system has automatically assigned products to categories based on predefined criteria.",  'wc-top-pick-by-sale' ) ); ?></p>

<p><?php printf( esc_html__( "Kindly download the CSV of all previously unassign products.",  'wc-top-pick-by-sale' ) ); ?></p>

<?php do_action( 'woocommerce_email_footer' );