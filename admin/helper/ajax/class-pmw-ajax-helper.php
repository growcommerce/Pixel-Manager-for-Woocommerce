<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://growcommerce.io/
 * @since      1.0.0
 *
 * @package    Pixel_Manager_For_Woocommerce
 */

if(!defined('ABSPATH')){
	exit; // Exit if accessed directly
}
if(!class_exists('PMW_AjaxHelper')):
  class PMW_AjaxHelper extends PMW_AdminHelper{
    protected $PMW_API;
    public function __construct(){
      $this->includes();
      $this->PMW_API = new PMW_AdminAPIHelper();
      add_action('wp_ajax_pmw_pixels_save', array($this,'pmw_pixels_save') );
      add_action('wp_ajax_pmw_pixels_support_save', array($this,'pmw_pixels_support_save') );
      add_action('wp_ajax_pmw_check_privecy_policy', array($this,'pmw_check_privecy_policy') );
    }

    public function includes(){
      if(!class_exists('PMW_AdminAPIHelper')){
        require_once( PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/helper/class-pmw-admin-api-helper.php');
      }
    }

    public function pmw_pixels_support_save(){
      $ajax_nonce = isset($_POST["pmw_ajax_nonce"])?sanitize_text_field($_POST["pmw_ajax_nonce"]):"";
      $name = isset($_POST["name"])?sanitize_text_field($_POST["name"]):"";
      $email_id = isset($_POST["email_id"])?sanitize_email($_POST["email_id"]):"";
      $issue = isset($_POST["issue"])?sanitize_textarea_field($_POST["issue"]):"";
      if($this->admin_safe_ajax_call($ajax_nonce, 'pmw_ajax_nonce')){
        $fields = array(
          "user_support" => array(
            "name" => $name,
            "email" => $email_id,
            "issue" => $issue
          )
        );
        $validate = $this->validate_pixels_support($fields);
        if(isset($validate["error"]) && $validate["error"] == true){
          echo wp_send_json( $validate );
          exit;
        }else{
          $store = $this->get_pmw_api_store();
          $store_id = ( isset($store['store_id']) )?$store['store_id']:"";
          $data = array(
            "store_id" => sanitize_text_field($store_id),
            "email" => $fields["user_support"]["email"],
            "issue_data" => $fields["user_support"]
          );
          $args = array(
            'timeout' => 10000,
            'headers' => array(
              'Authorization' => "Bearer PMDZCXJL==",
              'Content-Type' => 'application/json'
            ),
          'body' => wp_json_encode($data)
          ); 
          $api_rs = $this->PMW_API->pmw_api_call("support/save", $args);
          if (isset($api_rs->error) && $api_rs->error == '' ) {
            echo wp_send_json( array("error"=>false, 'message'=> __("We will get back to you soon..", "pixel-manager-for-woocommerce")) );
            exit;
          }else{
            echo wp_send_json( array("error"=>true, 'message'=> __("Your rquest not saved.", "pixel-manager-for-woocommerce")) );
            exit;
          }          
        }
      }else{
        echo wp_send_json( array("error"=>true, 'message'=> __("Your admin nonce is not valid.", "pixel-manager-for-woocommerce")) );
        exit;
      }
    }
    
    public function get_post_pmw_pixels_option_sanitize(){
      //$pixels = array("facebook_pixel", "pinterest_pixel", "snapchat_pixel");
      return array(
        "user" => array(
          "email_id" => isset($_POST["email_id"])?sanitize_email($_POST["email_id"]):""
        ),
        "facebook_pixel" => array(
          "pixel_id" => isset($_POST["facebook_pixel_id"])?sanitize_text_field($_POST["facebook_pixel_id"]):"",
          "is_enable" => isset($_POST["facebook_pixel_is_enable"])?sanitize_text_field($_POST["facebook_pixel_is_enable"]):false
        ),
        "pinterest_pixel" => array(
          "pixel_id" => isset($_POST["pinterest_pixel_id"])?sanitize_text_field($_POST["pinterest_pixel_id"]):"",
          "is_enable" => isset($_POST["pinterest_pixel_is_enable"])?sanitize_text_field($_POST["pinterest_pixel_is_enable"]):false
        ),
        "snapchat_pixel" => array(
          "pixel_id" => isset($_POST["snapchat_pixel_id"])?sanitize_text_field($_POST["snapchat_pixel_id"]):"",
          "is_enable" => isset($_POST["snapchat_pixel_is_enable"])?sanitize_text_field($_POST["snapchat_pixel_is_enable"]):false
        ),
        "privecy_policy" => array(
          "is_theme_plugin_list" => isset($_POST["is_theme_plugin_list"])?sanitize_text_field($_POST["is_theme_plugin_list"]):0,
          "privecy_policy" => 1
        )
      );
    }
    /**
     * Save Pixel data
     **/
    public function pmw_pixels_save(){
      $ajax_nonce = isset($_POST["pmw_ajax_nonce"])?sanitize_text_field($_POST["pmw_ajax_nonce"]):"";
      if($this->admin_safe_ajax_call($ajax_nonce, 'pmw_ajax_nonce')){
        $pixels_option = $this->get_post_pmw_pixels_option_sanitize();
        $validate = $this->validate_pixels($pixels_option);
        if(isset($validate["error"]) && $validate["error"] == true){
          echo wp_send_json( $validate );
          exit;
        }else{
          $store_data = array();
          //print_r($pixels_option);
          $pixels_option = $this->filter_pixels_option($pixels_option);       
          $pixels_option = apply_filters("pmw_pixels_option_before_save", $pixels_option);
          $this->save_pmw_pixels_option($pixels_option);
          $api_rs = $this->PMW_API->save_product_store($pixels_option, 1);
          if (!empty($api_rs) && isset($api_rs->error) && $api_rs->error == '' && isset($api_rs->data) ) {
            $this->save_pmw_api_store((array)$api_rs->data);
          }                    
          echo wp_send_json( array("error" => false, 'message' => __("Your pixel settings saved.", "pixel-manager-for-woocommerce")) );
          exit;
        }
      }else{
        echo wp_send_json( array("error" => true, 'message' => __("Your admin nonce is not valid.", "pixel-manager-for-woocommerce")) );
        exit;
      }
    }
    /**
     * Check privecy policy base on user email
     **/
    public function pmw_check_privecy_policy(){
      $ajax_nonce = isset($_POST["pmw_ajax_nonce"])?sanitize_text_field($_POST["pmw_ajax_nonce"]):"";
      if($this->admin_safe_ajax_call($ajax_nonce, 'pmw_ajax_nonce')){
        $pixels_option = $this->get_post_pmw_pixels_option_sanitize();
        $validate = $this->validate_pixels($pixels_option);
        if(isset($validate["error"]) && $validate["error"] == true){
          echo wp_send_json( $validate );
          exit;
        }else{
          $pixels_option_old = $this->get_pmw_pixels_option();
          if( isset($pixels_option_old['privecy_policy']['privecy_policy']) && $pixels_option_old['privecy_policy']['privecy_policy'] == 1 && $pixels_option_old['user']['email_id'] ==  $pixels_option['user']['email_id']){
            echo wp_send_json( array( "error" => false ) );
            exit;
          }else{
            echo wp_send_json( array( "error" => true ) );
            exit;
          }
        }
      }else{
        echo wp_send_json( array("error" => true, 'message' => __("Your admin nonce is not valid.", "pixel-manager-for-woocommerce")) );
        exit;
      }
    }
    /**
     * Check AJAX nonce
     **/
    protected function admin_safe_ajax_call( $nonce, $registered_nonce_name ) {
      // only return results when the user is an admin with manage options
      if ( is_admin() && wp_verify_nonce($nonce,$registered_nonce_name) ) {
        return true;
      } else {
        return false;
      }
    }
    /**
     * Genral sanitize function for post data
     **/
    /*protected function get_post_data_sanitize(array $data, string $field, string $default = null, string $field_type = "text"){
      if($field_type == "text" && isset($data[$field]) && $data[$field]){
        return sanitize_text_field( $data[$field] );
      }elseif($field_type == "email" && isset($data[$field]) && $data[$field]){
        return sanitize_email( $data[$field] );
      }else if($default != null){
        return $default;
      }
    }*/
    /**
     * validate the value of pixels
     **/
    public function validate_pixels(array $pixels_option){
      $return = array();
      
      if(!isset($pixels_option["user"]["email_id"]) || $pixels_option["user"]["email_id"] == "" || !is_email($pixels_option["user"]["email_id"]) ){
        $return = array("error" => true, "message" => __("Check your email ID.", "pixel-manager-for-woocommerce"));
      }else if(isset($pixels_option["facebook_pixel"]["pixel_id"]) && $pixels_option["facebook_pixel"]["pixel_id"] && !$this->is_facebook_pixel_id($pixels_option["facebook_pixel"]["pixel_id"])){
        $return = array("error" => true, "message" => __("Check your Facebook pixel ID.", "pixel-manager-for-woocommerce"));
      }else if(isset($pixels_option["pinterest_pixel"]["pixel_id"]) && $pixels_option["pinterest_pixel"]["pixel_id"] && !$this->is_pinterest_pixel_id($pixels_option["pinterest_pixel"]["pixel_id"])){
        $return = array("error" => true, "message" => __("Check your Pinterest pixel ID.", "pixel-manager-for-woocommerce"));
      }else if(isset($pixels_option["snapchat_pixel"]["pixel_id"]) && $pixels_option["snapchat_pixel"]["pixel_id"] && !$this->is_snapchat_pixel_id($pixels_option["snapchat_pixel"]["pixel_id"])){
        $return = array("error" => true, "message" => __("Check your Snapchat pixel ID.", "pixel-manager-for-woocommerce"));
      }
      return $return;
    }
    /**
     * validate the value of support
     **/
    public function validate_pixels_support(array $pixels_option){
      $return = array();      
      if(!isset($pixels_option["user_support"]["name"]) || $pixels_option["user_support"]["name"] == "" ){
        $return = array("error" => true, "message" => __("Enter your full name.", "pixel-manager-for-woocommerce"));
      }else if(!isset($pixels_option["user_support"]["email"]) || $pixels_option["user_support"]["email"] == "" || !is_email($pixels_option["user_support"]["email"]) ){
        $return = array("error" => true, "message" => __("check your email ID.", "pixel-manager-for-woocommerce"));
      }else if(!isset($pixels_option["user_support"]["issue"]) || $pixels_option["user_support"]["issue"] =="" ){
        $return = array("error" => true, "message" => __("Describe your Issue.", "pixel-manager-for-woocommerce"));
      }
      return $return;
    }
    /**
     * filter the pixels option
     **/
    public function filter_pixels_option(array $pixels_option){
      $return = array();
      if(!isset($pixels_option["facebook_pixel"]["pixel_id"]) && $pixels_option["facebook_pixel"]["pixel_id"] ==""){
        $pixels_option["facebook_pixel"]["is_enable"] = false;
      }
      if(!isset($pixels_option["facebook_pixel"]["is_enable"]) || $pixels_option["facebook_pixel"]["is_enable"] == null){
        $pixels_option["facebook_pixel"]["is_enable"] = false;
      }
      if(!isset($pixels_option["pinterest_pixel"]["pixel_id"]) && $pixels_option["pinterest_pixel"]["pixel_id"] == ""){
        $pixels_option["pinterest_pixel"]["is_enable"] = false;
      }
      if(!isset($pixels_option["pinterest_pixel"]["is_enable"]) || $pixels_option["pinterest_pixel"]["is_enable"] == null){
        $pixels_option["pinterest_pixel"]["is_enable"] = false;
      }
      if(!isset($pixels_option["snapchat_pixel"]["pixel_id"]) && $pixels_option["snapchat_pixel"]["pixel_id"] ==""){
        $pixels_option["snapchat_pixel"]["is_enable"] = false;
      }
      if(!isset($pixels_option["snapchat_pixel"]["is_enable"]) || $pixels_option["snapchat_pixel"]["is_enable"] == null){
        $pixels_option["snapchat_pixel"]["is_enable"] = false;
      }
      return $pixels_option;
    }
  }
endif;
new PMW_AjaxHelper();