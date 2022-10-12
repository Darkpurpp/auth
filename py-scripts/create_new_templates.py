from PIL import Image
import numpy as np

arr = [ 'error', 'error_apm', 'default', 'default_apm', 'active_hover', 'active_hover_apm', 'hover', 'hover_apm', 'mousedown', 'mousedown_apm' ]

X_email = np.arange(534,730, dtype=int)
Y_email = np.arange(374,401, dtype=int)

X_password = np.arange(532,575, dtype=int)
Y_password = np.arange(447,470, dtype=int)

X_email_apm = np.arange(533,730, dtype=int)
Y_email_apm = np.arange(449,474, dtype=int)

X_password_apm = np.arange(533,576, dtype=int)
Y_password_apm = np.arange(521,543, dtype=int)

X_apm = np.arange(532,620, dtype=int)
Y_apm = np.arange(375,405, dtype=int)

def changeColor(X,Y):
	for i in X:
		for j in Y:
			pixels[i,j] = pixels[X[0],Y[0]]

for x in arr:
	im = Image.open( x + '.png')
	pixels = im.load()
	if x.find('apm') < 0:
		changeColor(X_email, Y_email)
		changeColor(X_password, Y_password)
		im.save(x+'.png')
	else:
		changeColor(X_email_apm, Y_email_apm)
		changeColor(X_password, Y_password_apm)
		changeColor(X_apm, Y_apm)
		im.save(x+'.png')

	