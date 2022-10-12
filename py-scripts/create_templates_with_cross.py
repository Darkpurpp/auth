from PIL import Image
import numpy as np

def_img = Image.open('password-default1.png')

X_cross = np.arange(360,376, dtype=int)

Y_cross = np.arange(23,38, dtype=int)

def_pixels = def_img.load()

arr = [ 'email-default', 'email-default_apm', 'email-hover', 'email-hover_apm', 'password-default_apm', 'password-hover', 'password-hover_apm', 'apm-default_apm', 'apm-hover_apm' ]

for path in arr:
	img = Image.open(path + '.png');
	pixels = img.load()
	for i in X_cross:
		for j in Y_cross:
			pixels[i,j] = def_pixels[i,j];
	img.save( path + '1.png' );