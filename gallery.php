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
	<h1>WARGuilds Imagery Gallery</h1>
	<p>The WARGuilds screenshot gallery is a user contributed image gallery featuring all things WAR. Users are free to submit screenshots via the <a href="forums/viewforum.php?f=18">community forums</a> 
	for consideration for our daily screenshot. Without user contributions we will take screenshots of our favorite WARGuilds videos!</p>
	<!-- BEGIN GALLERY CODE -->
	<?PHP

	if(!isset($_GET['page'])){ $page = 1; } 
	else { $page = $_GET['page']; }

	$max_results = $_GET['ss_per_page'];
	if( !$max_results ) { $max_results = 15; }
	$from = (($page * $max_results) - $max_results); 

	$sql = "SELECT * FROM ".WAR_DB.".".SCREENSHOTS_TABLE." ORDER BY id DESC LIMIT $from, $max_results";
	$result = mysql_query($sql);
	$num_rows = mysql_num_rows($result);
	$count = 0;
	$columns = 3;
	if( mysql_num_rows($result) > 0 )
	{
		echo "\n<div style=\"width: 100%;\">\n";
		echo "<table class=\"gallery\">\n";

		for($i = 0; $i < $num_rows; $i++)
		{
			$row = mysql_fetch_array($result);
			if($i % $columns == 0) { echo "<tr>\n"; }

				echo "\n<td><div>\n<div class=\"dropshadow\">\n<div class=\"innerbox\">\n";
				echo "<a id=\"screenshot_".$i."\" href=\"".$row['filename']."\" class=\"highslide\" onclick=\"return hs.expand(this, {captionId: 'caption_".$i."'})\">\n";
				echo "\t<img class=\"tooltip\" src=\"".$row['thumbnail']."\" alt=\"Screenshot Gallery\" title=\"".$row['caption']."\" /></a>\n";
				echo "<div class='highslide-caption' id='caption_".$i."'>\n\t".$row['caption']."\n</div>\n";
				echo "</div>\n</div>\n</div></td>\n";

			if(($i % $columns) == ($columns - 1) || ($i + 1) == $num_rows) { echo "</tr>\n"; }
		}
	}

	echo "</table>\n</div>\n\n";

	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".WAR_DB.".".SCREENSHOTS_TABLE),0);
	$total_pages = ceil($total_results / $max_results);
	echo "<div class=\"pagination\">\n";

	if($page > 1){
		$prev = ($page - 1);
		echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$prev\"><img alt=\"previous\" src=\"" . HOME_PATH.IMAGE_PATH.PREV_ICON . "\" /></a>";
	}

	/*

	Uncomment this if you want to display 1 | 2 | 3 | 4 etc, style links.

	for($i = 1; $i <= $total_pages; $i++){
		if(($page) == $i){
			} else {
				echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$i\">$i</a> ";
		}
	}

	*/

	// Build Next Link
	if($page < $total_pages){
		$next = ($page + 1);
		echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$next\"><img alt=\"next\" src=\"" . HOME_PATH.IMAGE_PATH.NEXT_ICON . "\" /></a>";
	}
	echo "</div>";
	?>
	<!-- END GALLERY CODE -->

	</td>

  </tr>
</table>
<!-- END CONTENT -->

<?PHP 
@include(HOME_PATH . "template/html/footer.tpl"); 
?>

<?PHP mysql_close($conn); ?>