import numpy as np
import PIL.ImageDraw
import scipy.cluster
import math

N_CLUSTER = 5

def kmeans_process(img, n_cluster):
    sm_img = img.resize((100, 100))
    color_arr = np.array(sm_img)
    w_size, h_size, n_color = color_arr.shape
    color_arr = color_arr.reshape(w_size * h_size, n_color)
    color_arr = color_arr.astype(np.float64)

    codebook, distortion = scipy.cluster.vq.kmeans(color_arr, n_cluster)  # クラスタ中心
    code, _ = scipy.cluster.vq.vq(color_arr, codebook)  # 各データがどのクラスタに属しているか

    n_data = []  # 各クラスタのデータ数
    for n in range(n_cluster):
        n_data.append(len([x for x in code if x == n]))

    # print(n_data)
    desc_order = np.argsort(n_data)[::-1]  # データ数が多い順に「第○クラスタ、第○クラスタ、、、、」
    # print(desc_order)
    # print(n_data)
    # print(codebook)
    # print((codebook[elem].astype(int)) for elem in desc_order)
    # return ['#{:02x}{:02x}{:02x}'.format(*(codebook[elem].astype(int))) for elem in desc_order]
    return codebook

source_file = 'noodle.jpg'
source = PIL.Image.open(source_file)
colors = kmeans_process(source, N_CLUSTER)

# print(colors)

im_size = 100
im = PIL.Image.new('RGB', (im_size, im_size), (255, 255, 255, 255))
draw = PIL.ImageDraw.Draw(im)
single_width = im_size / N_CLUSTER

for i, color in enumerate(colors):
    # 色を描画
    # print(color)
    rgb_color = (int(color[0]), int(color[1]), int(color[2]))
    print(rgb_color)
    g_dist = math.sqrt(rgb_color[0] ** 2 + (255 - rgb_color[1]) ** 2 + rgb_color[2] ** 2)
    print(g_dist)
    p1 = (single_width * i, 0)
    p2 = (single_width * (i + 1), im_size)
    pos = [p1, p2]
    draw.rectangle(pos, fill=rgb_color)

im.save('YYY.png')