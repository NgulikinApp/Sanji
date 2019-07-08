var questionsDo = {};
    
$( document ).ready(function() {
    initGeneral();
    initServices();
});

function initServices(){
    listQuestions();
}

function listQuestions(){
    if(sessionStorage.getItem('tokenNgulikin') === null){
        generateToken("listQuestions");
    }else{
        $('.container-loader100').removeClass('dis-none').addClass('dis-block');
        $.ajax({
            type: 'GET',
            url: ASSISTANCESERVICES_API,
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
                            element += '    <div class="'+tableClass+' table-border-side table-content table-confirm100-text bgwhite dis-inline-block brdr-grey text-center box-elip">'+val.fullname+'</div>';
                            element += '    <div class="'+tableClass+' table-border-side table-content table-confirm100-text bgwhite dis-inline-block brdr-grey text-center box-elip">'+val.email+'</div>';
                            element += '    <div class="'+tableClass+' table-border-side table-content table-confirm100-text bgwhite dis-inline-block brdr-grey text-center m-l-min box-elip default-color">'+val.desc+'</div>';
                            element += '    <div class="'+tableClass+' table-border-side table-content table-confirm100-img bgwhite dis-inline-block brdr-grey text-center m-l-min">';
                            element += '        <a class="popup" href="/img/no-image.jpg" title="Deskripsi pertanyaan">';
                            element += '            <img src="/img/no-image.jpg" width="50" height="50"/>';
                            element += '        </a>';
                            element += '    </div>';
                            element += '    <div class="'+tableClass+' table-border-side table-content table-confirm100-text bgwhite dis-inline-block brdr-grey text-center">'+val.date+'</div>';
                            element += '    <div class="'+tableClass+' table-border-side table-content table-confirm100-text bgwhite dis-inline-block brdr-grey text-center p-t-5 p-b-5 m-l-min">';
                            element += '        <button class="btn-ver text-up fs-10 p-t-4 p-b-4 m-b-5" data-id="'+val.questions_id+'">selesai</button>';
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
                	    var questions_id = $(this).data('id');
                        $('.btn-ver').click(function(e){
                            $.confirm({
                                title: 'Konfirmasi',
                                icon: 'fa fa-warning',
                                animation: 'top',
                                animateFromElement: false,
                                type: 'dark',
                                content: 'Yakin ingin diselesaikan?',
                                buttons: {
                                    ya: function () {
                                        questionsDo.questions_id = questions_id;
                                        questionsAction();
                                    },
                                        tidak: function () {
                                    }
                                }
                            });
                        });
                    });
                }else{
                    generateToken("listQuestions");
                }
            } 
        });
    }
}

function questionsAction(){
    if(sessionStorage.getItem('tokenNgulikin') === null){
        generateToken("questionsAction");
    }else{
        $.ajax({
            type: 'POST',
            url: ASSISTANCESERVICES_ACTION_API,
            data:JSON.stringify({ 
                questions_id : questionsDo.questions_id
            }),
            dataType: 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + btoa(sessionStorage.getItem('tokenNgulikin')));
            },
            success: function(data,status) {
                if(result.status ===  'DENIED'){
                    generateToken("questionsAction");
                }else{
                    listQuestions();
                }
            }
        });
    }
}