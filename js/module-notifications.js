$( document ).ready(function() {
    initGeneral();
    initNotif();
});

function initNotif(){
    $('.notif-menu').on( 'click', function( e ){
        $('.notif-menu').removeClass('bck-grey');
        $(this).addClass('bck-grey');
    });
    
    $("#file-notif").change(function(){
        if (this.files && this.files[0]) {
          var reader = new FileReader();
              reader.onload = (function(theFile) {
                  var image = new Image();
                      image.src = theFile.target.result;
                  
                      image.onload = function() {
                          $("#img-notif").attr('src', this.src);
                      };
              });
          reader.readAsDataURL(this.files[0]);
        }
    });
    
    $(".btn-notif").click(function(){
        sendNotif();
    });
}

function sendNotif(){
    var data = new FormData(),
        filePath = $('#file-notif').val(),
        fileExt = (filePath.substr(filePath.lastIndexOf('\\') + 1).split('.')[1]).toLowerCase(),
        filePhoto = $('#file-notif')[0].files[0],
        fileSize = filePhoto.size/ 1024 / 1024;
        
        data.append('desc', $('.inpt-notif-desc').val());
        data.append('file', filePhoto);
    if(sessionStorage.getItem('tokenNgulikin') === null){
        generateToken("sendNotif");
    }else{
        if(fileSize < 2){
            if(fileExt === 'jpg' || fileExt === 'png'){
                $('.container-loader100').removeClass('dis-none').addClass('dis-block');
                $.ajax({
                    type: 'POST',
                    url: NOTIF_API,
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
                                content: 'Notifikasi sudah dikirim',
                                icon: 'fa fa-warning',
                                animation: 'top',
                                type: 'dark',
                                animateFromElement: false
                            });
                        }else{
                            generateToken("sendNotif");
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