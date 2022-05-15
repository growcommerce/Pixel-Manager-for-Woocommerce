<?php
/**
 * @since      1.0.0
 * Description: Footer
 */
if ( ! class_exists( 'PMW_Footer' ) ) {
	class PMW_Footer {	
		public function __construct( ){
			add_action('pmw_footer',array($this, 'before_end_footer'));
		}	
		public function before_end_footer(){ 
			?>
							</div>
						</div>
					</section>
				</main>
				<div id="pmw_form_message" class="toaster-bottom"></div>
				<div id="pmw_loader"></div>
			</div>
			<?php
		}
	}
}
new PMW_Footer();