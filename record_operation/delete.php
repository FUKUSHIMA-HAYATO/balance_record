<?php
  session_start();
  if( empty($_SESSION['id'])) {
    header('Location: /balance_record/account_related/login');exit ;
  }
  if( !empty($_POST['btn_delete_cancel'])){
    header('Location: /balance_record/home');exit ;}
?>
<?php
  $dsn       = 'mysql:host=localhost; dbname=dbname; charset=utf8';
  $user      = 'user';
  $password  = 'pass';
  $user_name = $_SESSION['id'];
?>
<?php
  if( !empty($_POST['btn_delete']) ) {
    try {
      $post_id = (int)htmlspecialchars($_POST['post_id'], ENT_QUOTES);
      $pdo = new PDO($dsn, $user, $password);
      $slc_day1 = $pdo->prepare(" DELETE FROM expenditure
                                  WHERE auto_number = :post_id");
      $slc_day1 ->bindValue(':post_id',$post_id);
      $slc_day1 ->execute();
      $slc_day2 =$slc_day1->fetchAll(PDO::FETCH_ASSOC);
      $pdo = null;
      $_SESSION['success_message'][] = '削除が完了しました。';
    } catch (PDOException $pdo_connect_error) {
      header('Location: /balance_record/error'); exit();
    }
    header('Location: /balance_record/home');exit ;
  }
?>
<?php
  //削除用データ読み込みmysql
    if( !empty($_GET['get_id']) && empty($_POST['post_id'])){
      try {
        $get_id = (int)htmlspecialchars($_GET['get_id'], ENT_QUOTES);
        $pdo = new PDO($dsn, $user, $password);
        $delete_data = $pdo->prepare("SELECT *
                                      FROM   expenditure
                                      WHERE  auto_number = :id");
        $delete_data ->bindValue(':id',$get_id);
        $delete_data ->execute();
        $delete_data =$delete_data->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
      } catch (PDOException $pdo_connect_error) {
        header('Location: /balance_record/error'); exit();
      }
    }
  //削除用データ読み込みmysql[END]
?>

<?php include( dirname(__FILE__).'/../header.php' ); ?>
  <?//レコード表示?>
    <div id="delete_log_wrap">
      <div class="delete_text">
        <p >下記データを削除します。</p>
        <p >よろしければ[削除]ボタンを押してください。</p>
      </div>
      <?php if( !empty($delete_data) ): ?>
        <?php foreach( $delete_data as $record_value ): ?>
          <div class="log_container">
            <div class="log_payment">
              <?php if($record_value['income'] - $record_value['expenses'] < 0): ?>
                <div class = "num_red"><?php echo "¥",$record_value['income'] - $record_value['expenses']; ?></div>
              <?php elseif($record_value['income'] - $record_value['expenses'] >= 0): ?>
                <div class = "num_black"><?php echo "¥",$record_value['income'] - $record_value['expenses']; ?></div>
              <?php endif; ?>
            </div>
            <div class="log_name">
              <div class="log_name_2">
                <?php echo nl2br($record_value['the_name']); ?>
              </div>
            </div>
            <div class="log_text">
              <div class="log_text_2">
                <?php echo nl2br($record_value['message']); ?>
              </div>
            </div>
            <div class="log_category">
              <i class="fas fa-tag"></i><?php if($record_value['category'] == "0"){echo "未選択";}else{echo $record_value['category'];}?>
            </div>
            <div class="log_date">
              <i class="fas fa-fist-raised"></i><?php echo date('Y/m/d', strtotime($record_value['entry_date'])); ?>
            </div>
            <div class="swiper_delete_btn">
              <div class="delete-btn"><i class="fas fa-trash-alt"></i></div>
            </div>
            <div class="swiper_edit_btn">
              <div class="edit-btn"  ><i class="fas fa-edit"></i></div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

  <?//レコード表示[END]?>
  <form method="post">
    <input id="delete_btn_cancel" type="submit" name="btn_delete_cancel"value="中止"><input id="delete_btn" type="submit" name="btn_delete" value="削除">
    <input type="hidden" name="post_id" value="<?php echo $record_value['auto_number'];?>">
	</form>
<?php include( dirname(__FILE__).'/../footer.php' ); ?>