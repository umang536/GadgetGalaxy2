function send_message(){
	var name=jQuery("#name").val();
	var email=jQuery("#email").val();
	var mobile=jQuery("#mobile").val();
	var message=jQuery("#message").val();
	
	if(name==""){
		alert('Please enter name');
	}else if(email==""){
		alert('Please enter email');
	}else if(mobile==""){
		alert('Please enter mobile');
	}else if(message==""){
		alert('Please enter message');
	}else{
		jQuery.ajax({
			url:'send_message.php',
			type:'post',
			data:'name='+name+'&email='+email+'&mobile='+mobile+'&message='+message,
			success:function(result){
				showSuccessAlert('Message Sent!');
			}	
		});
	}
}

function user_register(){
	jQuery('.field_error').html('');
	var name=jQuery("#name").val();
	var email=jQuery("#email").val();
	var mobile=jQuery("#mobile").val();
	var password=jQuery("#password").val();
	var is_error='';
	if(name==""){
		jQuery('#name_error').html('Please enter name');
		is_error='yes';
	}if(email==""){
		jQuery('#email_error').html('Please enter email');
		is_error='yes';
	}if(mobile==""){
		jQuery('#mobile_error').html('Please enter mobile');
		is_error='yes';
	}if(password==""){
		jQuery('#password_error').html('Please enter password');
		is_error='yes';
	}
	if(is_error==''){
		jQuery.ajax({
			url:'register_submit.php',
			type:'post',
			data:'name='+name+'&email='+email+'&mobile='+mobile+'&password='+password,
			success:function(result){
				result=result.trim();
				if(result=='email_present'){
					jQuery('#email_error').html('Email id already present');
				}
				if(result=='mobile_present'){
					jQuery('#mobile_error').html('Mobile number already present');
				}
				if(result=='insert'){
					showSuccessAlert('Thank you for registration');
				}
			}	
		});
	}
	
}


function user_login(){
	jQuery('.field_error').html('');
	var email=jQuery("#login_email").val();
	var password=jQuery("#login_password").val();
	var is_error='';
	if(email==""){
		jQuery('#login_email_error').html('Please enter email');
		is_error='yes';
	}if(password==""){
		jQuery('#login_password_error').html('Please enter password');
		is_error='yes';
	}
	if(is_error==''){
		jQuery.ajax({
			url:'login_submit.php',
			type:'post',
			data:'email='+email+'&password='+password,
			success:function(result){
				result=result.trim();
				if(result=='wrong'){
					jQuery('.login_msg p').html('Please enter valid login details');
				}
				if(result=='valid'){
				    showSuccessAlert('Thank you for Login');
					window.location.href=window.location.href;
				}
			}	
		});
	}	
}

function manage_cart_update(pid,type){
	// jQuery('#cid').val(color_id);
	// jQuery('#sid').val(size_id);
	manage_cart(pid,type);
}

function manage_cart(pid, type, is_checkout) {
    var qty = jQuery("#qty").val();
    jQuery.ajax({
        url: 'manage_cart.php',
        type: 'post',
        data: 'pid=' + pid + '&qty=' + qty + '&type=' + type,
        success: function(result) {
            result = result.trim();
            if (type == 'update' || type == 'remove') {
                showSuccessAlert('Cart Updated Successfully!');
                window.location.href = window.location.href;
            }
            if (type == 'add')
            {
                showSuccessAlert('Added to Cart');
            }
            if (result == 'not_available') {
                alert('Qty not available');
            } else {
                jQuery('.htc__qua').html(result);
                if (is_checkout == 'yes') {
                    window.location.href = 'checkout.php';
                }
            }
        }
    });
}

function loadAttr(c_s_id, pid, type) {
    // No need to load attributes
}

function showQty() {
    // No need to show quantity related to attributes
}

function getAttrDetails(pid) {
    // No need to get attribute details
}


function sort_product_drop(cat_id,site_path){
	var sort_product_id=jQuery('#sort_product_id').val();
	window.location.href=site_path+"categories.php?id="+cat_id+"&sort="+sort_product_id;
}

function wishlist_manage(pid,type){
	jQuery.ajax({
		url:'wishlist_manage.php',
		type:'post',
		data:'pid='+pid+'&type='+type,
		success:function(result){
			result=result.trim();
			if(result=='not_login'){
				window.location.href='login.php';
			}else{
				jQuery('.htc__wishlist').html(result);
			}
		}	
	});	
}

jQuery('.imageZoom').imgZoom();

// function loadAttr(c_s_id,pid,type){
// 	jQuery('#cart_qty').addClass('hide');
// 	jQuery('#is_cart_box_show').addClass('hide');
// 	jQuery('#cid').val(c_s_id);				
// 	if(is_size==0){
// 		jQuery('#cart_attr_msg').html('');
// 		jQuery('#cart_qty').removeClass('hide');
// 		getAttrDetails(pid);
// 	}else{
// 		jQuery('#cart_attr_msg').html('');
// 		jQuery.ajax({
// 			url:'load_attr.php',
// 			type:'post',
// 			data:'c_s_id='+c_s_id+'&pid='+pid+'&type='+type,
// 			success:function(result){
// 				jQuery('#size_attr').html("<option value=''>Size</option>"+result);
// 			}
			
// 		});	
// 	}
	
// }

// function showQty(){
// 	let cid=jQuery('#cid').val();
// 	if(cid=='' && is_color>0){
// 		jQuery('#cart_attr_msg').html('Please select color');
// 	}else{
// 		let sid=jQuery('#size_attr').val();
		
// 		jQuery('#sid').val(sid);
// 		getAttrDetails(pid);
// 	}
// }

// function getAttrDetails(pid){
// 	jQuery('#is_cart_box_show').addClass('hide');
// 	jQuery('#cart_qty').hide();
// 	let color=jQuery('#cid').val();
// 	let size=jQuery('#sid').val();
// 	jQuery.ajax({
// 		url:'getAttrDetails.php',
// 		type:'post',
// 		data:'pid='+pid+'&color='+color+'&size='+size,
// 		success:function(result){
// 			result=jQuery.parseJSON(result);
// 			jQuery('.old__prize').html(result.mrp);
// 			jQuery('.new__price').html(result.price);
// 			var qty=result.qty;
			
// 			if(qty>0){
// 				var html='';
// 				for(i=1;i<=qty;i++){
// 					html=html+"<option>"+i+"</option>";
// 				}
// 				jQuery('#cart_qty').show();
// 				jQuery('#qty').html(html);
// 				jQuery('#is_cart_box_show').removeClass('hide');
// 				jQuery('#cart_attr_msg').html('');
// 				jQuery('#cart_qty').removeClass('hide');
// 			}else{
// 				jQuery('#cart_attr_msg').html('Out of stock');
// 			}
// 		}
// 	});
// }