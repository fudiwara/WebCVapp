<?php
session_start();
$_SESSION = array();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>face detect WebApp</title>
</head>
<body>
	<form action="manage_image.php" method="post" enctype="multipart/form-data">
		<p>まず画像をアップロードします。<br>
		その後でアップロードした画像から顔検出をする<br>
		というデモプログラム(Webアプリケーション)です。<br>
		動的なWebの処理はPHPで、画像処理部はPython(MediaPipe)で動作しています。</p>
		<p><br></p>
		<p>画像を指定：<input type="file" name="upfile"></p>
		<p><input type="submit" value="アップロード"></p>
	</form>
</body>
</html>