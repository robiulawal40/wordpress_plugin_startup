<?php
/*
Plugin Name: WooCommerce Missing Checkout Form Editor
Plugin URI: https://github.com/robiulawal40/my-worked
Description: A best and simple way to edit checkout fields
Version: 1.0.1
Author: Robiul Awal
Author URI: https://github.com/robiulawal40/
Text Domain: woocommerce-mcfe
Domain Path: /languages/
*/

defined( 'ABSPATH' ) or die( 'Stop!!' );

if( !class_exists('woocommerceMCFE') ):

final class woocommerceMCFE{
	/*
	* class property
	*
	*
	*/

	private static $instance;

	/*
	* instance functions
	*
	*
	*/
	public static function instance(){
		if( is_null( self::$instance ) ){
			self::$instance = new woocommerceMCFE;
		}
		return self::$instance;
	}

	/*
	 * Cloning is forbidden.
	 *
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nvce' ), '1.0' );
	}

	/*
	 * Unserializing instances of this class is forbidden.
	 *
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nvce' ), '1.0' );
	}

	/*
	* Plugin constructor
	*
	*
	*/
	 function __construct(){

		add_filter( 'plugin_action_links_woocommerce-missing-checkout-form-editor/woocommerce-missing-checkout-form-editor.php', array( $this, 'ns_settings_link' ) );
		//plugins\woocommerce\includes\admin\class-wc-admin-settings.php
		add_filter("woocommerce_get_settings_pages", array( $this, "woocommerce_add_settings_pages") );
		
		add_action('admin_menu', array( $this,  'register_my_custom_submenu_page'),99);

		 $this->set_constants();
		 $this->includes();		 		 
		}

		public function woocommerce_add_settings_pages( $settings ){
			
			$settings[] = include MCFEDIR."inc/class-wc-admin-settings.php";
			return $settings;
		}	

	public function tab_link(){

	return esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout_settings' ) );

	}

	public function register_my_custom_submenu_page() {
		global $submenu;
		$submenu['woocommerce'][] = array( 'Checkout settings', 'manage_options', $this->tab_link() );

		}
        
     public function ns_settings_link( $links ) {

            $url = $this->tab_link();

            $settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
            array_push(
                $links,
                $settings_link
            );
            return $links;
        }
	/*
	* Setting the plugin  constant
	*
	*/
	public function set_constants(){

     if (!defined('MCFE_VERSION')) {
         define('MCFE_VERSION', '1.0.0');
     }
	 if( !defined('MCFE_DOMAIN') ){
		 define('MCFE_DOMAIN','woocommerce-mow');
	 }
	 if( !defined('MCFE_NAME') ){
		 define('MCFE_NAME','WooCommerce Missing Checkout Form Editor');
	 }
     if (!defined('MCFEDIR')) {
         define('MCFEDIR', plugin_dir_path(__FILE__));
     }
     if (!defined('MCFETEMP')) {
         define('MCFETEMP', MCFEDIR.'templates/');
     }
	 if ( !defined( 'MCFEBASENAME' ) ){
         define( 'MCFEBASENAME', plugin_basename( __FILE__ ) );
	 }
	 if (!defined('MCFEURL')) {
         define('MCFEURL', plugin_dir_url(__FILE__));
	 }
	 if (!defined('MCFEDEV')) {
         define('MCFEDEV', true );
     }	 
	}

	/*
	* Plugin include files
	*
	*/
	public function includes(){
		require_once MCFEDIR."inc/functions.php";
		require_once MCFEDIR."inc/class-checkout-edit.php";	
    }
}

function WMCFE(){
    return woocommerceMCFE::instance();
}
add_action( 'plugins_loaded', 'WMCFE' );

endif;