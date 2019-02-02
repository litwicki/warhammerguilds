<?PHP

@require("config.php");

################################################################################################

function ResizeScreenshot($img_sourse, $save_to, $quality, $width, $str)
{
	$size = GetImageSize($img_sourse);
	$im_in = ImageCreateFromJPEG($img_sourse);

	$new_height = ($width * $size[1]) / $size[0]; // Generate new height for image
	$im_out = imagecreatetruecolor($width, $new_height);

	ImageCopyResampled($im_out, $im_in, 0, 0, 0, 0, $width, $new_height, $size[0], $size[1]);
	   
	#Find X & Y for note
	$X_var = ImageSX($im_out);
	$X_var = $X_var - 130;
	$Y_var = ImageSY($im_out);
	$Y_var = $Y_var - 25;

	#Color
	$white = ImageColorAllocate($im_out, 0, 0, 0);

	#Add note(simple: site address)
	#ImageString($im_out,2,$X_var,$Y_var,$str,$white);

	ImageJPEG($im_out, $save_to, $quality); // Create image
	ImageDestroy($im_in);
	ImageDestroy($im_out);

	return 1;
}

################################################################################################

function getmicrotime() {

	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);

}

################################################################################################

function parse_news($text, $int){

	$text = preg_replace('/<div class=\"inline-attachment\">.*?<\/div>/', "", $text);
	$text = str_replace("class=\"postlink\"", "", $text);
	$text = str_replace("\n", "<br />\n", $text);
	$text = preg_replace('/<br \/>+/','<br />',$text);

	# ---------------------------------------------										
	#	If the length of the news post is longer
	#	than specified value, concatenate the post
	#	and display a View Full Story link
	#
	#	POST_LENGTH is defined in config.php
	# ---------------------------------------------
	if( $int == 1 && strlen($text) > POST_LENGTH )
	{	
        $text = $text . " ";
        $text = substr($text,0,300);
        $text = substr($text,0,strrpos($text,' '));
        $text = $text . "...";

		# --------------------------------------------
		# Fix unclosed HTML tags for XHTML validation
		# --------------------------------------------
		if( preg_match('/.*?<li>.*(...)$/',$text) ){
			$text = $text . "</li>\n</ul>\n"; }
		if( preg_match('/.*?<span.*?>.*(...)$/',$text) ){
			$text = $text . "</span>\n"; }
		if( preg_match('/<\/span>.*?<\/span>$/',$text) ){
			$text = preg_replace('/(.*)<\/span>$/','$1',$text); }

		$text = str_replace("<span...","...",$text);
	}
  return $text;
}

################################################################################################

function in_group($name, $group)
{
	# -----------------------------------------------
	# Make sure we are checking the correct database
	# -----------------------------------------------
	$db = mysql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD);
	mysql_select_db(FORUM_DB,$db);
	
	# ------------------------------------------------------------
	# Translate the group names to their group_id in the database
	# 2 - Registered Users (Default for registrations)
	# 5 - Administrators
	# 7 - Contributors (WAR Admin)
	# 8 - Members
	# ------------------------------------------------------------=
	
	if( $group == "registered" )
		$group_id = 2;
	if( $group == "admin" )
		$group_id = 5;
	if( $group == "writer" )
		$group_id = 7;
	if( $group == "member" )
		$group_id = 8;
	
	# ---------------------
	# Is this a real user?
	# ---------------------
	$q =	"SELECT user_id " .
			"FROM war_users " .
			"WHERE username = '$name'";
	$result = mysql_query($q, $db);
	$row = mysql_fetch_array($result);
	$user_id = $row['user_id'];
	#------------------------------------------
	# Is the user part of the specified group?
	# -----------------------------------------
	$q =	"SELECT * " .
			"FROM war_user_group " .
			"WHERE user_id = '$user_id'";
	$result = mysql_query($q, $db);
	while( $row = mysql_fetch_array($result) ){
		if( ( $row['group_id'] == $group_id ) )
			return 1;
	}
		return 0;
}

################################################################################################

function valid_email($email){
	if( preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email) )
		return true;
	else
		return false;
}

################################################################################################

function sec2hms ($sec){
    // holds formatted string
    $hms = "";
    
    // there are 3600 seconds in an hour, so if we
    // divide total seconds by 3600 and throw away
    // the remainder, we've got the number of hours
    $hours = intval(intval($sec) / 3600); 

    // add to $hms, with a leading 0 if asked for
    $hms .= ($padHours) 
          ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
          : $hours. ':';
     
    // dividing the total seconds by 60 will give us
    // the number of minutes, but we're interested in 
    // minutes past the hour: to get that, we need to 
    // divide by 60 again and keep the remainder
    $minutes = intval(($sec / 60) % 60); 

    // then add to $hms (with a leading 0 if needed)
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';

    // seconds are simple - just divide the total
    // seconds by 60 and keep the remainder
    $seconds = intval($sec % 60); 

    // add to $hms, again with a leading 0 if needed
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

    // done!
    return $hms;
    
  }

################################################################################################

function video_duration($filepath) {

	$movie = new ffmpeg_movie($filepath);

	// Get the duration of the movie or audio file in seconds.
	if($filepath && $movie) { $duration = $movie->getDuration(); }
	else { $duration = 0; }

	// Return the duration as a formatted "HH:MM:SS" string.
	$hours = $duration / 3600;
	$minutes = ($duration % 3600) / 60;
	$seconds = ($duration % 3600) % 60;
	return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
}

################################################################################################

function video_seconds($filepath) {
	$movie = new ffmpeg_movie($filepath);
	if($filepath && $movie) { 
		$duration = $movie->getDuration(); 
	}
	else { 
		$duration = 0; 
	}
  return $duration;
}

################################################################################################

function fix_url($url)
{
	$old_url = $url;

	if( preg_match('|^http(s)?://[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $old_url) )
	{
		$new_url = $old_url;
		$new_url = preg_replace('/^(.*[org|net|com|info|us])\/$/i','$1',$old_url);
	}
	else
	{
		if( !preg_match('/^www.*$/i',$old_url) ) { $new_url = "www." . $old_url; }
		if( !preg_match('/^http.*$/i',$old_url) ) { $new_url = "http://" . $old_url; }
	}

  $new_url = trim($new_url);
  return $new_url;
}