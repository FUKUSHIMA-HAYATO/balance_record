<?php
  //新規レコード書き込み用sql
    if(!empty($_POST["entry_btn"])){
      $token_post    = $_POST["token_post"];
      $token_session = $_SESSION["token_session"];
      unset($_SESSION["token_session"]);
      if($token_post === $token_session){
        //日付け
          if( !empty($_POST['entry_date'])){
            $clean['entry_date'] = $_POST['entry_date'];
          }else{
            $_SESSION['error_message'][] = '・日付けを入力してください。';
          }
        //商品名
          if( !empty($_POST['the_name'])){
            $clean['the_name'] = htmlspecialchars( $_POST['the_name'], ENT_QUOTES);
          }
        //支出
          if( !empty($_POST['expenses'])){
            $clean['expenses'] = $_POST['expenses'];
          }elseif(empty($_POST['expenses'])){
            $clean['expenses'] = 0;
          }
        //収入
          if( !empty($_POST['income'])){
            $clean['income'] = $_POST['income'];
          }elseif(empty($_POST['income'])){
            $clean['income'] = 0;
          }
        //メッセージ
          if( !empty($_POST['message']) ) {
            $clean['message'] = htmlspecialchars( $_POST['message'], ENT_QUOTES);
          }else{
            $clean['message'] = " ";
          }
        //カテゴリ
          if( !empty($_POST['category']) ) {
            $clean['category'] = htmlspecialchars($_POST['category'], ENT_QUOTES);
          }else{
            $_SESSION['error_message'][] = '・カテゴリーを選択してください。';
          }
          if(empty($_SESSION['error_message'])){
            try {
              $pdo = new PDO($dsn, $user, $password);
              $now_date   = date("Y-m-d H:i:s");
              $Create = $pdo->prepare("INSERT INTO expenditure ( account, entry_date, the_name, income, expenses, category, message, auto_date)
                                                        VALUES (:account,:entry_date,:the_name,:income,:expenses,:category,:message,:auto_date)");
              $Create->bindValue(':account',   $user_name);
              $Create->bindValue(':entry_date',$clean['entry_date']);
              $Create->bindValue(':the_name',  $clean['the_name']);
              $Create->bindValue(':income',    $clean['income']);
              $Create->bindValue(':expenses',  $clean['expenses']);
              $Create->bindValue(':category',  $clean['category']);
              $Create->bindValue(':message',   $clean['message']);
              $Create->bindValue(':auto_date', $now_date);
              $Create_res = $Create->execute();
              $pdo = null;
            } catch (PDOException $pdo_connect_error) {
              header('Location: /balance_record/error'); exit();
            }
            if($Create_res){
              $_SESSION['success_message'][] = '書き込みに成功しました。';
              unset($_POST);
            }else{
              $_SESSION['error_message'][] = '書き込みに失敗しました。';
            }
          }
        }else{
          $_SESSION['error_message'][] = 'トークンの有効期限切れです。';
          unset($_POST);
        }
    }
  //新規レコード書き込み用sql[END]

  //カテゴリー読み込みsql
    try {
      $pdo = new PDO($dsn, $user, $password);
      $slc_categorys = $pdo->prepare('SELECT name FROM categorys');
      $slc_categorys ->execute();
      $slc_categorys = $slc_categorys->fetchAll(PDO::FETCH_ASSOC);
      $pdo     = null;
    } catch (PDOException $pdo_connect_error) {
      header('Location: /balance_record/error'); exit();
    }
  //カテゴリー読み込みsql[END]

  //各データ記述用sql
    try {
      $pdo          = new PDO($dsn, $user, $password);
      $sta_log_date = date('Y-m-d',strtotime('2020-01-01'));
      $now_log_date = date('Y-m-d');
      $record_log   = $pdo->prepare('SELECT auto_number,account,entry_date,the_name,income,expenses,category,message
                                     FROM expenditure
                                     WHERE account = :user_name AND entry_date
                                     BETWEEN :start AND :end
                                     ORDER BY entry_date DESC, auto_number DESC
                                     ');
      $record_log   ->bindValue(':user_name', $user_name);
      $record_log   ->bindValue(':start'    , $sta_log_date);
      $record_log   ->bindValue(':end'      , $now_log_date);
      $record_log   ->execute();
      $record_log   = $record_log->fetchAll(PDO::FETCH_ASSOC);
      $pdo          = null;
    } catch (PDOException $pdo_connect_error) {
      header('Location: /balance_record/error'); exit();
    }
    foreach($record_log as $value_log){
      $total_income   = $total_income   + $value_log['income'];
      $total_expenses = $total_expenses + $value_log['expenses'];
    }
    $total_balance  = $total_income - $total_expenses;
  //各データ記述用sql[END]
?>
