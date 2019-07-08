var search = {},
    confirmDo = {};

$( document ).ready(function() {
    initGeneral();
    initConfirm();
});

function initConfirm(){
    var keywords = getUrlParam("keywords");
    
    search.keywords = (keywords === null)?'':keywords;
    
    $('.input-search100').val(search.keywords);
    listSales();
}

function listSales(){
    if(sessionStorage.getItem('tokenNgulikin') === null){
        generateToken("listSales");
    }else{
        $('.container-loader100').removeClass('dis-none').addClass('dis-block');
        $.ajax({
            type: 'GET',
            url: SALESCONFIRM_API,
            data:{
                search : search.keywords
            },
            dataType: 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + btoa(sessionStorage.getItem('tokenNgulikin')));
            },
            success: function(data, status) {
                if(data.status == "OK"){
                    var resultLen = data.result.length;
                    var element = '';
                    if(resultLen){
                        $.each(data.result, function( key, val ) {
                            var tableClass = "table-border";
                            if(resultLen > 1){
                                var keyVal = key+1;
                                if(keyVal === 1){
                                    tableClass = "table-border-top";
                                }else if(keyVal === resultLen){
                                    tableClass = "table-border-bottom";
                                }else{
                                    tableClass = "table-border-side";
                                }
                            }
                            element += '<div class="dis-block">';
                            element += '    <div class="'+tableClass+' table-border-side table-content table-confirm100-text bgwhite dis-inline-block brdr-grey text-center box-elip">'+val.username+'</div>';
                            element += '    <div class="'+tableClass+' table-border-side table-content table-confirm100-img bgwhite dis-inline-block brdr-grey text-center">';
                            element += '        <a class="popup" href="'+val.photo_card+'" title="Foto KTP">';
                            element += '            <img src="'+val.photo_card+'" width="50" height="50"/>';
                            element += '        </a>';
                            element += '    </div>';
                            element += '    <div class="'+tableClass+' table-border-side table-content table-confirm100-img bgwhite dis-inline-block brdr-grey text-center m-l-min">';
                            element += '        <a class="popup" href="'+val.photo_selfie+'" title="Foto Selfie/SIM/KTP">';
                            element += '            <img src="'+val.photo_selfie+'" width="50" height="50"/>';
                            element += '        </a>';
                            element += '    </div>';
                            element += '    <div class="'+tableClass+' table-border-side table-content table-confirm100-text bgwhite dis-inline-block brdr-grey text-center m-l-min box-elip">'+val.shop_name+'</div>';
                            element += '    <div class="'+tableClass+' table-border-side table-content table-confirm100-img bgwhite dis-inline-block brdr-grey text-center">';
                            element += '        <a class="popup" href="'+val.shop_icon+'" title="Foto KTP">';
                            element += '            <img src="'+val.shop_icon+'" width="50" height="50"/>';
                            element += '        </a>';
                            element += '    </div>';
                            element += '    <div class="'+tableClass+' table-border-side table-content table-confirm100-img bgwhite dis-inline-block brdr-grey text-center p-t-5 p-b-5 m-l-min">';
                            element += '        <button class="btn-ver text-up fs-10 p-t-4 p-b-4 m-b-5" data-id="'+val.user_id+'">verifikasi</button>';
                            element += '        <button class="btn-can text-up fs-10 p-t-4 p-b-4" data-id="'+val.user_id+'">batal</button>';
                            element += '    </div>';
                            element += '</div>';
                        });
                    }else{
                        element += '<div class="dis-block table-border w-full bgwhite text-center p-t-8 p-b-8">';
                        element += '    Tidak ada data';
                        element += '</div>';
                    }
                    $('.table-confirm100-list').html(element);
                    $('.container-loader100').removeClass('dis-block').addClass('dis-none');
                    
                    $(".popup").fancybox({
                		'hideOnContentClick': false,
                		'titlePosition'  : 'over'
                	});
                	
                	$('.btn-ver').click(function(e){
                	    var user_id = $(this).data('id');
                        $.confirm({
                            title: 'Konfirmasi',
                            icon: 'fa fa-warning',
                            animation: 'top',
                            animateFromElement: false,
                            type: 'dark',
                            content: 'Yakin ingin diverifikasi?',
                            buttons: {
                                ya: function () {
                                    confirmDo.user_id = user_id;
                                    confirmDo.action = 0;
                                    confirmAction();
                                },
                                    tidak: function () {
                                }
                            }
                        });
                    });
                    
                    $('.btn-can').click(function(e){
                        var user_id = $(this).data('id');
                        $.confirm({
                            title: 'Konfirmasi',
                            icon: 'fa fa-warning',
                            animation: 'top',
                            animateFromElement: false,
                            type: 'dark',
                            content: 'Yakin ingin dibatalkan?',
                            buttons: {
                                ya: function () {
                                    confirmDo.user_id = user_id;
                                    confirmDo.action = 1;
                                    confirmAction();
                                },
                                    tidak: function () {
                                }
                            }
                        });
                    });
                }else{
                    generateToken("listSales");
                }
            } 
        });
    }
}

function confirmAction(){
    if(sessionStorage.getItem('tokenNgulikin') === null){
        generateToken("confirmAction");
    }else{
        $.ajax({
            type: 'POST',
            url: SALESCONFIRM_ACTION_API,
            data:JSON.stringify({ 
                user_id : confirmDo.user_id,
                action : confirmDo.action
            }),
            dataType: 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + btoa(sessionStorage.getItem('tokenNgulikin')));
            },
            success: function(data,status) {
                if(result.status ===  'DENIED'){
                    generateToken("confirmAction");
                }else{
                    listSales();
                }
            }
        });
    }
}