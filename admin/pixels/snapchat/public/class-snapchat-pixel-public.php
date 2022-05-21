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
if(!class_exists('PMW_SnapchatPixelManager')):
	class PMW_SnapchatPixelManager extends PMW_PixelManager{
		protected $options;
		protected $pixel_id;
		protected $user_email;
		protected $user_ip;
		public function __construct( $options ){
			$this->options = $options;
			$this->pixel_id = (isset($options['snapchat_pixel']['pixel_id']))?$options['snapchat_pixel']['pixel_id']:"";
			$this->user_email = (isset($options['user']['email_id']))?$options['user']['email_id']:"";
			$this->user_ip = (isset($options['user_ip']))?$options['user_ip']:"";
			add_action( 'wp_head', array( $this, 'init_in_wp_head'),10 );
		}

		public function init_in_wp_head(){
			$this->inject_general_data_layer();
		}

		public function inject_general_data_layer(){
			?>
<!-- Snap Pixel Code -->
<script type='text/javascript'>
(function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
{a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
r.src=n;var u=t.getElementsByTagName(s)[0];
u.parentNode.insertBefore(r,u);})(window,document,
'https://sc-static.net/scevent.min.js');
snaptr('init', '<?php echo esc_js($this->pixel_id); ?>', {
'user_email': '<?php echo esc_js($this->user_email); ?>',
'ip_address':'<?php echo esc_js($this->user_ip); ?>'
});
snaptr('track', 'PAGE_VIEW');
</script>
<!-- End Snap Pixel Code -->
			<?php
		}
	}
endif;