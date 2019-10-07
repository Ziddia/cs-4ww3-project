Authors:
	Daniel Brunton 400024814
	Joshua Smith 400013323

	We are 4ww3 students and we are doing Add-on task 2

Links:
	Live address: https://transitrating.tk
	Git Repo: https://github.com/Ziddia/cs-4ww3-project

Add-on Task 2 writeup:
	i) Two different sized images of the map image are provided. One for large desktops and a small resolution image for tablets and phones.

	<picture>
		<source srcset="static/map.png" media="(min-width: 600px)">
		<img class="map" src="static/mapSmall.png" alt="Map of result locations">
	</picture>

	The source tag provides the reference to the alternative large image and the media query provides the ability to only display the image if the user's display is at least 600px wide.
	The img tag provides the link to the default small image and includes an alt attribute for screen readers.

	ii)
		1) Allows easy replacement of images depending on screen size.
		2) Allows you to easily serve the correct image depending on the image formats that their browser supports. 
		3) Saving bandwidth by not displaying images that are higher resolution that the user's screen can display.

	iii)
		It can be excessive if you are only using a single sized image. Thus using the picture and source tags would only add more lines of code without actually doing anything.

