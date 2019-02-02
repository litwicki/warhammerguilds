<?PHP

$debug_print = 1; // 1 = on, 0 = off

@include("includes/config.php");
@include("includes/functions.php");
@include("includes/session.php");

##################################################

$time = mktime( date('g,i,s,m,d,Y') );
$file_path = "/home/thezdin/public_html/files/";
$mencoder_path = "/usr/bin/mencoder/";
$flvtool_path = "/usr/bin/flvtool2/";
$ffmpeg_path = "/usr/bin/ffmpeg/";

$url = explode( "?", basename($_SERVER['REQUEST_URI']) ); 
$url = $url[0];

if( $user->data['is_registered'] )
{ 
	$user_id = $user->data['user_id'];
	$sid = $user->data['session_id'];
	$sql = 'select * from war_users where user_id = '.$user_id.' LIMIT 0, 1 ';
	$result = mysql_query($sql);
	while( $user = mysql_fetch_array($result) ){ 
		$user_group = $user['group_id']; 
		$username = $user['username'];
		define("USERNAME",$username);
	}
}

/* If the user is not a "writer" (admin) boot them out and kill their session. */

if( !in_group($username, "writer") )
{
	header('Location: index.php');
	$user->session_begin(false);
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
	<h1>WARGuilds Administration</h1>

	<ol>
	<li><a href="admin.php?f=screenshot">Screenshot Utilities</a></li>
	<li><a href="admin.php?f=video">Video Utilities</a></li>
	<li><a href="admin.php?f=spotlight">Video Spotlight</a></li>
	</ol>
	
	<?PHP
		if( isset($_GET['f']) && $_GET['f'] == "screenshot" )
		{
			if( !isset($_POST['add']) )
			{
				@include("template/html/add_screenshot.tpl");
			}
			else
			{
				$today = date('YmdHis');
				$filename = "screenshots/" . $today . ".jpg";
				$thumbname = "screenshots/" . $today . "-small.jpg";
				$caption = $_POST['caption'];
				$full_url = HOME_PATH . $filename;
				$full_thumb = HOME_PATH . $thumbname;

				if ($_FILES['image']['type'] == "image/jpg" || $_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/pjpeg")
				{
					// Get The File Extention In The Format Of , For Instance, .jpg, .gif or .php
					$file_ext = strrchr($_FILES['image']['name'], '.');

					// Move Image From Temporary Location To Permanent Location
					copy($_FILES['image']['tmp_name'], "/home/thezdin/public_html/".$filename );
					$debug .= "<p><strong>Temporary Filename</strong><br />" . $_FILES['image']['tmp_name'] . "</p>";
					$debug .= "<p><strong>New Filename</strong><br />" . $filename . "</p>";

					$str = '';

					list($imagew, $imageh, $imaget, $imageattr) = getimagesize($_FILES['image']['tmp_name']);
					$debug .= "<p><strong>Image Attributes</strong><br />Height: " . $imageh . "<br />Width: " . $imagew . "</p>\n";

					// if image is wider than 900 pixels, resize to 900
					if( $imagew > 900 )
					{ 
						$copy = ResizeScreenshot($filename, $filename, 100, 900, $str);

						ResizeScreenshot($filename, $thumbname, 100, 160, $str);
						$query = "INSERT INTO ".WAR_DB. ".war_screenshots VALUES('$id','$full_url','$full_thumb','$caption')";
						$debug .= "<p><strong>Screenshot Query</strong><br /><code><textarea cols=\"50\" rows=\"5\" style=\"font-size: 8pt;\">" . $query . "</textarea></code></p>";
						$result = mysql_query($query);

						if( $result ){ 
							print("<p>Screenshot Added!</p>"); }
						else { 
							print("<span class=\"small\">" . mysql_error() . "</span><br /><br />" ); }

					}
					else
					{
						$copy = ResizeScreenshot($filename, $filename, 100, $imagew, $str);

						ResizeScreenshot($filename, $thumbname, 100, 160, $str);
						$query = "INSERT INTO ".WAR_DB. ".war_screenshots VALUES('$id','$full_url','$full_thumb','$caption')";
						$debug .= "<p><strong>Screenshot Query</strong><br /><code><textarea cols=\"50\" rows=\"5\" style=\"font-size: 8pt;\">" . $query . "</textarea></code></p>";
						$result = mysql_query($query);

						if( $result ){ 
							print("<p>Screenshot Added!</p>"); }
						else { 
							print("<span class=\"small\">" . mysql_error() . "</span><br /><br />" ); }
					}

					if (!$copy) print ("<p><strong>Unable to upload image!</strong></p>");
				}
				else { print ("<p><strong>Only .jpg or .jpeg files allowed</strong></p>"); }
			}

			if( $debug_print == 1 )
				echo $debug;
		}
		elseif( isset($_GET['f']) && $_GET['f'] == "video" )
		{
			if( ($_GET['f'] == "video" && (isset($_GET['by_author']) && $_GET['by_author'] != "") ) || (isset($_GET['by_video_id']) && $_GET['by_video_id'] != "") || (isset($_GET['view']) && $_GET['view'] != "") )
			{
				$by_author = $_GET['by_author'];
				define("BY_AUTHOR",$by_author);

				$by_video_id = $_GET['by_video_id'];
				define("BY_VIDEO_ID",$by_video_id);

				$convert_videos = $_GET['view'];
				define("VIEW_CONVERT",$convert_videos);
				
				if(!isset($_GET['p'])){ $p = 1; } 
				else { $p = $_GET['p']; }
				$x = (($p * 1) - 1);

				if( isset($_POST['video_submit']) )
				{
					if( isset($_GET['view']) ) { $by_type = "Raw Video"; }
					if( isset($_GET['by_author']) ) { $by_type = "Author"; }
					if( isset($_GET['by_video_id']) ) { $by_type = "Video ID"; }
					if( isset($_GET['by_date']) ) { $by_type = "Date"; }

					//Delete checkbox is checked - delete record from database, move physical file to "_trash" directory and exit
					if( $_POST['delete']  && $by_type )
					{
						$debug .= "<p><strong>ADMIN UTILITY: DELETE VIDEO</strong> (By ".$by_type.")</p>\n";
						$query_info = "SELECT * FROM ".WAR_DB.".war_videos WHERE video_id = ".$_POST['delete'];
						$result = mysql_query($query_info);
						while( $file = mysql_fetch_array($result) ) { $original_file = $file['original_filename'].".".$file['original_extension']; }

						$delete = "DELETE FROM ".WAR_DB.".war_videos WHERE video_id = ".$_POST['delete'];
						$debug .= "<p class=\"small\"><strong>Delete Video SQL</strong><br /><code>" . $delete . "</code></code></p>\n";
						if( !mysql_query($delete) ) { 
							echo "<p class=\"small\">Unable to delete ID<br /><code>". $_POST['delete']." from database!</code></p>\n";
						}
						if( !@rename($file_path.$original_file, $file_path . "_trash/".$original_file) ) { 
							echo "<p class=\"small\"><strong><strong>UNABLE TO MOVE FILE <code>".$original_file."</code></strong></code></p>\n"; 
						}
						echo "<p class=\"small\"><strong>Video ID ".$_POST['delete']." deleted from WARGuilds database!</strong></p>\n";
						$skip = 1; // stops other queries from executing
					 }
					
					// Display Online
					if( $_POST['display_online'] && $skip == 0 && $by_type )
					{
						$debug .= "<p><strong>ADMIN UTILITY: DISPLAY ONLINE</strong> (By ".$by_type.")</p>\n";
						$display_online = "UPDATE ".WAR_DB.".war_videos SET display_online=1 WHERE video_id = ".$_POST['display_online'];
						$debug .= "<p class=\"small\"><strong>Display Online Query</strong><br /><code>". $display_online."</code></p>\n";
						if( mysql_query($display_online) ) { 
							$debug .= "<p class=\"small\"><strong>Query Successful</strong><br />Video ID #".$_POST['display_online']." now displaying online!</p>\n";
						}
					}
					
					// Take video offline
					if( $_POST['hide_online'] && $skip == 0 && $by_type )
					{
						$debug .= "<p><strong>ADMIN UTILITY: TAKE VIDEO OFFLINE</strong> (By ".$by_type.")</p>\n";
						$hide_online = "UPDATE ".WAR_DB.".war_videos SET display_online=0 WHERE video_id = ".$_POST['hide_online'];
						$debug .= "<p class=\"small\"><strong>Hide Video Offline</strong><br /><code>". $hide_online."</code></p>\n";
						if( mysql_query($hide_online) ) { 
							$debug .= "<p class=\"small\"><strong>Query Successful</strong><br />Video ID #".$_POST['hide_online']." now hidden offline!</p>\n";
						}
					}

					if( $_POST['edit'] && $skip == 0 )
					{
						$debug .= "<p><strong>ADMIN UTILITY: EDIT VIDEO TITLE/DESCRIPTION</strong>(By ".$by_type.")</p>\n";
						$edit1 = "UPDATE ".WAR_DB.".war_videos SET title='".$_POST['title']."' WHERE video_id=".$_POST['edit'];
						$debug .= "<p class=\"small\"><strong>Edit Title Query</strong><br /><code>".$edit1." </code></p>\n";
						if( mysql_query($edit1) ) { 
							$debug .= "<p class=\"small\">Video <strong>title</strong> updated for <strong>Video #".$_POST['edit']."</strong></p>\n";
						}

						$edit2 = "UPDATE ".WAR_DB.".war_videos SET description='".$_POST['description']."' WHERE video_id=".$_POST['edit'];
						$debug .= "<p class=\"small\"><strong>Edit Description Query</strong><br /><code>". $edit2."</code></p>\n";
						if( mysql_query($edit2) ) { 
							$debug .= "<p class=\"small\">Video <strong>description</strong> updated for <strong>Video #".$_POST['edit']."</strong></p>\n";
						}
					}

					//Convert checkbox is checked - convert video (move original, convert, delete original, move .flv to /videos/)
					if( $_POST['convert'] && $skip == 0 && $by_type )
					{
						echo "<p><strong>ADMIN UTILITY: VIDEO CONVERSION</strong> (By ".$by_type.")</p>\n";

						$query_info = "SELECT * FROM ".WAR_DB.".war_videos WHERE video_id = ".$_POST['convert'];
						$result = mysql_query($query_info);
						while( $file = mysql_fetch_array($result) ) { 
							$original_file = $file['original_filename'].".".$file['original_extension'];
							$new_filename = $file['new_filename'];
							$small_thumbnail = $file_path . "preview/" . $file['new_thumbnail'];
							$large_thumbnail = preg_replace('/(.*?)\.jpg/is','$1_large.jpg',$file['new_thumbnail']);
							$large_thumbnail = $file_path . "preview/" . $large_thumbnail;
						}

						$old_file = $file_path . $original_file;
						$new_file = $file_path . "flv/" . $new_filename;
						
						echo "<p class=\"small\"><strong>Old Filename</strong><br /><code>".$original_file."</code></p>\n";
						echo "<p class=\"small\"><strong>New Filename</strong><br /><code>".$new_filename."</code></p>\n";

						//convert raw video to flv
						//$mencoder_cmd = "mencoder $old_file -o $new_file -of lavf -oac mp3lame -lameopts abr:br=56 -ovc lavc -lavcopts vcodec=flv:vbitrate=800:mbd=2:mv0:trell:v4mv:cbp:last_pred=3 -lavfopts i_certify_that_my_video_stream_does_not_use_b_frames -vf scale=480:360 -srate 22050";
						$mencoder_cmd = "mencoder $old_file -0 $new_file vcodec=flv:vbitrate=640 -vf scale=480:360";
						
						if( @exec("$mencoder_cmd 2>&1", $output) ) { $debug .= "<p class=\"small\"><strong>MENCODER Command</strong><br /><code>".$mencoder_cmd."</code></p>\n"; }
						else { die("<p class=\"small\"><strong>COULD NOT CONVERT FILE ".$original_file." to ".$new_filename."</strong></p>"); }

						$new_filesize = round( ((filesize($new_file) / 1024) / 1024), 2 );

						//inject into flvtool for live display
						$flv_cmd = "$flvtool2 -U $new_file";
						if( @exec("$flv_cmd 2>&1", $output) ) {
							$debug .= "<p class=\"small\"><strong>FLV Command</strong><br /><code>".$flv_cmd."</code></p>\n"; 
						}

						$duration = video_duration($new_file);
						$debug .= "<p class=\"small\"><strong>Video Duration</strong><br /><code>".$duration."</code></p>\n";

						$seconds = video_seconds($new_file);
						$debug .= "<p class=\"small\"><strong>Total Video Seconds</strong><br /><code>".$seconds."</code></p>\n";
						$position = $seconds / 2;
						$debug .= "<p class=\"small\"><strong>Thumbnail Position</strong><br /><code>".$position."</code></p>\n";

						//generate thumbnail images for display list, and so the video player isn't black on first view
						$ffmpeg_cmd2 = "ffmpeg -i $new_file -ss $position -t 00:00:01 -s 120x90 -r 1 -f image2 $small_thumbnail";
						//execute and record output to variable
						if( @exec("$ffmpeg_cmd2 2>&1", $output) ) {
							echo "<p class=\"small\"><strong>Small Thumbnail Command</strong><br/><code> ".$ffmpeg_cmd2."</code></code></p>\n"; 
						}

						$ffmpeg_cmd3 = "ffmpeg -i $new_file -ss $position -t 00:00:01 -s 480x360 -r 1 -f image2 $large_thumbnail";
						//execute and record output to variable
						if( @exec("$ffmpeg_cmd3 2>&1", $output) ) {
							$debug .= "<p class=\"small\"><strong>Large Thumbnail Command</strong><br/><code> ".$ffmpeg_cmd3."</code></code></p>\n"; 
						}
						
						if( file_exists($new_file) ) {
							$convert = "UPDATE ".WAR_DB.".war_videos SET is_converted = 1 WHERE video_id = " . $_POST['convert'];
							if( mysql_query($convert) )
							{ 							
								$sql2 = "UPDATE ".WAR_DB.".war_videos SET display_online = 1 WHERE video_id = " . $_POST['convert'];
								$debug .= "<p class=\"small\"><strong>Display Online Query</strong><br /><code>".$sql2."</code></p>\n";
								mysql_query($sql2);
								
								$sql3 = "UPDATE ".WAR_DB.".war_videos SET last_modified = ".$time." WHERE video_id = " . $_POST['convert'];
								$debug .= "<p class=\"small\"><strong>Last Modified (Timestamp)</strong><br /><code>".$sql3."</code></p>\n";
								mysql_query($sql3);
								
								$sql4 = "UPDATE ".WAR_DB.".war_videos SET last_modified_by = '".USERNAME."' WHERE video_id = " . $_POST['convert'];
								$debug .= "<p class=\"small\"><strong>Last Modified By Query</strong><br /><code>".$sql4."</code></p>\n";
								mysql_query($sql4);
								
								$sql5 = "UPDATE ".WAR_DB.".war_videos SET duration = '".$duration."' WHERE video_id = " . $_POST['convert'];
								$debug .= "<p class=\"small\"><strong>Video Duration Query</strong><br /><code>".$sql5."</code></p>\n";
								mysql_query($sql5);

								echo "<p class=\"small\">Conversion Successful!<br /><code>" . $original_file . " >> <strong>" . $new_filename . " (" . $new_filesize . "MB)</code></strong></p>";
							}
						}
						else { echo "<p class=\"small\"><strong><code>".$new_file."</code> was not created!</code></p>\n"; }
					}
					
					//debugging
					if( $debug_print == 1 ) { echo $debug; }
				}
				else
				{
					$action_link = $_SERVER['REQUEST_URI'];
					$action_link = str_replace("/","",$action_link);
					$debug .= "<p><strong>ACTION URL:</strong> <code>".$action_link."</code></p>\n";

					$sql = "SELECT * FROM ".WAR_DB.".war_videos ";
					
					if( VIEW_CONVERT ) { 
						$sql .= "WHERE is_converted=0 "; }
					if( $by_video_id ) { 
						$sql .= "WHERE video_id=".BY_VIDEO_ID; }
					if( $by_author ) {
						$sql .= "WHERE author='".BY_AUTHOR."'"; }
					if( $by_date ) { 
						$sql .= "WHERE ".$date_search; }
						
					$sql .= " ORDER BY video_id DESC LIMIT $x, 1";
					$debug .= "<p><strong>Video Administration Query</strong><br /><code>".$sql."</code></p>\n";

					$result = mysql_query($sql);
					$num = mysql_num_rows($result);

					if( $num > 0 )
					{
						while( $video = mysql_fetch_array($result) ){ include("template/html/admin_video.tpl"); }
						if( !isset($_GET['p']) ) { $page = 1; }
						else { $page = $_GET['p']; }

						$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".WAR_DB.".war_videos"),0);
						$total_pages = ceil($total_results / 1);
						
						if( !$by_video_id ) 
						echo "<p class=\"small\" style=\"text-align: right;\">Total Results (".$total_pages.")</p>\n";
						
						echo "<div class=\"pagination\">\n";
						
						if( $by_author ) { $link = "admin.php?f=video&by_author=".$by_author."&p="; }
						else { $link = "admin.php?f=video&p="; }
					
						if( $page > 1 && !$by_video_id )
						{
							$prev = ($p - 1);
							echo "<a href=\"".$link.$prev."\"><img alt=\"previous\" src=\"" . HOME_PATH.IMAGE_PATH.PREV_ICON . "\" /></a>";
						}
						if( $page < $total_pages && !$by_video_id )
						{ 
							$next = ($page + 1);
							echo "<a href=\"".$link.$next."\"><img alt=\"next\" src=\"" . HOME_PATH.IMAGE_PATH.NEXT_ICON . "\" /></a>";
						}
						echo "</div>\n";
					}
					else { echo "<p><strong>Sorry, I couldn't match any videos to your search.</strong></p>\n"; }
					
					//debugging
					if( $debug_print == 1 ) { echo $debug; }
				}
			}
			else
			{
				@include("template/html/admin_video_search.tpl");
			}
		}
		elseif( isset($_GET['f']) && $_GET['f'] == "spotlight" )
		{
			if( isset($_POST['spotlight']) && preg_match('/\d+/is',$_POST['video_id']) && $_POST['date'] != "" )
			{
				if( preg_match('/(\d{2})\/(\d{2})\/(\d{4})/is',$_POST['date']) ){
					$date_ok = 1; $date = $_POST['date']; }
				else { $date_ok = 0; }
				

				$id_check = "select * from ".WAR_DB.".war_videos WHERE video_id=".$_POST['video_id'];
				$debug .= "<p class=\"small\"><strong>Check if Video ID Exists</strong><br /><code>".$id_check."</code></p>\n";
				$id_result = mysql_query($id_check);
				$id_exists = mysql_num_rows($id_result);

				if( $date_ok && $id_exists == 1 )
				{
					$sql = "UPDATE ".WAR_DB.".war_videos SET daily_spotlight='".$date."' WHERE video_id=".$_POST['video_id'];
					$debug .= "<p class=\"small\"><strong>Daily Spotlight Query</strong><br /><code>".$sql."</code></p>\n";
					if( mysql_query($sql) ){
						echo "<p class=\"small\"><strong>Daily Spotlight Updated</strong> for video id (".$_POST['video_id'].") on ".$_POST['date']."</p>\n";
					}
				}
				else
				{
					if( $date_ok = 0 ) { echo "<p class=\"small\"><strong>Invalid Data Entered</strong><br />Date <strong>must</strong> be formatted <strong>mm/dd/yyyy</strong></p>\n"; }
					if( !$id_exists ) { echo "<p><strong>ID ".$_POST['video_id']." does not exist!</strong></p>\n"; }
				}
			}
			else { @include("template/html/spotlight.tpl"); }
			//debugging
			if( $debug_print == 1 ) { echo $debug; }
		}
	?>
	</td>

  </tr>
</table>
<!-- END CONTENT -->

<?PHP 
if (!empty($onload)) { echo "<script type=\"text/javascript\">".$onload."</script>\n"; }
@include(HOME_PATH . "template/html/footer.tpl"); 
?>

<?PHP mysql_close($conn); ?>