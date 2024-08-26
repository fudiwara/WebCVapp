import sys
import pathlib
import cv2

img = cv2.imread(sys.argv[1], cv2.IMREAD_COLOR) # 画像読み込み
img_blur = cv2.GaussianBlur(img, (9, 9), 9) # ガウシアン平滑化

# 読み込んだファイル名に文字列を追加したものを用意する
input_file_name = pathlib.Path(sys.argv[1])
output_filename = input_file_name.stem + "_blur" + input_file_name.suffix
output_file_path = input_file_name.parent / output_filename

print(output_file_path) # 標準出力に出してPHPでひろう
cv2.imwrite(str(output_file_path), img_blur) # ファイル保存
