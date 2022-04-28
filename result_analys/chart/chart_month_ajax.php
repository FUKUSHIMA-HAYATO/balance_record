<?php
  session_start();
  $dsn         = 'mysql:host=localhost; dbname=name ; charset=utf8mb4';
  $user        = 'user';
  $password    = 'pass';
  $user_name   = $_SESSION['id'];
  $month_start = $_POST['month_start'];
  $month_end   = $_POST['month_end'];

  //グラフ描画のため指定期間より前の残高データ抽出
    try {
      $old_start_month_chart = date('Y-m-d', strtotime('2020-01-01'));
      $old_end_month_chart   = date("Y-m-d", strtotime($month_start.         'first day of'));
      $old_end_month_chart   = date('Y-m-d', strtotime($old_end_month_chart. 'last day of -1 month'));
      $pdo                   = new PDO($dsn, $user, $password);
      $month_old_pay         = $pdo->prepare("SELECT SUM(income) - SUM(expenses)
                                              FROM   expenditure
                                              WHERE  account= :account AND entry_date
                                              BETWEEN :start AND :end");
      $month_old_pay ->bindValue(':account',$user_name);
      $month_old_pay ->bindValue(':start'  ,$old_start_month_chart);
      $month_old_pay ->bindValue(':end'    ,$old_end_month_chart);
      $month_old_pay ->execute();
      $month_old_pay = $month_old_pay->fetchAll(PDO::FETCH_COLUMN);
      $pdo = null;
      if($month_old_pay[0] == NULL){//過去の合計値に値がなかった場合「0」を代入
        $month_old_pay[0] = '0';
      }
    } catch (PDOException $pdo_connect_error) {
      exit();
    }
  //グラフ描画のため指定期間の残高データ抽出
    try {
      $start_month_chart = date("Y-m-d", strtotime($month_start.'first day of'));
      $end_month_chart   = date('Y-m-d', strtotime($month_end.  'last day of'));
      $pdo               = new PDO($dsn, $user, $password);
      $month_chart       = $pdo->prepare('SELECT SUM(income) - SUM(expenses), DATE_FORMAT(entry_date, "%Y-%m")
                                          FROM  expenditure
                                          WHERE account = :account  AND  entry_date
                                          BETWEEN :start_day AND :end_day
                                          GROUP BY DATE_FORMAT(entry_date, "%Y%m")');
      $month_chart ->bindValue(':account'  , $user_name);
      $month_chart ->bindValue(':start_day', $start_month_chart);
      $month_chart ->bindValue(':end_day'  , $end_month_chart  );
      $month_chart ->execute();
      $month_chart = $month_chart->fetchAll(PDO::FETCH_ASSOC);
      $pdo         = null;
    } catch (PDOException $pdo_connect_error) {
      exit();
    }
?>

<?php
  //指定期間が何月あるか
    $start_comp = new DateTimeImmutable($start_month_chart);
    $end_comp   = new DateTimeImmutable($end_month_chart);
    $interval   = new DateInterval('P1M');
    $res_comp   = new DatePeriod($start_comp, $interval, $end_comp);
  //chart.jsのラベル表記用
    foreach ($res_comp as $what_month) {
      $what_month_res = $what_month_res.'"'.$what_month->format('Y-m').'",';
      $how_many_month = $how_many_month + 1;
    }
    $how_many_month = $how_many_month*40;
  //chart.jsのグラフ描画用
    $lps=0 ;
    foreach ($res_comp as $what_balance_month) {
      if($what_balance_month->format('Y-m') == $month_chart[$lps]['DATE_FORMAT(entry_date, "%Y-%m")']){
        $month_old_pay[0] = $month_old_pay[0] + $month_chart[$lps++]["SUM(income) - SUM(expenses)"];
        $what_balance_res = $what_balance_res.$month_old_pay[0].',';
      }else{
        $what_balance_res = $what_balance_res.$month_old_pay[0].',';
      }
    }
?>


<script>
  if ( month_Chart) {
    month_Chart.destroy();
  }
  var how_many_days = <?php echo "'".$how_many_month."px'"; ?>;
  document.getElementById('month_chart_Container').style.maxWidth = '';
  document.getElementById('month_chart_Container').style.minWidth = '100%';
  document.getElementById('month_chart_Container').style.width = how_many_days;
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