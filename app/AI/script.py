import os
import cv2
import time
import argparse
import requests
import numpy as np
from tqdm import tqdm
from PIL import Image
from io import BytesIO
import matplotlib.pyplot as plt


################################################
################################################


def rleToMask(rleString,height,width):
    rows,cols = height,width
    rleNumbers = [int(numstring) for numstring in rleString.split(' ')]
    rlePairs = np.array(rleNumbers).reshape(-1,2)
    img = np.zeros(rows*cols,dtype=np.uint8)
    for index,length in rlePairs:
        index -= 1
        img[index:index+length] = 255
    img = img.reshape(cols,rows)
    img = img.T
    return img

def auth():
    res = requests.get('https://www.visionhub.ru/api/v2/auth/generate_token/')
    if res.ok:
        token = res.json()['token']
    else:
        raise Exception(f'Failed to auth, reason : {res.reason}')
    return token


def push_task(image_path, token):
    res = requests.post('https://www.visionhub.ru/api/v2/process/img2img/', 
                        headers={'Authorization': f'Bearer {token}'},
                        files={'image': open(image_path, 'rb')},
                        data={'model': 'people_segmentator'})
    if res.ok:
        task_id = res.json()['task_id']
        return task_id
    else:
        raise Exception(f'Failed to process, reason : {res.reason}')


def get_status(task_id):
    res = requests.get(f'https://www.visionhub.ru/api/v2/task_result/{task_id}/',
                       headers={'Authorization': f'Bearer {token}'})
    if res.ok:
        res_json = res.json()
        return res_json
    else:
        raise Exception(f'Failed to get task_result, reason : {res.reason}')


def overlay_transparent(background, overlay, x, y):
    background_width = background.shape[1]
    background_height = background.shape[0]
    if x >= background_width or y >= background_height:
        return background
    
    h, w = overlay.shape[0], overlay.shape[1]
    if x + w > background_width:
        w = background_width - x
        overlay = overlay[:, :w]
    if y + h > background_height:
        h = background_height - y
        overlay = overlay[:h]
    if overlay.shape[2] < 4:
        overlay = np.concatenate([overlay,np.ones((overlay.shape[0], overlay.shape[1], 1), 
                                                  dtype=overlay.dtype) * 255], axis = 2)
    overlay_image = overlay[..., :3]
    mask = overlay[..., 3:] / 255.0
    background[y:y+h, x:x+w] = (1.0 - mask) * background[y:y+h, x:x+w] + mask * overlay_image
    
    return background


################################################
################################################


parser = argparse.ArgumentParser()
parser.add_argument('-d', help='input directory')
args = parser.parse_args()

folder_path = args.d
if folder_path is None:
	raise Exception('Folder path not given !')


################################################
################################################


##########################
##    Main processes    ##
##########################

start = time.time()

imgs_path = None
background = None
for file_name in os.listdir(folder_path):
    path = os.path.join(folder_path, file_name)
    if os.path.isdir(path):
        imgs_path = path
    elif os.path.isfile(path):
        background = cv2.imread(path)

if imgs_path is None or background is None:
    raise Exception('Given folder is invalid !')

original_size = (background.shape[1], background.shape[0])

print('\nGetting token...')
token = auth()

print('\nPushing tasks...')
images = []
task_ids = []
for img_name in tqdm(os.listdir(imgs_path)):
    images.append(cv2.imread(os.path.join(imgs_path, img_name)))
    task_ids.append(push_task(os.path.join(imgs_path, img_name), token))

print('\nWaiting for results...')
pngs = [None for _ in task_ids]
done = [False for _ in task_ids]
while sum(done) != len(done):
    for i, task_id in enumerate(task_ids):
        if not done[i]:
            res_json = get_status(task_id)
            if res_json['status'] == 'DONE':
                rle = eval(res_json['prediction'])
                mask = rleToMask(rle['counts'], *rle['size'])
                png = np.concatenate((images[i], mask[:,:,np.newaxis]), axis=2)
                
                mask = png[:,:,-1].astype(bool)
                ys, xs = np.meshgrid(np.arange(mask.shape[1]), 
                                     np.arange(mask.shape[0]))
                x_min, x_max = np.min(xs[mask]), np.max(xs[mask])
                y_min, y_max = np.min(ys[mask]), np.max(ys[mask])
                png = png[x_min:x_max, y_min:y_max]
                
                pngs[i] = png
                done[i] = True
                print(f'{sum(done)} done...')

print('\nDone !')

pngs.sort(key=lambda x: x.shape[0])
new_order_start = []
new_order_finish = []
for i in range(len(pngs)):
    if i%2 == 0: 
        new_order_start.append(pngs[i])
    else: 
        new_order_finish.append(pngs[i])
pngs = new_order_start + new_order_finish[::-1]

####################
##    Settings    ##
####################
x_padding_ratio = 0.1
y_padding_ratio = 0.1
space = 0

#################################
##    Background validation    ##
#################################
shape_0 = max([png.shape[0] for png in pngs])
shape_1 = sum([png.shape[1] for png in pngs]) + (len(pngs) - 1) * space
new_x, new_y = (2 * x_padding_ratio * background.shape[0] + shape_0, 
                2 * y_padding_ratio * background.shape[1] + shape_1)
ratio = max(new_x/background.shape[0], new_y/background.shape[1])
resize_shape = (int(ratio*background.shape[1]), 
                int(ratio*background.shape[0]))
background = cv2.resize(background, resize_shape)

######################
##    Overlaying    ##
######################

current_x = background.shape[0] - (background.shape[0] - shape_0) // 2
current_y = (background.shape[1] - shape_1) // 2
for png in pngs:
    overlay_transparent(background, png, current_y, current_x-png.shape[0])
    current_y += png.shape[1]
    current_y += space

background = cv2.resize(background, original_size)
cv2.imwrite('result.jpg', background)

finish = time.time()
print('Execution finished :', finish-start, 'sec')


















