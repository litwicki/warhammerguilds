<?PHP

include("includes/config.php");
include("includes/functions.php");
include("includes/session.php");

##################################################
##################################################
##												##
##  SACRIFICE GUILD WARHAMMER WEBSITE			##
##  Developed with intent to provide code		##
##  to other WAR related guilds, WITH the		##
##  maintained Copyright and credit to			##
##  SACRIFICE & Thezdin. PLEASE respect the		##
##  hundreds of hours of work and retain all	##
##  comments and Copyrights.					##
##												##
##  SACRIFICE - http://www.sacrificeguild.org	##
##  Thezdin - http://www.thezdin.com/			##
##												##
##################################################
##################################################

if( !isset($_GET['id']) ){ $id = "4,5,6,7,8"; }
elseif( isset($_GET['id']) ) { $id = $_GET['id']; }

define('FORUM_ID', $id);
define('FORUM_URL',			HOME_PATH . "forums/viewtopic.php?");

include($phpbb_root_path . 'includes/functions_display.php');
include($phpbb_root_path . 'includes/bbcode.php');

$COMMENTS_NONE = "<img height=\"18\" width=\"18\" alt=\"News Comments Icon\" src=\"" . HOME_PATH.IMAGE_PATH.COMMENTS_NONE . "\" />";
$COMMENTS_ICON = "<img height=\"18\" width=\"18\" alt=\"News Comments Icon\" src=\"" . HOME_PATH.IMAGE_PATH.COMMENTS_ICON . "\" />";
$COMMENTS_HOT = "<img height=\"18\" width=\"18\" alt=\"News Comments Icon\" src=\"" . HOME_PATH.IMAGE_PATH.COMMENTS_HOT . "\" />";

$FILE_ICON = "<img height=\"18\" width=\"18\" style=\"margin-bottom: -6px;\" alt=\"Download Icon\" src=\"" . HOME_PATH.IMAGE_PATH.DOWNLOAD_ICON . "\" />";

$query =
"SELECT u.user_id, u.username, u.user_id, t.topic_attachment, t.topic_title, t.topic_poster, t.forum_id, t.topic_id, t.topic_time, t.topic_views, t.topic_replies, t.topic_first_post_id, p.poster_id, p.topic_id, p.post_id, p.post_text, p.bbcode_bitfield, p.bbcode_uid
FROM ".USERS_TABLE." u, ".TOPICS_TABLE." t, ".POSTS_TABLE." p
WHERE u.user_id = t.topic_poster
AND u.user_id = p.poster_id
AND t.topic_id = p.topic_id
AND p.post_id = t.topic_first_post_id
AND t.forum_id IN (".FORUM_ID.")
ORDER BY t.topic_time DESC";

$result = $db->sql_query_limit($query, POST_LIMIT);
$posts = array();
$news = array();
$bbcode_bitfield = '';
$message = '';
$poster_id = 0;

while ($r = $db->sql_fetchrow($result))
{
	$posts[] = array(
	 'topic_id' => $r['topic_id'],
	 'topic_time' => $r['topic_time'],
	 'username' => $r['username'],
	 'user_id' => $r['user_id'],
	 'topic_title' => $r['topic_title'],
	 'post_text' => $r['post_text'],
	 'bbcode_uid' => $r['bbcode_uid'],
	 'bbcode_bitfield' => $r['bbcode_bitfield'],
	 'topic_replies' => $r['topic_replies'],
	 'topic_views' => $r['topic_views'],
	 'topic_attachment' => $r['topic_attachment'],
	);
	$bbcode_bitfield = $bbcode_bitfield | base64_decode($r['bbcode_bitfield']);
}

// Instantiate BBCode
if ($bbcode_bitfield !== ''){
	$bbcode = new bbcode(base64_encode($bbcode_bitfield));
}

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

#####################################################################################################

include_once(HOME_PATH . "template/html/header.tpl");

?>
<!-- BEGIN CONTENT -->
<table border="0" cellspacing="0">
<tr>

<td class="left_column">
<?PHP include_once(HOME_PATH . "template/html/side.tpl"); ?>
</td>

<td class="right_column">

<div id="home-header">
	
	<div style="display: inline; float: right; margin-top: 20px; width: 250px;">
	<p style="text-align: center; margin-bottom: 4px; padding: 0px;">
	<span style="font-family: Georgia; font-style: italic; font-size: 1.4em; color: #555;">Video Spotlight!</span>
	</p>
	<div id="video-block"><?PHP @include("includes/daily_video.php"); ?></div>
	<div id="video-block-bottom">&nbsp;</div>
	</div>

	<div id="intro-block">
	<h1>WARGuilds Community Network</h1>
	<p>WARGuilds brings its readers and community members the latest, most relevant WAR news available. Our contributing writers have extensive experience in a wide array of MMO games; several with seasoned backgrounds in Dark Age of Camelot, World of WarCraft, EverQuest, and more. What this means to our readers is that the news and updates found on WARGuilds will be what you want to know and read about.</p><p>WARGuilds features a unique WAR video community that also has an endless supply of WAR news, discussion, guild rankings, and <strong>free guild web-hosting</strong>. Look for constant, obsessive updates to guild rankings, RvR trash talking, ongoing community discussions on strategy, guides, and everything WAR!</p>
	</div>
