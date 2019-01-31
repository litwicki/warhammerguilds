<?

if( WarAccessLevel($user->data['user_id'],1) ) { $is_admin = 1; }

# --------------------------
# DEFINE VALUES FOR CONTENT
# --------------------------
if( $_SERVER['PHP_SELF'] == "/image.php" ) 
{ 
	$url_short = "image.php"; 
	$type = "image";
	$short_type = "img";
	$item_id = $_POST['image'];
	$get_id = $_GET['image'];
	$int_type = 1;
}

if( $_SERVER['PHP_SELF'] == "/video.php" ) 
{ 
	$url_short = "video.php"; 
	$type = "video";
	$short_type = "vid";
	$item_id = $_POST['video'];
	$get_id = $_GET['video'];
	$int_type = 2;
}

if( $_SERVER['PHP_SELF'] == "/uimod.php" ) 
{ 
	$url_short = "uimod.php"; 
	$type = "uimod";
	$short_type = "ui";
	$item_id = $_POST['uimod'];
	$get_id = $_GET['uimod'];
	$int_type = 3;
}

# ----------------------------------------------
# User Submitted Comments For Individual Object
# ----------------------------------------------
if( isset($_POST['add-comment']) ){
	if( ValidateComment($_POST['comment'], $item_id, $int_type, $user->data['user_id']) ){
		$num_comments++;
		header("location: " . $url_short."?".$short_type."=".$item_id);
	}
}

# ----------------------------------------------
# ADMINISTRATION FOR ADDING OBJECT TO SPOTLIGHT
# ----------------------------------------------
if( isset($_GET['a']) && $_GET['a'] == "spotlight")
{
	$url = $url_short;
	if( isset($_GET['spotlight']) )
	{
		if( preg_match('/(\d{4})(\d{2})(\d{2})/i',$_GET['StartDate']) && preg_match('/(\d{4})(\d{2})(\d{2})/i',$_GET['EndDate']) ) 
		{
			$start_year = preg_replace('/(\d{4})(\d{2})(\d{2})/i','$1',$_GET['StartDate']);
			$start_month = preg_replace('/(\d{4})(\d{2})(\d{2})/i','$2',$_GET['StartDate']);
			$start_day = preg_replace('/(\d{4})(\d{2})(\d{2})/i','$3',$_GET['StartDate']);

			$end_year = preg_replace('/(\d{4})(\d{2})(\d{2})/i','$1',$_GET['EndDate']);
			$end_month = preg_replace('/(\d{4})(\d{2})(\d{2})/i','$2',$_GET['EndDate']);
			$end_day = preg_replace('/(\d{4})(\d{2})(\d{2})/i','$3',$_GET['EndDate']);

			$start_date = mktime(0,0,0,$start_month,$start_day,$start_year);
			$end_date = mktime(0,0,0,$end_month,$end_day,$end_year);

			if( AddSpotlight($int_type, $get_id, $start_date, $end_date) ) 
			{
				warLog("\n".$type." ID(".$get_id.") Added to Spotlight By (".ucfirst($user->data['username']).") ON ".date('l dS \of F Y h:i:s A'),$admin_log);
				header("location: " . $url);
			}
		}
	}
	else
	{
		$display_spotlight_form = 1;
	}
}


# -----------------
# DISABLE COMMENTS
# -----------------
if( $_GET['a'] == "disable" && $is_admin )
{
	$url = $url_short."?".$short_type."=" . $get_id;
	if( DisableComments($int_type, $get_id) ) {
		warLog("\nCOMMENTS * DISABLED * FOR ".$type."(".$get_id.") BY (".$user->data['username'].") ON ".date('l dS \of F Y h:i:s A'),$admin_log);
		header("location: " . $url); 
	}
}
elseif( $_GET['a'] == "disable" && UserIsAuthor($int_type,$get_id,$user->data['user_id']) )
{
	$url = $url_short."?".$short_type."=" . $get_id;
	if( DisableComments($int_type, $get_id) ) {
		warLog("\nCOMMENTS * DISABLED * FOR ".$type."(".$get_id.") BY (".$user->data['username'].") ON ".date('l dS \of F Y h:i:s A'),$admin_log);
		header("location: " . $url); 
	}
}

# --------------------------------------
# BUILT IN ADMINISTRATION FUNCTIONALITY
# --------------------------------------
if( isset($_GET['a']) && isset($_GET['c']) )
{
	$url = $url_short."?".$short_type."=" . GetItemID($_GET['c']) ."&admin=on";

	# ---------------
	# ENABLE COMMENT
	# ---------------
	if( $_GET['a'] == "enable" && is_numeric($_GET['c']) && $is_admin )
	{
		if( EnableObject(4, $_GET['c']) ) {
			warLog("\n\nCOMMENT_ID(".$_GET['c'].") *ENABLED* BY (".$user->data['username'].") ON ".date('l dS \of F Y h:i:s A'),$admin_log);
			header("location: " . $url); 
		}
	}

	# ---------------
	# HIDE COMMENT
	# ---------------
	elseif( $_GET['a'] == "hide" && is_numeric($_GET['c']) && $is_admin )
	{
		if( HideObject(4,$_GET['c']) ) {
			warLog("\nCOMMENT_ID(".$_GET['c'].") *DISABLED* BY (".$user->data['username'].") ON ".date('l dS \of F Y h:i:s A'),$admin_log);
			header("location: " . $url);
		}
	}

	# ---------------
	# DELETE COMMENT
	# ---------------
	elseif( $_GET['a'] == "delete" && is_numeric($_GET['c']) && $is_admin )
	{
		$delete_comment = "DELETE FROM war_comments WHERE comment_id=".$_GET['c'];
		if( mysql_query($delete_comment) ) {
			warLog("\nCOMMENT_ID(".$_GET['c'].") **DELETED** BY (".$user->data['username'].") ON ".date('l dS \of F Y h:i:s A'),$admin_log);
			header("location: " . $url); 
		}
	}
}

?>