<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://growcommerce.io/
 * @since      1.0.0
 *
 * @package    Pixel_Manager_For_Woocommerce
 * @subpackage Pixel_Manager_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pixel_Manager_For_Woocommerce
 * @subpackage Pixel_Manager_For_Woocommerce/admin
 * @author     GrowCommerce
 */
if ( ! class_exists( 'Pixel_Manager_For_Woocommerce_Admin' ) ) {	
	class Pixel_Manager_For_Woocommerce_Admin {
		private $plugin_name;
		private $version;
		protected $screen_id;
		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string    $plugin_name       The name of this plugin.
		 * @param      string    $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {
			$this->includes();
			$this->plugin_name = $plugin_name;
			$this->version = $version;
			$this->screen_id = isset($_GET['page'])?sanitize_text_field($_GET['page']):"";
			add_action( 'admin_menu', array($this,'admin_menu'));
			add_action( 'admin_enqueue_scripts', array( $this, 'pmw_page_scripts' ) );
		}

		/**
		 * includes required fils
		 *
		 * @since    1.0.0
		 */
		public function includes() {
			if (!class_exists('PMW_AjaxHelper')) {
	      require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/helper/ajax/class-pmw-ajax-helper.php');
	    }
			if (!class_exists('PMW_Header')) {
	      require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/common/class-pmw-header.php');
	    }
	    if (!class_exists('PMW_Footer')) {
	      require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/common/class-pmw-footer.php');
	    }
		}

		public function pmw_page_scripts(){
			?>
			<script>
	      var pmw_ajax_url = '<?php echo esc_url_raw(admin_url( 'admin-ajax.php' )); ?>';
	    </script>
			<?php
		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
			wp_enqueue_style( $this->plugin_name, esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL . '/admin/css/pixel-manager-for-woocommerce-admin.css'), array(), $this->version, 'all' );
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( $this->plugin_name, esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL  . '/admin/js/pixel-manager-for-woocommerce-admin.js'), array( 'jquery' ), $this->version, false );
		}

		/**
		 * Add Menu for the admin area.
		 * @since    1.0.0
		 */
		public function admin_menu(){
			add_menu_page(
	      __('Pixel manager','pixel-manager-for-woocommerce'), __('Pixel manager','pixel-manager-for-woocommerce'), 'manage_options', 'pixel-manager', array($this, 'show_page'), 'dashicons-networking', 56 );
			add_submenu_page('pixel-manager', __('Support','pixel-manager-for-woocommerce'), __('Support','pixel-manager-for-woocommerce'), 'manage_options', 'pixel-manager-support', array($this, 'show_page'));
			add_submenu_page('pixel-manager', __('Rate Us','pixel-manager-for-woocommerce'), __('Rate Us','pixel-manager-for-woocommerce'), 'manage_options', 'pixel-manager-rate-us', array($this, 'show_page'));
		}

		/**
		 * Load page for the admin area.
		 * @since    1.0.0
		 */
		public function show_page() {
			do_action('pmw_header');
			$get_action = "e-soft-creator";
	   	if(isset($_GET['page'])) {
	      $get_action = str_replace("-", "_", sanitize_text_field($_GET['page']) );
	    }
	    if(method_exists($this, $get_action)){
	      $this->$get_action();
	    }
	    do_action('pmw_footer');
	  }

	  public function pixel_manager(){
	  	require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/pages/class-pmw-pixels.php');
	  	new PMW_Pixels();
	  }

	  public function pixel_manager_support(){
	  	require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/pages/class-pmw-pixels-support.php');
	  	new PMW_PixelsSupport();
	  }

	  public function pixel_manager_rate_us(){
	  	require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/pages/class-pmw-pixels-rate-us.php');
	  	new PMW_PixelsRateUS();
	  }

	}
}