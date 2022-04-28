//ホームの詳細ログ
  $(function(){
    $("#record_home_btn").on("click", function(){
      let split_day = $("#record_home_ajax").val().split(',');
      $.ajax({
        type: "POST",
        url: "/../balance_record/result_analys/log/home_ajax",
        data: { "day_start" : split_day[0] , "day_end" : split_day[1] },
        dataType : "html"
      }).done(function(data){
        document.getElementById("log").innerHTML = '';
        $('#log').html(data);
      }).fail(function(){
        alert('通信に失敗しました。');
      });
    });
  });
//チャート(月別)
  $(function(){
    $("#chart_month_btn").on("click", function(){
      let split_month = $("#chart_month_ajax").val().split(',');
      $.ajax({
        type: "POST",
        url: "/../balance_record/result_analys/chart/chart_month_ajax",
        data: { "month_start" : split_month[0] , "month_end" : split_month[1] },
        dataType : "html"
      }).done(function(data){
        $('#month_chart').html(data);
      }).fail(function(){
        alert('通信に失敗しました。');
      });
    });
    });
//チャート(日別)
  $(function(){
    $("#chart_day_btn").on("click", function(){
      let split_day = $("#chart_day_ajax").val().split(',');
      $.ajax({
        type: "POST",
        url: "/../balance_record/result_analys/chart/chart_day_ajax",
        data: { "day_start" : split_day[0] , "day_end" : split_day[1] },
        dataType : "html"
      }).done(function(data){
        $('#day_chart').html(data);
      }).fail(function(){
        alert('通信に失敗しました。');
      });
    });
  });
//カテゴリー(月別)
  $(function(){
    $("#category_month_btn").on("click", function(){
      let split_month = $("#category_month_ajax").val().split(',');
      $.ajax({
        type: "POST",
        url: "/../balance_record/result_analys/category/category_month_ajax",
        data: { "month_start" : split_month[0] , "month_end" : split_month[1] },
        dataType : "json"
      }).done(function(data){
        document.getElementById("ajax_category_month_payment_container").innerHTML = '';
        Object.keys(data).forEach((num) => {
          $("#ajax_category_month_payment_container").append(
            "<li class='category_name'   >"+data[num].category  +'</li>'+
            "<li class='category_payment'>"+data[num].payment   +'</li>'+
            "<li class='category_date'   >"+data[num].entry_date+'</li>'
          );
        });
      }).fail(function(){
        alert('通信に失敗しました。');
      });
    });
  });
//カテゴリー(日別)
  $(function(){
    $("#category_day_btn").on("click", function(){
      let split_day = $("#category_day_ajax").val().split(',');
      $.ajax({
        type: "POST",
        url: "/../balance_record/result_analys/category/category_day_ajax",
        data: { "day_start" : split_day[0] , "day_end" : split_day[1] },
        dataType : "json"
      }).done(function(data){
        document.getElementById("ajax_category_day_payment_container").innerHTML = '';
        Object.keys(data).forEach((num) => {
          $("#ajax_category_day_payment_container").append(
            "<li class='category_name'   >"+data[num].category  +'</li>'+
            "<li class='category_payment'>"+data[num].payment   +'</li>'+
            "<li class='category_date'   >"+data[num].entry_date+'</li>'
          );
        });
      }).fail(function(){
        alert('通信に失敗しました。');
      });
    });
  });
//高額商品(月別)
  $(function(){
    $("#month_hpi_btn").on("click", function(){
      let split_month = $("#month_hpi_ajax").val().split(',');
      $.ajax({
        type: "POST",
        url: "/../balance_record/result_analys/high_priced_item/high_priced_item_month_ajax",
        data: { "month_start" : split_month[0] , "month_end" : split_month[1] },
        dataType : "json"
      }).done(function(data){
        document.getElementById("ajax_hpi_month_payment_container").innerHTML = '';
        Object.keys(data).forEach((num) => {
          $("#ajax_hpi_month_payment_container").append(
            "<li class='hpi_name'   >"+data[num].the_name  +'</li>'+
            "<li class='hpi_payment'>"+data[num].payment   +'</li>'+
            "<li class='hpi_date'   >"+data[num].entry_date+'</li>'
          );
        });
      }).fail(function(){
        alert('通信に失敗しました。');
      });
    });
  });
//高額商品(日別)
  $(function(){
    $("#day_hpi_btn").on("click", function(){
      let split_day = $("#day_hpi_ajax").val().split(',');
      $.ajax({
        type: "POST",
        url: "/../balance_record/result_analys/high_priced_item/high_priced_item_day_ajax",
        data: { "day_start" : split_day[0] , "day_end" : split_day[1] },
        dataType : "json"
      }).done(function(data){
        document.getElementById("ajax_hpi_day_payment_container").innerHTML = '';
        Object.keys(data).forEach((num) => {
          $("#ajax_hpi_day_payment_container").append(
            "<li class='hpi_name'   >"+data[num].the_name  +'</li>'+
            "<li class='hpi_payment'>"+data[num].payment   +'</li>'+
            "<li class='hpi_date'   >"+data[num].entry_date+'</li>'
          );
        });
      }).fail(function(){
        alert('通信に失敗しました。');
      });
    });
  });