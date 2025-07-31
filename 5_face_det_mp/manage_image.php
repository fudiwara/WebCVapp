<?php
session_start();

if( !empty($_POST["proc_fdet"]) ){ // 「顔検出」ボタンを押してリロードする時の処理：顔検出本体
	$com = "python3 img_face_det_mp.py ". $_SESSION["proc_target_file_name"];

	// mpは警告等が表示されるので標準出力と分けて処理する
	$descriptorspec = array(
	0 => array("pipe", "r"),  // stdin
	1 => array("pipe", "w"),  // stdout
	2 => array("pipe", "w")   // stderr
	);
	
	$process = proc_open($com, $descriptorspec, $pipes);
	
	if(is_resource($process)) {
		$stdout = stream_get_contents($pipes[1]); // 標準出力からデータを読み込む
		fclose($pipes[1]);

		$_SESSION["dst_filename"] = $stdout; // 実行結果で標準出力されたファイル名を得る
		$_SESSION["flag_dst"] = true;
	}

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
		<p><input type="submit" name="proc_fdet" value="顔検出"></p>
	</form>
<?php
}
if( !empty($_SESSION["flag_dst"]) ){ // 画像処理が完了している場合
?>
	<p>顔検出の結果 ↓↓<br>
	<img src="<?= $_SESSION["dst_filename"] ?>"></p>

<?php
}
if( !$_SESSION["flag_upload"] ){ // ファイルアップロードが失敗した場合
?>
	<p>アップロードは失敗しました。</p>
<?php
}
?>
	<a href="index.php">アップロードフォームへ戻る</a>
</body>
</html>