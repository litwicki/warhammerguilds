<?
include("war-open.php");

if( $_GET['admin'] == "on" && !WarAccessLevel($user->data['user_id'],1) )
{
	warLog("\n** ATTEMPTED ADMIN ACCESS TO VIDEO.PHP **", $admin_log);
	warLog("IP Address of User: ".$_SERVER['REMOTE_ADDR'], $admin_log);
	warLog("Username: ".$user->data['username'], $admin_log);
	warLog("Browser Info: ".$_SERVER['HTTP_USER_AGENT'], $admin_log);
	warLog("Date of Attack: " . date('l dS \of F Y h:i:s A'), $admin_log);
	warLog("URL QueryString: " . selfURL(), $admin_log);
	header("location: video.php");
}
include("embed-admin.php");
include("war/html/war-header.tpl");
?>

<div id="war-content">

		<div id="war-left">

			<div style="margin: 10px;">
				<? @include("war/html/war-left.tpl"); ?>
			</div>

		</div>
		
		<div id="war-center">

			<div style="padding: 2em; margin: auto;">
			<!-- Begin Content -->
			<div class="blue" style="float: right; clear: both; padding-bottom: 1em;"><a href="upload.php?type=vid">UPLOAD YOUR VIDEOS!</a></div>
			<? 
				# -----------------------------------------------------------
				# * ADMIN ONLY * CONFIRM YOU WANT TO TAKE THIS VIDEO OFFLINE
				# -----------------------------------------------------------
				if( $_GET['a'] == "hide" && $is_admin )
				{
					$url = "video.php";
					if( isset($_GET['confirm']) ) 
					{
						HideObject(2, $_GET['video']);
						warLog("\nVIDEO_ID(".$_GET['video'].") *DISABLED* BY (".$user->data['username'].") ON ".date('l dS \of F Y h:i:s A'),$admin_log);
						echo '<script type="text/javascript">window.location = "'.$url.'?confirm"</script>';
					
					}
					elseif( isset($_POST['cancel']) ) {
						echo '<script type="text/javascript">window.location = "'.$url.'?cancel"</script>';
					}
					else {
						@include("war/html/war-confirm.tpl");
					}
				}

				# DISPLAY THE IMAGE SPOTLIGHT FORM?
				if( $display_spotlight_form ) { @include("war/html/war-spotlight-form.tpl"); }

				# ------------------------------------
				# DISPLAY SPECIFIC VIDEO AND DETAILS
				# ------------------------------------
				if( isset($_GET['vid']) && is_numeric($_GET['vid']) )
				{ 
					$sql = "SELECT * FROM war_videos WHERE display_online=1 AND video_id=".$_GET['vid'];
					$result = mysql_query($sql);
					$exists = mysql_num_rows($result);
					if( $exists )
					{
						# ----------------------
						# ADD +1 TO IMAGE VIEWS
						# ----------------------
						if( UniqueIncrement(2, $_GET['vid'], $_SERVER['REMOTE_ADDR']) ) {
							IncrementViews(2,$_GET['vid'],$_SERVER['REMOTE_ADDR']);
						}

						while( $video = mysql_fetch_array($result) )
						{
							# ADMIN OR AUTHOR ONLY * Disable Comments for Item
							if( $user->data['user_id'] == $video['user_id'] || $is_admin ) 
							{
								if( $video['comments_on'] )
								{
									echo '<div style="clear: both; width: 100%; display: block; padding: 1em; margin: 1em 0; border-bottom: 1px solid #d1d1d1; min-height: 60px;">';
									echo '<div class="blue authorbox">';
									echo 'As the author, you have the capability to disable comments given on this item. <br />Please note that once you have disabled comments, <strong>it cannot be enabled again</strong>.<br /><br /><a href="video.php?a=disable&video='.$video['video_id'].'">DISABLE COMMENTS</a></div>';
									
									# IF ADMIN IS ENABLED DISPLAY ABILITY TO TAKE ITEM OFFLINE
									if( $_GET['admin'] == "on" ) { echo ' <a href="video.php?a=hide&vid='.$video['video_id'].'"><img class="hide-icon" alt="HIDE IMAGE" src="war/img/icons/delete.png" /></a>'; }

									echo '</div>';
								}
							}

							# ---------------------------
							# Display the selected image
							# ---------------------------
							if( $video['comments_on'] == 1 ) { $display_comments = 1; }
							else { $display_comments = 0; }

							$date_dir = date('Ymd',$video['date_submitted']);
							$filename = HOME_URL . "war-content/war-videos/".$date_dir."/video__".$video['video_id'].".flv";
							$thumbnail = HOME_URL . "war-content/war-videos/img/".$date_dir."/video__".$video['video_id'].".jpg";
							//$large_img = HOME_URL . "war-content/war-videos/img/".$date_dir."/video__".$video['video_id']."-large.jpg";

							@include("war/html/war-video-large.tpl");
						}

						# --------------------------------
						# Display Comments For This Item
						# --------------------------------
						if( $display_comments ) 
						{
							$pending = 0;

							if( $is_admin ) 
								{ $comment_type = "admin"; }
							else 
								{ $comment_type = "normal"; }

							# --------------------------------
							# Display Comments For This Image
							# --------------------------------
							DisplayComments(2, $_GET['vid'], $user->data['user_id']);
							
							# -----------------------------------------
							# Only Display The Form To Logged In Users - need to fix
							# -----------------------------------------
							if( $user->data['user_id'] == ANONYMOUS || $user->data['is_bot'] ) {
								echo "<div class=\"red center\">You Must Be Logged In to Comment</div>\n"; 
							}
							else {
								@include("war/html/war-comments-form.tpl"); 
							}
						}
						else 
						{
							# ---------------------------------------------------
							# WERE THERE EVER ANY COMMENTS POSTED FOR THIS ITEM?
							# ---------------------------------------------------
							$sql = "SELECT COUNT(*) FROM war_comments WHERE video_id=".$_GET['vid'];
							$old_comments = mysql_query($sql);
							if( $old_comments > 0 ) {
								DisplayComments(2, $_GET['vid'], $user->data['user_id']);
							}
							echo "<div class=\"red center\">Comments Are Disabled!</div>\n";

							if( $_GET['admin'] == "on" && $is_admin ) {
								DisplayPendingComments(2, $_GET['vid'], $user->data['user_id']);
							}
						}

						# -----------------------------------
						# IF ADMIN, DISPLAY COMMENTS PENDING
						# -----------------------------------
						if( $_GET['admin'] == "on" && $display_comments ) {
							DisplayPendingComments(2, $_GET['vid'], $user->data['user_id']);
						}
					}
					else {
						echo "<h3>We're sorry, but that video does not exist.</h3>\n"; }
				}
				else
				{
				?>
				<h1><? echo SITE_NAME; ?> Video Gallery</h1>
				<p>The gallery below is maintained by the <? echo SITE_NAME; ?> volunteer community, and the content itself is contributed exclusively by our members of all kinds.</p>
				<p>To watch a video simply click the thumbnail preview and turn up your speakers! If you're impressed, feel free to leave a comment on as many videos as you see fit, but make sure you're logged in first.</p>
				<div style="border-top: 1px solid #d9d9d9; color: #555; font-size: 10pt; clear: both; padding: 6px; height: 40px;">

				<div class="left" style="padding: 4px;">
				<form name="per_page" method="get" action="<? echo selfURL(); ?>">
				<? if( isset($_GET['cat']) ) { ?>
				<input type="hidden" name="cat" value="<? echo $_GET['cat']; ?>" />
				<?}?>
				<select class="small black" name="per_page" onchange="document.per_page.submit();">
				<option value="16"<? if( $_GET['per_page'] != 40 || $_GET['per_page'] != 60 || $_GET['per_page'] != 100 ) { echo " selected"; } ?>>PER PAGE</option>
				<option value="40"<? if( $_GET['per_page'] == 40 ) echo " selected"; ?>>18 Videos</option>
				<option value="60"<? if( $_GET['per_page'] == 60 ) echo " selected"; ?>>36 Videos</option>
				<option value="100"<? if( $_GET['per_page'] == 100 ) echo " selected"; ?>>60 Videos</option>
				</select>
				</form>
				</div>

				<div class="left" style="padding: 4px;">
				<form name="category" method="get" action="<? echo selfURL(); ?>">
				<? if( isset($_GET['per_page']) ) { ?>
				<input type="hidden" name="per_page" value="<? echo $_GET['per_page']; ?>" />
				<?}?>
				<select class="small black" name="cat" onchange="document.category.submit();">
				<option value="0">CATEGORY</option>
				<?
					$sql = "SELECT * FROM war_video_categories WHERE restricted=0 ORDER BY description ASC";
					$result = mysql_query($sql);
					while( $cat = mysql_fetch_array($result) ) {
						$selected = "";
						if( $_GET['cat'] == $cat['category_id'] ){ $selected = " selected"; }
						echo "\t\t\t\t<option value=\"".$cat['category_id']."\"".$selected.">".$cat['description']."</option>\n";
					}
				?>
			    </select>
				</form>
				</div>

				<div class="left" style="font-size: 10px;">
				<a href="<? echo $sort_link; ?>"><img style="padding: 4px;" alt="sort" src="war/img/icons/<? echo $sort_img; ?>" /></a>
				</div>

				<div class="left" style="padding: 4px;"><a href="video.php">Entire Gallery</a></div>

				<? if( $_GET['user'] && !is_numeric($_GET['user']) ) { 
					echo "<div class=\"left\" style=\"font-size: 10pt; font-weight: bold; text-align: right; padding: 4px;\">| Images Submitted By " . $_GET['user'] . "</div>\n";
				}
				?>

				</div>
				<? 
					# DISPLAY ADMIN LINK IF ADMIN IS ENABLED
					if( $_GET['admin'] == "on" && $is_admin ) { echo "<h3><a href=\"admin-video.php\">Videos in Queue</a></h3>\n"; }

					# ---------------------
					# What Page Are We On?
					# ---------------------
					if(!isset($_GET['p']))
						{ $page = 1; } 
					else 
						{ $page = $_GET['p']; }
					
					# ------------------------------
					# GET NUMBER OF IMAGES PER PAGE
					# ------------------------------
					if( isset($_GET['per_page']) && is_numeric($_GET['per_page']) && $_GET['per_page'] < 41 )
						$max_view = $_GET['per_page'];
					else
						$max_view = 16;
					
					# ---------------------------------
					# BUILD FINAL QUERY FOR ALL IMAGES
					# ---------------------------------
					$sql = "SELECT * FROM war_videos WHERE display_online=1 ";
					$sql_count = "SELECT COUNT(*) FROM war_videos WHERE display_online=1 ";

					# ARE WE FILTERING BY CATEGORY?
					if( is_numeric($_GET['cat']) && $_GET['cat'] > 0 && $_GET['cat'] < 7 ) { 
						$sql .= "AND category=".$_GET['cat']." "; 
						$sql_count .= "AND category=".$_GET['cat']." ";
					}

					if( isset($_GET['user']) && !is_numeric($_GET['user']) )
					{
						$sql_user_id = GetUserID($_GET['user']);
						if( $sql_user_id ) {
							$sql .= "AND user_id=".$sql_user_id. " ";
							$sql_count .= "AND user_id=".$sql_user_id. " ";
						}
					}

					# ARE WE SORTING ASCENDING OR DESCENDING?
					if( isset($_GET['sort']) && $_GET['sort'] == "asc" || $_GET['sort'] == "desc" ) { 
						$sort = strtoupper($_GET['sort']); 
					} else { $sort = "DESC"; }

					# -----------------------------------
					# GET TOTAL IMAGES AND VIEW PER PAGE
					# -----------------------------------
					$total_images = mysql_result(mysql_query($sql_count),0);
					$total_pages = ceil($total_images / $max_view);
					$start_view = ( ($page * $max_view) - $max_view );
					
					# -------------------
					# FINALIZE THE QUERY
					# -------------------
					$sql .= "ORDER BY video_id $sort LIMIT $start_view, $max_view";
					$debug .= "Video Display Query: <br /><code>".$sql."</code>|";
					$debug .= "Video Count Query: <br /><code>".$sql_count."</code>|";
					
					$result = mysql_query($sql);
					$num_rows = mysql_num_rows($result);
					$count = 0;
					$columns = 4;

					# --------------------------
					# BUILD THE ARRAY OF IMAGES
					# --------------------------
					if( mysql_num_rows($result) > 0 )
					{
						if( $_GET['view'] == "plain" )
						{
							echo "\n\n<div class=\"videos-all\">\n";
							for($i=0; $i < $num_rows; $i++)
							{
								if( $rowclass == "row" )
									$rowclass = "row2";
								else
									$rowclass = "row";

								echo "\n<div class=\"".$rowclass."\">\n";
								$video = mysql_fetch_array($result);
								$date_dir = date('Ymd',$image['date_submitted']);
								$filename = HOME_URL . "war-content/war-videos/".$date_dir."/video__".$video['video_id'].".flv";
								@include("war/html/war-videos-plain.tpl");
								echo "</div>\n";
							}

							echo "</div>\n";
						}
						else
						{
							echo "\n\n<div class=\"videos-all\">\n";

							for($i=0; $i < $num_rows; $i++)
							{
								$video = mysql_fetch_array($result);
								$date_dir = date('Ymd',$video['date_submitted']);
								$filename = HOME_URL . "war-content/war-videos/".$date_dir."/video__".$video['video_id'].".flv";
								$thumbnail = HOME_URL . "war-content/war-videos/img/".$date_dir."/video__".$video['video_id'].".jpg";
								if($i % $columns == 0) { echo "<div class=\"video-row\">\n"; }
								@include("war/html/war-videos.tpl");
								if(($i % $columns) == ($columns - 1) || ($i + 1) == $num_rows) { echo "</div>\n"; }
							}

							echo "</div>\n";
						}
					}
					else
					{
						echo "<p class=\"red\">No Videos Available</p>\n";
					}

					# --------------------------------------------
					# BUILD PAGINATION: PREVIOUS | # - # - | NEXT
					# --------------------------------------------
					if( $total_images > $max_view ) 
					{
						echo "\n<div class=\"pagination\">\n";

						if( !$_SERVER['QUERY_STRING'] ) { $q = "?"; }
						elseif( preg_match('/.*?\?p=\d{1,2}/i',$_SERVER['REQUEST_URI']) ) { $q = "?"; } 
						else { $q = "&"; }

						if($page > 1) {
							$url = preg_replace('/[\?\&]p=\d{1,2}/i','',$url);
							$prev = ($page - 1);
							echo '<a href="'.$url.$q.'p='.$prev.'">Previous</a>  |  ';
						}
						else {
							echo "Previous  |  ";
						}

						for($i = 1; $i <= $total_pages; $i++){
							$url = preg_replace('/[\?\&]p=\d{1,2}/i','',$url);
							if($i == $page){ echo "   " .$i."   "; } else {
								//echo("<a href=\"$PHP_SELF&page=$i\">$i</a> "); 
								if( $total_pages == 1 ) {
									echo '   <a href="'.$url.$q.'p='.$i.'">'.$i.'</a>';
								} else {
									echo '   <a href="'.$url.$q.'p='.$i.'">'.$i.'</a>';
								}
							}
						}  

						if($page < $total_pages){
							$url = preg_replace('/[\?\&]p=\d{1,2}/i','',$url);
							$next = ($page + 1);
							echo '  |  <a href="'.$url.$q.'p='.$next.'">Next</a>';
						}
						else {
							echo "  |  Next";
						}

						echo "\n</div>\n";
					}
				}
			?>

			<!-- End Content -->
			</div>

		</div>
	</div>

	<div id="war-footer">

		<div style="margin: 10px;">
			<? @include("war/html/war-footer.tpl"); ?>
		</div>

	</div>

</div>
</div>

</body>
</html>

<!-- Close The Page -->
<? @include("war-close.php"); ?>