var pmw_helper = {
	pmw_loader:function(isShow){
		if (isShow){
	    jQuery("#pmw_loader").addClass("is_loading");
	  }else{
	  	jQuery("#pmw_loader").removeClass("is_loading");
	  }
	},
	addEventBindings:function(){
	},
	add_message:function(type, title, msg, is_close = true){
	  let pmw_popup_box = document.getElementById('pmw_form_message');
	  pmw_popup_box.classList.add("active");
	  title = (title)?"<h4>"+title+"</h4>":"";
	  if(type == "success"){
	    document.getElementById('pmw_form_message').innerHTML ="<div class='toaster-box tvc-alert-success'>"+ title +"<p>"+ msg +"</p></div>";
	  }else if(type == "error"){
	    document.getElementById('pmw_form_message').innerHTML ="<div class='toaster-box tvc-alert-error'>"+ title +"<p>"+ msg+"</p></div>";
	  }else if(type == "warning"){
	    document.getElementById('pmw_form_message').innerHTML ="<div class='toaster-box tvc-alert-warning'>"+ title +"<p>"+ msg+"</p></div>";
	  }
	  if(is_close){
	    pmw_time_out = setTimeout(function(){  
	    	pmw_popup_box.classList.remove("active");        
	    }, 4000);
	  } 
	},
	pmw_ajax_call:function(f_data) {
		var this_var = this;
		jQuery.ajax({
      type: "POST",
      dataType: "json",
      url: pmw_ajax_url,
      data: f_data,
      beforeSend: function(){
        this_var.pmw_loader(true);
      },
      success: function (response) {
      	console.log(response);
      	if( f_data.action == "pmw_check_privecy_policy" && !response.hasOwnProperty('message')){
      		if (response.error === true ){
      			pmw_helper.show_privacy_popup();
      		}else{
      			var data = {
			        action : 'pmw_pixels_save',
			       	data : jQuery("#pmw-pixels").serialize()
			      };
			      pmw_helper.pmw_ajax_call(data);			      
      		}
      		return false;
      	}
      	if( f_data.action == "pmw_pixels_save" || f_data.action == "pmw_check_privecy_policy"){ //remove disabled save button
      		document.getElementById("pixels_save").disabled = false;
      	}
      	if (response.error === false && response.hasOwnProperty('message') && response.message != "" ) {          
	        this_var.add_message("success", "Thank you!",  response.message);
	      }else if(response.hasOwnProperty('message') && response.message != ""){
	      	this_var.add_message("error", "Error", response.message);
	      }else{
	      	this_var.add_message("error", "Error", "while save data.");
	      }
      	this_var.pmw_loader(false);
      }
    });
	},
	show_privacy_popup:function(){
		let body = document.getElementsByClassName('toplevel_page_pixel-manager');
		let popup = document.getElementById('pmw_privacy_popup');
		body[0].classList.add("modal-open");
		popup.classList.add("show");
		body[0].insertAdjacentHTML('afterend', '<div id="modal-backdrop" class="modal-backdrop fade show"></div>');
	},
	close_privacy_popup:function(){
		let body = document.getElementsByClassName('toplevel_page_pixel-manager');
		let popup = document.getElementById('pmw_privacy_popup');
		body[0].classList.remove("modal-open");
		popup.classList.remove("show");
		let modal_backdrop = document.getElementById("modal-backdrop");
		if(modal_backdrop != null){
			modal_backdrop.remove();
		}
		this.pmw_loader(false);
		//remove disabled save button
		document.getElementById("pixels_save").disabled = false;
	}
};

(function( $ ){	
	jQuery(document).ready(function(){
		/* pixels page */
		jQuery("#pmw-pixels").on("submit", function( event ){
			event.preventDefault();
			document.getElementById("pixels_save").disabled = true;
			var data = {
        action : 'pmw_check_privecy_policy',
       	data : jQuery(this).serialize()
      };
      pmw_helper.pmw_ajax_call(data);
		});
		jQuery("#pmw_accept_privecy_policy").on("click", function () {
      event.preventDefault();
      pmw_helper.close_privacy_popup();
      if(document.getElementById("ch_is_theme_plugin_list").checked){
      	document.getElementById("is_theme_plugin_list").value = 1;
      }else {
		    document.getElementById("is_theme_plugin_list").value = 0;
		  }
			var data = {
        action : 'pmw_pixels_save',
       	data : jQuery("#pmw-pixels").serialize()
      };
      pmw_helper.pmw_ajax_call(data);
    });
    jQuery("#close").on("click", function () {
    	pmw_helper.close_privacy_popup();
    });
    jQuery(".toggle_title-text").on("click", function () {
      jQuery(this).toggleClass("active");
      jQuery(this).next('.pmw_slide-down-area').slideToggle();
    });
    /* support page */
		jQuery("#pmw-pixels-support").on("submit", function( event ){
			event.preventDefault();
			var data = {
        action : 'pmw_pixels_support_save',
       	data : jQuery(this).serialize()
      };
      pmw_helper.pmw_ajax_call(data);
		});

	});
})( jQuery );