<?php
session_start();

if( !empty($_POST["proc_bin"]) ){ // 「2値化処理」ボタンを押してリロードする時の処理：2値化処理本体
	
	if($_POST["bin_high_low"] == "high"){ // ラジオボタンのpostにあわせてコマンドライン引数を変える
		$bin_flag = 1;
	}else{
		$bin_flag = 0;
	}
	$com = "python3 img_bin.py ". $_SESSION["proc_target_file_name"] . " " . $bin_flag; # . で連結
	exec($com, $dst_filename, $ret); // execでpythonの処理を実行
	$_SESSION["dst_filename"] = $dst_filename[0]; // 実行結果で標準出力されたファイル名を得る
	$_SESSION["flag_dst"] = true;

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
		<p>
			<input type="radio" name="bin_high_low" value="high"
			<?php
				if($_POST["bin_high_low"] == "high") echo(" checked") // 該当部にチェック
			?> 
			>濃度値の高い方を黒にする
			&emsp;
			<input type="radio" name="bin_high_low" value="low"
			<?php
				if($_POST["bin_high_low"] == "low") echo(" checked") // 該当部にチェック
			?> 
			>濃度値の低い方を黒にする
		</p>
		<p><input type="submit" name="proc_bin" value="2値化処理"></p>
	</form>
<?php
}
if( !empty($_SESSION["flag_dst"]) ){ // 画像処理が完了している場合
?>
	<p>2値化処理の結果 ↓↓<br>
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