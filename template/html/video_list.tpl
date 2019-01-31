<? 
$img = "/home/thezdin/public_html/files/preview/".$list['new_thumbnail'];
if( !preg_match('/.*jpg/is',$img ) ){ $small_thumb = "warguilds_120.jpg"; }
else { $small_thumb = $list['new_thumbnail']; }
$duration = str_replace("00:","",$list['duration']);
$duration = str_replace("00:","",$duration);
$duration = preg_replace('/^(\d{2})$/is','0:$1',$duration);
if( !preg_match('/\d+/is',$duration) ) { $duration = "NA"; }
?>

<div class="<? echo $video_row; ?>">
<div style="float: left;">
<a href="video.php?v=<? echo $list['video_id']; ?>"><img height="90" width="120" alt="<? echo $list['title']; ?>" src="files/preview/<? echo $small_thumb; ?>" /></a><br />
</div>
<div style="margin-left: 140px; width: auto;">
<span style="font-size: 10pt; font-weight: bold"><? echo $list['title']; ?></span><br />
<strong>Author - </strong><? echo $list['author']; ?><br />

<? 
if( $list['display_guild'] == 1 )
{
	$sql_guild = "select pf_guildname, pf_guildsite from war_profile_fields_data where user_id=".$user_id;
	$result_guild = mysql_query($sql_guild);
	while( $guild = mysql_fetch_array($result_guild) ) {
		echo "<strong>Guild</strong> - <a href=\"".$guild['pf_guildsite']."\">" . $guild['pf_guildname'] . "</a><br />\n";
	}
}
?>
<strong>Time - </strong><? echo $duration; ?><br />
<strong>Views</strong> (<? echo $list['total_views']; ?>)<br />
</div>
</div>