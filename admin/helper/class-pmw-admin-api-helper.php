<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    
 * @package    PMW_Helper
 * 
 */
if(!defined('ABSPATH')){
  exit; // Exit if accessed directly
}
if(!class_exists('PMW_AdminAPIHelper')):
  class PMW_AdminAPIHelper extends PMW_AdminHelper{
    public function __construct() {
      //$this->includes();
      //add_action('admin_init',array($this, 'init'));
    }
    public function includes(){
    }
    public function init(){      
    }
    /**
     * API call function
     **/
    public function pmw_api_call( string $end_point, array $args ){
      try {
        if( !empty($args) && $end_point ){ 
          $url = PMW_API_URL.$end_point;
          $args['timeout']= "1000";
          $request = wp_remote_post(esc_url_raw($url), $args);
          return json_decode(wp_remote_retrieve_body($request));
          /*$response_code = wp_remote_retrieve_response_code($request);
          $response_message = wp_remote_retrieve_response_message($request);          
          if ((isset($response_body->error) && $response_body->error == '')) {
            return $response_body->data;
          } else {
            return new WP_Error($response_code, $response_message, $response_body);
          }*/
        }
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }

    public function save_product_store( $pixels_option = array() , $product_status = "1"){
      if(empty($pixels_option)){
        $pixels_option = $this->get_pmw_pixels_option();
      }
      if(empty($pixels_option)){
        return;
      }
      $current_user = wp_get_current_user();
      $store_data = $store_data = array(
        'store_info' => array(
          'country_code' => get_option('woocommerce_default_country'),
          'currency_code' => get_option('woocommerce_currency'),
          'language_code' => get_locale()
        )
      );
      if(isset($pixels_option["privecy_policy"]["is_theme_plugin_list"]) && $pixels_option["privecy_policy"]["is_theme_plugin_list"]){
        $store_data['active_plugins'] = get_plugins();
      }

      $data = array(
        "email" => sanitize_email($pixels_option['user']['email_id']),
        "first_name" => sanitize_text_field($current_user->user_firstname),
        "last_name" => sanitize_text_field($current_user->user_lastname),
        "website" => esc_url_raw(get_site_url()),            
        "product_id" => PMW_PRODUCT_ID,
        "store_data" => $store_data,
        "product_data" => $this->get_product_data($pixels_option, $product_status)
      );

      $args = array(
        'timeout' => 10000,
        'headers' => array(
          'Authorization' => "Bearer PMDZCXJL==",
          'Content-Type' => 'application/json'
        ),
      'body' => wp_json_encode($data)
      );
      return $this->pmw_api_call("store/save", $args);
    }
  }
endif;