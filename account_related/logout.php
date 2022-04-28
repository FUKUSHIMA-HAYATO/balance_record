<?php session_start(); ?>
<?php include( dirname(__FILE__).'/../header.php' ); ?>
  <?php
    $_SESSION = array();
    session_destroy();
  ?>
  <div id = "logout_explanation">
    <p>Thank you for using!!</p>
  </div>
  <div id = "logout_wrap">
    <a href="homeURL">ポートフォリオへ</a></p>
    <a href="/balance_record/account_related/login">ログイン画面へ</a></p>
  </div>
<?php include( dirname(__FILE__).'/../footer.php' ); ?>