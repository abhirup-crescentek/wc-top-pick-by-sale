<?php

if (!defined('ABSPATH'))
    exit;

/**
 * @version    1.0.0
 * @package    wc-top-pick-by-sale
 */

class WC_Top_Pick_By_Sale_Settings {

   public function __construct() {
      // Admin menu
      add_action( 'admin_menu', [ $this, 'add_settings_page' ], 100 );
   }

   /**
   * Add options page
   */
   public function add_settings_page() {

      add_menu_page(
         __( 'Top Picks', 'wc-top-pick-by-sale' ),
         __( 'Top Picks', 'wc-top-pick-by-sale' ),
         'manage_options',
         'wc-top-pick-by-sale-setting',
         [ $this, 'create_wc_top_pick_by_sale_settings' ],
         'dashicons-paperclip', 
         59
      );

      add_submenu_page(
         'wc-top-pick-by-sale-setting',                                   // parent slug
         __( 'Settings', 'wc-top-pick-by-sale' ),                         // page title
         __( 'Settings', 'wc-top-pick-by-sale' ),                         // menu title
         'manage_options',                                                // capability
         'wc-top-pick-by-sale-setting#&tab=settings&subtab=general',      // callback
         '__return_null'                                                  // position
      );

      remove_submenu_page( 'wc-top-pick-by-sale-setting', 'wc-top-pick-by-sale-setting' );
   }

   /**
   * Options page callback
   */
   public function create_wc_top_pick_by_sale_settings() {
      echo '<div id="wc-admin-top-pick-by-sale"></div>';
   }
}