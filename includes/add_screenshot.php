<?php

@include("config.php");

if( !isset($_POST['add']) || $_POST['password'] == "" )
{
?>
<div style="text-align: left; width: 100%; font-size: 8pt; color: #555;">
<form method="post" enctype="multipart/form-data">
Select Image<br />
<input style="font-size: 8pt; font-family: Verdana;" type="file" size="18" name="image" /><br /><br />

Image Description<br />
<input style="font-size: 8pt; font-family: Verdana;" type="text" name="caption" /><br /><br />

Password Validation<br />
<input style="font-size: 8pt; font-family: Verdana;" type="password" name="password" /><br /><br />

<input style="font-size: 8pt; font-family: Arial;" type="submit" name="add" value="Add Image" />
</form>
</div>

<?
}
else
{

	if( $_POST['password'] == $ADMIN_PASSWORD )
	{
		$today = date('YmdHis');
		$filename = "screenshots/" . $today . ".jpg";
		$thumbname = "screenshots/" . $today . "-video.jpg";

		$caption = $_POST['caption'];

		$full_url = HOME_PATH . $filename;
		$full_thumb = HOME_PATH . $thumbname;

		// Set $url To Equal The Filename For Later Use
		$url = $_FILES['image']['name'];   

		if ($_FILES['image']['type'] == "image/jpg" || $_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/pjpeg")
		{

			// Get The File Extention In The Format Of , For Instance, .jpg, .gif or .php
			$file_ext = strrchr($_FILES['image']['name'], '.');

			// Move Image From Temporary Location To Permanent Location
			copy($_FILES['image']['tmp_name'], $filename );
			echo $_FILES['image']['tmp_name'] . "<br />";
			echo $filename . "<br />";

			$str = '';
			$copy = ResizeScreenshot($filename, $filename, 100, 900, $str);

			if ($copy)
			{   

				ResizeScreenshot($filename, $thumbname, 100, 160, $str);

				$query = "INSERT INTO " . SCREENSHOTS_TABLE . " VALUES('$id','$full_url','$full_thumb','$caption')";
				echo $query . "<br />";
				$result = mysql_query($query);

				if( $result ) {
					print("<p>Screenshot Added!</p>");
				}
				else {
					print("<span class=\"small\">" . mysql_error() . "</span><br /><br />" );
				}

				/* ============================================ */
				

			}
			else
			{
				print ("<h1>Unable to upload image!</h1>");
			}
		}
		else
		{
			print ("Only .jpg or .jpeg files allowed");
		}
	}
	else
	{
		print("<strong>Invalid Password!</strong>");
	}
}
?>