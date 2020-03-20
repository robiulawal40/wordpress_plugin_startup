<?php
/**
 * WooCommerce General Settings
 *
 * @package WooCommerce/Admin
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WC_MCFE_SETTING', false ) ) {
	return new WC_MCFE_SETTING();
}

/**
 * WC_Admin_Settings_General.
 */
class WC_MCFE_SETTING extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'checkout_settings';
		$this->label = __( 'Checkout Settings', 'woocommerce' );

		// Exter
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		parent::__construct();
	}

	public function admin_scripts(){
		global $wp_query, $post;

		$screen       = get_current_screen();
		$screen_id    = $screen ? $screen->id : '';

		if( "woocommerce_page_wc-settings" != $screen_id) return;
		$wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );
		$suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script( 'wc-checkout-edit', MCFEURL. 'js/admin.scripts.js', array( 'jquery', 'wp-util', 'underscore', 'backbone', 'jquery-ui-sortable', 'wc-enhanced-select', 'wc-backbone-modal' ), WC_VERSION );
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		global $current_section, $hide_save_button;
		$this->generate_html(2);
	}

	/**
	 * Generate View from views
	 */
	public function generate_html($zone_id = 2){

		if ( 'new' === $zone_id ) {
			$zone = new WC_Shipping_Zone();
		} else {
			$zone = WC_Shipping_Zones::get_zone( absint( $zone_id ) );
		}

		if ( ! $zone ) {
			wp_die( esc_html__( 'Zone does not exist!', 'woocommerce' ) );
		}

		$allowed_countries   = WC()->countries->get_shipping_countries();
		$shipping_continents = WC()->countries->get_shipping_continents();

		// Prepare locations.
		$locations = array();
		$postcodes = array();

		foreach ( $zone->get_zone_locations() as $location ) {
			if ( 'postcode' === $location->type ) {
				$postcodes[] = $location->code;
			} else {
				$locations[] = $location->type . ':' . $location->code;
			}
		}

		wp_localize_script(
			'wc-checkout-edit',
			'checkoutEditData',
			array(
				'wc_admin_mcfe_nonce_generated' => wp_create_nonce( 'wc_admin_mcfe_nonce_generated' ),
				'mcfe_data'                     =>  MCFE_SETTING::get_data(),
				'strings'                 => array(
					'unload_confirmation_msg' => __( 'Your changed data will be lost if you leave this page without saving.', 'woocommerce' ),
					'save_changes_prompt'     => __( 'Do you wish to save your changes first? Your changed data will be discarded if you choose to cancel.', 'woocommerce' ),
					'save_failed'             => __( 'Your changes were not saved. Please retry.', 'woocommerce' ),
					'add_method_failed'       => __( 'Shipping method could not be added. Please retry.', 'woocommerce' ),
					'yes'                     => __( 'Yes', 'woocommerce' ),
					'no'                      => __( 'No', 'woocommerce' ),
					'default_zone_name'       => __( 'Zone', 'woocommerce' ),
				),
			)
		);
		//wp_dequeue_script( 'wc-shipping-zone-methods' );
		wp_enqueue_script( 'wc-checkout-edit' );

		include_once dirname( __FILE__ ) . '/views/html-admin-settings.php';
	}

	/**
	 * Save settings.
	 * is_wmow_licenced
	 */
	public function save() {
		$settings = $this->get_settings();

		WC_Admin_Settings::save_fields( $settings );
	}

}

return new WC_MCFE_SETTING();