<?php session_start(); ?>
<?php
  $search_id = $_POST['access_id'];
  try{
    $pdo    = new PDO('mysql:host=localhost; dbname=name; charset=utf8mb4', 'user', 'pass');
    $stmt   = $pdo->prepare("SELECT id,pass FROM user_id_password WHERE id = :search_id");
    $stmt   ->bindValue(':search_id', $search_id);
    $stmt   ->execute();
    $member = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }catch (PDOException $pdo_connect_error){
    header('Location: /balance_record/error'); exit();
  }

  //パスワードとハッシュ値が一致しているか確認
  if (password_verify($_POST['access_pass'], $member[0]['pass'])) {
    $_SESSION['id']   = $member[0]['id'];
    $_SESSION['pass'] = $member[0]['pass'];
    header('Location: /balance_record/home');exit ;
  } else {
    $msg       = 'IDもしくはパスワードが間違っています。';
    $link      = "login";
    $link_text = '戻る';
  }
?>
<?php include( dirname(__FILE__).'/../header.php' ); ?>
  <div id="login_result_wrap">
    <h1><?php echo $msg; ?></h1>
    <a href="<?php echo $link; ?>"><?php echo $link_text ?></a>
  </div>
<?php include( dirname(__FILE__).'/../footer.php' ); ?>