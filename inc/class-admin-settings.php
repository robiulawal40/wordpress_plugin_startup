<?php  
/**
 * MCFE Settings
 *
 * @package WooCommerce/Admin
 */



defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Form_Html_Generator', false) ) {
 
    include_once "form_html_generator.php";
    //echo "Code Running";
}

if ( class_exists( 'MCFE_SETTING', false ) ) {
    return new MCFE_SETTING();
    
}





/**
 * WC_Admin_Settings_General.
 */
class MCFE_SETTING {

	/**
	 * Constructor.
	 */
	public function __construct() {

        add_action( 'wp_ajax_woocommerce_mcfe_settings_data', array( __CLASS__ , 'mcfe_settings_data' ) );
       // add_action("wp_head", array( __CLASS__ , 'mcfe_settings_data' ) );
    }

    public static function default_fields(){
        $fields['billing'] = WC()->countries->get_address_fields();
        $fields['shipping'] = WC()->countries->get_address_fields('', 'shipping_');
        return $fields;
    }

    public static function options_for_fields($name, $field ){
        $return = [];
        $return['type']                    = $field['type']?:''; 
        $return['name']                    = $name?:''; 
        $return['label']                   = $field['label']?:''; 
        $return['placeholder']             = $field['placeholder']?:''; 
        $return['autocomplete']            = $field['autocomplete']?:''; 
        $return['priority']                = $field['priority']?   :''; 
        $return['default_value']           = $field['default_value']?:''; 
        $return['class']                   = $field['class']?:''; 
        $return['validate']                = $field['validate']?:''; 
        $return['required']                = $field['required']?   :''; 
        $return['is_enabled']              = $field['is_enabled']?:''; 
        $return['country']                 = $field['country']?:''; 
        $return['country_field']           = $field['country_field']?:''; 
        $return['display_in_email']        = $field['display_in_email']?:''; 
        $return['display_order_details']   = $field['display_order_details']?:''; 

        $generated_html = new Form_Html_Generator();
        $return['setting_html']            = $generated_html->get_admin_options_html($return);
        return $return;
    }

    public static function get_data(){

        $mcfe_data = get_option("mcfe_data");

        if( empty($mcfe_data) )
            $mcfe_data = self::default_fields();

            $new_mcfe_data = array();
            if( key_exists("billing", $mcfe_data) ){

                $billings = $mcfe_data['billing'];
                foreach ( $billings as $key => $value) {
                    $new_mcfe_data['billing'][$key] = self::options_for_fields( $key, $value );
                }
            }

            if( key_exists("shipping", $mcfe_data) ){

                $shippings = $mcfe_data['shipping'];
                foreach ( $shippings as $key => $value) {
                    $new_mcfe_data['shipping'][$key] = self::options_for_fields( $key, $value );
                }
            }
        return $new_mcfe_data;
    }

    public static  function mcfe_settings_data(){


        echo "<pre>";
             print_r( get_option("mcfe_data") );
        echo "</pre>";

        if ( ! isset( $_POST['wc_admin_mcfe_nonce'] , $_POST['mcfe_data'] ) ) {
            wp_send_json_error( 'missing_fields', $_POST );
            wp_die();
        }

        if ( ! wp_verify_nonce( wp_unslash( $_POST['wc_admin_mcfe_nonce'] ), 'wc_admin_mcfe_nonce_generated' ) ) { 
            wp_send_json_error( 'bad_nonce' );
            wp_die();
        }

        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_send_json_error( 'missing_capabilities' );
            wp_die();
        }





        update_option("mcfe_data", self::get_data() );

        wp_send_json_success(
            array(
                'wc_admin_mcfe_nonce'   => $_POST['wc_admin_mcfe_nonce'],
                'mcfe_data' => self::get_data(),
            )
        );
    }
}

return new MCFE_SETTING();