<?php
  session_start();
  if( empty($_SESSION['id'])) {
    header('Location: /balance_record/account_related/login');exit ;
  }
?>

<?php include( dirname(__FILE__).'/../header.php' ); ?>

  <div id = "account_delete_explanation">
    <p>アカウントを削除しますと、記入したデータは全て失われます。</p>
    <p>よろしければ実行ボタンを押してください。</p>
  </div>
  <div id   = "account_delete_wrap">
    <a href = "../home">戻る</a></p>
    <a href = "account_delete_result">実行</a></p>
  </div>
<?php include( dirname(__FILE__).'/../footer.php' ); ?>