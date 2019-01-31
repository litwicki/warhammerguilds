<? 
include("war-open.php");

# ----------------------------------------------------
# THIS PAGE IS RESTRICTED TO ADMINISTRATORS ONLY
# If the user is not in the appropriate group, log
# their username and IP Address and forward them out.
# ----------------------------------------------------
if( !WarAccessLevel($user->data['user_id'],1) ){
	warLog("\n** ATTEMPTED ADMIN ACCESS TO IMAGE ADMINISTRATION **", $admin_log);
	warLog("IP Address of User: ".$_SERVER['REMOTE_ADDR'], $admin_log);
	warLog("Username: ".$user->data['username'], $admin_log);
	warLog("Browser Info: ".$_SERVER['HTTP_USER_AGENT'], $admin_log);
	warLog("Date of Log: " . date('l dS \of F Y h:i:s A'), $admin_log);
	warLog("URL QueryString: " . selfURL(), $admin_log);
	header("Location: index.php");
} 
else 
{ 
	$is_admin = 1; 
}

include("war/html/war-header.tpl");

?>

<div id="war-content">
		<div id="war-left"><div style="margin: 10px;"><? @include("war/html/war-left.tpl"); ?></div></div>
		
		<div id="war-center">
			<div style="padding: 2em; margin: auto;">
			<!-- Begin Content -->
			<h1>Screenshot Administration</h1>
			<? 
				
				# --------------------------------
				# Include the WAR Welcome Message
				# --------------------------------
				@include("war/html/war-welcome.tpl");
				
				# --------------------------------
				# Page specific content here
				# --------------------------------
				if( $is_admin == 1 )
				{
					# -------------------
					# PROCES SCREENSHOTS
					# -------------------
					if( isset($_GET['delete']) && is_numeric($_GET['delete']) && $is_admin )
					{
						if( mysql_query("UPDATE war_screenshots SET display_online=2 WHERE screenshot_id=" . $_GET['delete']) ) 
						{
							warLog("SCREENSHOT_ID(".$_GET['delete'].") DELETED BY (".$user->data['username'].") on " .date('m/d/Y H:i:a',$date_now), $admin_log);
							echo '<script type="text/javascript">window.location = "admin-image.php"</script>';
						}
					}
					if( isset($_POST['update-image']) && is_numeric($_POST['screenshot_id']) && $is_admin )
					{
						$screenshot_id = $_POST['screenshot_id'];
						$new_description = $_POST['description'];	
						$new_category = $_POST['category'];

						# ----------------------------
						# Activate The Selected Image
						# ----------------------------
						if( $activate_image = mysql_query("UPDATE war_screenshots SET display_online=1 WHERE screenshot_id=".$_POST['screenshot_id']) ) {
							warLog("\n\nscreenshot_ID(".$screenshot_id.") *ACTIVATED* BY (".$user->data['username'].") ON ".date('l dS \of F Y h:i:s A'),$admin_log);
						}

						# ----------------------------
						# Did the Description Change?
						# ----------------------------
						$check_description = mysql_query("SELECT description FROM war_screenshots WHERE screenshot_id=".$_POST['screenshot_id']);
						while( $row = mysql_fetch_array($check_description) ) {
							$old_description = $row['description']; }
						
						if( $old_description != $new_description )
						{ 
							$update_description = mysql_query("UPDATE war_screenshots SET description='".$new_description."' WHERE screenshot_id=".$_POST['screenshot_id']); 
							warLog("Description for screenshot_ID(".$screenshot_id.") Changed To: \"" . $new_description . "\"", $admin_log);
						}

						# ----------------------------
						# Did the Category Change?
						# ----------------------------
						$check_category = mysql_query("SELECT category FROM war_screenshots WHERE screenshot_id=".$_POST['screenshot_id']);
						while( $row = mysql_fetch_array($check_category) ) {
							$old_category = $row['category']; }
						
						if( $old_category != $new_category )
						{ 
							$update_category = mysql_query("UPDATE war_screenshots SET category='".$new_category."' WHERE screenshot_id=".$_POST['screenshot_id']); 
							warLog("Category for screenshot_ID(".$screenshot_id.") Changed To: (" . $new_category .")", $admin_log);
						}
						echo '<script type="text/javascript">window.location = "admin-image.php"</script>';
					}

					# --------------------------
					# Build Category NAME Array
					# --------------------------
					$cat_name_sql = "SELECT description FROM war_screenshot_categories ORDER BY category_id ASC";
					$cat_name_result = mysql_query($cat_name_sql);
					while( $cat = mysql_fetch_array($cat_name_result) ) {
						$cat_names .= $cat['description'] ."|"; }
					$category_name = split('\|',$cat_names);

					# ------------------------
					# Build Category ID Array
					# ------------------------
					$cat_id_sql = "SELECT category_id FROM war_screenshot_categories ORDER BY category_id ASC";
					$cat_id_result = mysql_query($cat_id_sql);
					while( $id = mysql_fetch_array($cat_id_result) ) {
						$cat_ids .= $id['category_id'] ."|"; }
					$category_id = split('\|',$cat_ids);

					# -------------------------------
					# Get Total Number of Categories
					# -------------------------------
					$cat_cnt_sql = "SELECT * FROM war_screenshot_categories";
					$cat_cnt_result = mysql_query($cat_cnt_sql);
					$num_of_categories = mysql_num_rows($cat_cnt_result);

					# -----------------------------------
					# Build the Query of Disabled Images
					# -----------------------------------
					$sql = "SELECT * FROM war_screenshots WHERE display_online=0 ORDER BY screenshot_id ASC";
					$debug .= "ADMIN IMAGE QUERY: " . $sql . "|";
					$result = mysql_query($sql);
					$num_img = mysql_num_rows($result);

					# --------------
					# Output Images
					# --------------
					if( $num_img > 0 )
					{
						$columns = 4;
						echo "\n\n<div class=\"screenshots-all\">\n";
						for($img=0; $img < $num_img; $img++)
						{
							$image = mysql_fetch_array($result);
							$date_dir = date('Ymd',$image['date_submitted']);
							$filename = HOME_URL . "war-content/war-screenshots/lg/".$date_dir."/image__".$image['screenshot_id'].".jpg";
							$thumbnail = HOME_URL . "war-content/war-screenshots/sm/".$date_dir."/image__".$image['screenshot_id'].".jpg";
							if($i % $columns == 0) { echo "<div class=\"screenshot-row\">\n"; }
							@include("war/html/war-screenshot-utility.tpl");
							if(($i % $columns) == ($columns - 1) || ($i + 1) == $num_img) { echo "</div>\n"; }
						}
						echo "</div>\n";
					}
					else {
						echo "<h3>No Images In Admin Queue</h3>\n";
						echo "<p><a href=\"image.php\">Go to Live Gallery</a></p>\n";
					}
				}

				# ---------------------------------
				# Debugging And Data Logging Code
				# ---------------------------------
				if( $debug_on ) { @include("war-debug.php"); }
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
