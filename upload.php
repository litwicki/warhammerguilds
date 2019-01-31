<?PHP

$debug_print = 0; // 1 = on, 0 = off

include("includes/config.php");
include("includes/functions.php");
include("includes/session.php");

require("upload_config_inc.php");
require("xajax/xajax.inc.php");
$xajax = new xajax();
require("upload_xajax_inc.php");
$xajax->processRequest();

$video_display_online = 0;
$video_of_the_day = NULL;
$video_updated_timestamp = NULL;
$date_submitted = mktime( date('g,i,s,m,d,Y') );

$url = explode( "?", basename($_SERVER['REQUEST_URI']) ); $url = $url[0]; 
if( $user->data['is_registered'] )
{ 
	$user_id = $user->data['user_id'];
	$sid = $user->data['session_id'];
	$sql = 'select * from war_users where user_id = '.$user_id.' LIMIT 0, 1 ';
	$result = mysql_query($sql);
	   while( $user = mysql_fetch_array($result) )
	   { $user_group = $user['group_id']; $username = $user['username']; }
}

// if user is not logged in, do not let them view upload form
if( $user->data['session_time'] != $user->time_now ) 
{ 
	header("Location: video.php");
}

@include_once(HOME_PATH . "template/html/header.tpl");

?>
<!-- END HEADER -->

<!-- BEGIN CONTENT -->
<table border="0" cellspacing="0">
  <tr>

	<td class="left_column">
	<?PHP @include(HOME_PATH . "template/html/side.tpl"); ?>
	</td>

	<td class="right_column">
	<?PHP
	
	if( isset( $_GET['file'] ) && isset( $_GET['details'] ) && $_GET['details'] == "complete" )
	{
		$file_path = SERVER_PATH . "files/" . $_GET['file'];
		$filesize = filesize($file_path);
		$author = $username;

		$filename = $_GET['file'];
		$original_extension = preg_replace('/.*\.(.*)/is','$1',$filename);
		$original_filename = preg_replace('/(.*)\..*/is','$1',$filename);

		$video_category = $_POST['video_category'];

		$video_description = $_POST['video_description'];

		$new_filename = strtolower($author."_".$date_submitted.".flv");
		$new_thumbnail = strtolower($author."_".$date_submitted.".jpg");

		if( $_POST['title'] != "" )
		{ $video_title = $_POST['title']; }
		else { $video_title = $new_filename; }

		if( $_POST['display_guild'] ) {
			$display_guild = 1; }
		else {
			$display_guild = 0; }

		$debug .= "<p><strong>Full Filename</strong><br /><code>".$filename."</code></p>\n";
		$debug .= "<p><strong>Extension</strong><br /><code>".$original_extension."</code></p>\n";
		$debug .= "<p><strong>Filename</strong><br /><code>".$original_filename."</code></p>\n";
		$debug .= "<p><strong>Converted Filename</strong><br /><code>".$new_filename."</code></p>\n";
		$debug .= "<p><strong>Filesize</strong><br /><code>".$filesize."</code></p>\n"; //debugging
		$debug .= "<p><strong>Category</strong><br /><code>".$category."</code></p>\n"; //debugging
		$debug .= "<p><strong>Author</strong><br /><code>".$author."</code></p>\n"; //debugging
		$debug .= "<p><strong>Title</strong><br /><code>".$video_title."</code></p>\n"; //debugging
		$debug .= "<p><strong>Description</strong><br /><code>".$video_description."</code></p>\n"; //debugging
		$debug .= "<p><strong>Date Submitted</strong><br /><code>".$date_submitted."</code></p>\n"; //debugging
/*
	DATABASE VALUES
	video_id
	original_filename
	original_extension
	new_filename
	new_thumbnail
	filesize
	duration
	category
	author
	date_submitted
	display_online
	daily_spotlight
	last_modified
	last_modified_by
	description
	title
	is_converted
	total_views
	display_guild
*/
		$sql = "INSERT INTO ".WAR_DB.".war_videos VALUES (NULL, ".
				"'$original_filename',".
				"'$original_extension',".
				"'$new_filename',".
				"'$new_thumbnail',".
				"'$filesize',".
				"'$duration',".
				"'$video_category',".
				"'$author',".
				"'$date_submitted',".
				"0,".
				"NULL,".
				"NULL,".
				"NULL,".
				"'$video_description',".
				"'$video_title',".
				"0,".
				"0,".
				"$display_guild)";

		//echo "<p>SQL Query: ".$sql."</p>\n";
		if( !mysql_query($sql) )
		{ 
			die( mysql_error() ); 
		}
		else
		{	
			echo "<script type=\"text/javascript\" language=\"javascript\">window.location=\"news.php?s=".$sid."\";</script>\n";
		}
	}
	else
	{
		if( isset($_GET['file']) && file_exists(SERVER_PATH."files/".$_GET['file']) )
		{
			define(FILE_NAME, $_GET['file']);
			$filename = $_GET['file'];

			if( in_group($username,"registered") ) { 
				$debug .= "<p class=\"small\"><strong>User Details</strong><br />";
				$debug .= "<code>Username: ".$username."<br />Session ID: ".$sid. "<br />Timestamp: ".$_SERVER['REQUEST_TIME']. "<br />System: ".$_SERVER['HTTP_USER_AGENT'] . "</code></p>\n";
				@include(HOME_PATH . "template/html/upload_video_details.tpl"); 
			}
			else {
				@include(HOME_PATH . "template/html/unauthorized.tpl"); 
			}
		}
		else
		{
			if( in_group($username,"registered") ) 
				{ 
					$debug .= "<p class=\"small\"><strong>User Details</strong><br />";
					$debug .= "<code>Username: ".$username."<br />Session ID: ".$sid. "<br />Timestamp: ".$_SERVER['REQUEST_TIME']. "<br />System: ".$_SERVER['HTTP_USER_AGENT'] . "</code></p>\n";
					@include(HOME_PATH . "template/html/upload_video.tpl"); 
					if(!empty($onload)) echo "<script type='text/javascript'>".$onload."</script>"; 
				}
			else { @include(HOME_PATH . "template/html/unauthorized.tpl"); }
		}
	  // debugging
	  if( $debug_print == 1 ) { echo $debug; }
	}
	?>
	</td>

  </tr>
</table>
<!-- END CONTENT -->

<?PHP 
@include(HOME_PATH . "template/html/footer.tpl"); 
?>

<?PHP mysql_close($conn); ?>