<?php

if (!defined('ABSPATH'))
    exit;

/**
 * @version     1.0.0
 * @package     wc-top-pick-by-sale
 * 
 */

class WC_Top_Pick_By_Sale {
    public $token;
    public $plugin_url;
    public $plugin_path;
    public $version;
    public $template;
    public $admin;
    public $shortcode;
    public $frontend;
    private $file;

    public function __construct( $file ) {
        $this->file = $file;
        $this->plugin_url = trailingslashit( plugins_url( '', $plugin = $file ) );
        $this->plugin_path = trailingslashit( dirname( $file ) );
        $this->token = WC_TOP_PICK_BY_SALE_PLUGIN_TOKEN;
        $this->version = WC_TOP_PICK_BY_SALE_PLUGIN_VERSION;

        add_action( 'init', [ &$this, 'init' ] );
        // Woocommerce Email structure
        add_filter( 'woocommerce_email_classes', [ &$this, 'wctpbs_mail' ] );
        add_action( 'wctpbs_cron_job', 'wctpbs_cron_function' );
        add_action( 'widgets_init', [ $this, 'wctpbs_product_vendor_register_widgets' ] );
    }

    /**
     * initilize plugin on init
     */
    function init() {
        $this->load_plugin_textdomain();

        if ( is_admin() ) {
            $this->load_class( 'admin' );
            $this->admin = new WC_Top_Pick_By_Sale_Admin();
        }

        if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
            $this->load_class( 'frontend' );
            $this->frontend = new WC_Top_Pick_By_Sale_Frontend();

            $this->load_class( 'shortcode' );
            $this->shortcode = new WC_Top_Pick_By_Sale_Shortcode();
        }
        $this->load_class( 'template' );
        $this->template = new WC_Top_Pick_By_Sale_Template();

        if ( current_user_can( 'manage_options' ) ) {
            add_action( 'rest_api_init', [ $this, 'wctpbs_rest_routes' ] );
        }
    }

    /**
     * Load Localisation files.
     */
    public function load_plugin_textdomain() {
        $locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
        $locale = apply_filters( 'plugin_locale', $locale, 'wc-top-pick-by-sale' );
        load_textdomain( 'wc-top-pick-by-sale', WP_LANG_DIR . '/wc-top-pick-by-sale/wc-top-pick-by-sale-' . $locale . '.mo' );
        load_plugin_textdomain( 'wc-top-pick-by-sale', false, plugin_basename(dirname(dirname(__FILE__))) . '/languages' );
    }

    public function load_class( $class_name = '' ) {
        if ( '' != $class_name && '' != $this->token ) {
            require_once ( 'class-' . esc_attr( $this->token ) . '-' . esc_attr( $class_name ) . '.php' );
        }
    }

    /**
     * Sets a constant preventing some caching plugins from caching a page. Used on dynamic pages
     */
    public function nocache() {
        if ( ! defined( 'DONOTCACHEPAGE' ) )
            define( "DONOTCACHEPAGE", "true" );
            // WP Super Cache constant
    }

    /**
     * Install upon activation
     */
    public static function activate_wctpbs() {
        global $WC_Top_Pick_By_Sale;
        update_option( 'wctpbs_installed', 1 );
        // Init install
        $WC_Top_Pick_By_Sale->load_class( 'install' );
        new WC_Top_Pick_By_Sale_Install();
    }

    /**
     * Install upon deactivation
     *
     */
    public static function deactivate_wctpbs() {
        delete_option( 'wctpbs_installed' );
    }

    public function wctpbs_rest_routes() {
        register_rest_route( 'wc_top_pick_by_sale/v1', '/fetch_admin_tabs', [
            'methods'   => WP_REST_Server::READABLE,
            'callback'  => array( $this, 'wctpbs_fetch_admin_tabs' ),
            'permission_callback' => array( $this, 'wctpbs_permission' ),
        ] );
        register_rest_route( 'wc_top_pick_by_sale/v1', '/save_admin_settings', [
            'methods'   => WP_REST_Server::EDITABLE,
            'callback'  => array( $this, 'wctpbs_save_admin_settings' ),
            'permission_callback' => array( $this, 'wctpbs_permission' ),
        ] );
    }

    public function wctpbs_permission() {
        return current_user_can('manage_options');
    }
    
    public function wctpbs_fetch_admin_tabs() {
        $wc_top_pick_admin_tabs_data = wctpbs_admin_tabs() ? wctpbs_admin_tabs() : [];
        return rest_ensure_response( $wc_top_pick_admin_tabs_data );
    }

    public function wctpbs_save_admin_settings( $request ) {
        $all_details = [];
        $modulename = $request->get_param( 'modulename' );
        $modulename = str_replace( "-", "_", $modulename );
        $get_managements_data = $request->get_param( 'model' );
        $optionname = 'wctpbs_'.$modulename.'_tab_settings';
        update_option( $optionname, $get_managements_data );
        do_action( 'wctpbs_settings_after_save', $modulename, $get_managements_data );
        $all_details['error'] = __( 'Settings Saved', 'wc-top-pick-by-sale' );
        return $all_details;
        die;
    }

    public function wctpbs_mail( $emails ) {
        require_once( 'emails/class-wc-top-pick-cron-email.php' );
        $emails['WC_Admin_Email_Cron_Update'] = new WC_Admin_Email_Cron_Update();
        return $emails;
    }

    /**
     * Add vendor widgets
     */
    public function wctpbs_product_vendor_register_widgets() {
        require_once( 'widgets/class-wc-top-pick-by-sale-products.php' );
        register_widget( 'WCTP_By_Sale_Widget_Products' );
    }
}