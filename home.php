<?php
  session_start();
  if( empty($_SESSION['id'])) {
    header('Location: /balance_record/account_related/login');exit ;
  }
  $dsn       = 'mysql:host=localhost; dbname=dbname; charset=utf8';
  $user      = 'user';
  $password  = 'pass';
  $user_name = $_SESSION['id'];
?>
<?php include("db_operation/home_db_conn.php"); ?>
<?php include("db_operation/result_analys_db_conn.php"); ?>
<?php include( dirname(__FILE__).'/header.php' ); ?>

<?//ハンバーガー(balance_record/js/hamburger.js からjsを読み込んでいます。)?>
  <div class="blance_record_global_menu_container">
    <div class="blance_record_global_menu">
      <p><?php echo '家計簿アプリ(デモ)' ?></p>
    </div>
    <div class="x_mark_button" onclick="hamburger()">
      <div class="x_mark1"></div>
      <div class="x_mark2"></div>
      <div class="x_mark3"></div>
    </div>
    <div class = "hamburger_overlay"></div>
    <div class="hamburgerMenu_container">
      <div class="hamburgerMenu" >
        <div class="hamburgerMenu-box1">
          <ul>
            <li><a href= "http://hayato-study-note.site">ポートフォリオに戻る</a></li>
            <li><a href= "http://hayato-study-note.site/balance_record/account_related/account_delete">アカウント削除</a></li>
          </ul>
        </div>
        <div class="hamburgerMenu-box2">
            <i class="fa-solid fa-user"></i><?php echo $user_name."様 <br />"; ?>
            <i class="fa-solid fa-right-from-bracket"></i><a href="/balance_record/account_related/logout">ログアウト</a>
        </div>
      </div>
    </div>
  </div>
<?//ハンバーガーEND?>

<?//各処理後の画面表示メッセージ?>
  <?//処理成功の場合?>
    <?php if( !empty($_SESSION['success_message']) ): ?>
      <ul class="success_message">
        <?php foreach( $_SESSION['success_message'] as $value ): ?>
          <li><?php echo $value; ?></li>
        <?php endforeach; ?>
      </ul>
      <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
  <?//エラーの場合?>
    <?php if( !empty($_SESSION['error_message']) ): ?>
      <ul class="error_message">
        <?php foreach( $_SESSION['error_message'] as $value ): ?>
          <li><?php echo $value; ?></li>
        <?php endforeach; ?>
      </ul>
      <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
<?//各処理後の画面表示メッセージEND?>

