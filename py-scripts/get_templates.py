import time
import pyautogui as pag

time.sleep(3)

pag.click( 878, 73 )

pag.write('https://www.figma.com/file/bAeEYzEETIKEunfOhFQLxi/Hd-interface?node-id=0%3A1', interval=0.1)

pag.press('enter')

time.sleep( 30 )

for i in range(10):
	pag.click( 30, 313 + i*40 )
	time.sleep(1)
	pag.click( 1812, 172 )
	time.sleep(1)
	pag.click( 1891, 228 )
	time.sleep(1)
	pag.click( 1766, 320 )
	time.sleep(3)
