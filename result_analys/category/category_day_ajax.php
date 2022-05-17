<?php
  session_start();
  $dsn       = 'mysql:host=localhost; dbname=dbname; charset=utf8';
  $user      = 'user';
  $password  = 'pass';
  $user_name = $_SESSION['id'];
  $start_day = $_POST['day_start'];
  $end_day   = $_POST['day_end'];

  try {
    $pdo = new PDO($dsn, $user, $password);
    $day_chart = $pdo->prepare('SELECT SUM(income) - SUM(expenses), category, MAX(entry_date)
                                 FROM  expenditure
                                 WHERE account = :account  AND  entry_date BETWEEN :start_day AND :end_day
                                 GROUP BY category
                                 ORDER BY SUM(income) - SUM(expenses) ASC');
    $day_chart ->bindValue(':account'  , $user_name);
    $day_chart ->bindValue(':start_day', $start_day);
    $day_chart ->bindValue(':end_day'  , $end_day  );
    $day_chart ->execute();
    $day_chart = $day_chart->fetchAll(PDO::FETCH_ASSOC);
    $pdo = null;
  } catch (PDOException $pdo_connect_error) {
    exit();
  }

  foreach($day_chart as $value_log){
    $list[] = [
      "category"   => $value_log["category"],
      "payment"    => $value_log["SUM(income) - SUM(expenses)"],
      "entry_date" => $value_log["MAX(entry_date)"]
    ];
  }
  header("Content-type: application/json; charset=UTF-8");
  echo json_encode($list);
  exit;
?>