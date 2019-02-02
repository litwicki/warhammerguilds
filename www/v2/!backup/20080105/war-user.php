<? 
# ------------------
# DISABLE ADMIN URL
# ------------------
if( $_GET['admin'] == "on" && WarAccessLevel($user->data['user_id'],1) )
{
	$url = $_SERVER['REQUEST_URI']; 
	$url = str_replace("?admin=on","", $url); 
	$url = str_replace("&admin=on","",$url);
}
?>

<div style="float: left; width: 200px; font-size: 8pt;">

Welcome to WARGuilds<? if( $user->data['username'] != "Anonymous" ) { 
	echo ", " . $user->data['username'] . "!";
	echo "<br /><a title=\"Log Out of the WARGuilds Website - Goodbye!\" class=\"Tips1\" href=\"forums/ucp.php?mode=logout&amp;sid=".$user->data['session_id']."\">Logout</a>";
} else { 
	echo '<br /><a href="info.php?p=join">Join WARGuilds</a>';
}
?>
 
<a title="Upload screenshots, videos, and more to the website!" class="Tips1" href="upload.php">Upload Content</a><br />
<? 
if( WarAccessLevel($user->data['user_id'],1) ) {
	if($_GET['admin'] == "on" ) { echo '<a title="Disable Administration" class="Tips1" href="'.$url.'">Disable Admin</a>'; }
	else						{ echo '<a title="Enable Administration" class="Tips1" href="'.$admin_link.'">Enable Admin</a>'; }
}
?>

</div>

<div style="float: left; width: 735px; text-align: right;">
<? 
$content = http_fetch("http://www.warhammerguilds.net/war-ads.php?size=large");
echo $content;
?>
</div>