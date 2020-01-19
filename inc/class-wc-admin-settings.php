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
		// echo "<pre>";
		// 	 print_r($screen_id);
		// echo "</pre>";
		if( "woocommerce_page_wc-settings" != $screen_id) return;
		$wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );
		$suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script( 'wc-checkout-edit', MCFEURL. 'js/admin.scripts.js', array( 'jquery', 'wp-util', 'underscore', 'backbone', 'jquery-ui-sortable', 'wc-enhanced-select', 'wc-backbone-modal' ), WC_VERSION );
	}

	/**
	 * Get settings array.
	 * plugins\woocommerce\includes\admin\class-wc-admin-settings.php
	 * @return array
	 */
	public function get_settings() {

		$currency_code_options = get_woocommerce_currencies();

		foreach ( $currency_code_options as $code => $name ) {
			$currency_code_options[ $code ] = $name . ' (' . get_woocommerce_currency_symbol( $code ) . ')';
		}

		$woocommerce_default_customer_address_options = array(
			''                 => __( 'No location by default', 'woocommerce' ),
			'base'             => __( 'Shop base address', 'woocommerce' ),
			'geolocation'      => __( 'Geolocate', 'woocommerce' ),
			'geolocation_ajax' => __( 'Geolocate (with page caching support)', 'woocommerce' ),
		);

		if ( version_compare( PHP_VERSION, '5.4', '<' ) ) {
			unset( $woocommerce_default_customer_address_options['geolocation'], $woocommerce_default_customer_address_options['geolocation_ajax'] );
		}
		if( 'ok' == get_option("is_wmow_licenced") ){
			$readyonly = array('readonly' => 'readonly');
		}

		$settings = apply_filters(
			'woocommerce_workflow_settings',
			array(

				array(
					'title' => __( 'Plugin options', 'woocommerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'general_options',
				),
				array(
					'title'    	=> __( 'Select a page For Workflow', 'woocommerce' ),
					/* Translators: %s Page contents. */
					'desc'     	=> '<p>' . sprintf( __( 'These page need to be set so that workflow knows where the function to do. You can create a page with content: [%s]', 'woocommerce' ), 'workflow_orders', 'woocommerce' ) . '</p>',
					'id'       	=> 'woocommerce_wmow_manual_order_page_id',
					'type'     	=> 'single_select_page',
					'default'  	=> '',
					'class'    	=> 'wc-enhanced-select-nostd',
					'css'      	=> 'min-width:300px;',
					'desc_tip' 	=> false,
				),
				array(
					'title'    	=> __( 'License Key', 'woocommerce' ),
					/* Translators: %s Page contents. */
					'desc'     	=> '<p>' . sprintf( __( '', 'woocommerce' ), '', 'woocommerce' ) . '</p>',
					'id'       	=> 'woocommerce_wmow_license_key',
					'type'     	=> 'text',
					'default'  	=> '',
					'class'    	=> 'wc-enhanced-text-nostd',
					'css'      	=> 'min-width:300px;',
					'desc_tip' 	=> false,
					'custom_attributes'  => $readyonly,
				),
				array(
					'title'    => __( 'Color', 'woocommerce' ),
					'desc_tip' => __( 'Each Color in each line', 'woocommerce' ),
					'id'       => 'woocommerce_wmow_color',
					'default'  => sprintf( __( "Color_1 \nColor_2 \nColor_3 \n", 'woocommerce' )),
					'type'     => 'textarea',
					'css'      => 'min-width: 50%; height: 75px;',
				),
				array(
					'title'    => __( 'Filter', 'woocommerce' ),
					'desc_tip' => __( 'Each Filter in each line', 'woocommerce' ),
					'id'       => 'woocommerce_wmow_filter',
					'default'  => sprintf( __( "Filter_1 \nFilter_2 \nFilter_3 \n", 'woocommerce' )),
					'type'     => 'textarea',
					'css'      => 'min-width: 50%; height: 75px;',
				),
				array(
					'title'    => __( 'Input Model', 'woocommerce' ),
					'desc_tip' => __( 'Each model in each line', 'woocommerce' ),
					'id'       => 'woocommerce_wmow_model',
					'default'  => sprintf( __( "model_1 \nmodel_2 \nmodel_3 \n", 'woocommerce' )),
					'type'     => 'textarea',
					'css'      => 'min-width: 50%; height: 75px;',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'general_options',
				),

			)
		);

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		global $current_section, $hide_save_button;
		$this->generate_html(2);
		//$settings = $this->get_settings();
		//WC_Admin_Settings::output_fields( $settings );
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
			'wc-shipping-zone-methods',
			'shippingZoneMethodsLocalizeScript',
			array(
				'methods'                 => $zone->get_shipping_methods( false, 'json' ),
				'zone_name'               => $zone->get_zone_name(),
				'zone_id'                 => $zone->get_id(),
				'wc_shipping_zones_nonce' => wp_create_nonce( 'wc_shipping_zones_nonce' ),
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