<!DOCTYPE html>
   <html>
   <head>
   <meta  charset="UTF-8">
   </head>
   <body>	

<?php

	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//4-1で書いた接続のコードの下に続けて記載する。
$sql = "CREATE TABLE IF NOT EXISTS bulletinboard ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"//自動で設定されて新規データを入れるごとに＋１される
	. "name char(32),"
	. "comment TEXT,"
        . "date DATETIME,"
	. "password TEXT );";
        $stmt = $pdo ->query($sql);//テーブル作成
        $plane = 'SELECT * FROM bulletinboard';//参照するテーブルを選択
        $ppap = $pdo ->query($plane);
        $boards = $ppap ->fetchAll();//投稿された物を配列変数'borads'として取得
	//以下は編集したコメントを投稿するときのIF文
 


  if(!empty($_POST["number"]) and !empty($_POST["name"]) and !empty($_POST["comment"]) and !empty($_POST["password1"])){
   foreach($boards as $board){
  if($board["id"] == $_POST["number"] and $board["password"] == $_POST["password1"]) {
   	                        $id = $board["id"];
				$name = $_POST["name"];
				$comment = $_POST["comment"];
				$password = $_POST["password1"];
				$date = date('Y/m/d H:i:s');//date関数で投稿日時を取得
				$sql = "update bulletinboard set name=:name, comment=:comment, password=:password1, date=:date where id=:id";//UPDATEコマンド。カラム名=:代入する値
				$stmt = $pdo ->prepare($sql);
				$stmt ->bindParam(":id", $id, PDO::PARAM_STR);
				$stmt ->bindParam(":name", $name, PDO::PARAM_STR);
				$stmt ->bindParam(":comment", $comment, PDO::PARAM_STR);
				$stmt ->bindParam(":password1", $password, PDO::PARAM_STR);
				$stmt ->bindParam(":date", $date, PDO::PARAM_STR);
				$stmt ->execute();//実行
			}
		}
	}//以下新規投稿のIF文
  elseif(!empty($_POST["name"]) and !empty($_POST["comment"]) and !empty($_POST["password1"])) {
 $sql = $pdo -> prepare("INSERT INTO bulletinboard (name, comment, password, date) VALUES(:name, :comment, :password1, :date)");
		$sql -> bindParam(":name", $namae, PDO::PARAM_STR);
		$sql -> bindParam(":comment", $comment, PDO::PARAM_STR);
		$sql -> bindParam(":password1", $password, PDO::PARAM_STR);
		$sql -> bindParam(":date", $date, PDO::PARAM_STR);
		$namae = $_POST['name'];
		$comment = $_POST['comment'];
		$password = $_POST['password1'];
		$date = date('Y/m/d H:i:s');
		$sql -> execute();
	}
	//投稿削除のIF文//削除フォーム
     if (!empty($_POST["delete"])and !empty($_POST["password2"]) ) {
              foreach($boards as $board){
			if ($_POST["delete"] == $board["id"] and $_POST["password2"] == $board["password"]) {
				$id = $board["id"];
				$sql = "delete from bulletinboard where id=:id";
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam("id", $id, PDO::PARAM_INT);
				$stmt->execute();
			}
		}
	}
 //編集する投稿指定のIF文
if (!empty($_POST["edit"]) and !empty($_POST["password3"])) {
			foreach($boards as $board){
			if ($_POST["edit"] == $board["id"] and $_POST["password3"] == $board["password"]) {
				//投稿フォームに渡す情報を指定
				$edit_number = $board["id"];
				$edit_name = $board["name"];
				$edit_comment = $board["comment"];
				$edit_password3 = $board["password"];
				}
			}	
		}
	
  ?>


   <form  action="mission_5-1.php" method="POST">
   
   <td><input type="text" name="name" value = "<?php if (!empty($edit_name)){ echo $edit_name; } ?>" placeholder ="名前"/></td><br>

   <td><input type="text" name="comment" value = "<?php if (!empty($edit_comment)){ echo $edit_comment;} ?>" placeholder ="コメント"/></td><br/>
   
        <input type="text" name="password1" value = "<?php if (!empty($edit_password3)) {echo $edit_password3;} ?>" placeholder ="パスワード"/>
       <input name="submit" type ="submit" value="送信"/><br/>
        <input type = "hidden" name = "number" value = "<?php if(!empty($edit_number)){echo $edit_number;} ?>">
   
   
       <input type="text" name="delete" placeholder="削除対象番号"/><br/>
      
      <input type="text" name="password2"placeholder ="パスワード"/>
　    <input name="submit "type ="submit" value="削除"/><br/>
    
   
      <input type="text" name="edit" placeholder ="編集対象番号"/><br/>
      
      <input type="text" name="password3"placeholder ="パスワード"/>
      <input name="submit" type ="submit" value="編集"/> 

   </form>
 

<?php		
        $plane = "SELECT * FROM bulletinboard";
	$ppap = $pdo ->query($plane);
	$boards = $ppap ->fetchAll();
	foreach($boards as $board){
		echo $board["id"]. ',';
		echo $board["name"]. ',';
		echo $board["comment"]. ',';
		echo $board["date"]. '<br>';
		echo "<hr>";
	}	
?>
</body>
</html>
