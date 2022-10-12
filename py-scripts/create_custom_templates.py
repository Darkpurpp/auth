from PIL import Image
import json

with open('config.txt') as json_file:
	data1 = json.load(json_file)
with open('config_apm.txt') as json_file:
	data2 = json.load(json_file)

arr1 = ['email', 'password', 'forget', 'check', 'default_enter', 'apm_enter', 'enter' ]

arr2 = ['email', 'password', 'apm', 'forget', 'check', 'default_enter', 'login_enter', 'enter' ]

pngs = ['error', 'mousedown', 'hover', 'active_hover', 'default' ]

def createCustomTemplates( arr, data, pngs ):
	for x in arr:
		for p in pngs:
			if 'apm' in arr:
				path = p + '_apm.png'
				v = '_apm'
			else:
				path = p + '.png'
				v = ''
			img = Image.open(path)

			XY = data[x].split(';')

			xy0 = XY[0].split(',')

			xy1 = XY[1].split(',')

			print(xy0,xy1)

			img_crop = img.crop((int(xy0[0]),int(xy0[1]),int(xy1[0]),int(xy1[1])))

			img_crop.save( x + '-' + p + v + '.png' )

createCustomTemplates( arr1, data1, pngs )
createCustomTemplates( arr2, data2, pngs )