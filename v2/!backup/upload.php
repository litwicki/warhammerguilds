<? 
include("war-open.php");

if( WarAccessLevel($user->data['user_id'], 4) ){ $allow_access = 1; } else { $allow_access = 0; }

# -------------------------------
# SET UPLOAD TIMER TO LIMIT SPAM
# -------------------------------
if( isset($_GET['type']) && $_GET['type'] == "img" ) 
{
	$date_result = mysql_query("SELECT date_submitted FROM war_screenshots WHERE user_id=".$user->data['user_id']." ORDER BY screenshot_id DESC LIMIT 1");
	if( mysql_num_rows($date_result) > 0 )
	{
		while( $date = mysql_fetch_array($date_result) )
		{
			$hour2 = date('H',$date['date_submitted']);
			$min2 = date('i',$date['date_submitted']);
			$sec2 = date('s',$date['date_submitted']);
			$month2 = date('n',$date['date_submitted']);
			$day2 = date('d',$date['date_submitted']);
			$year2 = date('Y',$date['date_submitted']);

			if( $min2 < 54 ) {
				$timer_end = mktime($hour2,$min2+5,$sec2,$month2,$day2,$year2);
			} else {
				$timer_end = mktime($hour2+1,0,0,$month2,$day2,$year2);
			}
		}
	}
}
elseif( isset($_GET['type']) && $_GET['type'] == "ui" )
{
	$date_result = mysql_query("SELECT date_submitted FROM war_uimods WHERE user_id=".$user->data['user_id']." ORDER BY uimod_id DESC LIMIT 1");
	if( mysql_num_rows($date_result) > 0 )
	{
		while( $date = mysql_fetch_array($date_result) )
		{
			$hour2 = date('H',$date['date_submitted']);
			$min2 = date('i',$date['date_submitted']);
			$sec2 = date('s',$date['date_submitted']);
			$month2 = date('n',$date['date_submitted']);
			$day2 = date('d',$date['date_submitted']);
			$year2 = date('Y',$date['date_submitted']);

			if( $min2 < 54 ) {
				$timer_end = mktime($hour2,$min2+5,$sec2,$month2,$day2,$year2);
			} else {
				$timer_end = mktime($hour2+1,0,0,$month2,$day2,$year2);
			}
		}
	}
}
include("war/html/war-header.tpl");
?>

