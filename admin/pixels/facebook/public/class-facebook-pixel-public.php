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
if(!class_exists('PMW_FBPixelManager')):
	class PMW_FBPixelManager extends PMW_PixelManager{
		protected $options;
		protected $pixel_id;
		public function __construct( $options ){
			$this->options = $options;
			$this->pixel_id = (isset($options['facebook_pixel']['pixel_id']))?$options['facebook_pixel']['pixel_id']:"";
			add_action( 'wp_head', array( $this, 'init_in_wp_head'),10 );
		}

		public function init_in_wp_head(){
			$this->inject_general_data_layer();
		}

		public function inject_general_data_layer(){
			?>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '<?php echo esc_js($this->pixel_id); ?>');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=<?php echo esc_js($this->pixel_id); ?>&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
			<?php
		}
	}
endif;