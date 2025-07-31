import sys
import pathlib
import cv2
import mediapipe as mp

# options = mp.tasks.vision.FaceDetectorOptions( # 検出器のオプション
#     mp.tasks.BaseOptions(
#         model_path = "blaze_face_short_range.tflite",
#         delegate = mp.tasks.BaseOptions.Delegate.CPU)
#         )

# options = mp.tasks.vision.FaceDetectorOptions(
#     base_options = mp.tasks.BaseOptions(
#         model_asset_path = "blaze_face_short_range.tflite",
#         delegate = mp.tasks.BaseOptions.Delegate.CPU # ここでCPUを指定
#     ),
#     running_mode = mp.tasks.vision.RunningMode.IMAGE # 静止画処理の場合
# )

# base_options = mp.tasks.BaseOptions(
#     model_asset_path="blaze_face_short_range.tflite",
#     # delegate=mp.tasks.BaseOptions.Delegate.CPU を削除し、
#     # TensorFlow Lite の CPU デリゲートを直接指定します
#     delegate=mp.tasks.BaseOptions.Delegate.from_tensorflow_lite_delegate(
#         mp.tasks.vision.TfLiteDelegate.CPU  # または mp.tasks.BaseOptions.Delegate.from_options(mp.tasks.core.GpuDelegateOptions())
#     )
# )

base_options = mp.tasks.BaseOptions(
    model_asset_path="blaze_face_short_range.tflite",
    delegate=mp.tasks.BaseOptions.Delegate.CPU # ここはシンプルにCPUを指定
)


options = mp.tasks.vision.FaceDetectorOptions(
    base_options=base_options,
    running_mode=mp.tasks.vision.RunningMode.IMAGE
)

detector = mp.tasks.vision.FaceDetector.create_from_options(options) # 検出器の初期化

img_bgr = cv2.imread(sys.argv[1], cv2.IMREAD_COLOR) # 画像読み込み
img_rgb = cv2.cvtColor(img_bgr, cv2.COLOR_BGR2RGB) # MediaPipe用にRGBに
mp_image = mp.Image(image_format = mp.ImageFormat.SRGB, data = img_rgb) # MediaPipeのImageオブジェクトへ

detection_result = detector.detect(mp_image) # 顔検出

if detection_result.detections:
    for i, detection in enumerate(detection_result.detections): # 各顔に対するループ
        b = detection.bounding_box

        p0 = (b.origin_x, b.origin_y) # バウンディングボックスは入力画像の座標
        p1 = (b.origin_x + b.width, b.origin_y + b.height)
        cv2.rectangle(img_bgr, p0, p1, (0, 255, 0), 2) # 緑色の矩形


# 読み込んだファイル名に文字列を追加したものを用意する
input_file_name = pathlib.Path(sys.argv[1])
output_filename = input_file_name.stem + "_fdetmp" + input_file_name.suffix
output_file_path = input_file_name.parent / output_filename

print(output_file_path) # 標準出力に出してPHPでひろう
cv2.imwrite(str(output_file_path), img_bgr) # ファイル保存
