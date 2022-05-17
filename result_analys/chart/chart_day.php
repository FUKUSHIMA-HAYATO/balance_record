<div id="day_chart_Container">
  <canvas id="day_chart"></canvas>
</div>

<?php
  //指定期間が何月あるか(db_operation/result_analys_db_conn.phpで出した情報を参照しています)
    $start_comp = new DateTimeImmutable(date('Y-m-d-00:00', strtotime($start_day_chart)));
    $end_comp   = new DateTimeImmutable(date('Y-m-d-24:00', strtotime($end_day_chart)));
    $interval   = new DateInterval('P1D');
    $res_comp   = new DatePeriod($start_comp, $interval, $end_comp);
  //chart.jsのラベル表記用
    foreach ($res_comp as $what_day) {
      $what_day_res = $what_day_res.'"'.$what_day->format('Y-m-d').'",';
    }
  //chart.jsのグラフ描画用(db_operation/result_analys_db_conn.phpで出した情報を参照しています)
    $lps=0 ;
    foreach ($res_comp as $what_balance_day) {
      if($what_balance_day->format('Y-m-d') == $day_chart[$lps]["entry_date"]){
        $day_old_pay[0] = $day_old_pay[0] + $day_chart[$lps++]["total_amount"];
        $what_balance_day_res = $what_balance_day_res.$day_old_pay[0] .',';
      }else{
        $what_balance_day_res = $what_balance_day_res.$day_old_pay[0].',';
      }
    }
?>

<script>
  var day_Chart = new Chart(document.getElementById("day_chart").getContext('2d'), {
  	type: 'line',
  	data: {
      labels :  [<?php echo $what_day_res;?>],
  		datasets: [
        {
          label: "",
  				data: [<?php echo $what_balance_day_res;?>],
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