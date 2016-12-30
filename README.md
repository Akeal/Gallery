# Gallery
Gallery web app for displaying images
The Gallery is meant to display images in categories. The main page displays all images stored on the server. When clicked, the image is zoomed in on. If there are additional related images, arrows are shown allowing the viewer to cycle through them. There are also pages almost identical to the main page that limit the shown images to categories. An additional two admin pages (meant to be hosted locally) allow adding and removing images to/from the server. On adding images, if the image file contains EXIF orientation information, the page will extract it and recreate the file rotated right-side up before storing it on the server. This saves client-side work on each later page load on having to rotate the image.

Add database connection information in connection.php
Gallery.sql included to set up database.
