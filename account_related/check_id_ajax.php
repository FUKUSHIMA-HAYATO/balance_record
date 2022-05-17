<?php
  if ($_POST['check']) {
    try {
      $check_id= $_POST['check'];
      $pdo     = new PDO('mysql:host=localhost; dbname=dbname; charset=utf8', 'user', 'pass');
      $stmt    = $pdo->prepare("SELECT id FROM user_id_password WHERE id = ? LIMIT 1");
      $stmt    ->bindValue(1, $check_id);
      $stmt    ->execute();
      $id_Comp = $stmt->fetch();
      $pdo     = null;
    } catch (PDOException $pdo_connect_error) {
      $flag = "4";
      echo json_encode($flag);
      exit();
    }
  }

  if($_POST['check'] == "check73948"){
    $flag = "2";
  }elseif(!$id_Comp){
    $flag = "0";
  }elseif($id_Comp){
    $flag = "1";
  }
  if(preg_match("/^[a-zA-Z0-9_]+$/", $_POST['check'])){
  } else {
    $flag = "3";
  }
  echo json_encode($flag);
?>