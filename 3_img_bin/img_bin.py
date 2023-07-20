import sys
import pathlib
import cv2

img = cv2.imread(sys.argv[1], cv2.IMREAD_GRAYSCALE) # グレースケールで画像読み込み
ret, img_bin = cv2.threshold(img, 0, 255, cv2.THRESH_OTSU) # 大津の自動閾値で2値化

if int(sys.argv[2]) == 1: # 0:低い方を黒く、1:高い方を黒く
    img_bin = cv2.bitwise_not(img_bin)

# 読み込んだファイル名に文字列を追加したものを用意する
input_file_name = pathlib.Path(sys.argv[1])
output_filename = input_file_name.stem + "_bin" + input_file_name.suffix
output_file_path = input_file_name.parent / output_filename

print(output_file_path) # 標準出力に出してPHPでひろう
cv2.imwrite(str(output_file_path), img_bin) # ファイル保存
