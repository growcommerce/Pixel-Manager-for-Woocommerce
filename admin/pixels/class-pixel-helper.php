<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    
 * @package    PMW_PixelHelper
 * 
 */
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}
if(!class_exists('PMW_PixelHelper')):	
	class PMW_PixelHelper{
		protected $options;
		public function __construct(){
			$this->req_int();
			$this->options = $this->get_option();
		}
		public function req_int(){
			if (!function_exists('is_plugin_active')) {
			  include_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}
		}

		public function get_option(){
			return unserialize( get_option("pmw_pixels_option") );
		}
		/**
		 * ceck pixel active 
		 */
		public function is_facebook_enable(){
			if(isset($this->options['facebook_pixel']) && isset($this->options['facebook_pixel']['pixel_id'])){
				$pixel = $this->options['facebook_pixel'];
				if(isset($pixel['pixel_id']) && isset($pixel['is_enable']) && $pixel['pixel_id'] && $pixel['is_enable']){
					return true;
				}
			}
			return false;
		}

		public function is_pinterest_enable(){
			if(isset($this->options['pinterest_pixel']) && isset($this->options['pinterest_pixel']['pixel_id'])){
				$pixel = $this->options['pinterest_pixel'];
				if(isset($pixel['pixel_id']) && isset($pixel['is_enable']) && $pixel['pixel_id'] && $pixel['is_enable']){
					return true;
				}
			}
			return false;
		}

		public function is_snapchat_enable(){
			if(isset($this->options['snapchat_pixel']) && isset($this->options['snapchat_pixel']['pixel_id'])){
				$pixel = $this->options['snapchat_pixel'];
				if(isset($pixel['pixel_id']) && isset($pixel['is_enable']) && $pixel['pixel_id'] && $pixel['is_enable']){
					return true;
				}
			}
			return false;
		}

		/*check other plugin active */
		public function is_yith_wc_brands_active() {
      return is_plugin_active('yith-woocommerce-brands-add-on-premium/init.php');
    }
    public function is_woocommerce_brands_active() {
      return is_plugin_active('woocommerce-brands/woocommerce-brands.php');
    }
    public function is_wpml_woocommerce_multi_currency_active() {
      global $woocommerce_wpml;
      if (is_plugin_active('woocommerce-multilingual/wpml-woocommerce.php') && is_object($woocommerce_wpml->multi_currency)) {
        return true;
      } else {
        return false;
      }
    }
    public function is_woocommerce_active() {
      return is_plugin_active('woocommerce/woocommerce.php');
    }
    /**
     * get user IP
     **/
    public function get_user_ip() {
	    $ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    return $ipaddress;
		}
	}
endif;