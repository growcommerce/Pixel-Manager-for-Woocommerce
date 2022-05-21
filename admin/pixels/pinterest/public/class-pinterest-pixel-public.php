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
if(!class_exists('PMW_PinterestPixelManager')):
	class PMW_PinterestPixelManager extends PMW_PixelManager{
		protected $options;
		protected $pixel_id;
		protected $pixel_email;
		public function __construct( $options ){
			$this->options = $options;
			$this->pixel_id = (isset($options['pinterest_pixel']['pixel_id']))?$options['pinterest_pixel']['pixel_id']:"";
			$this->pixel_email = (isset($options['user']['email_id']))?$options['user']['email_id']:"";
			add_action( 'wp_head', array( $this, 'init_in_wp_head'),10 );
		}

		public function init_in_wp_head(){
			$this->inject_general_data_layer();
		}

		public function inject_general_data_layer(){
			?>
<!-- Pinterest Tag -->
<script>
!function(e){if(!window.pintrk){window.pintrk = function () {
window.pintrk.queue.push(Array.prototype.slice.call(arguments))};var
  n=window.pintrk;n.queue=[],n.version="3.0";var
  t=document.createElement("script");t.async=!0,t.src=e;var
  r=document.getElementsByTagName("script")[0];
  r.parentNode.insertBefore(t,r)}}("https://s.pinimg.com/ct/core.js");
pintrk('load', <?php echo esc_js($this->pixel_id); ?>, {em: '<?php echo esc_js($this->pixel_email); ?>'});
pintrk('page');
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt=""
  src="https://ct.pinterest.com/v3/?event=init&tid=<?php echo esc_js($this->pixel_id); ?>&pd[em]=<<?php echo esc_js($this->pixel_email); ?>>&noscript=1" />
</noscript>
<!-- end Pinterest Tag -->
			<?php
			//
		}
	}
endif;