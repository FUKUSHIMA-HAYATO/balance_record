<?php
  session_start();
  $dsn         = 'mysql:host=localhost; dbname=dbname; charset=utf8';
  $user        = 'user';
  $password    = 'pass';
  $user_name   = $_SESSION['id'];
  $start_month = date('Y-m-d', strtotime('first day of ' . $_POST['month_start']));
  $end_month   = date('Y-m-d', strtotime('last day of  ' . $_POST['month_end']));

  try {
    $pdo = new PDO($dsn, $user, $password);
    $month_category = $pdo->prepare('SELECT SUM(income) - SUM(expenses), category, MAX(entry_date)
                                     FROM  expenditure
                                     WHERE account = :account  AND  entry_date BETWEEN :start_day AND :end_day
                                     GROUP BY category
                                     ORDER BY SUM(income) - SUM(expenses) ASC');
    $month_category ->bindValue(':account'  , $user_name);
    $month_category ->bindValue(':start_day', $start_month);
    $month_category ->bindValue(':end_day'  , $end_month  );
    $month_category ->execute();
    $month_category = $month_category->fetchAll(PDO::FETCH_ASSOC);
    $pdo = null;
  } catch (PDOException $pdo_connect_error) {
    exit();
  }

  foreach($month_category as $value_log){
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