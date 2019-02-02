 <?php

include("war-open.php");
include("war-inc/war-functions.php");

// display the correct content upload form
if( isset($_GET['type']) )
{
	$upload_type = trim($_GET['type']);

	if( $upload_type == "screenshot" )
	{
		$page_title = "Screenshot Upload";
		$page_template = "upload_screenshot.html";
	}
	elseif( $upload_type == "video" )
	{
		$page_title = "Video Upload";
		$page_template = "upload_video.html";
	}
	elseif( $upload_type == "uimod" )
	{
		$page_title = "UIMod Upload";
		$page_template = "upload_uimod.html";
	}
	else
	{
		//if the user is guessing or messing with the
		//querystring, just dump them to the start
		header("location: upload.php");
	}
}
else
{
	$page_title = "Begin Upload";
	$page_template = "upload.html";
}

// done with all the code, let's show the page
page_header($page_title);
$template->set_filenames( array('body' => $page_template) );
page_footer();
// close the page and do some admin stuff
@include("war-close.php");
?>