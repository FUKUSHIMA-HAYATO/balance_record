<div id="month_chart_Container">
  <canvas id="month_chart"></canvas>
</div>

<?php
  //指定期間が何月あるか(db_operation/result_analys_db_conn.phpで出した情報を参照しています)
    $start_comp = new DateTimeImmutable($start_month_chart);
    $end_comp   = new DateTimeImmutable($end_month_chart);
    $interval   = new DateInterval('P1M');
    $res_comp   = new DatePeriod($start_comp, $interval, $end_comp);
  //chart.jsのラベル表記用
    foreach ($res_comp as $what_month) {
      $what_month_res = $what_month_res.'"'.$what_month->format('Y-m').'",';
    }
  //chart.jsのグラフ描画用(db_operation/result_analys_db_conn.phpで出した情報を参照しています)
    $lps=0 ;
    foreach ($res_comp as $what_balance_month) {
      if($what_balance_month->format('Y-m') == $month_chart[$lps]['entry_date']){
        $month_old_pay[0] = $month_old_pay[0] + $month_chart[$lps++]['total_amount'];
        $what_balance_res = $what_balance_res.$month_old_pay[0] .',';
      }else{
        $what_balance_res = $what_balance_res.$month_old_pay[0].',';
      }
    }
?>

<script>
  var month_Chart = new Chart(document.getElementById("month_chart").getContext('2d'), {
  	type: 'line',
  	data: {
      labels :  [<?php echo $what_month_res;?>],
  		datasets: [
        {
          label: "",
  				data: [<?php echo $what_balance_res;?>],
          borderColor :         "rgba(255, 87, 87, 0.8)",
          pointBackgroundColor: "rgb(160, 160, 160)",
          pointBorderColor:     "rgba(0,0,0,0)",
          pointRadius: 5,
  			},
  		]
  	},
    options: {
      maintainAspectRatio: false,
      plugins:{
        legend: {
          position:false,
        },
      },
    },
  });
</script>