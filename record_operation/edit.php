<?php
  session_start();
  if( empty($_SESSION['id'])) {
    header('Location: /balance_record/account_related/login');exit ;
  }
  if( !empty($_POST['btn_edit_cancel'])){
    header('Location: /balance_record/home');exit ;}
?>
<?php
  $dsn       = 'mysql:host=localhost; dbname=name ; charset=utf8mb4';
  $user      = 'user';
  $password  = 'pass';
  $user_name = $_SESSION['id'];
?>
<?php
  if(!empty($_POST["btn_submit_edit"])){

    if( !empty($_POST['entry_date'])){//日付け
      $clean['entry_date'] = $_POST['entry_date'];
    }else{
      $_SESSION['error_message'][] = '・日付けを入力してください。';
    }

    if( !empty($_POST['the_name'])){//商品名
      $clean['the_name'] = htmlspecialchars( $_POST['the_name'], ENT_QUOTES, 'UTF-8');
    }

    if( !empty($_POST['category']) ) {//カテゴリ
      $clean['category'] = htmlspecialchars( $_POST['category'], ENT_QUOTES, 'UTF-8');
    }else{
      $_SESSION['error_message'][] = '・カテゴリーを選択してください。';
    }

    if( !empty($_POST['expenses'])){//支出
      $clean['expenses'] = $_POST['expenses'];
    }elseif(empty($_POST['expenses'])){
      $clean['expenses'] = 0;
    }

    if( !empty($_POST['income'])){//収入
      $clean['income'] = $_POST['income'];
    }elseif(empty($_POST['income'])){
      $clean['income'] = 0;
    }

    if( !empty($_POST['message']) ) {//メッセージ
      $clean['message'] = htmlspecialchars( $_POST['message'], ENT_QUOTES, 'UTF-8');
    }else{
      $clean['message'] = " ";
    }

    if(empty($_SESSION['error_message'])){
      try {
        $pdo = new PDO($dsn, $user, $password);
        $edit_date = $pdo->prepare('UPDATE  expenditure
                                    SET     entry_date = :entry_date,
                                            the_name   = :the_name,
                                            income     = :income,
                                            expenses   = :expenses,
                                            auto_date  = :auto_date,
                                            message    = :message,
                                            category   = :category
                                    WHERE   auto_number= :id');
        $edit_date ->bindValue(':entry_date',$clean['entry_date']);
        $edit_date ->bindValue(':the_name',  $clean['the_name']);
        $edit_date ->bindValue(':income',    $clean['income']);
        $edit_date ->bindValue(':expenses',  $clean['expenses']);
        $edit_date ->bindValue(':auto_date', $clean['auto_date']);
        $edit_date ->bindValue(':message',   $clean['message']);
        $edit_date ->bindValue(':category',  $clean['category']);
        $edit_date ->bindValue(':id',        $_POST['auto_number']);
        $edit_date_res = $edit_date ->execute();
        $pdo = null;
      } catch (PDOException $pdo_connect_error) {
        header('Location: /balance_record/error'); exit();
      }
    }
    if($edit_date_res){
      $_SESSION['success_message'][] = '編集が完了しました。';
      unset($_POST);
    }else{
      $_SESSION['error_message'][] = '編集に失敗しました。';
    }
    header('Location: /balance_record/home');exit ;
  }
?>

<?php include( dirname(__FILE__).'/../header.php' ); ?>

  <!-- カテゴリー読み込み-->
    <?php
      try {
        $pdo = new PDO($dsn, $user, $password);
        $slc_categorys = $pdo->prepare('SELECT * FROM categorys  ');
        $slc_categorys ->execute();
        $slc_categorys = $slc_categorys->fetchAll(PDO::FETCH_ASSOC);
        $pdo     = null;
      } catch (PDOException $pdo_connect_error) {
        header('Location: /balance_record/error'); exit();
      }
    ?>
  <!-- /カテゴリー読み込み-->

  <!-- 編集用データ読み込み-->
    <?php
      try {
        $get_id = (int)htmlspecialchars($_GET['get_id'], ENT_QUOTES);
        $pdo = new PDO($dsn, $user, $password);

        $edit_data = $pdo->prepare("SELECT *
                                    FROM   expenditure
                                    WHERE  auto_number = :id");
        $edit_data ->bindValue(':id',$get_id);
        $edit_data ->execute();
        $edit_data =$edit_data->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
      } catch (PDOException $pdo_connect_error) {
        header('Location: /balance_record/error'); exit();
      }
    ?>
  <!-- /編集用データ読み込み-->

  <!-- 収支表示-->
    <div id="edit_log_wrap">
      <div class="edit_text">
        <p >下記ログを編集します。</p>
        <p >入力内容を確認の上[編集]ボタンを押してください。</p>
      </div>
      <?php if( !empty($edit_data) ): ?>
        <?php foreach( $edit_data as $record_value ): ?>
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
      <!-- /収支表示-->

      <!-- 入力欄-->
      <div id="entry_payments_container_phone">
        <form method="post" autocomplete="off">
          <div class="form_box_container">
            <label for="entry_date" class="label-danger">必須</label>
            <label for="entry_date" class="label-text">日付け</label>
            <input id ="entry_date" class="datepicker-here danger_frame" type="text" name="entry_date" data-language="jp" data-position='bottom left'value="<?php if( !empty($edit_data[0]['entry_date']) ){ echo $edit_data[0]['entry_date']; }else{echo date('Y-m-d');} ?>" readonly="readonly"/>
          </div>

          <div class="form_box_container">
            <label    for="message" class="label-danger">必須</label>
            <label    for="message" class="label-text">カテゴリ</label>
            <select class="category" name="category" >
              <option value = <?php echo $edit_data[0]['category'];?>><?php echo $edit_data[0]['category'];?></option>
              <?php foreach($slc_categorys as $value_categorys){?>
                <option value = "<?php echo $value_categorys['name'];?>"><?php echo $value_categorys['name'];?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form_box_container">
            <label for="the_name" class="label-freedom">任意</label>
            <label for="the_name" class="label-text">商品名</label>
            <input id ="the_name" class="freedom_frame"type="message" name="the_name" value="<?php if( !empty($edit_data[0]['the_name']) ){ echo $edit_data[0]['the_name']; }?>" />
          </div>

          <div class="form_box_container">
            <label for="income" class="label-freedom">任意</label>
            <label for="income" class="label-text">収入</label>
            <input id ="income" class="freedom_frame"type="number" name="income" value="<?php if( !empty($edit_data[0]['income']) ){ echo $edit_data[0]['income']; }?>" placeholder="無記入の場合0円となります" />
          </div>

          <div class="form_box_container">
            <label for="expenses" class="label-freedom">任意</label>
            <label for="expenses" class="label-text">支出</label>
            <input id ="expenses" class="freedom_frame" type="number" name="expenses" value="<?php if( !empty($edit_data[0]['expenses']) ){ echo $edit_data[0]['expenses']; }?>" placeholder="無記入の場合0円となります" />
          </div>

          <div class="form_box_container">
            <label    for="message" class="label-freedom">任意</label>
            <label    for="message" class="label-text">コメント</label>
	          <textarea id ="message" type="text" class="message" name="message"><?php if( !empty($edit_data[0]['message']) ){ echo $edit_data[0]['message']; } ?></textarea>
          </div>

          <input type="hidden" name="auto_number" value="<?php echo $edit_data[0]['auto_number'];?>">
          <input id="edit_btn_cancel" type="submit" name="btn_edit_cancel"value="中止"><input id="edit_btn" type="submit" name="btn_submit_edit" value="編集">
        </form>
      </div>
    </div>
  <!-- /入力欄-->

  <?php include( dirname(__FILE__).'/../footer.php' ); ?>