<div id = "analyze_and_home_container">
  <?//各分析結果表示(balance_record/db_operation/result_analys_db_conn.php でDB接続した内容を表示しています。)?>
    <div id="record_analyze" class="tabcontent">
      <?//合計収支?>
        <?php if($total_balance < 0): ?>
          <div id = "deposit_red">
            <p><?php echo "¥".$total_balance; ?></p>
          </div>
        <?php elseif($total_balance >= 0): ?>
          <div id = "deposit_black">
            <p><?php echo "¥".$total_balance; ?></p>
          </div>
        <?php endif; ?>
      <?//チャートグラフ?>
        <div id="chart">
          <div class="chart_title">
            <?php echo "残高グラフ"; ?>
          </div>
          <div class="chart_btn_container">
            <div class="chart_btn">
              <button id="chart_month" class="chart_num" onclick="chart_btn(event, 'month_chart_expense' )">月</button>
              <button id="chart_day"   class="chart_num" onclick="chart_btn(event, 'day_chart_expense')">日</button>
            </div>
          </div>
          <div id="month_chart_expense" class="chart_content">
            <div class="select_chart_month_container">
              <?php   $chart_month_place = date('Y-m',strtotime('-5 month')).','.date('Y-m');?>
              <input  id="chart_month_ajax"class="datepicker-here" type="text" name="entry_date" data-language="jp" data-min-view="months"data-view="months"data-date-format="yyyy-mm" data-position='bottom left' data-range="true"data-multiple-dates-separator="," placeholder="<?php echo $chart_month_place; ?>" readonly="readonly" />
              <button id="chart_month_btn"><i class="far fa-paper-plane"></i></button>
            </div>
            <div id = "chart_result_month">
              <?php include(dirname(__FILE__).'/result_analys/chart/chart_month.php'); ?>
            </div>
          </div>
          <div id="day_chart_expense" class="chart_content">
            <div class="select_chart_day_container">
              <?php $chart_day_place = date('Y-m-d', strtotime('-6 day')).','.date('Y-m-d');?>
              <input  id="chart_day_ajax" class="datepicker-here" type="text" name="entry_date" data-language="jp" data-date-format="yyyy-mm-dd" data-position='bottom left' data-range="true"data-multiple-dates-separator="," placeholder="<?php echo $chart_day_place;?>" readonly="readonly" />
              <button id="chart_day_btn"><i class="far fa-paper-plane"></i></button>
            </div>
            <div id = "chart_result_day">
              <?php include(dirname(__FILE__).'/result_analys/chart/chart_day.php'); ?>
            </div>
          </div>
        </div>
      <?//カテゴリー別収入支出?>
        <div id="category">
          <div class="chart_title">
            <?php echo "カテゴリー別収入支出"; ?>
          </div>
          <div class="category_btn_container">
            <div class="category_btn">
              <button id="category_month" class="category_num" onclick="category_btn(event, 'month_category_expense')">月</button>
              <button id="category_day"   class="category_num" onclick="category_btn(event, 'day_category_expense'  )">日</button>
            </div>
          </div>
          <div id="month_category_expense" class="category_content">
            <div class="select_category_month_container">
              <?php $category_month = date('Y-m',strtotime('-5 month')).','.date('Y-m');?>
              <input  id="category_month_ajax" class="datepicker-here" type="text" name="entry_date" data-language="jp" data-min-view="months"data-view="months"data-date-format="yyyy-mm" data-position='bottom left' data-range="true"data-multiple-dates-separator="," placeholder="<?php echo $category_month;?>" readonly="readonly" />
              <button id="category_month_btn"><i class="far fa-paper-plane"></i></button>
            </div>
            <div id = "category_result_month">
              <div id="ajax_category_month_payment_title">
                <p class="category_name"   >カテゴリー</p>
                <p class="category_payment">金額</p>
                <p class="category_date"   >最終更新日</p>
                <div id="ajax_category_month_payment_container">
                  <?php
                    foreach($month_category as $value_log){
                      echo "<li class='category_name'   >".$value_log['category']."</li>";
                      echo "<li class='category_payment'>".$value_log['SUM(income) - SUM(expenses)']."</li>";
                      echo "<li class='category_date'   >".$value_log['MAX(entry_date)']."</li>";
                    }
                  ?>
                </div>
              </div>
            </div>
          </div>
          <div id="day_category_expense" class="category_content">
            <div class="select_category_day_container">
              <?php $category_day = date('Y-m-d', strtotime('-6 day')).','.date('Y-m-d');?>
              <input  id="category_day_ajax" class="datepicker-here" type="text"  name="entry_date" data-language="jp" data-date-format="yyyy-mm-dd" data-position='bottom left' data-range="true"data-multiple-dates-separator="," placeholder="<?php echo $category_day;?>" readonly="readonly" />
              <button id="category_day_btn"><i class="far fa-paper-plane"></i></button>
            </div>
            <div id = "category_result_day">
              <div id="ajax_category_day_payment_title">
                <p class="category_name"   >カテゴリー</p>
                <p class="category_payment">金額</p>
                <p class="category_date"   >最終更新日</p>
                <ul id="ajax_category_day_payment_container">
                  <?php
                    foreach($day_category as $value_log){
                      echo "<li class='category_name'   >".$value_log['category']."</li>";
                      echo "<li class='category_payment'>".$value_log['SUM(income) - SUM(expenses)']."</li>";
                      echo "<li class='category_date'   >".$value_log['MAX(entry_date)']."</li>";
                    }
                  ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      <?//高額商品ランキング?>
        <div id="high_priced_item">
          <div class="hpi_title">
            <?php echo "高額商品ランキング"; ?>
          </div>
          <div class="hpi_btn_wrap">
              <button id="hpi_month" class="hpi_num" onclick="hpi_btn(event, 'month_hpi_wrap')">月</button>
              <button id="hpi_day"   class="hpi_num" onclick="hpi_btn(event, 'day_hpi_wrap'  )">日</button>
          </div>
          <div id = "month_hpi_wrap" class="hpi_content">
            <div class="month_slct_hpi">
              <?php $hpi_month = date('Y-m', strtotime('first day of ' . date('Y-m', strtotime('-5 month')))).','.date('Y-m');?>
              <input  id="month_hpi_ajax" class="datepicker-here" type="text" name="entry_date" data-language="jp" data-min-view="months"data-view="months"data-date-format="yyyy-mm" data-position='bottom left' data-range="true"data-multiple-dates-separator="," placeholder="<?php echo $hpi_month; ?>" readonly="readonly" />
              <button id="month_hpi_btn"><i class="far fa-paper-plane"></i></button>
            </div>
            <div id = "month_hpi_result">
              <div id="ajax_hpi_month_payment_title">
                <p class="hpi_name"   >商品名</p>
                <p class="hpi_payment">金額</p>
                <p class="hpi_date"   >日付</p>
                <div id="ajax_hpi_month_payment_container">
                  <?php
                    foreach($month_hpi as $value_hpi){
                      if($value_hpi["income - expenses"] < 0){
                        echo "<li class='hpi_name'   >".$value_hpi['the_name']."</li>";
                        echo "<li class='hpi_payment'>".$value_hpi['income - expenses']."</li>";
                        echo "<li class='hpi_date'   >".$value_hpi['entry_date']."</li>";
                      }
                    }
                  ?>
                </div>
              </div>
            </div>
          </div>
          <div id = "day_hpi_wrap" class="hpi_content">
            <div class="day_slct_hpi">
              <?php $hpi_day = date('Y-m-d', strtotime('-6 day')).','.date('Y-m-d');?>
              <input  id="day_hpi_ajax" class="datepicker-here" type="text" name="entry_date" data-language="jp" data-date-format="yyyy-mm-dd" data-position='bottom left' data-range="true"data-multiple-dates-separator="," placeholder="<?php echo $hpi_day;?>" readonly="readonly" />
              <button id="day_hpi_btn"><i class="far fa-paper-plane"></i></button>
            </div>
            <div id = "day_hpi_result">
              <div id="ajax_hpi_day_payment_title">
                <p class="hpi_name"   >商品名</p>
                <p class="hpi_payment">金額</p>
                <p class="hpi_date"   >日付</p>
                <ul id="ajax_hpi_day_payment_container">
                  <?php
                    foreach($day_hpi as $value_hpi){
                      if($value_hpi["income - expenses"] < 0){
                        echo "<li class='hpi_name'   >".$value_hpi['the_name']."</li>";
                        echo "<li class='hpi_payment'>".$value_hpi['income - expenses']."</li>";
                        echo "<li class='hpi_date'   >".$value_hpi['entry_date']."</li>";
                      }
                    }
                  ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
  <?//各分析結果表示END?>

  <?//詳細ログ一覧(balance_record/db_operation/home_db_conn.php でDB接続した内容を表示しています。)?>
    <div id= "record_home" class="tabcontent">
      <input type="text" class="datepicker-here" id="record_home_ajax" name="entry_date" data-language="jp" data-date-format="yyyy-mm-dd" data-position='bottom left' data-range="true"data-multiple-dates-separator="," placeholder="期間選択" readonly="readonly" />
      <button id="record_home_btn"><i class="far fa-paper-plane"></i></button>
      <div class="button-open_br_container">
        <button class="js-open_br button-open_br" data-id="3"><i class="fa-solid fa-plus"></i></button>
      </div>
      <div id = "detail_expense">
        <div id="log" class="log">
          <?php $counter = "0"; ?>
          <?php if( !empty($record_log) ): ?>
            <?php foreach( $record_log as $record_value ): ?>
              <?php if ($counter >= 100) {break;} $counter++;?>
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
                  <div class="delete-btn"><a href="/balance_record/record_operation/delete?get_id=<?php echo $record_value['auto_number']; ?>"><i class="fas fa-trash-alt"></i></a></div>
                </div>
                <div class="swiper_edit_btn">
                  <div class="edit-btn"  ><a href="/balance_record/record_operation/edit?get_id=<?php echo $record_value['auto_number']; ?>"><i class="fas fa-edit"></i></a></div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?//詳細ログ一覧END?>
