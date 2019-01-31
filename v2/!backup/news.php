<? 
include("war-open.php");
# ----------------------------------
# INCLUDE HEADER AND BEGIN TEMPLATE
# ----------------------------------
include("war/html/war-header.tpl");

?>

<div id="war-content">
		<div id="war-left">

			<div style="margin: 10px;">
				<? @include("war/html/war-left.tpl"); ?>
			</div>

		</div>
		
		<div id="war-center">
			<div style="margin: auto;">
			<div id="war-news">
			<div style="padding: 1em;">
			<!-- Begin Content -->
			<? 
				$news = array();
				$count = 0;

				if( isset($_GET['total']) && is_numeric($_GET['total']) ) {
					$post_limit = $_GET['total'];
				} else {
					$post_limit = NEWS_LIMIT;
				}
				
				# --------------------------------
				# IS THE USER FILTERING THE NEWS?
				# --------------------------------
				$good_filters = array(6,13);
				if( isset($_GET['f'] ) && in_array($_GET['f'],$good_filters) )
				{
					$sql = "SELECT u.user_id, u.username, u.user_id, t.topic_attachment, t.topic_title, t.topic_poster, t.forum_id, t.topic_id, t.topic_time, t.topic_views, t.topic_replies, t.topic_first_post_id, p.poster_id, p.topic_id, p.post_id, p.post_text
					FROM war_users u, war_topics t, war_posts p
					WHERE u.user_id = t.topic_poster
					AND u.user_id = p.poster_id
					AND t.topic_id = p.topic_id
					AND p.post_id = t.topic_first_post_id";

					if( isset($_GET['f']) ) {
						$forum = $_GET['f'];
						$sql .= " AND t.forum_id=".$forum." ORDER BY t.topic_time DESC LIMIT 0," . $post_limit;
					} else {
						$sql .= " AND t.forum_id IN(4,5,6,7) ORDER BY t.topic_time DESC LIMIT 0," . $post_limit;
					}

					$result = mysql_query($sql);
					$total = mysql_num_rows($result);

					if( $total > 0 )
					{
						while ($warguilds_news = mysql_fetch_array($result) )
						{
							# -----------
							# GET AVATAR
							# -----------
							if( $news['forum_id'] == 6 ) {
								$avatar = "rvr.jpg";
							} elseif( $news['forum_id'] == 13 ) {
								$avatar = "pve.jpg";
							} else {
								$avatar = "warguilds.jpg";
							}
							
							# --------------------------
							# GET AUTHOR, POST DATE, ETC
							# --------------------------
							$author = ucfirst($warguilds_news['username']);
							$date_posted = $warguilds_news['topic_time'];
							$byline = "<div class=\"byline-left\">Posted on " . $date_posted . " by " . $author . " </div>\n";
							$byline .= "<div class=\"byline-right\">Views (".$warguilds_news['topic_views'].") Replies (".$warguilds_news['topic_replies'].")</div>";
							$views = $warguilds_news['topic_views'];
							$replies = $warguilds_news['topic_replies'];
							$title = $warguilds_news['topic_title'];
							$link = FORUM_URL."viewtopic.php?t=".$warguilds_news['topic_id'];

							# ----------------------------------------------------
							# GET FULL POST AND FIRST "POST_LENGTH" CHARS OF POST
							# ----------------------------------------------------
							$small_text = bb2html($warguilds_news['post_text']);

							# ------------------------------
							# OUTPUT NEWS POST VIA TEMPLATE
							# ------------------------------
							//@include("war/html/war-news.tpl");

							$news[$count]['date'] = $date_posted;
							$news[$count]['title'] = $title;
							$news[$count]['link'] = $link;
							$news[$count]['avatar'] = $avatar;
							$news[$count]['author'] = $author;
							$news[$count]['text'] = $small_text;

							$count++;
						}
						$count--;
					}
					else
					{
						echo "<h1>No Posts Available For This Topic</h1>\n";
					}

					sort($news,SORT_REGULAR);
					$sort_news = array_reverse($news,TRUE);

					# -----------------
					# DISPLAY THE NEWS
					# -----------------
					$num_of_posts = 0;
					foreach ($sort_news as $key => $val)
					{
						if( $num_of_posts < NEWS_LIMIT ) 
						{
							if(isset($val))
							{
								foreach($val as $x => $data)
								{
									if( $x == "date" ) $date_posted = date('m/d/Y',$data);
									if( $x == "author" ) $author = $data;
									if( $x == "title" ) $title = $data;
									if( $x == "link" ) $title_link = $data;
									if( $x == "avatar" ) $avatar = $data;
									if( $x == "text" ) $small_text = $data;

									$byline = "<div class=\"byline-left\">Posted on " . $date_posted . " by " . $author . " </div>\n";
								}
								@include("war/html/war-news.tpl");
							}
							$num_of_posts++;
						}
					}
				}
				else
				{
					# --------------------------------------
					# GET THE LATEST FROM THE WARHERALD RSS
					# --------------------------------------
					$feed_url = "http://feeds.warhammerherald.com/news/";
					$doc = new DOMDocument();
					$doc->load($feed_url);
					$arrFeeds = array();
					foreach ($doc->getElementsByTagName('item') as $node)
					{
						$herald_news = array ( 
							'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
							'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
							'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
							'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
							);

						array_push($arrFeeds, $herald_news);
						$title = $herald_news['title'];
						$link = $herald_news['link'];
						$avatar = "mythic.jpg";
						$author = "EA Mythic in the <em>Warhammer Herald</em>";
						$date_posted = $herald_news['date'];
						$small_text = utf8_encode($herald_news['desc']);
						//@include("war/html/war-news.tpl");
						
						# --------------------------
						# CONVERT DATE TO UNIX DATE
						# --------------------------
						# Tue, 18 Dec 2007 13:30:51 EST
						list($blah,$day,$month,$year,$time) = explode(" ",$date_posted);
						list($hour,$min,$sec) = explode(":",$time);
						$month = month2int($month);
						$news_date = mktime($hour,$min,$sec,$month,$day,$year);
						
						$news[$count]['date'] = $news_date;
						$news[$count]['title'] = $title;
						$news[$count]['link'] = $link;
						$news[$count]['avatar'] = $avatar;
						$news[$count]['author'] = $author;
						$news[$count]['text'] = $small_text;
						
						//increment
						$count++;
					}

					# ------------------------------------
					# GET THE LATEST WARGuilds FORUM NEWS
					# ------------------------------------
					$sql = "SELECT u.user_id, u.username, u.user_id, t.topic_attachment, t.topic_title, t.topic_poster, t.forum_id, t.topic_id, t.topic_time, t.topic_views, t.topic_replies, t.topic_first_post_id, p.poster_id, p.topic_id, p.post_id, p.post_text
					FROM war_users u, war_topics t, war_posts p
					WHERE u.user_id = t.topic_poster
					AND u.user_id = p.poster_id
					AND t.topic_id = p.topic_id
					AND p.post_id = t.topic_first_post_id AND t.forum_id IN(4,5,6,7) ORDER BY t.topic_time DESC LIMIT 0," . NEWS_LIMIT;

					$result = mysql_query($sql);
					$total = mysql_num_rows($result);

					if( $total > 0 )
					{
						while ($warguilds_news = mysql_fetch_array($result) )
						{
							# -----------
							# GET AVATAR
							# -----------
							if( $news['forum_id'] == 5 ) {
								$avatar = "rvr.jpg";
							} elseif( $news['forum_id'] == 8 ) {
								$avatar = "pve.jpg";
							} else {
								$avatar = "warguilds.jpg";
							}
							
							# --------------------------
							# GET AUTHOR, POST DATE, ETC
							# --------------------------
							$author = ucfirst($warguilds_news['username']);
							$date_posted = $warguilds_news['topic_time'];
							$byline = "<div class=\"byline-left\">Posted on " . $date_posted . " by " . $author . " </div>\n";
							$byline .= "<div class=\"byline-right\">Views (".$warguilds_news['topic_views'].") Replies (".$warguilds_news['topic_replies'].")</div>";
							$views = $warguilds_news['topic_views'];
							$replies = $warguilds_news['topic_replies'];
							$title = $warguilds_news['topic_title'];
							$link = FORUM_URL."viewtopic.php?t=".$warguilds_news['topic_id'];

							# ----------------------------------------------------
							# GET FULL POST AND FIRST "POST_LENGTH" CHARS OF POST
							# ----------------------------------------------------
							$small_text = bb2html($warguilds_news['post_text']);

							# ------------------------------
							# OUTPUT NEWS POST VIA TEMPLATE
							# ------------------------------
							//@include("war/html/war-news.tpl");

							$news[$count]['date'] = $date_posted;
							$news[$count]['title'] = $title;
							$news[$count]['link'] = $link;
							$news[$count]['avatar'] = $avatar;
							$news[$count]['author'] = $author;
							$news[$count]['text'] = $small_text;

							$count++;
						}
						$count--;
					}

					sort($news,SORT_REGULAR);
					$sort_news = array_reverse($news,TRUE);

					# -----------------
					# DISPLAY THE NEWS
					# -----------------
					$num_of_posts = 0;
					foreach ($sort_news as $key => $val)
					{
						if( $num_of_posts < NEWS_LIMIT ) 
						{
							if(isset($val))
							{
								foreach($val as $x => $data)
								{
									if( $x == "date" ) $date_posted = date('m/d/Y',$data);
									if( $x == "author" ) $author = $data;
									if( $x == "title" ) $title = strtoupper($data);
									if( $x == "link" ) $title_link = $data;
									if( $x == "avatar" ) $avatar = $data;
									if( $x == "text" ) $small_text = $data;

									$byline = "<div class=\"byline-left\">Posted on " . $date_posted . " by " . $author . " </div>\n";
								}
								@include("war/html/war-news.tpl");
							}
							$num_of_posts++;
						}
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
			<div id="spotlight">
			<? @include("war/html/war-spotlight.tpl"); ?>
			</div>
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

<!-- Begin Page Close Code -->
<? @include("inc/war-close.php"); ?>
<!-- End Page Close Code -->