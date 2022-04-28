<?php
  session_start();
  $new_create_id   = $_POST['new_create_id'];
  $new_create_pass = password_hash($_POST['new_create_pass'], PASSWORD_DEFAULT);
  $now_date        = date("Y-m-d H:i:s");

  if ($_POST['new_create_id']) {
    try {
      $pdo    = new PDO('mysql:host=localhost; dbname=name; charset=utf8mb4', 'user', 'pass');
      $stmt   = $pdo->prepare("SELECT id FROM user_id_password WHERE id = :search_id LIMIT 1");
      $stmt   ->bindValue(':search_id', $new_create_id);
      $stmt   ->execute();
      $member = $stmt->fetch();
    } catch (PDOException $pdo_connect_error) {
      header('Location: /balance_record/error'); exit();
    }
    if($member['id'] === $new_create_id) {
      $msg       = '申し訳ございません登録が完了できませんでした。';
      $link      = "creation";
      $link_text ='戻る';
    } else {
      $stmt      = $pdo->prepare("INSERT INTO user_id_password(id, pass, date) VALUES (:create_id, :create_pass, :create_date)");
      $stmt      ->bindValue(':create_id'  , $new_create_id);
      $stmt      ->bindValue(':create_pass', $new_create_pass);
      $stmt      ->bindValue(':create_date', $now_date);
      $stmt      ->execute();
      $msg       = '会員登録が完了しました';
      $link      = "login";
      $link_text ='ログインページへ';
      $pdo       = null;
    }
  }
?>

<?php include( dirname(__FILE__).'/../header.php' ); ?>
  <div id="creation_result_wrap">
    <h1><?php echo $msg; ?></h1>
    <a href="<?php echo $link; ?>"><?php echo $link_text ?></a>
  </div>
<?php include( dirname(__FILE__).'/../footer.php' ); ?>