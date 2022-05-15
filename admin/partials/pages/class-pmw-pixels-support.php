<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Pixel_Manager_For_Woocommerce
 * @package    Pixel_Manager_For_Woocommerce/admin/partials
 * Pixel Manager For Woocommerce
 */

if(!defined('ABSPATH')){
	exit; // Exit if accessed directly
}
if(!class_exists('PMW_PixelsSupport')){
	class PMW_PixelsSupport extends PMW_AdminHelper{
    public function __construct( ) {
      $this->req_int();
      $this->load_html();
    }
    public function req_int(){
    }
    protected function load_html(){
      $this->page_html();
      $this->page_js();
    }
    /**
     * Page HTML
     **/
    protected function page_html(){
      $fields = [  
       "user_name"=>[
          [
            "type" => "text",
            "label" => __("Name", "pixel-manager-for-woocommerce"),
            "name" => "name",
            "id" => "name",
            "placeholder" => __("Enter your full name", "pixel-manager-for-woocommerce"),
            "class" => "user_name",
            "tooltip" =>[
              "title" => __("Enter your full name.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "email_id" => [
          [
            "type" => "text",
            "label" => __("Email Id", "pixel-manager-for-woocommerce"),
            "name" => "email_id",
            "id" => "email_id",
            "placeholder" => __("Enter your email", "pixel-manager-for-woocommerce"),
            "class" => "email_id",
            "tooltip" =>[
              "title" => __("Enter your email.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "issue" => [
          [
            "type" => "textarea",
            "label" => __("Issue", "pixel-manager-for-woocommerce"),
            "name" => "issue",
            "id" => "issue",
            "placeholder" => __("Describe your Issue", "pixel-manager-for-woocommerce"),
            "class" => "issue",
            "tooltip" =>[
              "title" => __("Describe your Issue.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],        
        "button" => [
          [
            "type" => "button",
            "name" => "pixels_save",
            "id" => "pixels_save",
            "class" => "pixels_save"
          ]
        ]
      ];
      $form = array("name" => "pmw-pixels-support", "id" => "pmw-pixels-support", "method" => "post", "class" => "pmw-pixels-support-from");
      $this->add_form_fields($fields, $form);
    }
    /**
     * Page JS
     **/
    protected function page_js(){
    }
	}
}