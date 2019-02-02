<div id="side" style="margin-left: auto; margin-right: auto;">	
	<div style="text-align: center; margin-top: 10px;">
		<a href="<?PHP echo HOME_PATH; ?>guilds.php"><img alt="WARGuilds" src="template/images/warguilds_logo_180.png" /></a>
		<br />Warhammer Guilds Network<br /><br />
	</div>

	<h3><a onclick="toggle_hide('war-news');" title="WAR News">
	<img alt="Collapse Menu Icon" class="icon16" src="<?PHP echo HOME_PATH.IMAGE_PATH.WAR_ICON; ?>" /></a>COMMUNITY NEWS</h3>
	
	<div id="war-news" class="menu">
		<ul>
		<li><a href="news.php">All News</a></li>
		<li><a href="news.php?id=5">RvR Discussion</a></li>
		<li><a href="news.php?id=8">General Info</a></li>
		</ul>
	</div>

	<? if( $url != "gallery.php" ) { @include(HOME_PATH . "includes/last_screenshot.php"); } ?>

	<h3><a onclick="toggle_hide('war-rss');" title="WAR RSS">
	<img alt="Collapse Menu Icon" class="icon16" src="<?PHP echo HOME_PATH.IMAGE_PATH.WAR_ICON; ?>" /></a>WARHERALD NEWS</h3>

	<div id="war-rss" class="menu">
		<?PHP @include(HOME_PATH . "includes/rss.php"); ?>
	</div>

	<div style="margin-left: 10px;">
		<script type="text/javascript"><!--
		google_ad_client = "pub-0826379302706777";
		google_ad_width = 160;
		google_ad_height = 605;
		google_ad_format = "160x600_as";
		google_ad_type = "text_image";
		google_ad_channel = "";
		google_color_border = "FFFFFF";
		google_color_bg = "FFFFFF";
		google_color_link = "000000";
		google_color_text = "000000";
		google_color_url = "999999";
		google_ui_features = "rc:6";
		//-->
		</script>
		<script type="text/javascript"
		  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>
	</div>
</div>