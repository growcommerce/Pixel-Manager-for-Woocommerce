<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://growcommerce.io/
 * @since      1.0.0
 *
 * @package    Pixel_Manager_For_Woocommerce
 * @package    Pixel_Manager_For_Woocommerce/admin/partials
 * Pixel Manager For Woocommerce
 */

if(!defined('ABSPATH')){
	exit; // Exit if accessed directly
}
if(!class_exists('PMW_PixelsRateUS')){
	class PMW_PixelsRateUS extends PMW_AdminHelper{
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
      ?>
      <div class="pmw_form-wrapper">
        <div class="pmw_form-row">
          <div class="pmw_form-group">
            <label class="pmw_row-title"><?php echo esc_attr__('Rate Us', 'pixel-manager-for-woocommerce'); ?></label>
            <p><?php esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco', 'pixel-manager-for-woocommerce'); ?></p>
            <a href="#" target="_blank"><?php echo esc_attr__('Rate Us :)', 'pixel-manager-for-woocommerce'); ?></a>
          </div>
        </div>
      </div>
      <?php
    }
    /**
     * Page JS
     **/
    protected function page_js(){
    }
	}
}