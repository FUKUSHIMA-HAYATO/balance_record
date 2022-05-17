<?php include( dirname(__FILE__).'/../header.php' ); ?>
  <div id="creation_wrap">
    <h1 id="creation_title">新規会員登録</h1>
    <form action="creation_result.php" method="post" autocomplete="off">
      <div id="creation_id">
        <input type="text" name="new_create_id" id="search_id"pattern="^[0-9a-zA-Z_]+$" placeholder="ユーザーID" required>
        <span id="search_result"></span>
      </div>
      <div id="creation_pass" >
        <input type="password" name="new_create_pass" placeholder="パスワード" required>
      </div>
      <div id="creation_btn" >
      <input id="creation_btn" type="submit" value="新規登録">
      </div>
    </form>
    <p id="creation_login">すでに登録済みの方は<a href="login">こちら</a></p>
  </div>

  <script>
    $('#search_id').blur(function() {
      let check_str = $(this).val();
      if(!check_str){
         check_str = "check73948";
      }
      if(check_str){
        $.ajax({
          url: 'check_id_ajax.php',
          type:"POST",
          data:{check : check_str},
          dataType: 'json',
        }).done(function(flag){
          if(flag == 0){
            $("#search_result").text('登録可能です');
            document.getElementById('search_result').style.color = "green";
          }
          if(flag == 1){
            $("#search_result").text('そのIDは既に登録されています');
            document.getElementById('search_result').style.color = "red";
          }
          if(flag == 2){
            document.getElementById("search_result").innerHTML = '';
          }
          if(flag == 3){
            $("#search_result").text('半角英数字で入力してください');
            document.getElementById('search_result').style.color = "red";
          }
          if(flag == 4){
            alert('申し訳ございません、ただいま不具合が発生しております。[1]');
          }
        }).fail(function(){
          alert('申し訳ございません、ただいま不具合が発生しております。[2]');
        })
      }
    });
  </script>
<?php include( dirname(__FILE__).'/../footer.php' ); ?>