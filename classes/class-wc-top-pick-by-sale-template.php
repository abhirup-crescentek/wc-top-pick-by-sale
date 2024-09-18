<?php

if (!defined('ABSPATH'))
    exit;

/**
 * @version		1.0.0
 * @package		wc-top-pick-by-sale
 */

class WC_Top_Pick_By_Sale_Template {

    public $template_url;

    public function __construct() {
        $this->template_url = 'wc-top-pick-by-sale/';
    }

    /**
     * Get other templates (e.g. product attributes) passing attributes and including the file.
     */
    public function get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

        if ( $args && is_array( $args ) )
            extract( $args );

        $located = $this->locate_template( $template_name, $template_path, $default_path );

        include ( $located );
    }

    /**
     * Locate a template and return the path for inclusion.
     *
     * This is the load order:
     *
     * 		yourtheme		/	$template_path	/	$template_name
     * 		yourtheme		/	$template_name
     * 		$default_path	/	$template_name
     *
     */
    public function locate_template( $template_name, $template_path = '', $default_path = '' ) {
        global $woocommerce, $WC_Top_Pick_By_Sale;
        $default_path = apply_filters( 'template_path', $default_path );
        if ( ! $template_path ) {
            $template_path = $this->template_url;
        }
        if ( ! $default_path ) {
            $default_path = $WC_Top_Pick_By_Sale->plugin_path . 'templates/';
        }
        // Look within passed path within the theme - this is priority
        $template = locate_template( array( trailingslashit( $template_path ) . $template_name, $template_name ) );
        // Add support of third perty plugin
        $template = apply_filters( 'wc_top_pick_by_sale_locate_template', $template, $template_name, $template_path, $default_path );
        // Get default template
        if ( ! $template ) {
            $template = $default_path . $template_name;
        }
        
        return $template;
    }

    /**
     * Get store templates (e.g. product attributes) passing attributes and including the file.
     *
     */
    public function get_store_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
        if ( $args && is_array( $args ) )
            extract( $args );
        $located = $this->store_locate_template( $template_name, $template_path, $default_path );
        include ( $located );
    }

    public function store_locate_template( $template_name, $template_path = '', $default_path = '' ) {
        global $woocommerce, $WC_Top_Pick_By_Sale;
        $default_path = apply_filters( 'template_path', $default_path );
        if ( ! $template_path ) {
            $template_path = $this->template_url;
        }
        if ( ! $default_path ) {
            $default_path = $WC_Top_Pick_By_Sale->plugin_path . 'templates/';
        }
        // Look within passed path within the theme - this is priority
        $template = locate_template( array( trailingslashit( $template_path ) . $template_name, $template_name ) );
        // Add support of third perty plugin
        $template = apply_filters( 'wc_top_pick_by_sale_store_locate_template', $template, $template_name, $template_path, $default_path );
        // Get default template
        if ( ! $template ) {
            $template = $default_path . $template_name;
        }
        return $template;
    }

    /**
     * Get template part (for templates like the shop-loop).
     */
    public function get_template_part( $slug, $name = '' ) {
        global $WC_Top_Pick_By_Sale;
        $template = '';

        // Look in yourtheme/slug-name.php and yourtheme/woocommerce/slug-name.php
        if ( $name )
            $template = $this->locate_template( array( "{$slug}-{$name}.php", "{$this->template_url}{$slug}-{$name}.php" ) );

        // Get default slug-name.php
        if ( ! $template && $name && file_exists( $WC_Top_Pick_By_Sale->plugin_path . "templates/{$slug}-{$name}.php" ) )
            $template = $WC_Top_Pick_By_Sale->plugin_path . "templates/{$slug}-{$name}.php";

        // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php
        if ( !$template )
            $template = $this->locate_template( array( "{$slug}.php", "{$this->template_url}{$slug}.php" ) );

        echo $template;

        if ( $template )
            load_template( $template, false );
    }
}