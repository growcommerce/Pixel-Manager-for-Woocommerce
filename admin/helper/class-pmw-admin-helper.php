<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://growcommerce.io/
 * @since      1.0.0
 *
 * @package    
 * @package    PMW_Helper
 * 
 */
if(!defined('ABSPATH')){
  exit; // Exit if accessed directly
}
if(!class_exists('PMW_AdminHelper')):
  require_once( PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/helper/class-pmw-setting-helper.php');
  class PMW_AdminHelper extends PMW_SettingHelper{

    public function __construct() {
      //$this->includes();
      add_action('init',array($this, 'init'));
    }
    public function init(){
      add_filter('sanitize_option_pmw_pixels_option', array($this, 'sanitize_option_pmw_general'), 10, 2);
      add_filter('sanitize_option_pmw_api_store', array($this, 'sanitize_option_pmw_general'), 10, 2);
    }
    /**
     * sanitize options fields
     **/
    public function sanitize_option_pmw_general($value, $option){
      global $wpdb;
      $value = $wpdb->strip_invalid_text_for_column( $wpdb->options, 'option_value', $value );
      if ( is_wp_error( $value ) ) {
        $error = $value->get_error_message();
      }
      if ( ! empty( $error ) ) {
        $value = get_option( $option );
        if ( function_exists( 'add_settings_error' ) ) {
          add_settings_error( $option, "invalid_{$option}", $error );
        }
      }
      return $value;
    }
    /**
     * Pixels options
     **/
    public function save_pmw_pixels_option($pixels_option){
      //print_r(serialize($pixels_option));
      update_option("pmw_pixels_option", serialize( $pixels_option ));
      //exit;
    }
    public function get_pmw_pixels_option(){
      return unserialize( get_option("pmw_pixels_option"));
    }
    /**
     * API options
     **/
    public function save_pmw_api_store($data){
      update_option("pmw_api_store", serialize( $data ));
    }

    public function get_pmw_api_store(){
      return unserialize( get_option("pmw_api_store"));
    }
    /**
     * validate pixels function
     **/
    protected function is_facebook_pixel_id( $string ){
      if( empty($string) ){
        return true;
      }
      $re = '/^\d{14,16}$/m';
      return $this->validate_with_regex( $re, $string );
    }

    protected function is_pinterest_pixel_id( $string ){
      if( empty($string) ){
        return true;
      }
      $re = '/^\d{13}$/m';
      return $this->validate_with_regex( $re, $string );
    }

    protected function is_snapchat_pixel_id( $string ){
      if( empty($string) ){
        return true;
      }
      $re = '/^[a-z0-9\-]*$/m';
      return $this->validate_with_regex( $re, $string );
    }

    protected function validate_with_regex( string $re, $string ){
      preg_match_all( $re, $string, $matches, PREG_SET_ORDER, 0 );      
      if( isset( $matches[0] ) ){
        return true;
      }else{
        return false;
      }    
    }

    public function get_product_data(array $pixels_option= array(), $product_status = "1"){
      $product_data = array();
      if(empty($pixels_option)){
        $pixels_option = $this->get_pmw_pixels_option();
      }
      return array(
        "settings" => $pixels_option,
        "status" => $product_status,
        "version" => PIXEL_MANAGER_FOR_WOOCOMMERCE_VERSION,
        "domain" => esc_url_raw(get_site_url()),
        "update_date" => date("Y-m-d")
      );
    }
    
  }
endif;
new PMW_AdminHelper();