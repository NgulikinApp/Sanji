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
        uploadPhoto();
    });
}

function uploadPhoto(){
    var data = new FormData(),
        filePath = $('#file-notif').val(),
        fileExt = (filePath.substr(filePath.lastIndexOf('\\') + 1).split('.')[1]).toLowerCase(),
        filePhoto = $('#file-notif')[0].files[0],
        fileSize = filePhoto.size/ 1024 / 1024;
        
        data.append('type', 'product');
        data.append('file', filePhoto);
    if(sessionStorage.getItem('tokenNgulikin') === null){
        generateToken("uploadPhotoProduct");
    }else{
        if(fileSize < 2){
            if(fileExt === 'jpg' || fileExt === 'png'){
                /*$.ajax({
                    type: 'POST',
                    url: PUTFILE_API,
                    data: data,
                    async: true,
                    contentType: false, 
                    processData: false,
                    dataType: 'json',
                    beforeSend: function(xhr, settings) { 
                        xhr.setRequestHeader('Authorization','Bearer ' + btoa(sessionStorage.getItem('tokenNgulikin')));
                    },
                    success: function(result){
                        productList.push(result.src);
                        $('.loaderPopup').addClass('hidden');
                        
                        readProductURL();
                    }
                });*/
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

function readProductURL() {
    var fileListDisplay = document.getElementById('img-notif');
    fileListDisplay.innerHTML = '';
    productList.forEach(function (file, index) {
        
        var img = document.createElement("img");
            img.setAttribute("src", file);
            img.setAttribute("width", "60");
            img.setAttribute("height", "60");
          
          fileListDisplay.appendChild(img);
    });
}