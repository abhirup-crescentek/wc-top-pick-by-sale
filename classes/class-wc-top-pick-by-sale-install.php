<?php

if (!defined('ABSPATH'))
    exit;

/**
 * @version		1.0.0
 * @package		wc-top-pick-by-sale
 */

class WC_Top_Pick_By_Sale_Install {

	public function __construct() {
		if ( ! get_option( 'wctpbs_cron_start' ) ) {
            $this->wctpbs_start_sale_cron_job();
        }
        if ( ! get_option( 'wctpbs_setting_saved' ) ) {
            $this->wctpbs_start_default_settings();
        }
	}

	/*
     * This function will start the cron job
     */
    public function wctpbs_start_sale_cron_job() {
        wp_clear_scheduled_hook( 'wctpbs_cron_job' );
        wp_schedule_event( time(), 'daily', 'wctpbs_cron_job' );
        update_option( 'wctpbs_cron_start', 1 );
    }

    public function wctpbs_start_default_settings() {
        $stock_notifier_settings = array(
            'unassign_prev_products' => array('unassign_prev_products'),
            'get_items_from_last_date' => array( 
                'value' => 6,
                'label' => 'Last Six Month',
                'index' => 5,
            ),
            'order_status_to_include' => array(
                array(
                    'value' => 'wc-completed',
                    'label' => 'Completed',
                    'index' => 3
                ),
                array(
                    'value' => 'wc-processing',
                    'label' => 'Processing',
                    'index' => 1
                )
            ),
        );

        if ( ! get_option( 'wctpbs_general_tab_settings' ) ) {
            update_option( 'wctpbs_general_tab_settings', $stock_notifier_settings );
            update_option( 'wctpbs_setting_saved', 1 ); 
        }
    }
}