<div id="war-content">
	<div id="war-left"><div style="padding: 10px;"><? @include("war/html/war-left.tpl"); ?></div></div>
		<div id="war-center">
			<div style="padding: 2em; margin: auto;">
			<!-- Begin Content -->
			<div id="upload-status" style="background-color: #5698d0; color: #fff;">
			<img src="war/img/icons/loader_blue2.gif" alt="Upload Status" /><br />File Is Being Processed
			<p class="black">Please be patient while your file is being processed. <br />Exiting this page will cancel or corrupt your file!</p>
			</div>
			<? 

				# --------------------------------
				# Include the WAR Welcome Message
				# --------------------------------
				@include("war/html/war-welcome.tpl");
				
				# -----------------------------------------------------
				# UPLOAD TIMER RESTRICTION TO EASE SERVER USAGE
				# If the user is not an admin, member, or moderator, 
				# they must wait for the timer before they can upload
				# again. The timer limit is set above.
				# -----------------------------------------------------

					if( ($timer_end < $date_now) || $special_user == 1 )
					{	
						if( (isset($_GET['img']) && is_numeric($_GET['img'])) || (isset($_GET['vid']) && is_numeric($_GET['vid']))  || (isset($_GET['ui']) && is_numeric($_GET['ui'])) ) {
							echo "<p class=\"blue\"><strong>Your file has been submitted to the processing queue for review by our moderators. It will appear online shortly. We <em>greatly</em> appreciate your contribution to the WARGuilds Community!</strong></p>\n";
						}

						#############################################################
						#############################################################
						##														   ##
						##	PROCESS SCREENSHOTS									   ##
						##														   ##
						#############################################################
						#############################################################

						if( $_GET['type'] == "img" && $allow_access )
						{
							$display_form = 1;
							# -----------------------------------
							# DISPLAY THE SCREENSHOT UPLOAD FORM
							# -----------------------------------
							if( isset($_POST['upload-screenshot']) && $display_form == 1 )
							{
								$day = date('Ymd',$date_now);
								$file_dir = "/home/thezdin/public_html/war-content/war-screenshots";
								$author = $user->data['user_id'];
								$user_ip = $_SERVER['REMOTE_ADDR'];
								$browser_details = $_SERVER['HTTP_USER_AGENT'];
								$description = $_POST['description'];
								$category = $_POST['category'];
								$date_submitted = $date_now;
								$o_filename = $_FILES['image']['name'];
								$o_extension = "";
								$n_extension = ".jpg";
								$filename = strtolower($user->data['username']) . "__" . $date_submitted . ".jpg";
								$new_filename = $file_dir."/lg/".$day."/".$filename;
								$new_thumbnail = $file_dir."/sm/".$day."/".$filename;
								
								if( $_POST['comments_on'] ){ $comments = 1; }
								else { $comments = 0; }

								# --------------------------------------------
								# CREATE "day" DIRECTORY IF IT DOES NOT EXIST
								# ie: war-content/war-folder/20070101/
								# --------------------------------------------
								if( !file_exists($file_dir . "/lg/" . $day . "/" ) ) { 
									ftpmkdir('/public_html/war-content/war-screenshots/lg/', $day);
									$index_file = "/public_html/war-content/war-screenshots/lg/".$day."/index.php";
									$file_handle = fopen($index_file, 'a+');
									fclose($file_handle);
									$debug .= "MAKE DIRECTORY:<br /><code>" . $file_dir . "/lg/" . $day . "/</code>|";
								}

								if( !file_exists($file_dir . "/sm/" . $day . "/" ) ) { 
									ftpmkdir('/public_html/war-content/war-screenshots/sm/', $day);
									$index_file = "/public_html/war-content/war-screenshots/sm/".$day."/index.php";
									$file_handle = fopen($index_file, 'a+');
									fclose($file_handle);
									$debug .= "MAKE DIRECTORY:<br /><code>" . $file_dir . "/sm/" . $day . "/</code>|";
								}

								$debug.= "<strong>SCREENSHOT UPLOAD DEBUGGING</strong>|";
								$debug.= "Author: <code>".$author."</code>|";
								$debug.= "Category: <code>".$category."</code>|";
								$debug.= "Date Submitted: <code>".$date_submitted."</code>|";
								$debug.= "Original Filename: <code>".$o_filename."</code>|";
								$debug.= "New Filename: <code>".$filename."</code>|";
								
								# ----------------------
								# Error Checking
								# ----------------------
								$img_error_flag = 0;

								if ( $_FILES['image']['type'] == "image/jpg" || $_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/pjpeg" || $_FILES['image']['type'] == "image/png" || $_FILES['image']['type'] == "image/gif" )
								{
									$img_error_flag = 0;
								}
								else
								{
									$img_error_flag = 1;
									$img_error_msg .= "Invalid Image Type|";
								}

								if( filesize($_FILES['image']['tmp_name']) > 25165824 )
								{
									$img_error_flag = 1;
									$img_error_msg .= "Invalid Filesize (maximum 3MB)|";
								}
								// add a validation query on the user_id here select * from blah where user_id=$authot, etc.
								if( $author < 2 )
								{
									$img_error_flag = 1;
									$img_error_msg .= "Unauthorized Access (you must be registered to upload)|";
								}

								if( file_exists($new_filename) || file_exists($new_thumbnail) )
								{
									$img_error_flag = 1;
									$img_error_msg .= "You Cannot Overwrite an Existing File!|";
								}

								if( $category == -1 )
								{
									$img_error_flag = 1;
									$img_error_msg .= "Please Select an Image Category|";
								}

								if( !ValidateFormText($description) )
								{
									$img_error_flag = 1;
									$img_error_msg .= "Invalid Description Text|";
									warLog("Possible SQL Injection: " . $description, $log_file);
									$debug.= "Possible SQL Injection: <code>".$description."</code>|";
								}

								# --------------------------------------------------------
								# There was an error, alert the user and log if necessary
								# --------------------------------------------------------
								if( $img_error_flag ) 
								{
									if( $allow_access )
									{ 
										$error_output = split('\|', $img_error_msg);
										echo "<ul class=\"ul-error\">\n";
										foreach($error_output as $value) { 
											if( strlen($value) > 0 ) { 
												echo "<li>".$value."</li>\n"; 
											}
										}
										echo "</ul>\n";
										@include("war/html/war-upload-screenshot.tpl"); 
									}
									else
									{ 
										@include("war/html/war-unauthorized.tpl");
										warLog("\nUnauthorized Access By IP: " . $_SERVER['REMOTE_ADDR'], $admin_log);
										warLog("Attempted Access: " . $date_now, $admin_log);
									}
								}
								else
								{
									$o_extension = strrchr($_FILES['image']['name'], '.');
									$debug.= "Original Extension: <code>" . $o_extension . "</code>|";

									# -----------------------------------------
									# MOVE THE IMAGE TO LOCAL CONTENT DIRECTORY
									# -----------------------------------------
									move_uploaded_file($_FILES['image']['tmp_name'], $new_filename );

									# ----------------------------------------
									# RESIZE TO 900px WIDE IF LARGER THAN 900
									# ----------------------------------------
									list($imagew, $imageh, $imaget, $imageattr) = GetImageSize($new_filename);
									$debug.= "Image Width (Original): <code>" . $imagew . "</code>|";
									$debug.= "Image Height (Original): <code>" . $imageh . "</code>|";
									
									# ----------------------------------
									# RESIZE TO MAX WIDTH OF 900 PIXELS
									# ----------------------------------
									system('convert ' . $new_filename . ' -resize "900x800>" ' . $new_filename);

									# ---------------------------------
									# CREATE THUMBNAIL 120 PIXELS WIDE
									# ---------------------------------
									//system('convert ' . $new_filename . ' -thumbnail "120x120>" -background white -gravity center -extent 120x120 ' . $new_thumbnail);
									system('convert ' . $new_filename . ' -thumbnail "300x300<" -gravity center -crop 140x100+0+0  +repage ' . $new_thumbnail);
									
									# -------------------------------------------------------
									# GET RESIZED IMAGE DIMENSIONS & FILESIZES FOR DEBUGGING
									# -------------------------------------------------------
									list($imagew, $imageh, $imaget, $imageattr) = GetImageSize($new_filename);
									$filesize = filesize($new_filename);
									
									$debug.= "Image Width (Resized): <code>" . $imagew . "</code>|";
									$debug.= "Image Height (Resized): <code>" . $imageh . "</code>|";

									# ------------------------------
									# INSERT NEW RECORD TO DATABASE
									# ------------------------------
									if( file_exists($new_filename) && file_exists($new_thumbnail) )
									{
										if( $special_user ) { $display_online = 1; } else { $display_online = 0; }

										$sql_img = "INSERT INTO war_screenshots(original_filename, description, category, display_online, user_id, date_submitted, height, width, filesize, comments_on) VALUES('$o_filename','$description',$category,$display_online,$author,$date_submitted, $imageh, $imagew, $filesize, $comments)";
										$debug.= "Insert New Screenshot (Resized): <code><span class=\"small\">" . $sql_img . "</span></code>\n";

										$result_img = mysql_query($sql_img);
										$screenshot_id = getInsertID();

										if( $result_img ) 
										{	
											# -------------------------------------------
											# RENAME IMAGES TO image_(screenshot_id).jpg
											# -------------------------------------------
											$id_filename = $file_dir. "/lg/".$day."/image__" . $screenshot_id . ".jpg";
											$id_thumbnail = $file_dir."/sm/".$day."/image__" . $screenshot_id . ".jpg";

											@rename($new_filename,$id_filename);
											@rename($new_thumbnail,$id_thumbnail);
											
											if(!$display_online) { EmailAlert(1,$admin_emails); }
											
											echo '<script type="text/javascript">window.location = "upload.php?img='.$screenshot_id.'"</script>';
										} 
										else 
										{ 
											warLog("\nERROR UPLOADING SCREENSHOT: " . mysql_error(), $debug_log);
											warLog("Date of Error: " . date('l dS \of F Y h:i:s A'), $debug_log);
											echo '<script type="text/javascript">window.location = "upload.php?t=img&result=error"</script>'; 
										}
									}
									else
									{
										warLog("ERROR INSERT NEW SCREENSHOT TO DATABASE",$debug_log);
										warLog("Date of Error: " . date('l dS \of F Y h:i:s A'), $debug_log);
										warLog("User IP: (" . $_SERVER['REMOTE_ADDR'] . ")",$debug_log);
										warLog("Username: (" . GetUserEmail($user->data['user_id']) . ")",$debug_log);
									}
								}
							}
							else
							{
								if( $_GET['result'] == "no" )
								{
									$img_error_flag = 1;
									$img_error_msg = "You've encountered a truly unique bug here..<br /><br />Please contact <a href=\"mailto:bugs@warhammerguilds.net?Subject=Screenshot Upload Bug\">bugs@warhammerguilds.net</a> so we can make sure this does not happen again.<br /><br />If you're feeling lucky...try uploading again!";
									$error_output = split('\|', $img_error_msg);
									echo "<ul class=\"ul-error\">\n";
									foreach($error_output as $value) { 
										if( strlen($value) > 0 ) { 
											echo "<li>".$value."</li>\n"; 
										}
									}
									echo "</ul>\n";
								}

								# ----------------------------------------------------
								# The form has not been completed, so we need to
								# display it for the user to input valid data.
								# But first we must make sure they are a registered
								# user, or we'll let them know they need to register.
								# ----------------------------------------------------
								if( $display_form )
								{
									if( $allow_access )
										{ @include("war/html/war-upload-screenshot.tpl"); }
									else
										{ @include("war/html/war-unauthorized.tpl"); }
								}
							}
							# LOG EVERYTHING THAT HAPPENED
							warCompleteLog($debug,$debug_log);
						}

						#############################################################
						#############################################################
						##														   ##
						##	PROCESS VIDEOS										   ##
						##														   ##
						#############################################################
						#############################################################

						elseif( $_GET['type'] == "vid" && $allow_access )
						{
							$video_path = SERVER_PATH . "war-content/war-videos";
							$display_form = 1;

							if( isset($_POST['war-video']) )
							{
								# -----------------------------------------------------------
								# GET CURRENT FILENAME (temp) AND THEN RENAME WITH TIMESTAMP
								# -----------------------------------------------------------
								$temp_filename = $_FILES['video']['name'];
								$ext = preg_replace('/.*\.(.*)/i','$1',$temp_filename);
								$good_ext = array("avi", "mpg", "mov", "mpeg", "mp4", "wmv");

								# Validate file is accepted video format
								if( !in_array($ext, $good_ext) ) { 
									$vid_error = 1;
									$vid_error_msg = "INVALID FILE TYPE: " . $_FILES['video']['name']. "|";
								}

								# SET MAXIMUM FILE SIZE
								if( filesize($_FILES['video']['tmp_name']) > $max_vid_size ) {
									$vid_error = 1;
									$vid_error_msg = "Maximum Filesize Allowed 150 MB|";
								}
								
								if( !$vid_error )
								{
									$author = $user->data['user_id'];
									$date_submitted = $date_now;
									$comments = 0;
									if( isset($_POST['comments']) ) { $comments = 1; }
									$category = $_POST['category'];
									$description = ValidateFormText($_POST['description']);
									if( $category == -1 ) { $category = 0; }

									$temp_filename = preg_replace('/(.*)\.(.*?)/i','$1___'.$date_submitted.'.$2',$temp_filename);
									$temp_filename = str_replace(" ","_",$temp_filename);
									$filename = $temp_filename;

									# ---------------------------------
									# CHECK FOR, AND CREATE ARCHIVE 
									# DIRECTORIES IF THEY DO NOT EXIST
									# ---------------------------------
									$file_dir = "/home/thezdin/public_html/war-content/war-videos";
									$day_dir = date('Ymd',$date_submitted);

									# Check for /yyyymmdd/ directory for video storage
									if( !file_exists($file_dir."/".$day_dir."/") ) { 
										ftpmkdir('/public_html/war-content/war-videos/', $day_dir);
										$index_file = SERVER_PATH . "war-content/war-videos/".$day_dir."/index.php";
										$file_handle = fopen($index_file, 'x+');
										fclose($file_handle);
										$debug .= "MAKE DIRECTORY:<br /><code>".$file_dir."/".$day_dir."/</code>|";
									}

									# Check for /img/yyyymmdd/ directory for thumbnails (to be used later by ffmpeg)
									if( !file_exists($file_dir."/img/".$day_dir."/") ) { 
										ftpmkdir('/public_html/war-content/war-videos/img/', $day_dir);
										$index_file = "/home/thezdin/public_html/war-content/war-videos/img/".$day_dir."/index.php";
										$file_handle = fopen($index_file, 'x+');
										fclose($file_handle);
										$debug .= "MAKE DIRECTORY:<br /><code>".$file_dir."/img/".$day_dir."/</code>|";
									}

									$debug .= "RAW FILE: " . $_FILES['video']['name'] . "|";
									$debug .= "PROCESSED FILE: " . $file_dir."/".$day_dir."/".$filename . "|";

									if( move_uploaded_file($_FILES['video']['tmp_name'], $file_dir."/".$day_dir."/".$filename ) ) { 
										$debug .= "File (".$file_dir."/".$day_dir."/".$filename.") transferred successfully!|";
										$upload_success = 1;
									} else {
										$debug .= "File (".$_FILES['video']['tmp_name'].") transfer *FAILED!*|";
									}

									$filesize = filesize($file_dir."/".$day_dir."/".$filename);

									# -------------
									# DEBUG VALUES
									# -------------
									$debug.= "\n<strong>VIDEO UPLOAD DEBUGGING</strong>|";
									$debug.= "Author: <code>".$author."</code>|";
									$debug.= "Category: <code>".$category."</code>|";
									$debug.= "Date Uploaded: <code>".date('m/d/Y H:i:a',$date_submitted)."</code>|";
									$debug.= "Filename: <code>".$filename."</code>|";
									$debug.= "Description: ".$description."</code>|";
									$debug.= "Filesize: " . $filesize."|";

									if( isset($upload_success) ) 
									{ 
										$filename_no_ext = preg_replace('/(.*)\..*/i','$1',$filename);
										$debug .= "Filename NO EXTENSION: " . $filename_no_ext . "|";
									
										# ---------------------------
										# INSERT RECORD OF NEW VIDEO
										# ---------------------------
										if( $special_user == 1 ) { $display_online = 1; } else { $display_online = 0; }

										$sql_vid = "INSERT INTO war_videos(original_filename, original_extension, original_filesize, description, user_id, date_submitted, category, converted, display_online, comments_on) VALUES('$filename_no_ext','$ext',$filesize,'$description',$author,'$date_submitted', $category,0,$display_online,$comments)";
										$debug .= "SQL VIDEO QUERY: <code>".$sql_vid."</code>|";
										
										if( $result = mysql_query($sql_vid) ) {
											$video_id = GetInsertID(); // get video_id of last inserted record
										} else { $debug .= "ERROR INSERTING NEW VIDEO: " . mysql_error() . "|"; }
										
										$new_filename = "video__" . $video_id . ".flv";
										$new_thumbnail = "video__" . $video_id . ".jpg";
										
										$debug.= "Image Filename: <code>".$new_thumbnail."</code>|";
										$debug.= "FLV Filename: <code>".$new_filename."</code>|";

										# --------------------------------
										# CONVERT AND PROCESS VIDEO
										# MOVE THE VIDEO FROM USER FOLDER
										# --------------------------------
										if( is_numeric($video_id) && $video_id > 0 ) 
										{
											$old_file = $file_dir."/".$day_dir."/".$filename;
											$flv_file = $file_dir."/".$day_dir."/".$new_filename;
											$new_thumbnail = $video_path."/img/".$day_dir."/".$new_thumbnail;
											
											$ffmpeg = "/usr/bin/ffmpeg";
											$flvtool2 = "/usr/bin/flvtool2";
											$mencoder = "/usr/bin/mencoder";
											
											$video = new ffmpeg_movie($old_file);
											$video_width = $video->getFrameWidth();
											$video_height = $video->getFrameHeight();
											$video_fps = $video->getFrameRate();
											$video_fps = preg_replace('/(.*?)\..*/i','$1',$video_fps);
											$video_duration = $video->getDuration();
											$video_duration = preg_replace('/(.*?)\..*/i','$1',$video_duration);
											$thumb_pos = sec2hms($video_duration / 2);

											$debug .= "Video Width: " . $video_width . "|";
											$debug .= "Video Height: " . $video_height . "|";
											$debug .= "Video FPS: " . $video_fps . "|";
											$debug .= "Video Duration: " . $video_duration . "|";
											$debug .= "Thumbnail Position: " . $thumb_pos . "|";
											
											$ffmpeg_cmd = "";
											# FFMPEG COMMAND TO CONVERT VIDEO FILE!
											$ffmpeg_cmd = "$mencoder $old_file -o $flv_file -of lavf -oac mp3lame -lameopts abr:br=56 -ovc lavc -lavcopts vcodec=flv:vbitrate=512:mbd=2:mv0:trell:v4mv:cbp:last_pred=3 -lavfopts i_certify_that_my_video_stream_does_not_use_b_frames -vf scale=720:480 -srate 22050";

											$debug .= "\nCONVERT COMMAND: <code>".$ffmpeg_cmd."</code>\n|";

											@exec($ffmpeg_cmd, $convert_result);
											foreach($convert_result as $value) { $video_debug .= $value."|"; }

											$new_filesize = @filesize($flv_file);
											$debug .= "NEW FILESIZE: " . $new_filesize . "|";

											# DELETE RAW VIDEO
											//@unlink($new_file);
												
											# CREATE THUMBNAIL
											$thumb_cmd = "$ffmpeg -i $flv_file -ss $thumb_pos -t 00:00:01 -s '320x240>' -r 1 -f mjpeg $new_thumbnail";

											$debug .= "\nTHUMBNAIL COMMAND: <code>".$thumb_cmd."</code>\n|";

											@exec($thumb_cmd, $thumb_result);

											# Convert and resize thumbnail
											system('convert ' . $new_thumbnail . ' -thumbnail "320x240>" -gravity center -crop 140x100+0+0  +repage ' . $new_thumbnail);

											foreach($thumb_result as $value) { $video_debug .= $value."|"; }

											# -----------------------------
											# INSERT NEW STUFF TO DATABASE
											# -----------------------------
											$sql_convert = "UPDATE war_videos SET converted=1, date_converted=".$date_now.", duration=".$video_duration.", height=".$video_height.", width=".$video_width.", frames=".$video_fps.", new_filesize=".$new_filesize." WHERE video_id=".$video_id;
											$debug .= "UPDATE VIDEO SQL: " . $sql_convert . "|";

											$convert = mysql_query($sql_convert);

											if($convert) { 
												if(!$display_online) { EmailAlert(2,$admin_emails); }
												echo '<script type="text/javascript">window.location = "upload.php?vid='.$video_id.'"</script>'; 
											} 
											else 
											{
												echo "<h3 class=\"red\">Error Converting Video!</h3>\n";
												echo "<p>We have logged the error and will investigate a solution as quickly as possible. If you have any other questions or comments please contact <em>bugs@warhammerguilds.net</em></p>\n";
											}
										}
									}
									else
									{
										echo "<h3 class=\"red\">Error Uploading File!</h3>\n";
										echo "<p class=\"red\">Please contact <em>admin@warhammerguilds.net</em> and we will assist you as quickly as possible.</p>\n";
									}
								}
								else
								{
									$error_output = split('\|', $vid_error_msg);
									echo "<ul class=\"ul-error\">\n";
									foreach($error_output as $value) { 
										if( strlen($value) > 0 ) { 
											echo "<li>".$value."</li>\n"; 
										}
									}
									echo "</ul>\n";
									echo "<h3 class=\"blue\"><a href=\"upload.php?type=vid\">Try Uploading Another Video</a></h3>\n";
								}
							}
							else
							{
								if( $allow_access ) { @include("war/html/war-upload-video.tpl"); }
							}
							# LOG EVERYTHING THAT HAPPENED
							warCompleteLog($debug,$debug_log);
							warCompleteLog($video_debug,$video_log);
						}

						#############################################################
						#############################################################
						##														   ##
						##	PROCESS INTERFACE MOD FILES							   ##
						##														   ##
						#############################################################
						#############################################################

						elseif( $_GET['type'] == "ui" && $allow_access )
						{
							$display_form = 1;
							# -----------------------------------
							# DISPLAY THE INTERFACE UPLOAD FORM
							# -----------------------------------
							if( isset($_POST['upload-uimod']) && $display_form == 1 )
							{
								$day = date('Ymd',$date_now);
								$file_dir = "/home/thezdin/public_html/war-content/war-uimods";
								$author = $user->data['user_id'];
								$user_ip = $_SERVER['REMOTE_ADDR'];
								$browser_details = $_SERVER['HTTP_USER_AGENT'];
								$description = ValidateFormText($_POST['description']);
								$category = $_POST['category'];
								$date_submitted = $date_now;
								$filename = $_FILES['uimod']['name'];
								$filename_no_ext = preg_replace('/(.*)\..*/i','$1',$filename);
								$ext = preg_replace('/.*\.(.*?)/i','$1',$filename);
								$filename = preg_replace('/(.*)\.(.*)/i','$1_'.$date_now.'.$2',$filename);
								$ui_filename = $file_dir."/".$day."/".$filename;
								
								# GENERATE THE PREVIEW IMAGE
								if( filesize($_FILES['image']['tmp_name']) > 0 ){ 
									$img_flag = 1;
									$imagename = $_FILES['image']['name'];
									$imagename = preg_replace('/(.*)\.(.*)/i','$1_'.$date_now.'.jpg',$imagename);
									$ui_img_filename = $file_dir."/img/".$day."/".$imagename;
								} else { 
									$img_flag = 0; 
								}
								
								if( $_POST['comments_on'] ){
									$comments = 1; }
								else {
									$comments = 0; }

								# -------------
								# DEBUG VALUES
								# -------------
								$debug.= "<strong>UI MOD UPLOAD DEBUGGING</strong>|";
								$debug.= "Author: <code>".$author."</code>|";
								$debug.= "Category: <code>".$category."</code>|";
								$debug.= "Date Submitted: <code>".date('m/d/Y H:i:a',$date_submitted)."</code>|";
								$debug.= "UI Filename: <code>".$filename."</code>|";
								$debug.= "Image Filename: <code>".$imagename."</code>|";

								# --------------------------------------------
								# CREATE "day" DIRECTORY IF IT DOES NOT EXIST
								# ie: war-content/war-folder/20070101/
								# --------------------------------------------
								if( !file_exists($file_dir . "/" . $day . "/" ) ) { 
									ftpmkdir('/public_html/war-content/war-uimods/', $day);
									$index_file = "/home/thezdin/public_html/war-content/war-uimods/".$day."/index.php";
									$file_handle = fopen($index_file, 'a+');
									fclose($file_handle);
									$debug .= "MAKE DIRECTORY:<br /><code>" . $file_dir . "/" . $day . "/</code>|";
								}

								# -------------------------------------------------------
								# CREATE "day" DIRECTORY FOR IMAGES IF IT DOES NOT EXIST
								# ie: war-content/war-interface/20070101/
								# -------------------------------------------------------
								if( !file_exists($file_dir."/img/".$day."/" ) ) { 
									ftpmkdir('/public_html/war-content/war-uimods/img/', $day);
									$index_file = "/home/thezdin/public_html/war-content/war-uimods/img/".$day."/index.php";
									$file_handle = fopen($index_file, 'a+');
									fclose($file_handle);
									$debug .= "MAKE DIRECTORY:<br /><code>" . $file_dir . "/img/" . $day . "/</code>|";
								}
								
								# ----------------------
								# Error Checking
								# ----------------------
								$ui_error_flag = 0;

								if ( $_FILES['preview_image']['type'] == "image/jpg" || $_FILES['preview_image']['type'] == "image/jpeg" || $_FILES['preview_image']['type'] == "image/pjpeg" || $_FILES['preview_image']['type'] == "image/png" || $_FILES['preview_image']['type'] == "image/gif" || !$_FILES['preview_image']['name'] )
								{
									$ui_error_flag = 0;
								}
								else
								{
									$ui_error_flag = 1;
									$ui_error_msg .= "Invalid Image Type (.jpg, .gif, .png only)|";
								}

								if( !$_FILES['uimod']['name'] )
								{
									$ui_error_flag = 1;
									$ui_error_msg .= "Please Select an Interface File to Upload|";
								}

								if( filesize($_FILES['preview_image']['tmp_name']) > 25165824 )
								{
									$ui_error_flag = 1;
									$ui_error_msg .= "Invalid Filesize (maximum 3MB)|";
								}

								if( filesize($_FILES['uimod']['tmp_name']) > 83886080 )
								{
									$ui_error_flag = 1;
									$ui_error_msg .= "Invalid UI Mod Filesize (Maximum 10MB)|";
								}

								if( $category == -1 )
								{
									$ui_error_flag = 1;
									$ui_error_msg .= "Please Select a Category|";
								}

								# --------------------------------------------------------
								# There was an error, alert the user and log if necessary
								# --------------------------------------------------------
								if( $ui_error_flag ) 
								{
									if( $allow_access )
									{ 
										$error_output = split('\|', $ui_error_msg);
										echo "<ul class=\"ul-error\">\n";
										foreach($error_output as $value) { 
											if( strlen($value) > 0 ) { 
												echo "<li>".$value."</li>\n"; 
											}
										}
										echo "</ul>\n";
										@include("war/html/war-upload-uimod.tpl"); 
									}
									else
									{ 
										@include("war/html/war-unauthorized.tpl");
										warLog("\nUnauthorized Access By IP: " . $_SERVER['REMOTE_ADDR'], $admin_log);
										warLog("Attempted Access: " . date('m/d/Y H:i:a',$date_now), $admin_log);
									}
								}
								else
								{
									# ---------------------------------------------------
									# COPY THE INTERFACE FILE TO LOCAL CONTENT DIRECTORY
									# ---------------------------------------------------
									move_uploaded_file($_FILES['uimod']['tmp_name'],$ui_filename);
									$ui_filesize = filesize($ui_filename);
									$debug .= "UI Filesize: " . $ui_filesize . "|";

									# -------------------------------------------
									# PROCESS IMAGE IF THERE WAS ONE FOR THIS UI
									# -------------------------------------------
									if( $img_flag == 1 ) 
									{ 
										move_uploaded_file($_FILES['image']['tmp_name'], $ui_img_filename );
										# ---------------------------------
										# CREATE THUMBNAIL 140 PIXELS WIDE
										# ---------------------------------
										system('convert ' . $ui_img_filename . ' -thumbnail "300x300<" -gravity center -crop 140x100+0+0  +repage ' . $ui_img_filename);

										$debug .= 'convert ' . $ui_img_filename . ' -thumbnail "300x300<" -gravity center -crop 140x100+0+0  +repage ' . $ui_img_filename . '|';
									}

									# ------------------------------
									# INSERT NEW RECORD TO DATABASE
									# ------------------------------
									if( file_exists($ui_filename) )
									{
										if( $special_user ) { $display_online = 1; } else { $display_online = 0; }
										$sql_ui = "INSERT INTO war_uimods (user_id,date_submitted,display_online,category,description,original_filename,ext,filesize,image,comments_on) VALUES($author,$date_submitted,$display_online,$category,'$description','$filename_no_ext','$ext',$ui_filesize,$img_flag,$comments)";

										$debug.= "Insert New Interface Mod: <code><span class=\"small\">" . $sql_ui . "</span></code>\n";

										$result_ui = mysql_query($sql_ui);
										if( $result_ui )
										{	
											$uimod_id = getInsertID();

											# -------------------------------------------
											# RENAME IMAGES TO image_(screenshot_id).jpg
											# -------------------------------------------
											$new_ui_img = $file_dir. "/img/".$day."/uimod__" . $uimod_id . ".jpg";
											@rename($ui_img_filename,$new_ui_img);

											echo '<script type="text/javascript">window.location = "upload.php?ui='.$uimod_id.'"</script>';
										}
										else 
										{ 
											warLog("\nERROR UPLOADING INTERFACE: " . mysql_error(), $debug_log);
											warLog("Date of Error: " . date('l dS \of F Y h:i:s A'), $debug_log);
											echo '<script type="text/javascript">window.location = "upload.php?t=ui&result=error"</script>'; 
										}
									}
									else
									{
										warLog("ERROR INSERTING NEW INTERFACE TO DATABASE",$debug_log);
										warLog("Date of Error: " . date('l dS \of F Y h:i:s A'), $debug_log);
										warLog("User IP: (" . $_SERVER['REMOTE_ADDR'] . ")",$debug_log);
										warLog("Email: (" . GetUserEmail($user->data['user_id']) . ")",$debug_log);
									}
								}

								# OUTPUT DEBUGGING STUFF
								warCompleteLog($debug,$debug_log);
							}
							else
							{
								if( $_GET['result'] == "no" )
								{
									$img_error_flag = 1;
									$img_error_msg = "You've encountered a truly unique bug here..<br /><br />Please contact <a href=\"mailto:bugs@warhammerguilds.net?Subject=Interface Upload Bug\">bugs@warhammerguilds.net</a> so we can make sure this does not happen again.<br /><br />If you're feeling lucky...try uploading again!";
									$error_output = split('\|', $img_error_msg);
									echo "<ul class=\"ul-error\">\n";
									foreach($error_output as $value) { 
										if( strlen($value) > 0 ) { 
											echo "<li>".$value."</li>\n"; 
										}
									}
									echo "</ul>\n";
								}

								# ----------------------------------------------------
								# The form has not been completed, so we need to
								# display it for the user to input valid data.
								# But first we must make sure they are a registered
								# user, or we'll let them know they need to register.
								# ----------------------------------------------------
								if( $display_form )
								{
									if( $allow_access )
										{ @include("war/html/war-upload-uimod.tpl"); }
									else
										{ @include("war/html/war-unauthorized.tpl"); }
								}
							}
						}
						else
						{
								# --------------------------------------------------------------
								# The user must have accessed this page directly. We need
								# to make sure they aren't accessing functionality incorrectly
								# so kill the page and prompt them here to start over.
								# --------------------------------------------------------------
							if( $allow_access ) {
						?>
							<h3>Please Select From Below</h3>
							<p>We weren't able to determine what kind of file you're attempting to upload. Please select from below and we'll get you started with how to contribute content to the <? echo SITE_NAME; ?> website!</p>
							<ul class="ul-links">
							<li><a href="upload.php?type=vid">Video</a></li>
							<li><a href="upload.php?type=img">Screenshot</a></li>
							<li><a href="upload.php?type=ui">Interface/Mod</a></li>
							</ul>

						<? 
							} else { echo "<h3 class=\"red\">You Must Be Registered to Contribute</h3>\n"; }
						}
					}
					else
					{
						echo "<h3>Your Upload Was Successful!</h3>\n";
						echo "<p>We currently have a five minute timer for all interface and screenshot uploads to prevent the server from being overloaded. If you are interested in contributing to WARGuilds by donating bandwidth or server space please contact <a href=\"mailto:admin@warhammerguilds.net?Subject=Bandwidth\">admin@warhammerguilds.net</a>.</p>";
						echo "<p>You may upload again at: " . date("m/d/Y H:i:a", $timer_end);
					}
			?>

			<!-- End Content -->
			</div>
		</div>
	</div>

	<div id="war-footer"><div style="margin: 10px;"><? @include("war/html/war-footer.tpl"); ?></div></div>

</div>
</div>

</body>
</html>

<!-- Begin Page Close Code -->
<? @include("inc/war-close.php"); ?>
<!-- End Page Close Code -->