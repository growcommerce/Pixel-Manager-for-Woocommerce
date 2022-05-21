<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    
 * @package    PMW_Helper
 * 
 */
if(!defined('ABSPATH')){
  exit; // Exit if accessed directly
}
if(!class_exists('PMW_SettingHelper')):
  class PMW_SettingHelper {
    public function add_form_fields(array $fields, array $form){
      if(!empty($fields)){
        $name = $this->get_array_val($form, "name");
        $id = $this->get_array_val($form, "id");
        $method = $this->get_array_val($form, "method");
        $class = $this->get_array_val($form, "class");
        ?>
        <div class="pmw_form-wrapper">
        <form name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" method="<?php echo esc_attr($method); ?>" class="<?php echo esc_attr($class); ?>">
        <?php
        foreach($fields as $key => $pixel_fields){
          if(empty($pixel_fields)){
            continue;
          }
          ?>
          <div class="pmw_form-row">
            <div class="pmw_form-group">
            <?php
            foreach($pixel_fields as $key => $value){
              if(isset($value['type'])){
                if($value['type'] == "section") {
                  $this->add_section($value);
                }else if($value['type'] == "text") {
                  $this->add_text_fiels($value);
                }else if($value['type'] == "textarea") {
                  $this->add_textarea_fiels($value);
                }else if($value['type'] == "switch") {
                  $this->add_switch_fiels($value);
                }else if($value['type'] == "text_with_switch") {
                  $this->add_text_fiels_with_switch($value);
                }else if($value['type'] == "switch_with_text") {
                  $this->add_switch_fiels_with_text($value);
                }else if($value['type'] == "button") {
                  $this->add_button($value);
                }else if($value['type'] == "hidden") {
                  $this->add_hidden_fiels($value);
                }
              }
            } ?>
            </div>
          </div>
        <?php
        }
        ?>
        <input type="hidden" name="pmw_ajax_nonce" id="pmw_ajax_nonce" value="<?php echo wp_create_nonce( 'pmw_ajax_nonce' ); ?>">
        </form>
        </div>
        <?php
      }
    }
    public function get_array_val(array $vals, string $key, string $default = null){
      if(isset($vals[$key]) ){ //&& $vals[$key]
        return $vals[$key];
      }else if ($default != "") {
        return $default;
      }
    }
    public function add_section(array $args){
      $class = $this->get_array_val($args, "class");
      $label = $this->get_array_val($args, "label");
      ?>
      <div class="section-row">
        <h3 class="pmw-section <?php echo esc_attr($class); ?>"><?php echo esc_attr($label); ?></h3>
      </div>
      <?php
    }
    public function add_text_fiels(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $placeholder = $this->get_array_val($args, "placeholder");
        $class = $this->get_array_val($args, "class");
        $label = $this->get_array_val($args, "label");
        $value = $this->get_array_val($args, "value");
        $note = $this->get_array_val($args, "note");
        $tooltip = $this->get_array_val($args, "tooltip");
        ?>
        <label class="pmw_row-title"><?php echo esc_attr($label); ?></label>
        <div class="form-input-inline">
          <div class="pmw_input-col-lg">
            <input type="text" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($value); ?>" class="pmw_form-control <?php echo esc_attr($class); ?>">
            <span class="form-input-highlite-text"><?php echo esc_attr($note); ?></span>
          </div>
          <div class="im_input-col-sm offspace-top-1">
            <div class="alert-wrapper">
            <?php if( !empty($tooltip) && isset($tooltip['title']) ){
              $title = $this->get_array_val($tooltip, "title");
              $link_title = $this->get_array_val($tooltip, "link_title", "Installation Manual");
              $link = $this->get_array_val($tooltip, "link");
              ?>
              <button class="alert-btn"><i class="alert-icon"></i></button>
              <div class="alert-text"><p><?php echo esc_attr($title); ?></p>
                <?php if($link){?>
                  <a target="_blank" href="<?php echo esc_url_raw($link); ?>"><?php echo esc_attr($link_title); ?></a>
                <?php } ?>
              </div>            
            <?php }?>
            </div>
          </div>
        </div>         
        <?php
      }
    }
    public function add_hidden_fiels(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $value = $this->get_array_val($args, "value");        
        ?>
        <input type="hidden" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>">        
        <?php
      }
    }
    public function add_textarea_fiels(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $placeholder = $this->get_array_val($args, "placeholder");
        $class = $this->get_array_val($args, "class");
        $label = $this->get_array_val($args, "label");
        $value = $this->get_array_val($args, "value");
        $note = $this->get_array_val($args, "note");
        $tooltip = $this->get_array_val($args, "tooltip");
        ?>
        <label class="pmw_row-title"><?php echo esc_attr($label); ?></label>
        <div class="form-input-inline">
          <div class="pmw_input-col-lg">
            <textarea name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" placeholder="<?php echo esc_attr($placeholder); ?>"  class="pmw_form-control <?php echo esc_attr($class); ?>"><?php echo esc_attr($value); ?></textarea>
            <span class="form-input-highlite-text"><?php echo esc_attr($note); ?></span>
          </div>
          <div class="im_input-col-sm offspace-top-1">
            <div class="alert-wrapper">
            <?php if( !empty($tooltip) && isset($tooltip['title']) ){
              $title = $this->get_array_val($tooltip, "title");
              $link_title = $this->get_array_val($tooltip, "link_title", "Installation Manual");
              $link = $this->get_array_val($tooltip, "link");
              ?>
              <button class="alert-btn"><i class="alert-icon"></i></button>
              <div class="alert-text"><p><?php echo esc_attr($title); ?></p>
                <?php if($link){?>
                  <a target="_blank" href="<?php echo esc_url_raw($link); ?>"><?php echo esc_attr($link_title); ?></a>
                <?php } ?>
              </div>            
            <?php }?>
            </div>
          </div>
        </div>         
        <?php
      }
    }

    public function add_text_fiels_with_switch(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $placeholder = $this->get_array_val($args, "placeholder");
        $class = $this->get_array_val($args, "class");
        $label = $this->get_array_val($args, "label");
        $value = $this->get_array_val($args, "value");
        $note = $this->get_array_val($args, "note");
        ?>
        <label class="pmw_row-title"><?php echo esc_attr($label); ?></label>
        <div class="form-input-inline">
          <div class="pmw_input-col-lg">
            <input type="text" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php echo esc_attr($value); ?>" class="pmw_form-control <?php echo esc_attr($class); ?>">
            <span class="form-input-highlite-text"><?php echo esc_attr($note); ?></span>
          </div>          
        <?php
      }
    }
    public function add_switch_fiels_with_text(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $placeholder = $this->get_array_val($args, "placeholder");
        $class = $this->get_array_val($args, "class");
        /*$label = $this->get_array_val($args, "label"); <label class="row-title"><?php echo esc_attr($label); ?></label>*/
        $value = $this->get_array_val($args, "value");
        $checked = ($value ==1)?"checked":"";
        $tooltip = $this->get_array_val($args, "tooltip");
        ?>
        <div class="pmw_input-col-sm offspace-top-1">
          <div class="alert-wrapper">
          <?php if( !empty($tooltip) && isset($tooltip['title']) ){
            $title = $this->get_array_val($tooltip, "title");
            $link_title = $this->get_array_val($tooltip, "link_title", "Installation Manual");
            $link = $this->get_array_val($tooltip, "link");
            ?>
            <button class="alert-btn"><i class="alert-icon"></i></button>
            <div class="alert-text"><p><?php echo esc_attr($title); ?></p>
              <?php if($link){?>
                <a target="_blank" href="<?php echo esc_url_raw($link); ?>"><?php echo esc_attr($link_title); ?></a>
              <?php } ?>
            </div>          
          <?php }?>
          </div>
          <div class="custom-control custom-switch <?php echo esc_attr($class); ?>">
            <input type="checkbox" <?php echo esc_attr($checked); ?> name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" value="1" class="pmw_custom-control-input pmw_switch">
            <label class="pmw_custom-control-label" for="<?php echo esc_attr($id); ?>"></label>            
          </div>
        </div>
        </div>
        <?php
      }
    }
    public function add_switch_fiels(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $placeholder = $this->get_array_val($args, "placeholder");
        $class = $this->get_array_val($args, "class");
        $value = $this->get_array_val($args, "value");
        $checked = ($value ==1)?"checked":"";
        $tooltip = $this->get_array_val($args, "tooltip"); 
        ?>
        <div class="pmw_input-col-sm offspace-top-1">
          <div class="alert-wrapper">
            <?php if( !empty($tooltip) && isset($tooltip['title']) ){
            $title = $this->get_array_val($tooltip, "title");
            $link_title = $this->get_array_val($tooltip, "link_title", "Installation Manual");
            $link = $this->get_array_val($tooltip, "link");
            ?>
            <button class="alert-btn"><i class="alert-icon"></i></button>
            <div class="alert-text"><p><?php echo esc_attr($title); ?></p>
              <?php if($link){?>
                <a target="_blank" href="<?php echo esc_url_raw($link); ?>"><?php echo esc_attr($link_title); ?></a>
              <?php } ?>
            </div>          
          <?php }?>
          </div>
          <div class="custom-control custom-switch <?php echo esc_attr($class); ?>">
            <input type="checkbox" <?php echo esc_attr($checked); ?> name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" value="1" class="pmw_custom-control-input pmw_switch">
            <label class="pmw_custom-control-label" for="<?php echo esc_attr($id); ?>"></label>            
          </div>
        </div>
        <?php
      }
    } 

    public function add_button(array $args){
      $name = $this->get_array_val($args, "name");
      if($name != ""){
        $id = $this->get_array_val($args, "id");
        $placeholder = $this->get_array_val($args, "placeholder");
        $class = $this->get_array_val($args, "class");
        $label = $this->get_array_val($args, "label", "Save");
        ?>
        <div class="action_button">
          <button name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($id); ?>" class="pmw_btn pmw_btn-fill <?php echo esc_attr($class); ?>"><?php echo esc_attr($label); ?></button>
        </div>
        <?php
      }
    }  
  }
endif;