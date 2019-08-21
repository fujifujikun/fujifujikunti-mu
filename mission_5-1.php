<form method="post"action="">
	<input type="text" name="okuri" placeholder="名前"><br>
	<input type="text" name="okuri2" placeholder="コメント"><br>
	<input type="text" name="message_pw" placeholder="パスワード"><br>
	<input type="submit" name="sou" value="送信"> <input type="reset" value="リセット"><br>
</form>
<form method="post"action="">
	<input type="text" name="deleteNo" placeholder="削除対象番号"><br>
	<input type="text" name="message_pw" placeholder="パスワード"><br>
	<input type="submit" name="delete" value="削除">
</form>
<form action="" method="post">
    <input type="text" name="hensyuNo" placeholder="編集対象番号"><br>
    <input type="text" name="henname" placeholder="編集名前"><br>
	<input type="text" name="naiyou" placeholder="編集内容"><br>
	<input type="text" name="message_pw" placeholder="パスワード"><br>
	<input type="submit" name="hensyu" value="編集"><br>
</form>
<?php
//接続
$dsn = 'mysql:dbname=tb210239db;host=localhost';
$user = 'tb-210239';
$password = '7DF6bYrfze';
//エラー警告
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS mission5"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "message_pw TEXT,"
. "time DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
.");";
$stmt = $pdo->query($sql);
//作ったテーブル一覧
$sql ='SHOW TABLES';
$result = $pdo -> query($sql);
foreach ($result as $row){
	echo $row[0];
	echo '<br>';
}
echo "<hr>";
//テーブルの中身一覧
$sql ='SHOW CREATE TABLE mission5';
$result = $pdo -> query($sql);
foreach ($result as $row){
	echo $row[1];
}
echo "<hr>";
//テーブルへデータ入力
if(!empty($_POST["sou"])){
	$name = $_POST["okuri"];
	$comment = $_POST["okuri2"];
	$message_pw = $_POST["message_pw"];
	$time = date("Y/m/d H:i:s");
	$sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, message_pw, time) VALUES (:name, :comment, :message_pw, :time)");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':message_pw', $message_pw, PDO::PARAM_STR);
	$sql -> bindParam(':time',$time,PDO::PARAM_STR);
	$sql -> execute();
}
//削除
if(!empty($_POST["delete"])){
	$sql = "SELECT * FROM mission5";
	$stmt = $pdo->query($sql);
	$result = $stmt->fetchAll();
	foreach($result as $i){
		if($i["message_pw"] == $_POST["message_pw"]){
			$id=$_POST["deleteNo"];
			$sql = 'delete from mission5 where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
		}
	}
}
//編集
if(!empty($_POST["hensyu"])){
	$sql = "SELECT * FROM mission5";
	$stmt = $pdo->query($sql);
	$result = $stmt->fetchAll();
	foreach($result as $i){
		if($i["id"] == $_POST["hensyuNo"] && $i["message_pw"] == $_POST["message_pw"]){
			$id=$_POST["hensyuNo"];
			$name=$_POST["henname"];
			$comment=$_POST["naiyou"];
			$sql = 'update mission5 set name=:name,comment=:comment where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
		}
	}
}
//表示
$sql = 'SELECT * FROM mission5';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['time']."<br>";
echo "<hr>";
}
?>