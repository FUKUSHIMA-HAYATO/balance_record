<?php
  session_start();
  $dsn       = 'mysql:host=localhost; dbname=dbname; charset=utf8';
  $user      = 'user';
  $password  = 'pass';
  $user_name = $_SESSION['id'];
  $day_start = $_POST['day_start'];
  $day_end   = $_POST['day_end'];
?>

<?php
  try {
    $pdo = new PDO($dsn, $user, $password);
    $record_log = $pdo->prepare('SELECT *
                                 FROM expenditure
                                 WHERE account = :account  AND  entry_date
                                 BETWEEN :start_day AND :end_day
                                 ORDER BY entry_date DESC, auto_number DESC');
    $record_log ->bindValue(':account', $user_name);
    $record_log ->bindValue(':start_day'  , $day_start);
    $record_log ->bindValue(':end_day'    , $day_end);
    $record_log ->execute();
    $record_log = $record_log->fetchAll(PDO::FETCH_ASSOC);
    $pdo    = null;
  } catch (PDOException $pdo_connect_error) {
    header('Location: /balance_record/error'); exit();
  }
  foreach($record_log as $value_log){
    $total_income   = $total_income   + $value_log['income'];
    $total_expenses = $total_expenses + $value_log['expenses'];
  }
  $total_balance  = $total_income - $total_expenses;
?>

<?php if($total_balance < 0): ?>
  <div id = "deposit_red">
    <p><?php echo "¥".$total_balance; ?></p>
  </div>
<?php elseif($total_balance >= 0): ?>
  <div id = "deposit_black">
    <p><?php echo "¥".$total_balance; ?></p>
  </div>
<?php endif; ?>

<?php if( !empty($record_log) ): ?>
  <?php foreach( $record_log as $record_value ): ?>
    <div class="log_container">
      <div class="log_payment">
        <?php if($record_value['income'] - $record_value['expenses'] < 0): ?>
          <h2 class = "num_red"><?php echo "¥",$record_value['income'] - $record_value['expenses']; ?></h2>
        <?php elseif($record_value['income'] - $record_value['expenses'] >= 0): ?>
          <h2 class = "num_black"><?php echo "¥",$record_value['income'] - $record_value['expenses']; ?></h2>
        <?php endif; ?>
      </div>
      <div class="log_name">
        <div class="log_name_2">
          <i><?php echo nl2br($record_value['the_name']); ?></i>
        </div>
      </div>
      <div class="log_text">
        <div class="log_text_2">
          <i><?php echo nl2br($record_value['message']); ?></i>
        </div>
      </div>
      <div class="log_category">
        <i class="fas fa-tag"></i><?php if($record_value['category'] == "0"){echo "未選択";}else{echo $record_value['category'];}?>
      </div>
      <div class="log_date">
        <i class="fas fa-fist-raised"></i><?php echo date('Y/m/d', strtotime($record_value['entry_date'])); ?>
      </div>
      <div class="swiper_delete_btn">
        <p class="delete-btn"><a href="/balance_record/record_operation/delete?get_id=<?php echo $record_value['auto_number']; ?>"><i class="fas fa-trash-alt"></i></a>
      </div>
      <div class="swiper_edit_btn">
        <p class="edit-btn"  ><a href="/balance_record/record_operation/edit?get_id=<?php echo $record_value['auto_number']; ?>"><i class="fas fa-edit"></i></a>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>