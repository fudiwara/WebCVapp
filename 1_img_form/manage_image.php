<?php
session_start();

if( !empty($_POST["proc_blur"]) ){ // 「ぼかし処理」ボタンを押してリロードする時の処理：ぼかし処理本体
	$com = "python3 img_blur.py ". $_SESSION["proc_target_file_name"];
	exec($com, $dst_filename, $ret); // execでpythonの処理を実行
	$_SESSION["dst_filename"] = $dst_filename[0]; // 実行結果で標準出力されたファイル名を得る
	$_SESSION["flag_dst"] = true;
	$_SESSION["proc_target_file_name"] = $dst_filename[0]; // 2回目以降でぼかし処理を繰り返せるように

}else{ // 最初に開く時点での処理：画像のアップロード
	$tmp_file_name = $_FILES["upfile"]["tmp_name"]; // POSTで得た仮のファイル名
	$src_file_name = $_FILES["upfile"]["name"]; // 元のファイル名

	$up_file_name  = ""; // サーバ上で保存するファイル名
	$flag_upload = false; // アップロードが完了しているかのフラグ
	if( is_uploaded_file($tmp_file_name) ){ // アップロード成功の可否 (やりようによっては必要なし)
		$split_str = explode('.', $src_file_name);
		$ext = end($split_str); // ↑でピリオドで区切った最後のブロックを拡張子とする
		if($ext != "" && $ext != $src_file_name){ // 拡張子が得られたら
			$up_file_name = "image/". date("Ymd_His") . ".$ext";
			$flag_upload = move_uploaded_file($tmp_file_name, $up_file_name); // 仮置き場から移動
			$_SESSION["flag_upload"] = $flag_upload;
			$_SESSION["up_file_name"] = $up_file_name;
			$_SESSION["proc_target_file_name"] = $up_file_name;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>image image</title>
</head>
<body>
<?php
if( $_SESSION["flag_upload"] ){ // ファイルアップロードが完了している場合
?>
	<p>アップロードされた画像 ↓↓<br>
	<img src="<?= $_SESSION["up_file_name"] ?>"></p>

	<form method="post">
		<p><input type="submit" name="proc_blur" value="ぼかし処理"></p>
	</form>
<?php
}
if( !empty($_SESSION["flag_dst"]) ){ // ファイルアップロードが完了している場合
?>
	<p>ぼかし処理の結果 ↓↓<br>
	<img src="<?= $_SESSION["dst_filename"] ?>"></p>

<?php
}
if( !$_SESSION["flag_upload"] ){ // ファイルアップロードが完了している場合
?>
	<p>アップロードは失敗しました。</p>
<?php
}
?>
	<a href="index.php">アップロードフォームへ戻る</a>
</body>
</html>