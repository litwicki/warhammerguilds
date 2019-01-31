<?PHP

$debug_print = 0; // 1 = on, 0 = off
##################################################

include("includes/config.php");
include("includes/functions.php");
include("includes/session.php");

if( !isset($_GET['p']) ) { $page = 1; }
else { $page = $_GET['p']; }

if($_GET['s'] == "search by username"){ header("Location: video.php"); }

##################################################

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

@include(HOME_PATH . "template/html/header.tpl");

?>
<!-- END HEADER -->

<!-- BEGIN CONTENT -->
<table border="0" cellspacing="0">
  <tr>

	<td class="left_column">
	<?PHP @include(HOME_PATH . "template/html/side.tpl"); ?>
	</td>

	<td class="right_column">
	<div style="float: right; margin-top: 26px; display: block;"><? @include("template/html/video_category.tpl"); ?></div>
	<h1>WARGuilds Video Gallery</h1>
	
	<p>The WARGuilds video gallery is intended for all registered users of the community to post videos of any flavor. Everything from your guilds latest accomplishments, to 
	an array of PvP videos, comedy, or just videos showing appreciation for the Warhammer Universe.</p>

	<?
		// max # of results per page, build limit query
		if( !$max_results ) { $max_results = 10; }
		$from = (($page * $max_results) - $max_results); 

		if( !isset($_GET['u']) && !isset($_GET['v']) && !isset($_GET['c']) && !isset($_GET['s']) )
		{
			$sql = "SELECT * FROM ".WAR_DB.".war_videos WHERE is_converted=1 AND display_online=1 ORDER BY video_id DESC LIMIT $from, $max_results";
			$debug .= "<p class=\"small\"><strong>View All SQL</strong><br /><code>" . $sql . "</code></p>\n";
			$result = mysql_query($sql);
			$count = mysql_num_rows($result);
			
			if( $count > 0 ) 
			{
				$video_row = "video_row2";
				while( $list = mysql_fetch_array($result) )
				{
					if( $video_row == "video_row1" ){ 
					$video_row = "video_row2"; }
					else { $video_row = "video_row1"; }

					@include("template/html/video_list.tpl"); 
					echo "\n\n"; 
				}
				$link = $_SERVER['PHP_SELF']."?";
				@include("template/html/video_pagination.tpl");
			}
		}
		elseif( isset($_GET['s']) )
		{
			$author = $_GET['s'];
			$sql_s = "SELECT DISTINCT author FROM ".WAR_DB.".war_videos WHERE author LIKE '%" . $author ."%'";
			$debug .= "<p class=\"small\"><strong>Author Search SQL</strong><br /><code>" . $sql_s . "</code></p>\n";
			$result_s = mysql_query($sql_s);
			$count_s = mysql_num_rows($result_s);
			$debug .= "<p class=\"small\"><strong>Author Search Result Count: </strong><code>" . $count_s . "</code></p>\n";
			if( $count_s > 0 ) 
			{
				echo "<p style=\"font-style: italic;\">The following users matched your search.</p>\n";
				echo "\n<ol>\n";
				while( $list_s = mysql_fetch_array($result_s) ){
					echo "<li><a href=\"video.php?u=".$list_s['author']."\">".$list_s['author']."</a></li>\n";
				}
				echo "</ol>\n\n";
			}
			else { echo "<p style=\"color: #ff0000; font-weight: bold;\">Sorry, I couldn't find any users that match your search!</p>\n"; }
		}
		elseif( isset($_GET['v']) )
		{
			$sql = "SELECT * FROM ".WAR_DB.".war_videos WHERE display_online=1 AND is_converted=1 AND video_id=".$_GET['v'];
			$result = mysql_query($sql);
			$count = mysql_num_rows($result);
			$debug .= "<p class=\"small\"><strong>Video ID SQL</strong><br /><code>" . $sql . "</code></p>\n";
			if( $count == 1 ){
				while( $video = mysql_fetch_array($result) )
				{
					$author				= $video['author'];
					
					if( in_group($author, "writer")) { $member_color = "green"; }
					if( in_group($author, "member")) { $member_color = "blue"; }
					if( in_group($author, "admin")) { $member_color = "red"; }
					if( in_group($author, "registered")) { $member_color = "black"; }

					$file				= $video['new_filename'];
					$title				= $video['title'];
					$description		= $video['description'];
					$duration			= $video['duration'];
					$category_id		= $video['category'];
					$sql_cat			= "SELECT category_name FROM ".WAR_DB.".war_video_categories WHERE category_id=".$category_id;
					$result_cat			= mysql_query($sql_cat);
					$total_views		= $video['total_views'];

					while($row = mysql_fetch_array($result_cat) ) { $category_name = $row['category_name']; }
					$debug .= "<p class=\"small\"><strong>Category Name SQL</strong><br /><code>" . $sql_cat . "</code></p>\n";
					$debug .= "<p class=\"small\"><strong>Category Name</strong><br /><code>" . $category_name . "</code></p>\n";


					$date_submitted		= date('m/d/Y',$video['date_submitted']);
					$thumbnail			= $video['new_thumbnail'];
					$large_thumbnail	= preg_replace('/(.*?)\.jpg/is','$1_large.jpg',$thumbnail);
					$debug .= "<p class=\"small\"><strong>Large Thumbnail</strong><br /><code>" . $large_thumbnail . "</code></p>\n";

					@include("template/html/video.tpl");
					//error check view_updates
					$new_views = $total_views + 1;
					$update_views = "UPDATE ".WAR_DB.".war_videos SET total_views=".$new_views." WHERE video_id=".$_GET['v'];
					$debug .= "<p class=\"small\"><strong>Update Video Views Query</strong><br /><code>" . $update_views . "</code><br /></p>\n";
					if( mysql_query($update_views) ){
						$debug .= "<p class=\"small\"><strong>Updated Video Views</strong><br /> Previous [".$total_views."] New Total [".$new_views."]</p>\n";
					}
				}
			}
			else { echo "<p>Invalid Video ID provided!</p>\n"; }
		}
		else
		{
			##################################################################################
			##																				##
			##	Build a search query from input box to display list of usernames that		##
			##	match the search input. We do this first because this isn't part of the		##
			##	querystring to display actual videos.										##
			##																				##
			##################################################################################
			
			$sql = "SELECT * FROM ".WAR_DB.".war_videos WHERE display_online=1 AND is_converted=1";

			if( isset($_GET['u']) ) { 
				$username = $_GET['u'];
				$sql .= " AND author='".$username."'";
				$q .= "u=".$_GET['u'];
			}
			if( isset($_GET['c']) ) { 
				$category = $_GET['c'];
				$sql .= " AND category=".$category;
				$q .= "c=".$_GET['c'];
			}
			
			$sql .= " ORDER BY video_id desc LIMIT $from, $max_results";
			$debug .= "<p class=\"small\"><strong>Dynamic SQL</strong><br /><code>" . $sql . "</code></p>\n";
			$result = mysql_query($sql);
			$count = mysql_num_rows($result);
			if( $count > 0 )
			{
				$video_row = "video_row2";
				while( $list = mysql_fetch_array($result) )
				{
					$author = $list['author'];
					$duration = $list['duration'];
					$title = $list['title'];
					$description = $list['description'];
					$category = $list['category'];
					$int_date = $list['date_submitted'];

					if( $video_row == "video_row1" ){ 
					$video_row = "video_row2"; }
					else { $video_row = "video_row1"; }

					@include("template/html/video_list.tpl");
				}
				$link = $_SERVER['PHP_SELF']."?".$q."&amp;";
				@include("template/html/video_pagination.tpl");
			}
			else{ echo "<p style=\"color: #ff0000; font-weight: bold;\">Sorry, I couldn't find any videos for this criteria!</p>\n"; }
		}
		//debugging
		if( $debug_print == 1 ) { echo $debug; }
	?>
	</td>

  </tr>
</table>
<!-- END CONTENT -->
<?PHP @include(HOME_PATH . "template/html/footer.tpl"); ?>

<?PHP mysql_close($conn); ?>