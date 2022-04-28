<?php
  session_start();
  $dsn       = 'mysql:host=localhost; dbname=name ; charset=utf8mb4';
  $user      = 'user';
  $password  = 'pass';
  $user_name = $_SESSION['id'];
  $start_day = $_POST['day_start'];
  $end_day   = $_POST['day_end'];

  try {
    $pdo     = new PDO($dsn, $user, $password);
    $day_hpi = $pdo->prepare('SELECT income - expenses, the_name, entry_date
                                 FROM  expenditure
                                 WHERE account = :account  AND  entry_date
                                 BETWEEN :start_day AND :end_day
                                 ORDER BY income - expenses ASC');
    $day_hpi ->bindValue(':account'  , $user_name);
    $day_hpi ->bindValue(':start_day', $start_day);
    $day_hpi ->bindValue(':end_day'  , $end_day  );
    $day_hpi ->execute();
    $day_hpi = $day_hpi->fetchAll(PDO::FETCH_ASSOC);
    $pdo     = null;
  } catch (PDOException $pdo_connect_error) {
    exit();
  }

  foreach($day_hpi as $value_log){
    if($value_log["income - expenses"] < 0){
      $list[] = [
        "the_name"   => $value_log["the_name"],
        "payment"    => $value_log["income - expenses"],
        "entry_date" => $value_log["entry_date"]
      ];
    }
  }
  header("Content-type: application/json; charset=UTF-8");
  echo json_encode($list);
  exit;
?>