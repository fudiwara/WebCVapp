import sys
import pathlib
import cv2
import mediapipe as mp

mp_face_detection = mp.solutions.face_detection # mediapipeの初期化
face_detection = mp_face_detection.FaceDetection(min_detection_confidence = 0.5)

img = cv2.imread(sys.argv[1], cv2.IMREAD_COLOR) # 画像読み込み
ch, cw, _ = img.shape # 画像サイズ取得

img_gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY) # グレースケール変換

results = face_detection.process(cv2.cvtColor(img, cv2.COLOR_BGR2RGB)) # mediapipeに処理を渡す
if results.detections:
    for i in range(len(results.detections)):
        # 顔領域の検出結果描画
        b = results.detections[i].location_data.relative_bounding_box
        x0 = int(b.xmin * cw)
        y0 = int(b.ymin * ch)
        x1 = int((b.xmin + b.width) * cw)
        y1 = int((b.ymin + b.height) * ch)
        cv2.rectangle(img, (x0, y0), (x1, y1), (0, 255, 0))

# 読み込んだファイル名に文字列を追加したものを用意する
input_file_name = pathlib.Path(sys.argv[1])
output_filename = input_file_name.stem + "_fdetmp" + input_file_name.suffix
output_file_path = input_file_name.parent / output_filename

print(output_file_path) # 標準出力に出してPHPでひろう
cv2.imwrite(str(output_file_path), img) # ファイル保存
