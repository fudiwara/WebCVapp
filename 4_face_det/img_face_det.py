import sys
import pathlib
import cv2
import numpy as np

# cascade_path = "/usr/share/opencv4/haarcascades/haarcascade_frontalface_default.xml"
cascade_path = "haarcascade_frontalface_default.xml"
face_det = cv2.CascadeClassifier(cascade_path) # カスケードファイルの読み込み

img = cv2.imread(sys.argv[1], cv2.IMREAD_COLOR) # 画像読み込み
img_gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY) # グレースケール変換
faces = face_det.detectMultiScale(img_gray) # 顔検出

for i in range(len(faces)):
    x0 = faces[i][0]
    y0 = faces[i][1]
    x1 = faces[i][0] + faces[i][2]
    y1 = faces[i][1] + faces[i][3]
    cv2.rectangle(img, (x0, y0), (x1, y1), (0, 255, 0))

# 読み込んだファイル名に文字列を追加したものを用意する
input_file_name = pathlib.Path(sys.argv[1])
output_filename = input_file_name.stem + "_fdet" + input_file_name.suffix
output_file_path = input_file_name.parent / output_filename

print(output_file_path) # 標準出力に出してPHPでひろう
cv2.imwrite(str(output_file_path), img) # ファイル保存
