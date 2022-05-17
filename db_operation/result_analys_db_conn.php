<?php
  //残高グラフ(月別)
    //グラフ描画のため指定期間より前の残高データ抽出
    try {
      $old_start_month_chart = date('Y-m-d',strtotime('2020-01-01'));
      $old_end_month_chart   = date("Y-m-d", strtotime('first day of'));
      $old_end_month_chart   = date('Y-m-d',strtotime($old_end_month_chart.'last day of -6 month'));
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
      header('Location: /balance_record/error'); exit();
    }
    //グラフ描画のため指定期間の残高データ抽出
    try {
      $start_month_chart = date("Y-m-d", strtotime('first day of'));
      $start_month_chart = date('Y-m-d', strtotime($start_month_chart.'-5 month'));
      $end_month_chart   = date('Y-m-d');
      $pdo               = new PDO($dsn, $user, $password);
      $month_chart       = $pdo->prepare("SELECT SUM(income) - SUM(expenses) as total_amount , DATE_FORMAT(entry_date, '%Y-%m') as entry_date
                                          FROM  expenditure
                                          WHERE account = :account  AND  entry_date
                                          BETWEEN :start_day AND :end_day
                                          GROUP BY DATE_FORMAT(entry_date, '%Y-%m')");
      $month_chart       ->bindValue(':account'  , $user_name);
      $month_chart       ->bindValue(':start_day', $start_month_chart);
      $month_chart       ->bindValue(':end_day'  , $end_month_chart  );
      $month_chart       ->execute();
      $month_chart       = $month_chart->fetchAll(PDO::FETCH_ASSOC);
      $pdo               = null;
    } catch (PDOException $pdo_connect_error) {
      header('Location: /balance_record/error'); exit();
    }
  //残高グラフ(日別)
    //グラフ描画のため指定期間より前の残高データ抽出
    try {
      $old_start_day_chart = date('Y-m-d',strtotime('2020-01-01'));
      $old_end_day_chart   = date('Y-m-d', strtotime('-7 day'));
      $pdo                   = new PDO($dsn, $user, $password);
      $day_old_pay         = $pdo->prepare("SELECT SUM(income) - SUM(expenses)
                                            FROM   expenditure
                                            WHERE  account= :account AND entry_date
                                            BETWEEN :start AND :end");
      $day_old_pay ->bindValue(':account',$user_name);
      $day_old_pay ->bindValue(':start'  ,$old_start_day_chart);
      $day_old_pay ->bindValue(':end'    ,$old_end_day_chart);
      $day_old_pay ->execute();
      $day_old_pay = $day_old_pay->fetchAll(PDO::FETCH_COLUMN);
      $pdo         = null;
      if($day_old_pay[0] == NULL){//過去の合計値に値がなかった場合「0」を代入
        $day_old_pay[0] = '0';
      }
    } catch (PDOException $pdo_connect_error) {
      header('Location: /balance_record/error'); exit();
    }
    //グラフ描画のため指定期間の残高データ抽出
    try {
      $start_day_chart = date('Y-m-d', strtotime('-6 day'));
      $end_day_chart   = date('Y-m-d');
      $pdo             = new PDO($dsn, $user, $password);
      $day_chart       = $pdo->prepare("SELECT SUM(income) - SUM(expenses) as total_amount , DATE_FORMAT(entry_date, '%Y-%m-%d') as entry_date
                                        FROM  expenditure
                                        WHERE account = :account  AND  entry_date
                                        BETWEEN :start_day AND :end_day
                                        GROUP BY DATE_FORMAT(entry_date, '%Y-%m-%d')");
      $day_chart ->bindValue(':account'  , $user_name);
      $day_chart ->bindValue(':start_day', $start_day_chart);
      $day_chart ->bindValue(':end_day'  , $end_day_chart  );
      $day_chart ->execute();
      $day_chart = $day_chart->fetchAll(PDO::FETCH_ASSOC);
      $pdo       = null;
    } catch (PDOException $pdo_connect_error) {
      header('Location: /balance_record/error'); exit();
    }
  //カテゴリー別収入支出(月別)
    try {
      $start_month    = date('Y-m-d', strtotime('first day of ' . date('Y-m-d', strtotime('-5 month'))));
      $end_month      = date('Y-m-d');
      $pdo            = new PDO($dsn, $user, $password);
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
      $pdo            = null;
    } catch (PDOException $pdo_connect_error) {
      header('Location: /balance_record/error'); exit();
    }
  //カテゴリー別収入支出(日別)
    try {
      $start_day    = date('Y-m-d', strtotime('-6 day'));
      $end_day      = date('Y-m-d');
      $pdo          = new PDO($dsn, $user, $password);
      $day_category = $pdo->prepare('SELECT SUM(income) - SUM(expenses), category, MAX(entry_date)
                                   FROM  expenditure
                                   WHERE account = :account  AND  entry_date BETWEEN :start_day AND :end_day
                                   GROUP BY category
                                   ORDER BY SUM(income) - SUM(expenses) ASC');
      $day_category ->bindValue(':account'  , $user_name);
      $day_category ->bindValue(':start_day', $start_day);
      $day_category ->bindValue(':end_day'  , $end_day  );
      $day_category ->execute();
      $day_category = $day_category->fetchAll(PDO::FETCH_ASSOC);
      $pdo          = null;
    } catch (PDOException $pdo_connect_error) {
      header('Location: /balance_record/error'); exit();
    }
  //高額商品ランキング(月別)
    try {
      $start_month = date('Y-m-d', strtotime('first day of ' . date('Y-m-d', strtotime('-5 month'))));
      $end_month   = date('Y-m-d');
      $pdo         = new PDO($dsn, $user, $password);
      $month_hpi   = $pdo->prepare('SELECT income - expenses, the_name,entry_date
                                    FROM  expenditure
                                    WHERE account = :account  AND  entry_date
                                    BETWEEN :start_day AND :end_day
                                    ORDER BY income - expenses ASC
                                    LIMIT 10');
      $month_hpi ->bindValue(':account'  , $user_name);
      $month_hpi ->bindValue(':start_day', $start_month);
      $month_hpi ->bindValue(':end_day'  , $end_month  );
      $month_hpi ->execute();
      $month_hpi = $month_hpi->fetchAll(PDO::FETCH_ASSOC);
      $pdo       = null;
    } catch (PDOException $pdo_connect_error) {
      header('Location: /balance_record/error'); exit();
    }
  //高額商品ランキング(日別)
    try {
      $start_day = date('Y-m-d', strtotime('-6 day'));
      $end_day   = date('Y-m-d');
      $pdo       = new PDO($dsn, $user, $password);
      $day_hpi   = $pdo->prepare('SELECT income - expenses, the_name, entry_date
                                  FROM  expenditure
                                  WHERE account = :account  AND  entry_date
                                  BETWEEN :start_day AND :end_day
                                  ORDER BY income - expenses ASC
                                  LIMIT 10');
      $day_hpi ->bindValue(':account'  , $user_name);
      $day_hpi ->bindValue(':start_day', $start_day);
      $day_hpi ->bindValue(':end_day'  , $end_day  );
      $day_hpi ->execute();
      $day_hpi = $day_hpi->fetchAll(PDO::FETCH_ASSOC);
      $pdo     = null;
    } catch (PDOException $pdo_connect_error) {
      header('Location: /balance_record/error'); exit();
    }
?>