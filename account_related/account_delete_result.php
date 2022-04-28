<?php
  session_start();
  if( empty($_SESSION['id'])) {
    header('Location: /balance_record/account_related/login');exit ;
  }else{
    $account = $_SESSION['id'];
  }
?>
<?php
  //アカウント情報削除
    try{
      $pdo    = new PDO('mysql:host=localhost; dbname=name; charset=utf8mb4', 'user', 'pass');
      $stmt   = $pdo->prepare("DELETE FROM user_id_password WHERE id = :account");
      $stmt   ->bindValue(':account', $account);
      $stmt   ->execute();
      $pdo    = null;
      $stmt   = null;
    }catch (PDOException $pdo_connect_error){
      header('Location: /balance_record/error'); exit();
    }
  //アカウントの収入支出データ削除
    try{
      $pdo    = new PDO('mysql:host=localhost; dbname=name; charset=utf8mb4', 'user', 'pass');
      $stmt   = $pdo->prepare("DELETE FROM expenditure WHERE account = :account");
      $stmt   ->bindValue(':account', $account);
      $stmt   ->execute();
      $pdo    = null;
      $stmt   = null;
    }catch (PDOException $pdo_connect_error){
      header('Location: /balance_record/error'); exit();
    }
    $_SESSION = array();
    session_destroy();
    $result_text = "<p>アカウントを削除しました。</p><p>ご利用ありがとうございました。</p>";
    $url_1       = "<a href='homeURL'>ポートフォリオへ</a></p>";
    $url_2       = "<a href='login'>ログイン画面へ</a></p>";
?>

<?php include( dirname(__FILE__).'/../header.php' ); ?>
  <div id = "account_delete_result_explanation">
    <?php echo $result_text ;?>
  </div>
  <div id = "account_delete_result_wrap">
    <?php echo $url_1 ;?>
    <?php echo $url_2 ;?>
  </div>
<?php include( dirname(__FILE__).'/../footer.php' ); ?>