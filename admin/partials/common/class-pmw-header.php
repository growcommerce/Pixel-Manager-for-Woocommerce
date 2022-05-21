<?php
/**
 * @since      1.0.0
 * Description: Header
 */
if ( ! class_exists( 'PMW_Header' ) ) {
	class PMW_Header extends PMW_AdminHelper{
		public function __construct( ){
			$this->site_url = "admin.php?page=";		
			add_action('pmw_header',array($this, 'before_start_header'));
			add_action('pmw_header',array($this, 'header_notices'));
			add_action('pmw_header',array($this, 'page_header'));
			add_action('pmw_header',array($this, 'header_menu'));
		}	
		
		/**
     * before start header section
     *
     * @since    1.0.0
     */
		public function before_start_header(){
			?>
			<div class="pmw_page">
			<?php
		}
		/**
     * header notices section
     *
     * @since    1.0.0
     */
		public function header_notices(){
			?>
			<div class="top_bar">
        <div class="pmw_container">
          <p><?php echo esc_attr__('Thank you for using pixel manager...!!  Upgrade to pro', 'pixel-manager-for-woocommerce'); ?></p>
        </div>
      </div>
			<?php
		}
		/**
     * header section
     *
     * @since    1.0.0
     */
		public function page_header(){
			?>
      <main>
	      <section class="hero-section">
					<div class="pmw_container">
					  <div class="pmw_row align-items-center pmw_sm-align-bottom">
					    <div class="pmw_col-7 pmw_col-sm-6">
								<div class="hero-caption">
								  <h1><?php echo esc_attr__('Lorem Ipsum Dolar Simit amet', 'pixel-manager-for-woocommerce'); ?></h1>
								  <button class="pmw_btn pmw_btn-light-default"><?php echo esc_attr__('Learn More', 'pixel-manager-for-woocommerce'); ?></button>
								</div>
					    </div>
					    <div class="pmw_col-5 pmw_col-sm-6">
								<div class="hero_image-holder">
								  <img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/banner.png"); ?>" alt="Image">
								</div>
					    </div>
					  </div>
					</div>
					</section>
					<section class="pmw_section-tabbing">
	        	<div class="pmw_container">
	            <div class="pmw_section-tab-box">
			<?php			
		}

		/* add active tab class */
	  protected function is_active_menu($page=""){
      if($page!="" && isset($_GET['page']) && sanitize_text_field($_GET['page']) == $page){
        return "active";
      }
      return;
	  }
	  /**
     * header section
     *
     * @since    1.0.0
     */
	  public function menu_list(){
	  	//slug => arra();
	  	$menu_list = array(
	  		'pixel-manager' => array(
	  			'title'=>__('Pixels', 'pixel-manager-for-woocommerce'),
	  			'icon'=>'',
	  			'css-icon'=>'pmw_icon-setting',
	  			'acitve_icon'=>''
	  		),'pixel-manager-support'=>array(
	  			'title'=>__('Support', 'pixel-manager-for-woocommerce'),
	  			'icon'=>'im_icon im_icon-support',
	  			'css-icon'=>'pmw_icon-support',
	  			'acitve_icon'=>''
	  		),'pixel-manager-rate-us'=>array(
	  			'title'=>__('Rate Us', 'pixel-manager-for-woocommerce'),
	  			'css-icon'=>'pmw_icon-rateus',
	  			'icon'=>'',
	  			'acitve_icon'=>''
	  		)
	  	);
	  	return apply_filters('wc_order_menu_list', $menu_list, $menu_list);
	  }
		/**
     * header menu section
     *
     * @since    1.0.0
     */
		public function header_menu(){
			$menu_list = $this->menu_list();
			if(!empty($menu_list)){
				?>				
      	<div class="pmw_d-flex pmw_justify-content-beetween align-items-center">
      		<ul class="pmw_main-tab-list">
					<?php
					foreach ($menu_list as $key => $value) {
						if(isset($value['title']) && $value['title']){
							$is_active = $this->is_active_menu($key);
							$icon = "";
							if(!isset($value['icon']) && !isset($value['acitve_icon'])){
								$icon = PIXEL_MANAGER_FOR_WOOCOMMERCE_URL.'/admin/images/'.$key.'-menu.png';					
								if($is_active == 'active'){
									$icon = PIXEL_MANAGER_FOR_WOOCOMMERCE_URL.'/admin/images/'.$is_active.'-'.$key.'-menu.png';
								}
							}else{
								$icon = (isset($value['icon']))?$value['icon']:((isset($value['acitve_icon']))?$value['acitve_icon']:"");
								if($is_active == 'active' && isset($value['acitve_icon'])){
									$icon =$value['acitve_icon'];
								}
							}
							?>								
							<li class="pmw_main-tab-item">
	              <a href="<?php echo esc_url_raw($this->site_url.$key); ?>" class="pmw_main-tab-link <?php echo esc_attr($is_active); ?>">
	              	<?php if( isset($value['css-icon']) && $value['css-icon'] ){?>
	              		<i class="pmw_icon <?php echo esc_attr($value['css-icon']); ?>"></i>
	              	<?php }else if($icon!=""){?>
	                	<span class="navinfoicon"><img src="<?php echo esc_url_raw($icon); ?>" /></span>
	              	<?php } ?>
	                <span class="navinfonavtext"><?php echo esc_attr($value['title']); ?></span>
	              </a>
		          </li>
							<?php	
						}
					}?>
					</ul>
					<ul class="pmw_link-list sm-none">
            <li class="pmw_link-list-item"><a href="#" class="pmw_link-list-link"><?php echo esc_attr__('Instalation Manual', 'pixel-manager-for-woocommerce'); ?></a></li>
            <li class="pmw_link-list-item"><a href="#" class="pmw_link-list-link"><?php echo esc_attr__('FAQs', 'pixel-manager-for-woocommerce'); ?></a></li>
          </ul>
				</div>
				<?php
			}			
		}

	}
}
new PMW_Header();