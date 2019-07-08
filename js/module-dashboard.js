$( document ).ready(function() {
    initGeneral();
    initDashboard();
});

docraptorJavaScriptFinished = function() {
    var chartCanvas = document.getElementById("canvas");

    // do not do anything unless the chart is finished rendering
    if (chartCanvas == null || chartCanvas.style.length < 1) {
        return false;
    }

    // dump the canvas to an img tag
    var imageElement = document.createElement("img");
    imageElement.style = "width: 100%;";
    imageElement.src = chartCanvas.toDataURL("image/png", 0);
    chartCanvas.remove();
    document.body.appendChild(imageElement);
    return true;
}

function initDashboard(){
    sumStatistics();
}

function sumStatistics(){
    if(sessionStorage.getItem('tokenNgulikin') === null){
        generateToken("listSales");
    }else{
        $('.container-loader100').removeClass('dis-none').addClass('dis-block');
        $.ajax({
            type: 'GET',
            url: SUMSTATISTICS_API,
            dataType: 'json',
            beforeSend: function(xhr, settings) { 
                xhr.setRequestHeader('Authorization','Bearer ' + btoa(sessionStorage.getItem('tokenNgulikin')));
            },
            success: function(data, status) {
                if(data.status == "OK"){
                    var result = data.result,
                        sumUser = result.sumUser,
                        colorSumUser = result.colorSumUser,
                        sumUserSeller = result.sumUserSeller,
                        colorSumUserSeller = result.colorSumUserSeller,
                        sumProducts = result.sumProducts,
                        colorSumProducts = result.colorSumProducts,
                        sumTransactions = result.sumTransactions,
                        colorSumTransactions = result.colorSumTransactions;
                    
                    var element = '<span>'+sumUser+'</span>';
                        
                        if(colorSumUser === "red"){
                            $('#sumUser').addClass('fn-red');
                            element += '<img src="/img/down.png" class="icon-arrow pos-absolute"/>';
                        }else{
                            $('#sumUser').addClass('fn-bluesky');
                            element += '<img src="/img/up.png" class="icon-arrow pos-absolute"/>';
                        }
                        
                        $('#sumUser').html(element);
                        
                    var element = '<span>'+sumUserSeller+'</span>';
                        if(colorSumUserSeller === "red"){
                            $('#sumUserSeller').addClass('fn-red');
                            element += '<img src="/img/down.png" class="icon-arrow pos-absolute"/>';
                        }else{
                            $('#sumUserSeller').addClass('fn-bluesky');
                            element += '<img src="/img/up.png" class="icon-arrow pos-absolute"/>';
                        }
                        
                        $('#sumUserSeller').html(element);
                        
                    var element = '<span>'+sumProducts+'</span>';
                        if(colorSumProducts === "red"){
                            $('#sumProducts').addClass('fn-red');
                            element += '<img src="/img/down.png" class="icon-arrow pos-absolute"/>';
                        }else{
                            $('#sumProducts').addClass('fn-bluesky');
                            element += '<img src="/img/up.png" class="icon-arrow pos-absolute"/>';
                        }
                        
                        $('#sumProducts').html(element);
                    
                    var element = '<span>'+sumTransactions+'</span>';
                        if(colorSumTransactions === "red"){
                            $('#sumTranNum').addClass('fn-red');
                            element += '<img src="/img/down.png" class="icon-arrow pos-absolute"/>';
                        }else{
                            $('#sumTranNum').addClass('fn-bluesky');
                            element += '<img src="/img/up.png" class="icon-arrow pos-absolute"/>';
                        }
                        
                        $('#sumTranNum').html(element);
                    
                    if(colorSumUser === "red"){
                        statistic("User",window.chartColors.red);
                    }else{
                        statistic("User",window.chartColors.blue);
                    }    
    
                    $('.user-number').click(function(e){
                        var type = parseInt($(this).data('type'));
                        $('.curr-menu').text($(this).data("dashboard"));
                        if($(this).data("dashboard") === "Transaction number"){
                            if(colorSumTransactions === "red"){
                                statistic($(this).data("dashboard"),window.chartColors.red,type);
                            }else{
                                statistic($(this).data("dashboard"),window.chartColors.blue,type);
                            }
                        }else if($(this).data("dashboard") === "Products"){
                            if(colorSumProducts === "red"){
                                statistic($(this).data("dashboard"),window.chartColors.red,type);
                            }else{
                                statistic($(this).data("dashboard"),window.chartColors.blue,type);
                            }
                        }else if($(this).data("dashboard") === "User seller"){
                            if(colorSumUserSeller === "red"){
                                statistic($(this).data("dashboard"),window.chartColors.red,type);
                            }else{
                                statistic($(this).data("dashboard"),window.chartColors.blue,type);
                            }
                        }else{
                            if(colorSumUser === "red"){
                                statistic($(this).data("dashboard"),window.chartColors.red,type);
                            }else{
                                statistic($(this).data("dashboard"),window.chartColors.blue,type);
                            }
                        }
                    });
                }else{
                    generateToken("sumStatistics");
                }
            } 
        });
    }
}

function statistic(title,color,type){
    var lineChartData = {
      labels: [],
      datasets: [{
        label: title,
        borderColor: window.chartColors.black,
        backgroundColor: color,
        fill: false,
        data: [],
        yAxisID: "y-axis-1",
      }]
    };
    
    var ctx = document.getElementById("canvas").getContext("2d");
    
    $.ajax({
        type: 'GET',
        url: GRAPHSTATISTICS_API,
        data:{
                type : type
            },
        dataType: 'json',
        beforeSend: function(xhr, settings) { 
            xhr.setRequestHeader('Authorization','Bearer ' + btoa(sessionStorage.getItem('tokenNgulikin')));
        },
        success: function(data, status) {
            if(data.status == "OK"){
                var text = "";
                $.each(data.result, function( key, val ) {
                    if(key === 0){
                        text += val.date;
                    }
                    if(key+1 === data.result.length){
                        text += " - ";
                        text += val.date;
                    }
                    lineChartData.labels.push(val.date);
        			lineChartData.datasets.forEach(function(dataset) {
        					dataset.data.push(val.sumData);
        			});
                });
                window.myLine = Chart.Line(ctx, {
                    data: lineChartData,
                    options: {
                      responsive: true,
                      hoverMode: 'index',
                      stacked: false,
                      title: {
                        display: true,
                        text: text
                      },
                      tooltips: {
                        backgroundColor: color
                    },
                      scales: {
                        yAxes: [{
                          type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                          display: true,
                          position: "left",
                          id: "y-axis-1",
                        }],
                      }
                    }
                  });
                  
                 $('.graph-statistic').addClass('dis-none');
            }
        } 
    });
}