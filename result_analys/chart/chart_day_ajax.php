<?php
  session_start();
  $dsn       = 'mysql:host=localhost; dbname=name ; charset=utf8mb4';
  $user      = 'user';
  $password  = 'pass';
  $user_name = $_SESSION['id'];
  $day_start = $_POST['day_start'];
  $day_end   = $_POST['day_end'];

  //グラフ描画のため指定期間より前の残高データ抽出
    try {
      $old_start_day_chart = date('Y-m-d',strtotime('2020-01-01'));
      $old_end_day_chart   = date('Y-m-d', strtotime($day_start.'-1 day'));
      $pdo                 = new PDO($dsn, $user, $password);
      $day_old_pay         = $pdo->prepare("SELECT SUM(income) - SUM(expenses)
                                            FROM   expenditure
                                            WHERE  account= :account AND entry_date
                                            BETWEEN :start AND :end");
      $day_old_pay ->bindValue(':account',$user_name);
      $day_old_pay ->bindValue(':start'  ,$old_start_day_chart);
      $day_old_pay ->bindValue(':end'    ,$old_end_day_chart);
      $day_old_pay ->execute();
      $day_old_pay = $day_old_pay->fetchAll(PDO::FETCH_COLUMN);
      $pdo = null;
      if($day_old_pay[0] == NULL){//過去の合計値に値がなかった場合「0」を代入
        $day_old_pay[0] = '0';
      }
    } catch (PDOException $pdo_connect_error) {
      exit();
    }
  //グラフ描画のため指定期間の残高データ抽出
    try {
      $start_day_chart = date('Y-m-d', strtotime($day_start));
      $end_day_chart   = date('Y-m-d', strtotime($day_end));
      $pdo             = new PDO($dsn, $user, $password);
      $day_chart       = $pdo->prepare('SELECT SUM(income) - SUM(expenses), DATE_FORMAT(entry_date, "%Y-%m-%d")
                                        FROM  expenditure
                                        WHERE account = :account  AND  entry_date
                                        BETWEEN :start_day AND :end_day
                                        GROUP BY DATE_FORMAT(entry_date, "%Y%m%d")');
      $day_chart ->bindValue(':account'  , $user_name);
      $day_chart ->bindValue(':start_day', $start_day_chart);
      $day_chart ->bindValue(':end_day'  , $end_day_chart  );
      $day_chart ->execute();
      $day_chart = $day_chart->fetchAll(PDO::FETCH_ASSOC);
      $pdo               = null;
    } catch (PDOException $pdo_connect_error) {
      exit();
    }
?>

<?php
  //指定期間が何月あるか
    $start_comp = new DateTimeImmutable(date('Y-m-d-00:00', strtotime($start_day_chart)));
    $end_comp   = new DateTimeImmutable(date('Y-m-d-24:00', strtotime($end_day_chart)));
    $interval   = new DateInterval('P1D');
    $res_comp   = new DatePeriod($start_comp, $interval, $end_comp);
  //chart.jsのラベル表記用
    foreach ($res_comp as $what_day) {
      $what_day_res = $what_day_res.'"'.$what_day->format('Y-m-d').'",';
      $how_many_days  = $how_many_days + 1;
    }
    $how_many_days = $how_many_days*40;
  //chart.jsのグラフ描画用
    $lps=0 ;
    foreach ($res_comp as $what_balance_day) {
      if($what_balance_day->format('Y-m-d') == $day_chart[$lps]['DATE_FORMAT(entry_date, "%Y-%m-%d")']){
        $day_old_pay[0] = $day_old_pay[0] + $day_chart[$lps++]["SUM(income) - SUM(expenses)"];
        $what_balance_day_res = $what_balance_day_res.$day_old_pay[0] .',';
      }else{
        $what_balance_day_res = $what_balance_day_res.$day_old_pay[0].',';
      }
    }
?>


<script>
  if ( day_Chart) {
    day_Chart.destroy();
  }
  var how_many_days = <?php echo "'".$how_many_days."px'"; ?>;
  document.getElementById('day_chart_Container').style.maxWidth = '';
  document.getElementById('day_chart_Container').style.minWidth = '100%';
  document.getElementById('day_chart_Container').style.width = how_many_days;
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