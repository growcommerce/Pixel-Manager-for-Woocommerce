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
if(!class_exists('PMW_Pixels')){
	class PMW_Pixels extends PMW_AdminHelper{
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
      $current_user = wp_get_current_user();
      $pixels_option = $this->get_pmw_pixels_option();
      $email_id = isset($pixels_option['user']['email_id'])?$pixels_option['user']['email_id']:$current_user->user_email;
      $facebook_pixel_id = isset($pixels_option['facebook_pixel']['pixel_id'])?$pixels_option['facebook_pixel']['pixel_id']:"";
      $facebook_pixel_is_enable = isset($pixels_option['facebook_pixel']['is_enable'])?$pixels_option['facebook_pixel']['is_enable']:"";

      $pinterest_pixel_id = isset($pixels_option['pinterest_pixel']['pixel_id'])?$pixels_option['pinterest_pixel']['pixel_id']:"";
      $pinterest_pixel_is_enable = isset($pixels_option['pinterest_pixel']['is_enable'])?$pixels_option['pinterest_pixel']['is_enable']:"";

      $snapchat_pixel_id = isset($pixels_option['snapchat_pixel']['pixel_id'])?$pixels_option['snapchat_pixel']['pixel_id']:"";
      $snapchat_pixel_is_enable = isset($pixels_option['snapchat_pixel']['is_enable'])?$pixels_option['snapchat_pixel']['is_enable']:"";
      $privecy_policy = isset($pixels_option['privecy_policy']['privecy_policy'])?$pixels_option['privecy_policy']['privecy_policy']:"";
      $is_theme_plugin_list = isset($pixels_option['privecy_policy']['is_theme_plugin_list'])?$pixels_option['privecy_policy']['is_theme_plugin_list']:"0";
      $fields = [
        "user" => [    
          [
            "type" => "text",
            "label" => __("Email Id", "pixel-manager-for-woocommerce"),
            "name" => "email_id",
            "id" => "email_id",
            "value" => $email_id,
            "placeholder" => __("Enter your email", "pixel-manager-for-woocommerce"),
            "class" => "email_id",
            "tooltip" =>[
              "title" => __("Enter your email.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "facebook_pixel" => [    
          [
            "type" => "text_with_switch",
            "label" => __("Facebook pixel ID", "pixel-manager-for-woocommerce"),
            "note"  => __("Ex.The Facebook pixel ID: 2885195855125459", "pixel-manager-for-woocommerce"),
            "name" => "facebook_pixel_id",
            "id" => "facebook_pixel_id",
            "value" => $facebook_pixel_id,
            "placeholder" => __("2885195855125459", "pixel-manager-for-woocommerce"),
            "class" => "facebook_pixel_id"
          ],[
            "type" => "switch_with_text",
            "label" => __("Pixel status", "pixel-manager-for-woocommerce"),
            "name" => "facebook_pixel_is_enable",
            "id" => "facebook_pixel_is_enable",
            "value" => $facebook_pixel_is_enable,
            "class" => "facebook_pixel_is_enable",
            "tooltip" =>[
              "title" => __("Create and install a Facebook pixel.", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://www.facebook.com/business/help/952192354843755?id=1205376682832142"
            ]
          ]
        ],
        "pinterest_pixel" => [
          [
            "type" => "text_with_switch",
            "label" => __("Pinterest Pixel ID", "pixel-manager-for-woocommerce"),
            "note"  => __("Ex.The Pinterest pixel ID: 2613257208392", "pixel-manager-for-woocommerce"),
            "name" => "pinterest_pixel_id",
            "id" => "pinterest_pixel_id",
            "value" => $pinterest_pixel_id,
            "placeholder" => __("2613257208392", "pixel-manager-for-woocommerce"),
            "class" => "pinterest_pixel_id"
          ],[
            "type" => "switch_with_text",
            "label" => __("Pixel status", "pixel-manager-for-woocommerce"),
            "name" => "pinterest_pixel_is_enable",
            "id" => "pinterest_pixel_enable",
            "value" => $pinterest_pixel_is_enable,
            "class" => "pinterest_pixel_is_enable",
            "tooltip" =>[
              "title" => __("Pinterest Pixel Base Code.", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://developers.pinterest.com/docs/tag/conversion/#basecode"
            ]
          ]
        ],
        "snapchat_pixel" => [
          [
            "type" => "text_with_switch",
            "label" => __("Snapchat Pixel ID", "pixel-manager-for-woocommerce"),
            "note"  => __("Ex.The Snapchat pixel ID: 12e1ec0a-91aa-4267-b1a3-182c355710e7", "pixel-manager-for-woocommerce"),
            "name" => "snapchat_pixel_id",
            "id" => "snapchat_pixel_id",
            "value" => $snapchat_pixel_id,
            "placeholder" => __("12e1ec0a-91aa-4267-b1a3-182c355710e7", "pixel-manager-for-woocommerce"),
            "class" => "snapchat_pixel_id"
          ],[
            "type" => "switch_with_text",
            "label" => __("Pixel status", "pixel-manager-for-woocommerce"),
            "name" => "snapchat_pixel_is_enable",
            "id" => "snapchat_pixel_is_enable",
            "value" => $snapchat_pixel_is_enable,
            "class" => "snapchat_pixel_is_enable",
            "tooltip" =>[
              "title" => __("Snapchat Pixel Base Code.", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://businesshelp.snapchat.com/s/article/pixel-website-install?language=en_US"
            ]
          ]
        ],
        "hidden" => [
          [
            "type" => "hidden",
            "name" => "privecy_policy",
            "id" => "privecy_policy",
            "value" => $privecy_policy
          ],[
            "type" => "hidden",
            "name" => "is_theme_plugin_list",
            "id" => "is_theme_plugin_list",
            "value" => $is_theme_plugin_list
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
      $form = array("name" => "pmw-pixels", "id" => "pmw-pixels", "method" => "post", "class" => "pmw-pixels-from");
      $this->add_form_fields($fields, $form);      
      ?>
      <div id="pmw_privacy_popup" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
          <!-- Modal content -->
          <div class="modal-content">
            <div class="modal-header">
              <span id="close" class="close">&times;</span>
            </div>
            <div class="modal-body">
              <div class="modal-top-area">
                <div class="logo-section">
                  <div class="logo_section-img"><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/wp.png"); ?>" alt="img"></div>
                  <div class="logo_section-img"><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/pixel-icon.png"); ?>" alt="img"></div>
                </div>
              </div>
              <div class="modal-middle-area">
                <p><strong>Hey <?php echo esc_attr($current_user->user_firstname); ?>,</strong></p>
               <p><?php echo esc_attr__('Never miss an important update - opt in to our security and feature updates notifications, and non-sensitive diagnostic tracking with', 'pixel-manager-for-woocommerce'); ?> <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/"); ?>">GrowCommerce</a></p>
                <p><a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/privacy-policy/"); ?>"><?php echo esc_attr__('Privacy & Terms', 'pixel-manager-for-woocommerce'); ?></a></p>
                <div class="modal_button-area">
                  <button class="pmw_btn pmw_btn-fill" id="pmw_accept_privecy_policy"><?php echo esc_attr__('Allow & Continue', 'pixel-manager-for-woocommerce'); ?></button>
                  <?php /*<button class="pmw_btn pmw_btn-default">Skip</button>*/ ?>
                </div>
              </div>
              <div class="modal-bottom-area">
                <h2 class="toggle_title-text"><?php echo esc_attr__('What Permissions are being Granted?', 'pixel-manager-for-woocommerce'); ?></h2>
                <div class="pmw_slide-down-area">
                  <ul>
                    <li>
                      <div class="pmw_slide-area-image"><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/Icon-profile.png"); ?>" alt="img"></div>
                      <div class="pmw_slide-area-content">
                        <span class="pmw_slide-area-title"><?php echo esc_attr__('Your Profile Overview', 'pixel-manager-for-woocommerce'); ?></span>
                        <p><?php echo esc_attr__('Name and email address', 'pixel-manager-for-woocommerce'); ?></p>
                      </div>
                    </li>
                    <li>
                      <div class="pmw_slide-area-image"><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/Icon-site-overview.png"); ?>" alt="img"></div>
                      <div class="pmw_slide-area-content">
                        <span class="pmw_slide-area-title"><?php echo esc_attr__('Your Site Overview', 'pixel-manager-for-woocommerce'); ?></span>
                        <p><?php echo esc_attr__('Site URL, country, currency, WP version, PHP info', 'pixel-manager-for-woocommerce'); ?></p>
                      </div>
                    </li>
                    <li>
                      <div class="pmw_slide-area-image"><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/Icon-notice.png"); ?>" alt="img"></div>
                      <div class="pmw_slide-area-content">
                        <span class="pmw_slide-area-title"><?php echo esc_attr__('Admin Notice', 'pixel-manager-for-woocommerce'); ?></span>
                        <p><?php echo esc_attr__('Updates, announcements, marketing, no spam', 'pixel-manager-for-woocommerce'); ?></p>
                      </div>
                    </li>
                    <li>
                      <div class="pmw_slide-area-image"><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/Icon-status.png"); ?>" alt="img"></div>
                      <div class="pmw_slide-area-content">
                        <span class="pmw_slide-area-title"><?php echo esc_attr__('Current Plugin Status', 'pixel-manager-for-woocommerce'); ?></span>
                        <p><?php echo esc_attr__('Active, deactivated, or uninstalled, settings', 'pixel-manager-for-woocommerce'); ?></p>
                      </div>
                    </li>
                    <li>
                      <div class="pmw_slide-area-image"><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/icon-plugin.png"); ?>" alt="img"></div>
                      <div class="pmw_slide-area-content">
                        <span class="pmw_slide-area-title"><?php echo esc_attr__('Plugins & Themes', 'pixel-manager-for-woocommerce'); ?></span>
                        <p><?php echo esc_attr__('Title, slug, version, and is active', 'pixel-manager-for-woocommerce'); ?></p>
                      </div>
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="pmw_custom-control-input" id="ch_is_theme_plugin_list" checked>
                        <label class="pmw_custom-control-label" for="ch_is_theme_plugin_list"></label>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
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
