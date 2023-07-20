import sys
import pathlib
import cv2

img = cv2.imread(sys.argv[1], cv2.IMREAD_GRAYSCALE) # グレースケールで画像読み込み

# 上側、下側の閾値を読み込む
th_l = int(sys.argv[2])
th_h = int(sys.argv[3])

img_canny = cv2.Canny(img, th_l, th_h) # Cannyによる輪郭線抽出

# 読み込んだファイル名に文字列を追加したものを用意する
input_file_name = pathlib.Path(sys.argv[1])
output_filename = input_file_name.stem + "_canny" + input_file_name.suffix
output_file_path = input_file_name.parent / output_filename

print(output_file_path) # 標準出力に出してPHPでひろう
cv2.imwrite(str(output_file_path), img_canny) # ファイル保存
