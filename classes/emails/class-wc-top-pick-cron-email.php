<?php

if (!defined('ABSPATH'))
    exit;

if ( ! class_exists( 'WC_Admin_Email_Cron_Update' ) ) :

/**
 *
 * An email will be sent to the admin when customer subscribe an out of stock product.
 *
 * @class 		WC_Admin_Email_Cron_Update
 * @extends 	WC_Email
 */
class WC_Admin_Email_Cron_Update extends WC_Email {

    public $attachments;

	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
		global $WC_Top_Pick_By_Sale;
		
		$this->id 				= 'wctp_cron_update';
		$this->title 			= __( 'Notify admin', 'wc-top-pick-by-sale' );
		$this->description		= __( 'Admin will get a notification email when top pick cron run', 'wc-top-pick-by-sale' );
		$this->template_html 	= 'emails/cron_admin_email.php';
		$this->template_plain 	= 'emails/plain/cron_admin_email.php';
		$this->template_base 	= $WC_Top_Pick_By_Sale->plugin_path . 'templates/';
		// Call parent constuctor
		parent::__construct();
	}

	/**
	 * trigger function.
	 *
	 * @access public
	 * @return void
	 */
	function trigger( $attachments ) {
		
		$this->recipient = get_option('admin_email');
		
		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}
		if( is_array( $attachments ) && count( $attachments ) > 0 ){
            $this->attachments = $attachments;
        }
		
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

	/**
	 * Get email subject.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_default_subject() {
		return apply_filters( 'top_pick_cron_admin_email_subject', __( 'Cron report', 'wc-top-pick-by-sale' ), $this->object );
	}

	/**
	 * Get email heading.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_default_heading() {
		return apply_filters( 'top_pick_cron_admin_email_heading', __( 'Welcome to {site_title}', 'wc-top-pick-by-sale' ), $this->object );
	}

	/**
     * Get email attachments.
     *
     * @return string
     */
    public function get_attachments() {
        return apply_filters( 'top_pick_cron_admin_email_attachments', $this->attachments, $this->id, $this->object );
    }

	/**
	 * get_content_html function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_html() {
		ob_start();
		wc_get_template( $this->template_html, array(
			'email_heading' 	=> $this->get_heading(),
			'sent_to_admin' 	=> true,
			'plain_text' 		=> false,
			'email' 			=> $this,
			), '', $this->template_base );
		return ob_get_clean();
	}

	/**
	 * get_content_plain function.
	 *
	 * @access public
	 * @return string
	 */
	function get_content_plain() {
		ob_start();
		wc_get_template( $this->template_plain, array(
			'email_heading' 	=> $this->get_heading(),
			'sent_to_admin' 	=> true,
			'plain_text' 		=> true
			) ,'', $this->template_base );
		return ob_get_clean();
	}
}
endif;