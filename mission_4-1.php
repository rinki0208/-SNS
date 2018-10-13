<html>

 <head>

   <meta http-equiv = "Content-Type" content = "text/html; charset=utf-8" />

 </head>

 <body>


   <?php


    //接続/////////////////////////////////////////////////////////////////////////////////
    try {
      $pdo = new PDO('データベース名','ユーザー名','パスワード');
    } catch (PDOException $e) {
        exit('データベース接続失敗。'.$e->getMessage());
      }
    ///////////////////////////////////////////////////////////////////////////////////////




    //変数////////////////////////////////////////////////////////////////////////////
    $today = date("F j, Y, g:i a");
    $name = htmlspecialchars($_POST["name"]);
    $comment = htmlspecialchars($_POST["comment"]);
    $password1 = htmlspecialchars($_POST["password1"]);
    $password2 = htmlspecialchars($_POST["password2"]);
    $password3 = htmlspecialchars($_POST["password3"]);
    $deleteNumber = htmlspecialchars($_POST["deleteNumber"]);
    $editNumber = htmlspecialchars($_POST["editNumber"]);
    //////////////////////////////////////////////////////////////////////////////////////




    //編集(番号選択)////////////////////////////////////////////////////////////////////////
    if(isset($editNumber) and $editNumber != "" and isset($password3) and $password3 != ""){
      //編集対象番号が半角数字で入力された時
      $sql = "SELECT * FROM newdb";
      $results = $pdo -> query($sql);

      foreach ($results as $row){

        if($editNumber == $row['id'] and $password3 == $row['password']){
          $nowEName = $row['name'];
          $nowEComment = $row['comment'];
          $nowENumber = $row['id'];
        }
      }

    }
    ////////////////////////////////////////////////////////////////////////////////////////


   ?>




   <form method = "POST" action = "mission_4-1.php">

    <input type = "text" name = "name" value = "<?php echo $nowEName; ?>" placeholder = "名前"><br>
    <input type = "text" name = "comment" value = "<?php echo $nowEComment; ?>" placeholder = "コメント"><br>
    <input type = "text" name = "password1" value = "" placeholder = "パスワード">
    <input type = "hidden" name = "Number" value = "<?php echo $nowENumber; ?>">
    <input type = "submit" value = "送信"><br>
    <br>
    <input type = "text" name = "deleteNumber" value = "" placeholder = "削除対象番号(半角数字)"><br>
    <input type = "text" name = "password2" value = "" placeholder = "パスワード">
    <input type = "submit" value = "削除"><br>
    <br>
    <input type = "text" name = "editNumber" value = "" placeholder = "編集対象番号(半角数字)"><br>
    <input type = "text" name = "password3" value = "" placeholder = "パスワード">
    <input type = "submit" value = "編集"><br>

   </form>




   <?php

    //接続/////////////////////////////////////////////////////////////////////////////////
    try {
      $pdo = new PDO('データベース名','ユーザー名','パスワード');
    } catch (PDOException $e) {
        exit('データベース接続失敗。'.$e->getMessage());
      }
    //////////////////////////////////////////////////////////////////////////////////////




    //変数/////////////////////////////////////////////////////////////////////
    $today = date("F j, Y, g:i a");
    $name = htmlspecialchars($_POST["name"]);
    $comment = htmlspecialchars($_POST["comment"]);
    $password1 = htmlspecialchars($_POST["password1"]);
    $password2 = htmlspecialchars($_POST["password2"]);
    $password3 = htmlspecialchars($_POST["password3"]);
    $deleteNumber = htmlspecialchars($_POST["deleteNumber"]);
    $editNumber = htmlspecialchars($_POST["editNumber"]);
    ///////////////////////////////////////////////////////////////////////////////////




    //編集時用変数////////////////////////////////////////////////////////////////////
    $editToday = date("F j, Y, g:i a"); //編集時の時間
    $editName = htmlspecialchars($_POST["name"]);  //編集後の名前
    $editComment = htmlspecialchars($_POST["comment"]); //編集後のコメント
    $Number = htmlspecialchars($_POST["Number"]); //隠されているテキストボックスに載っている番号
    ////////////////////////////////////////////////////////////////////////////////




    //名前とコメントとパスワード/////////////////////////////////////////////////////
    if(!empty($name) and !empty($comment) and empty($Number) and preg_match("/^[a-zA-Z0-9]+$/", $password1)){
      //名前とコメントとパスワードが入力された時
      $sql = $pdo -> prepare("INSERT INTO newdb (name , comment , password , today) VALUES (:name , :comment , :password , :today)");
      $sql -> bindParam(':name', $name, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $sql -> bindParam(':password', $password1, PDO::PARAM_STR);
      $sql -> bindParam(':today', $today, PDO::PARAM_STR);
      $sql -> execute();


      //入力データ表示///////////////////////////
      $sql = 'SELECT * FROM newdb';
      $results = $pdo -> query($sql);
      foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['today'].'<br>';
      }
      //////////////////////////////////////////

      print("ご入力ありがとうございます。<br>");

    }

    if(empty($name) or empty($comment) or empty($password1)){
      //名前とコメントとパスワードのどれかが空の時
    }

    if(empty($name) or empty($comment) or !preg_match("/^[a-zA-Z0-9]+$/", $password1)){
      //名前とコメントとパスワードのどれかが空の時
    }
    ///////////////////////////////////////////////////////////////////////////////////



    //削除////////////////////////////////////////////////////////////////////////////
    if(isset($deleteNumber) and $deleteNumber != "" and isset($password2) and $password2 != ""){
      //削除対象番号が半角数字で入力された時
      $sql = "DELETE FROM newdb WHERE id = :id AND password = :password";
      $result = $pdo -> prepare($sql);
      $result -> bindValue(':id', $deleteNumber, PDO::PARAM_INT);
      $result -> bindParam(':password', $password2, PDO::PARAM_STR);
      $result -> execute();

      //入力データ表示///////////////////////////
      $sql = 'SELECT * FROM newdb';
      $results = $pdo -> query($sql);
      foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['today'].'<br>';
      }
      //////////////////////////////////////////

      print("ご入力ありがとうございます。<br>");
    }

    if($deleteNumber == "" or $password2 == ""){
      //削除対象番号かパスワードが間違っていた時
    }
    ///////////////////////////////////////////////////////////////////////////////////



    //編集(編集中)///////////////////////////////////////////////////////////////////
    if(!empty($Number)){
    //編集対象番号と投稿番号が一致した時
      $sql = "UPDATE newdb SET name = :name , comment = :comment , today = :today WHERE id = :id";
      $result = $pdo -> prepare($sql);
      $result -> bindParam(':name' , $name , PDO::PARAM_STR);
      $result -> bindParam(':comment' , $comment , PDO::PARAM_STR);
      $result -> bindParam(':today' , $today , PDO::PARAM_STR);
      $result -> bindValue(':id', $Number , PDO::PARAM_INT);
      $result -> execute();

      //入力データ表示///////////////////////////
      $sql = 'SELECT * FROM newdb';
      $results = $pdo -> query($sql);
      foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['today'].'<br>';
      }
      //////////////////////////////////////////


      print("ご入力ありがとうございます。<br>");

    }
    /////////////////////////////////////////////////////////////////////////////////


   ?>


 </body>

</html>
