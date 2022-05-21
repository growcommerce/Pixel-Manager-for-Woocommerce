class PMW_PixelManagerJS {
  constructor(options = {}){    
    this.options = {
      esc_tracking: true
    };
    if(options){
      Object.assign(this.options, options);
    } 
    //console.log(this.options);
    this.addEventBindings();  
  }
  addEventBindings(){
    var add_to_cart_btn = document.querySelectorAll(".add_to_cart_button:not(.product_type_variable), .ajax_add_to_cart, .single_add_to_cart_button");    
    if(add_to_cart_btn.length > 0){
      for (let i = 0; i < add_to_cart_btn.length; i++) {
        add_to_cart_btn[i].addEventListener("click", () => Datahelper.AddToCartClick(event));
      }
    }
  }
}