<?php
error_reporting(0); // genbuの設定で出てしまうWarningを非表示にする

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
if( $flag_upload ){ // ファイルアップロードが完了している場合
?>
	<p>画像がアップロードされました。<br>
	<img src="<?= $up_file_name ?>"></p>
<?php
}else{
?>
	<p>アップロードは失敗しました。</p>
<?php
}
?>
	<a href="index.html">アップロードフォームへ戻る</a>
</body>
</html>