</div>

<div id="news">
<!-- BEGIN NEWS -->
<?
$count = 0;
	// Output the posts
	foreach($posts as $m)
	{
		$TOPIC_LINK = FORUM_URL . "t=" . $m['topic_id'];
		$PROFILE_LINK = "http://www.warhammerguilds.net/forums/memberlist.php?mode=viewprofile&amp;u=" . $m['user_id'];

		$file_flag = $m['topic_attachment'];
		if( $file_flag )
		{
			// Get the number of attachments
			$sql = "select attach_id, extension, real_filename, filesize, download_count from ".ATTACHMENTS_TABLE." where topic_id='" . $m['topic_id'] . "'";
			$attachments = mysql_query($sql);
			$num_rows = mysql_num_rows($attachments);
			
			// Build array of attachments - only worry about image/zip/rar files
			while( $a = mysql_fetch_array($attachments) )
			{
				// Is it an image?
				if( ereg("jpg|gif|jpeg",$a['extension']) )
				{
					$IMG .= "<a rel=\"lightbox\" href=\"" . HOME_PATH . FORUM_PATH . "download.php?id=" . $a['attach_id'] . "\">\n";
					$IMG .= "<img alt=\"" . $a['real_filename'] . "\" src=\"../" . HOME_PATH . FORUM_PATH . "download.php?id=" . $a['attach_id'] . "\" /></a>";
				}

				// Is it a zip/rar file?
				if( ereg("zip|rar",$a['extension']) )
				{
					$FILE .= "<a href=\"" . HOME_PATH . FORUM_PATH . "download.php?id=" . $a['attach_id'] . "\">" . $a['real_filename'] . "</a>" . $FILE_ICON . "<br />";
					$details .= "<span class=\"small\">(" . $a['filesize'] . " kb) Downloads [ " . $a['download_count'] . " ]</span>\n";
				}
			}
		}

		$poster_id = $m['user_id'];
		$message = $m['post_text'];
		if($m['bbcode_bitfield']){ $bbcode->bbcode_second_pass($message, $m['bbcode_uid'], $m['bbcode_bitfield']); }
		
		$full_text = parse_news($message,0);
		$message = parse_news($message,1);

		// If there are images then build a <div> for them.
		if( $IMG != "" ) 
		{ $message .= "<div class=\"news-img\">\n\t" . $IMG . "\n</div>\n"; $IMG = ""; }
		
		// If there are files then build a <div> for them.
		if( $FILE  != "" ) { $message = $message . "<p class=\"file\">\n" . $FILE . "\n\t" . $details . "</p>\n"; $FILE = ""; $details = ""; }

		if( $m['topic_replies'] == 1 )
			$comment = "Comment";
		else
			$comment = "Comments";

		if( $m['topic_replies'] > 0 )
		{ $C_ICON = $COMMENTS_ICON; }
		
		if( $m['topic_replies'] > 9 )
		{ $C_ICON = $COMMENTS_HOT; }

		if( $m['topic_replies'] == 0 )
		{ $C_ICON = $COMMENTS_NONE; }
		
		$avatar = SERVER_PATH.IMAGE_PATH."avatars/".strtolower($m['username'])."_avatar.gif";
		if( !file_exists($avatar) )
		{
			$USER_ICON = "<a href=\"".$PROFILE_LINK."\">\n";
			$USER_ICON .= "<img class=\"avatar\" alt=\"Default Avatar\" src=\"".HOME_PATH.IMAGE_PATH."avatars/default_avatar.gif\" />\n";
			$USER_ICON .= "</a>\n";
		}
		else
		{
			$USER_ICON = "<a href=\"".$PROFILE_LINK."\">\n";
			$USER_ICON .= "<img class=\"avatar\" alt=\"".$m['username']."'s Avatar\" src=\"".HOME_PATH.IMAGE_PATH."avatars/".strtolower($m['username'])."_avatar.gif\" />\n";
			$USER_ICON .= "</a>\n";
		}

		include("template/html/news.tpl");
		
		unset($message,$poster_id);
		$count++;
	}

?>
</div>
</td>
</tr>
</table>
<!-- END CONTENT -->

<?PHP include_once(HOME_PATH . "template/html/footer.tpl"); ?>

<?PHP mysql_close($conn); ?>