<?

$today = date('m/d/Y');
$sql = "SELECT * FROM ".WAR_DB.".war_videos WHERE daily_spotlight='".$today."'";
$result = mysql_query($sql);
$count = mysql_num_rows($result);

if( $count == 1 )
{
	while( $video = mysql_fetch_array($result) )
	{
		$large_thumbnail = preg_replace('/(.*?)\.jpg/is','$1_large.jpg',$video['new_thumbnail']);
		echo "<a href=\"video.php?v=".$video['video_id']."\">\n";
		echo "<img width=\"240\" alt=\"".$video['title']."\" src=\"files/preview/".$large_thumbnail."\" />\n";
		echo "</a>\n";
	}
}
elseif( $count == 0 )
{
	// If no video is selected for "today" then select a random video that has been highlighted before.
	$sql = "SELECT * FROM ".WAR_DB.".war_videos WHERE daily_spotlight IS NOT NULL LIMIT 1";
	$result = mysql_query($sql);
	while( $video = mysql_fetch_array($result) )
	{
		$large_thumbnail = preg_replace('/(.*?)\.jpg/is','$1_large.jpg',$video['new_thumbnail']);
		echo "<a href=\"video.php?v=".$video['video_id']."\">\n";
		echo "<img width=\"240\" alt=\"".$video['title']."\" src=\"files/preview/".$large_thumbnail."\" />\n";
		echo "</a>\n";
	}
}

//echo "<p class=\"small\"><strong>Date Information</strong><br />".$today."</p>\n";
?>