</div>

<?//レコード新規作成フォーム?>
  <div id="record_entry" class="tabcontent">
    <div id="overlay_br" class="overlay_br"></div>
    <div class="modal-window_br" data-id="modal1">
      <nav class="modal_button_br">
        <div class="Product_close_br"><i class="fa-solid fa-circle-xmark"></i></div>
      </nav>
      <div class="record_entry_br_container">
        <div id="entry_payments_container">
          <div class="entry_title">
            <p >新規作成</p>
          </div>
          <form method="post" autocomplete="off">
            <div class="form_box_container">
              <label for="entry_date" class="label-danger">必須</label>
              <label for="entry_date" class="label-text">日付け</label>
              <input id ="entry_date" class="datepicker-here danger_frame" type="text" name="entry_date" data-language="jp" data-position='bottom left'value="<?php if( !empty($_POST['entry_date']) ){ echo $_POST['entry_date']; }else{echo date('Y-m-d');} ?>" readonly="readonly"/>
            </div>
            <div class="form_box_container">
              <label    for="message" class="label-danger">必須</label>
              <label    for="message" class="label-text">カテゴリ</label>
              <select class="category" name="category" >
                <option value = "">カテゴリー選択</option>
                <?php foreach($slc_categorys as $value_categorys){?>
                  <option value = "<?php echo $value_categorys['name'];?>"><?php echo $value_categorys['name'];?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form_box_container">
              <label for="the_name" class="label-freedom">任意</label>
              <label for="the_name" class="label-text">商品名</label>
              <input id ="the_name" class="freedom_frame"type="message" name="the_name" value="<?php if( !empty($_POST['the_name']) ){ echo $_POST['the_name']; }?>" />
            </div>
            <div class="form_box_container">
              <label for="income" class="label-freedom">任意</label>
              <label for="income" class="label-text">収入</label>
              <input id ="income" class="freedom_frame"type="number" name="income" value="<?php if( !empty($_POST['income']) ){ echo $_POST['income']; }?>" placeholder="無記入の場合0円となります" />
            </div>
            <div class="form_box_container">
              <label for="expenses" class="label-freedom">任意</label>
              <label for="expenses" class="label-text">支出</label>
              <input id ="expenses" class="freedom_frame" type="number" name="expenses" value="<?php if( !empty($_POST['expenses']) ){ echo $_POST['expenses']; }?>" placeholder="無記入の場合0円となります" />
            </div>
            <div class="form_box_container">
              <label    for="message" class="label-freedom">任意</label>
              <label    for="message" class="label-text">コメント</label>
	            <textarea id ="message" type="text" class="message" name="message"><?php if( !empty($_POST['message']) ){ echo $_POST['message']; } ?></textarea>
            </div>
            <?php $token = uniqid('', true); ?>
            <?php $_SESSION['token_session'] = $token; ?>
            <input type="hidden" name="token_post" value="<?php echo $token;?>">
            <input class="entry_btn" type="submit" name="entry_btn" value="送信">
          </form>
        </div>
      </div>
    </div>
  </div>
<?//レコード新規作成フォームEND?>

<?//タブ(balance_record/js/tab_function.js からjsを読み込んでいます。)?>
  <div class="tab_container">
    <div class="tab">
      <button id="log_home"    class="tablinks" onclick="tab_btn(event, 'record_home'   )"><i class="fa-solid fa-chart-bar"></i></button>
      <button id="log_analyze" class="tablinks" onclick="tab_btn(event, 'record_analyze')"><i class="fa-solid fa-house-chimney"></i></button>
      <button id="log_entry"   class="tablinks" onclick="tab_btn(event, 'record_entry'  )"><i class="fa-solid fa-file-circle-plus"></i></button>
    </div>
  </div>
<?//タブEND?>


<?php include( dirname(__FILE__).'/footer.php' ); ?>