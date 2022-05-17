<?php 
	session_start();
	if( !empty($_SESSION['id'])) {
    header('Location: /balance_record/home');exit ;
  }
?>
<?php include( dirname(__FILE__).'/../header.php' ); ?>

	<div id="login_explanation">
		<p>データが入ったアカウントでログインする場合は、<br>下記のIDとPASSWORDでログインしてください。<br>ID:demo_user<br>PASS:demo_user</p>
	</div>

	<div id="login_wrap">
		<h1 id="login_title">ログインページ</h1>
		<form action="login_result" method="post">
			<div id="login_id">
			  <input type="text" name="access_id" placeholder="ユーザーID" autocomplete="off" required>
			</div>
			<div id="login_pass">
			  <input type="password" name="access_pass" placeholder="パスワード" autocomplete="off" required>
			</div>
			<div id="login_btn">
				<input type="submit" value="ログイン">
			</div>
		</form>
		<p id="login_creation"><a href="creation">アカウント作成</a></p>
	</div>

<?php include( dirname(__FILE__).'/../footer.php' ); ?>

