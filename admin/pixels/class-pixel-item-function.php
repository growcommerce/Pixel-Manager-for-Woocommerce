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
if(!class_exists('PMW_PixelItemFunction')):
	class PMW_PixelItemFunction {
		protected $PixelHelper;
		public function __construct(){
			$this->req_int();
			$this->PixelHelper = new PMW_PixelHelper();
		}
		public function req_int(){
			if (!class_exists('PMW_PixelHelper')) {
			  require_once('class-pixel-helper.php');
			}
		}
		/**
		 * Product details data
		 **/
		public function get_product_details_for_datalayer($product){
			global $woocommerce_wpml;
			$dyn_r_ids = $this->get_dyn_r_ids($product);
			$price = "";
			if ($this->PixelHelper->is_wpml_woocommerce_multi_currency_active()) {
				$price = $woocommerce_wpml->multi_currency->prices->get_product_price_in_currency($product->get_id(), get_woocommerce_currency());
			} else {
				$price = $product->get_price();
			}
			$product_details = [
				'id'          => (string)$product->get_id(),
				'sku'         => (string)$product->get_sku(),
				'name'        => (string)$product->get_name(),
				'price'       => (float)$price,
				'currency'		=> get_woocommerce_currency(),
				'brand'       => $this->get_brand_name($product->get_id()),
				'category'    => $this->get_product_category($product->get_id()),
				'quantity'    => 1,
				'dyn_r_ids'   => $dyn_r_ids,
				'isVariable'  => $product->get_type() == 'variable',
				'isVariation' => false
			];

			if ($product->get_type() == 'variation') {
				$parent_product = wc_get_product($product->get_parent_id());
				if ($parent_product) {
					$product_details['name']               = $parent_product->get_name();
					$product_details['parentId_dyn_r_ids'] = $this->get_dyn_r_ids($parent_product);
					$product_details['parentId']           = $parent_product->get_id();
				} else {
					wc_get_logger()->debug('Variation ' . esc_attr($product->get_id()). ' doesn\'t link to a valid parent product.', ['source' => 'pixel_manager_for_woocommerce']);
				}
				$product_details['isVariation'] = true;
				$product_details['variant']     = $this->get_formatted_variant_text($product);
			}
			return $product_details;
		}

		protected function get_dyn_r_ids($product){
			$dyn_r_ids = [
				'post_id' => (string)$product->get_id(),
				'sku'     => (string)$product->get_sku() ? $product->get_sku() : $product->get_id(),
				'gpf'     => 'woocommerce_gpf_' . (string)$product->get_id()
			];
			// if you want to add a custom dyn_r_id for each product
			$dyn_r_ids = apply_filters('pixel_manager_for_woocommerce_product_ids', $dyn_r_ids, $product);
			return $dyn_r_ids;
		}
		/**
		 * Product variant text
		 **/
		protected function get_formatted_variant_text($product){
			$variant_text_array = [];
			$attributes = $product->get_attributes();
			if ($attributes) {
				foreach ($attributes as $key => $value) {
					$key_name             = str_replace('pa_', '', $key);
					$variant_text_array[] = ucfirst($key_name) . ': ' . strtolower($value);
				}
			}
			return implode(' | ', $variant_text_array);
		}
		/**
		 * Product brand
		 **/
		public function get_brand_name($product_id){
				$brand_taxonomy = 'pa_brand';

				if ($this->PixelHelper->is_yith_wc_brands_active()) {
						$brand_taxonomy = 'yith_product_brand';
				} else if ($this->PixelHelper->is_woocommerce_brands_active()) {
						$brand_taxonomy = 'product_brand';
				}

				$brand_taxonomy = apply_filters('pixel_manager_for_woocommerce_custom_brand_taxonomy', $brand_taxonomy);

				return $this->get_brand_by_taxonomy($product_id, $brand_taxonomy) ?:
						$this->get_brand_by_taxonomy($product_id, 'pa_' . $brand_taxonomy) ?:
								'';
		}
		/**
		 * Product brand
		 **/
		public function get_brand_by_taxonomy($product_id, $taxonomy){
			if (taxonomy_exists($taxonomy)) {
					$brand_names = wp_get_post_terms($product_id, $taxonomy, ['fields' => 'names']);
					return reset($brand_names);
			} else {
					return '';
			}
		}
		protected function log_problematic_product_id($product_id = 0){
			wc_get_logger()->debug(
					'WooCommerce detects the page ID ' . esc_attr($product_id) . ' as product, but when invoked by wc_get_product( ' . esc_attr($product_id) . ' ) it returns no product object',
					['source' => 'pixel_manager_for_woocommerce']
			);
		}
		/**
		 * get an array with product categories
		 **/
		public function get_product_category($product_id){
			$prod_cats        = get_the_terms($product_id, 'product_cat');
			$prod_cats_output = [];
			if (!empty($prod_cats)) {
				foreach ((array)$prod_cats as $key) {
						array_push($prod_cats_output, $key->name);
				}
				$prod_cats_output = apply_filters_deprecated('wgact_filter', [$prod_cats_output], '1.10.2', '', 'This filter has been deprecated without replacement.');
			}
			return $prod_cats_output;
		}

		public function get_variation_id_or_product_id($item, $variations_output = true){
      if (isset($item['variation_id']) && $item['variation_id'] <> 0 && $variations_output == true) {
        return $item['variation_id'];
      } else {
        return $item['product_id'];
      }
    }
    /**
		 * get woocommerce currency
		 **/
    public function get_woo_currency(){
    	return get_woocommerce_currency();
    }

    public function get_order_from_order_received_page(){
    	if ($this->get_order_from_query_vars()) {
        return $this->get_order_from_query_vars();
      } else if ($this->get_order_with_url_order_key()) {
        return $this->get_order_with_url_order_key();
      } else {
        return false;
      }
    }
    protected function get_order_from_query_vars(){
      global $wp;
      $order_id = absint($wp->query_vars['order-received']);
      if ($order_id && $order_id != 0) {
        return new WC_Order($order_id);
      } else {
        /*wc_get_logger()->debug(
            'WooCommerce error the order ID from $wp->query_vars[\'order-received\']',
            ['source' => 'pixel_manager_for_woocommerce']
        );
        wc_get_logger()->debug(
            print_r($wp->query_vars, true),
            ['source' => 'pixel_manager_for_woocommerce']
        );*/
        return false;
      }
    }

    protected function get_order_with_url_order_key(){
      if (isset($_GET['key'])) {
        $order_key = sanitize_text_field($_GET['key']);
        return new WC_Order(wc_get_order_id_by_order_key($order_key));
      } else {
        wc_get_logger()->debug(
            'WooCommerce couldn\'t retrieve the order ID from order key in the URL',
            ['source' => 'pixel_manager_for_woocommerce']
        );
        return false;
      }
    }

    protected function has_order_bought( $order, $value = 0 ){
      global  $wpdb ;
      // Based on user ID (registered users)      
      if ( is_numeric( $value ) ) {
          $meta_key = '_customer_user';
          $meta_value = ( $value == 0 ? (int) get_current_user_id() : (int) $value );
      } else {
          $meta_key = '_billing_email';
          $meta_value = sanitize_email( $value );
      }
      
      $paid_order_statuses = array_map( 'esc_sql', wc_get_is_paid_statuses() );
      $count = $wpdb->get_var( $wpdb->prepare( "\n SELECT COUNT(p.ID) FROM {$wpdb->prefix}posts AS p\n        INNER JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id\n        WHERE p.post_status IN ( 'wc-" . implode( "','wc-", $paid_order_statuses ) . "' )\n        AND p.post_type LIKE 'shop_order'\n        AND p.ID <> {$order->get_id()}\n        AND pm.meta_key = '%s'\n        AND pm.meta_value = %s\n        LIMIT 1\n    ", $meta_key, $meta_value ) );
      // Return a boolean value based on orders count
      return ( $count > 0 ? true : false );
    }

	}
endif;