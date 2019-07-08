$( document ).ready(function() {
    initGeneral();
    initLogin();
});

function initLogin(){
    $('.login100-form-btn').on( 'click', function( e ){
	   ajax_auth();
	});
	
	$('.input100').keypress(function(e) {
	    if(e.which == 13) {
    	    $('.login100-form-btn').trigger('click');
	    }
	});
	
	$('.input100').on( 'click', function( e ){
	   $('.login100-form-alert').html('');
	});
}

function ajax_auth(){
    if(sessionStorage.getItem('tokenNgulikin') === null){
        generateToken("ajax_auth");
    }else{
        var email = $('#login100-username').val(),
	        pass = $('#login100-pass').val();
	    
	    if(email === '' || pass === ''){
	       $('.login100-form-alert').html('Username dan password harus diisi');
	    }else{
	        $('.container-loader100').removeClass('dis-none').addClass('dis-block');
	        pass = (SHA256(pass)).toUpperCase();
    	    $.ajax({
                type: 'POST',
                data: JSON.stringify({ token: sessionStorage.getItem('tokenNgulikin')}),
                crossDomain: true,
                async: true,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Basic ' + btoa(email+':'+pass)),
                    xhr.withCredentials = true;
                },
                url: SIGNIN_API,
                contentType: "application/json",
                dataType: "json",
                success: function(result) {
                    if(result.status ===  'DENIED'){
                        generateToken("ajax_auth");
                    }else if(result.status ===  'NO'){
	                    $('.login100-form-alert').html(result.message);
                    }else{
                        location.href = url+'/dashboard';
                    }
                    $('.container-loader100').removeClass('dis-block').addClass('dis-none');
                }
            });
	   }
    }
}