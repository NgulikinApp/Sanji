$( document ).ready(function() {
    initGeneral();
    initBanner();
});

function initBanner(){
    $('#file-banner').on( 'change', function( e ){
        if (this.files && this.files[0]) {
            var reader = new FileReader();
                reader.onload = (function(theFile) {
                    var image = new Image();
                        image.src = theFile.target.result;
                    
                    image.onload = function() {
                        if(this.width <= 1055 || this.height <= 247){
                            notif("info","ukuran foto sampul minimal 1056x248","center","top");
                            $.alert({
                                title: 'Alert!',
                                content: 'Ukuran foto sampul minimal 1056x248',
                                icon: 'fa fa-warning',
                                animation: 'top',
                                type: 'red',
                                animateFromElement: false
                            });
                        }else{
                            $("#img-banner").attr('src', this.src);
                        }
                    };
                });
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    $(".btn-banner").click(function(){
        changeBanner();
    });
}

function changeBanner(){
    var data = new FormData(),
        filePath = $('#file-banner').val(),
        fileExt = (filePath.substr(filePath.lastIndexOf('\\') + 1).split('.')[1]).toLowerCase(),
        filePhoto = $('#file-banner')[0].files[0],
        fileSize = filePhoto.size/ 1024 / 1024;
        
        data.append('file', filePhoto);
    if(sessionStorage.getItem('tokenNgulikin') === null){
        generateToken("changeBanner");
    }else{
        if(fileSize < 2){
            if(fileExt === 'jpg' || fileExt === 'png'){
                $('.container-loader100').removeClass('dis-none').addClass('dis-block');
                $.ajax({
                    type: 'POST',
                    url: BANNER_API,
                    data: data,
                    async: true,
                    contentType: false, 
                    processData: false,
                    dataType: 'json',
                    beforeSend: function(xhr, settings) { 
                        xhr.setRequestHeader('Authorization','Bearer ' + btoa(sessionStorage.getItem('tokenNgulikin')));
                    },
                    success: function(data, status){
                        if(data.status === "OK"){
                            $('.container-loader100').removeClass('dis-block').addClass('dis-none');
                            $.alert({
                                title: 'Alert!',
                                content: 'Default banner sudah diubah',
                                icon: 'fa fa-warning',
                                animation: 'top',
                                type: 'dark',
                                animateFromElement: false
                            });
                        }else{
                            generateToken("changeBanner");
                        }
                    }
                });
            }else{
                $.alert({
                    title: 'Alert!',
                    content: 'Format file hanya boleh jpg atau png',
                    icon: 'fa fa-warning',
                    animation: 'top',
                    type: 'red',
                    animateFromElement: false
                });
            }
        }else{
            $.alert({
                title: 'Alert!',
                content: 'Format file hanya boleh jpg atau png',
                icon: 'fa fa-warning',
                animation: 'top',
                type: 'red',
                animateFromElement: false
            });
        }
    }
}