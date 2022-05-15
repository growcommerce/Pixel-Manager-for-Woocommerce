<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://growcommerce.io/
 * @since             1.0.0
 * @package           Pixel_Manager_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Pixel Manager for WooCommerce
 * Plugin URI:        
 * Description:       Pixel Manager for Woocommerce, plugin track the eCommerce store evets like Page View, Customer Search, Add To Cart (product listing and product detail page), Checkout, Order Conversion (Thank you page) and Other custom events.
 * Version:           1.0.0
 * Author:            GrowCommerce
 * Author URI:        https://growcommerce.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pixel-manager-for-woocommerce
 * Domain Path:       /languages
 * WC requires at least: 3.4.0
 * WC tested up to: 6.5.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_VERSION', '1.0.1' );
define( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_PREFIX', 'pixel-manager-for-woocommerce' );
if( ! defined( 'PIXEL_MANAGER_FOR_WOOCOMMERCE' ) ){
  define( 'PIXEL_MANAGER_FOR_WOOCOMMERCE', basename(__DIR__) );
}
if( ! defined( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR' ) ){
  define( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR', plugin_dir_path( __FILE__ ) );
}
if( ! defined( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_URL' ) ) {
  define( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_URL', plugins_url() . '/'.PIXEL_MANAGER_FOR_WOOCOMMERCE );
}
if( ! defined( 'PMW_API_URL' ) ){
  define( 'PMW_API_URL', 'https://api.growcommerce.io/api/' );
}
if( ! defined( 'PMW_PRODUCT_ID' ) ){
  define( 'PMW_PRODUCT_ID', '1' );
}
if ( ! class_exists( 'PMW_AdminHelper' ) ) {
  require_once( PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/helper/class-pmw-admin-helper.php');
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pixel-manager-for-woocommerce-activator.php
 */
function activate_pixel_manager_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pixel-manager-for-woocommerce-activator.php';
	Pixel_Manager_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pixel-manager-for-woocommerce-deactivator.php
 */
function deactivate_pixel_manager_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pixel-manager-for-woocommerce-deactivator.php';
	Pixel_Manager_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pixel_manager_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_pixel_manager_for_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pixel-manager-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pixel_manager_for_woocommerce() {
	$plugin = new Pixel_Manager_For_Woocommerce();
	$plugin->run();
}
run_pixel_manager_for_woocommerce();