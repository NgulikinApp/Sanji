function initGeneral(){
    if(sessionStorage.getItem('tokenNgulikin') === null){
        $.getJSON(TOKEN_API, function( data ) {
	        sessionStorage.setItem('tokenNgulikin',data.result);
	    });
	}
	
	$("div[data-menu='"+(window.location.pathname).replace("/", "")+"']").addClass('fn-bluesky font-nova');
	
	$('.list-mainmenu').find('div').click(function(e){
        $(this).parent().children('ul').slideToggle("slow");
        
        if($(this).data("menu") !== "setting")location.href = url+"/"+$(this).data("menu");
    });
    
    $('.icon-search100-global').on( 'click', function( e ){
	   var searchVal = $('.input-search100').val();
	   location.href = url+"/confirm?keywords="+searchVal;
	});
	
	$('.input-search100').on('keydown', function (e) {
	    if (e.which == 13) {
	        $('.icon-search100-global').trigger('click');
	    }
	});
    
    $('.signout').click(function(e){
        $.confirm({
            title: 'Konfirmasi',
            icon: 'fa fa-warning',
            animation: 'top',
            animateFromElement: false,
            type: 'dark',
            content: 'Yakin ingin keluar?',
            buttons: {
                ya: function () {
                    logout();
                },
                    tidak: function () {
                }
            }
        });
    });
}

function generateToken(name){
    $.getJSON(TOKEN_API, function( data ) {
	    sessionStorage.setItem('tokenNgulikin',data.result);
	    window[name]();
	});
}

function logout(){
    $.ajax({
        type: 'GET',
        url: SIGNOUT_API,
        success: function(data, res) {
            location.reload();
        } 
    });
}

//get value parameter from url
function getUrlParam(param){
    var url = new URL(window.location.href);
    var val = url.searchParams.get(param);
    return val;
}