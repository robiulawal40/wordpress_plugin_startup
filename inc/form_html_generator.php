<?php  
/**
 * MCFE Settings
 *
 * @package WooCommerce/Admin
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Form_Html_Generator', false ) ) {
	return new Form_Html_Generator();
}

class Form_Html_Generator extends WC_Settings_API {

    public static function get_html( $field ){
        
        return $generated_html->get_admin_options_html($field);
    }
	/**
	 * Return admin options as a html string.
	 *
	 * @return string
	 */
	public function get_admin_options_html($field) {

			$settings_html = $this->generate_settings_html( $this->build_array($field), false );

		return '<table class="form-table">' . $settings_html . '</table>';
    }

    public function build_array( $field ){
       $returned_field = [];

       foreach ($field as $key=>$value) {
       
        
        switch ($key) {
            case "type" :
                $returned_field[$key]['type'] = "checkbox";
                $returned_field[$key]['title'] = "Type";
                $returned_field[$key]['default'] = $value;
                ;
                break;
            
            case "name" :
                $returned_field[$key]['type'] = "text";
                $returned_field[$key]['title'] = "Name";
                $returned_field[$key]['default'] = $value;
                ;
                break;
            
            case "label" :
                $returned_field[$key]['type'] = "text";
                $returned_field[$key]['title'] = "Label";
                ;
                break;
            
            case "placeholder" :
                $returned_field[$key]['type'] = "text";
                $returned_field[$key]['title'] = "Placeholder";
                ;
                break;
            
            case "autocomplete" :
                $returned_field[$key]['type'] = "text";
                $returned_field[$key]['title'] = "Autocomplete";
                ;
                break;
            
            case "priority" :
                $returned_field[$key]['type'] = "text";
                $returned_field[$key]['title'] = "Priority";
                ;
                break;
            
            case "default_value" :
                $returned_field[$key]['type'] = "text";
                $returned_field[$key]['title'] = "Default Value";
                ;
                break;
            
            case "class" :
                $returned_field[$key]['type'] = "select";
                $returned_field[$key]['title'] = "Class";
                $returned_field[$key]['options'] = array(
                    "One" => "One",
                    "Two" => "Two",
                    "Three" => "Three",
                );
                ;
                break;
            
            case "validate" :
                $returned_field[$key]['type'] = "multiselect";
                $returned_field[$key]['title'] = "Validate";
                $returned_field[$key]['options'] = array(
                    "One" => "One",
                    "Two" => "Two",
                    "Three" => "Three",
                );
                ;
                break;
            
            case "required" :
                $returned_field[$key]['type'] = "checkbox";
                $returned_field[$key]['title'] = "Required";
                ;
                break;
            
            case "is_enabled" :
                $returned_field[$key]['type'] = "checkbox";
                $returned_field[$key]['title'] = "Is Enabled?";
                ;
                break;
            
            case "country" :
                $returned_field[$key]['type'] = "text";
                $returned_field[$key]['title'] = "Country";
                ;
                break;
            
            case "country_field" :
                $returned_field[$key]['type'] = "text";
                $returned_field[$key]['title'] = "Country Field";
                ;
                break;
            
            case "display_in_email" :
                $returned_field[$key]['type'] = "checkbox";
                $returned_field[$key]['title'] = "Display in email";
                ;
                break;
            
            case "display_order_details" :
                $returned_field[$key]['type'] = "text";
                $returned_field[$key]['title'] = "display_order_details";
                ;
                break;        
                

            default:
            $returned_field[$key]['type'] = "text";
            $returned_field[$key]['title'] = "text";
                ;
                break;
        }

       }
       return $returned_field;
    }
    
}