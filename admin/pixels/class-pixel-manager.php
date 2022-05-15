<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    
 * @package    PMW_Pixel
 * PixelManagerDataLayer, PixelManagerEvent, PixelManagerOptions
 */
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if(!class_exists('PMW_PixelManager')):
	class PMW_PixelManager extends PMW_Pixel {
    protected $options;
    protected $PixelItemFunction;
    public $PixelManagerDataLayer = array();
    protected $version;
		public function __construct( $options ){
      $this->version = PIXEL_MANAGER_FOR_WOOCOMMERCE_VERSION;
      $this->options = $options;
      
      $this->req_int();
      $this->PixelItemFunction = new PMW_PixelItemFunction();
			add_action( 'wp_head', array( $this, 'init_in_wp_head') , 120);
			if ( did_action( 'wp_body_open' ) ) {				
			  add_action( 'wp_body_open', array($this,'init_in_wp_body') );
			}
      add_action( 'woocommerce_after_shop_loop_item',[ $this, 'PMW_injectproduct_id_tag' ], 10, 1 );
      add_filter( 'woocommerce_blocks_product_grid_item_html', [ $this, 'PMW_injectproduct_id_tag' ], 10,  3  );

      add_action("wp_footer", array($this, "PMW_create_products_data_object"));
      add_action( 'wp_enqueue_scripts', array($this,'enqueue_scripts'));
        
		}
		public function req_int(){
      if (!class_exists('PMW_PixelItemFunction')) {
        require_once('class-pixel-item-function.php');
      }
    }
    public function enqueue_scripts() {
      wp_enqueue_script("pmw-pixel-manager.js", esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL . '/admin/pixels/js/pixel-manager.js'), array('jquery'), $this->version, false);
    }

		public function init_in_wp_head(){      
  			$this->inject_gtm_data_layer();
        $this->inject_option_data_layer();
  			$this->PMW_woocommerce_inject_data_layer_product();
        $this->PMW_PixelManagerPageType();      
      /*$this->PMW_home_page();
      $this->PMW_cart_page();
      $this->PMW_checkout_page();*/
		}

		public function init_in_wp_body(){
		?><!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-53QW9VJ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
		<?php		
		}

		public function inject_gtm_data_layer(){
			?><!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-53QW9VJ');</script>
<!-- End Google Tag Manager --><?php if (!did_action( 'wp_body_open' ) ) {?><!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-53QW9VJ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
			<?php 
			}
		}

    public function inject_option_data_layer(){
      ?>
    <script type="text/javascript" data-cfasync="false">
      window.PixelManagerOptions= window.PixelManagerOptions || [];
      window.PixelManagerOptions.push({options:<?php echo json_encode($this->options); ?>});
    </script>
      <?php
    }
    /**
     * list products
     **/
    public function PMW_injectproduct_id_tag(){
       if(!is_array($this->PixelManagerDataLayer) ){
          $this->PixelManagerDataLayer = array();
        }
        global $product;
        $data = $this->PixelItemFunction->get_product_details_for_datalayer($product);
        $this->PixelManagerDataLayer['product_list'][get_the_id()] = $data;
    }
    /**
     * print PixelManagerDataLayer
     **/
    public function PMW_create_products_data_object(){
      $this->PixelManagerDataLayer['currency'] = get_woocommerce_currency();
      ?>
      <script type="text/javascript" data-cfasync="false">
        window.PixelManagerDataLayer = window.PixelManagerDataLayer || [];
        window.PixelManagerDataLayer.push({data:<?php echo json_encode($this->PixelManagerDataLayer); ?>});
        new PMW_PixelManagerJS();
      </script>
      <?php
    }

		public function PMW_woocommerce_inject_data_layer_product(){
			if ( is_product() ) {
        //global $product;
        $product = wc_get_product( get_the_id() );
        $this->PixelManagerDataLayer["PageType"] = "ProductDetailView";
        $this->PixelManagerDataLayer["PoductType"] = $product->get_type();      
        if ( is_object( $product ) ) {
          $this->get_product_data_layer_script( $product, false, true );
        } else {
          wc_get_logger()->debug( 'woocommerce_inject_product_data_on_product_page provided no product on a product page: .' . get_the_id(), [
              'source' => 'pixel_manager_for_woocommerce',
          ] );
        }        
        if ( $product->is_type( 'grouped' ) ) {
          foreach ( $product->get_children() as $product_id ) {
            $product = wc_get_product( $product_id );                
            if ( is_object( $product ) ) {
              $this->get_product_data_layer_script( $product, false, true );
            } else {
              $this->log_problematic_product_id( $product_id );
            }
          
          }
        }
        if ( $product->is_type( 'variable' ) ) {
          foreach ( $product->get_available_variations() as $key => $variation ) {
            $variable_product = wc_get_product( $variation['variation_id'] );
            if ( is_object( $variable_product ) ) {
              $this->get_product_data_layer_script( $variable_product, false, true );
            } else {
              $this->log_problematic_product_id( $variation['variation_id'] );
            }            
          }
        }
      }elseif ( is_search() ) {
        $search_data = array(
          "keyword"   => (isset($_GET["s"]))?sanitize_text_field($_GET["s"]):"",
          "post_type" => (isset($_GET["post_type"]))?sanitize_text_field($_GET["post_type"]):""
        );
        $this->PixelManagerDataLayer['search'] = $search_data;
      }elseif ( (is_cart() || is_checkout()) && !is_order_received_page()  ) {
        //global  $wp_query, $woocommerce ;
        $cart_obj = WC()->cart->get_cart();
        foreach ( $cart_obj as $cart_i_key => $values ) {
          $product_id = (isset($values['product_id']))?$values['product_id']:"";
          if((isset($values['variation_id'])) && $values['variation_id']){
            $product_id = $values['variation_id'];
          }
          $product = wc_get_product( $product_id );

          $data = $this->PixelItemFunction->get_product_details_for_datalayer($product);
          $data['quantity'] = (int)$values['quantity'];
          $this->PixelManagerDataLayer['checkout']['cart_product_list'][$product_id] = $data;
        }
        $cart_data = array(
          "cart_total"  => (float)WC()->cart->get_cart_contents_total(),
          "currency"    => $this->PixelItemFunction->get_woo_currency()
        );
        $this->PixelManagerDataLayer['checkout'] = array_merge( $this->PixelManagerDataLayer['checkout'], $cart_data );

      }elseif ( is_order_received_page() ) {
        if( $this->PixelItemFunction->get_order_from_order_received_page() ) {
          $order = $this->PixelItemFunction->get_order_from_order_received_page();        
          $order_items = $order->get_items();
          if( is_user_logged_in() ) {
            $user = get_current_user_id();
          }else{
            $user = $order->get_billing_email();
          }
          if(!empty($order_items)){
            foreach((array)$order_items as $order_item){
              $product_id = $this->PixelItemFunction->get_variation_id_or_product_id($order_item->get_data(), true);
              $product = wc_get_product( $product_id );
              $data = $this->PixelItemFunction->get_product_details_for_datalayer($product);
              
              $data['quantity'] = (int)$order_item['quantity'];
              $this->PixelManagerDataLayer['checkout']['cart_product_list'][$product_id] = $data;
            }
          }
          $order_data = array(
            "id"              => $order->get_order_number(),
            "total"           => $order->get_total(),
            "total_discount"  => $order->get_total_discount(),
            "tax"             => (string) $order->get_total_tax(),
            "shipping"        => (string) $order->get_total_shipping(),
            "coupon"          => $order->get_used_coupons(),
            "currency"        => $this->PixelItemFunction->get_woo_currency(),
            "payment_method"  => $order->get_payment_method()
          );
          $this->PixelManagerDataLayer['checkout'] = array_merge( $this->PixelManagerDataLayer['checkout'], $order_data );
        }
      }
		}

    public function get_product_data_layer_script($product, $set_position = true, $meta_tag = false){
      if (!is_object($product)) {
        wc_get_logger()->debug('get_product_data_layer_script received an invalid product', ['source' => 'PixelManagerWoocommerce']);
        return '';
      }
      $data = $this->PixelItemFunction->get_product_details_for_datalayer($product);
      $this->PixelManagerDataLayer['product_single'][$product->get_id()] = $data;
    }

    /*public function PMW_home_page(){
      if(is_home() || is_front_page()){        
      }
    }*/

    public function PMW_PixelManagerPageType(){
      $PageType = "";
      if ( is_product_category() ) {
        $PageType = __('CategoryPage', 'pixel-manager-for-woocommerce');
      } elseif ( is_product_tag() ) {
        $PageType = __('ProductTagPage', 'pixel-manager-for-woocommerce');
      } elseif ( is_search() ) {
        $PageType = __('SearchPage', 'pixel-manager-for-woocommerce');
      } elseif ( is_shop() ) {
        $PageType = __('ShopPage', 'pixel-manager-for-woocommerce');
      } elseif ( is_product() ) {
        $PageType = __('ProductPage', 'pixel-manager-for-woocommerce');
      } elseif ( is_cart() ) {
        $PageType = __('CartPage', 'pixel-manager-for-woocommerce');
      } elseif ( is_front_page() ) {
        $PageType = __('HomePage', 'pixel-manager-for-woocommerce');
      } elseif ( is_checkout() && !is_order_received_page() ) {
        $PageType = __('CheckoutPage', 'pixel-manager-for-woocommerce');
      } else if ( is_order_received_page() ) {
        $PageType = __('OrderReceivedPage', 'pixel-manager-for-woocommerce');
      }else{
        $PageType = __('OtherPage', 'pixel-manager-for-woocommerce');        
      }
      ?>
      <script type="text/javascript" data-cfasync="false">
        window.PixelManagerEvent= "<?php echo esc_js($PageType); ?>";
      </script>
      <?php
    }
    /*public function PMW_cart_page(){
      if(is_cart()){ 
      }
    }
    public function PMW_checkout_page(){
      if(is_checkout()){     
      }
    }*/
	}
endif;