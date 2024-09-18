<?php

if (!defined('ABSPATH'))
    exit;

/**
 * @version		1.0.0
 * @package		wc-top-pick-by-sale
 */

class WC_Top_Pick_By_Sale_Admin {

	public $settings;
	
	public function __construct() {
		// load menu
        $this->load_class( 'settings' );
        $this->settings = new WC_Top_Pick_By_Sale_Settings();

        //load Script
        add_action( 'admin_enqueue_scripts', [ $this, 'wctpbs_enqueue_admin_script' ] );
	}

    /**
     * Admin Scripts
     */
    public function wctpbs_enqueue_admin_script() {
        global $WC_Top_Pick_By_Sale;
        $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        
        if ( get_current_screen()->id == 'toplevel_page_wc-top-pick-by-sale-setting' ) {
            wp_enqueue_script( 'wc-top-picks-by-sale-script', $WC_Top_Pick_By_Sale->plugin_url . 'build/index.js', array( 'wp-element' ), $WC_Top_Pick_By_Sale->version, true );
            wp_localize_script( 'wc-top-picks-by-sale-script', 'wctpbsLocalizer', apply_filters( 'wc_top_pick_admin_default', 
                [
                    'apiUrl'        => home_url('/wp-json'),
                    'nonce'         => wp_create_nonce('wp_rest'),
                ]
            ) );
            wp_enqueue_style( 'wc-top-picks-by-sale-style', $WC_Top_Pick_By_Sale->plugin_url . 'build/index.css', array(), '1.0.0');
        }
    }

	public function load_class( $class_name = '' ) {
        global $WC_Top_Pick_By_Sale;
        if ( '' != $class_name ) {
            require_once( $WC_Top_Pick_By_Sale->plugin_path . '/admin/class-' . esc_attr( $WC_Top_Pick_By_Sale->token ) . '-' . esc_attr( $class_name ) . '.php' );
        }
    }
}