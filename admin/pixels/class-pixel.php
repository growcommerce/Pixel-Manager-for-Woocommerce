<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    
 * @package    PMW_Pixel
 * 
 */
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}
require_once( 'class-pixel-helper.php');
if(!class_exists('PMW_Pixel')):
	class PMW_Pixel extends PMW_PixelHelper{
		protected $options;
		public function __construct(){
			$this->req_int();
			new PMW_PixelHelper();
			$this->options = $this->get_option();
      add_action('after_setup_theme', array($this, 'inject_pixels'));
		}

		public function req_int(){
      if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
      }
			require_once( 'class-cookie-consent-manager.php');
			require_once( 'class-pixel-manager.php');	
			require_once( 'facebook/public/class-facebook-pixel-public.php');
			require_once( 'pinterest/public/class-pinterest-pixel-public.php');
      require_once( 'snapchat/public/class-snapchat-pixel-public.php');			
		}

		public function inject_pixels(){
      // set current user
      /*$current_user = wp_get_current_user();
      if( isset($current_user->ID) && $current_user->ID != 0 ){
        $this->options['user_id'] = $current_user->ID;
        $this->options['user_email'] = (isset($current_user->data->user_email))?$current_user->data->user_email:"";
      }*/
      // set user ip
      $this->options['user_ip'] = $this->get_user_ip();

      // check if cookie prevention has been activated
      // load the cookie consent management functions
      $cookie_consent = new PMW_CookieConsentManagement();
      $cookie_consent->set_plugin_prefix(PIXEL_MANAGER_FOR_WOOCOMMERCE_PREFIX);

      if ($cookie_consent->is_cookie_prevention_active() == false) {
        // inject pixels
        if($this->is_woocommerce_active()){
          new PMW_PixelManager($this->options);
        }
        if($this->is_facebook_enable()){
        	new PMW_FBPixelManager($this->options);
        }   
        if($this->is_pinterest_enable()){
        	new PMW_PinterestPixelManager($this->options);
        }
        if($this->is_snapchat_enable()){
          new PMW_SnapchatPixelManager($this->options);
        }    
      }
    }
	}
endif;
new PMW_Pixel();