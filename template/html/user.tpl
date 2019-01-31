<div class="userbox">
<div class="userpadding">	
  <div class="userlinks">
	<script type="text/javascript"><!--
	google_ad_client = "pub-0826379302706777";
	google_ad_width = 468;
	google_ad_height = 60;
	google_ad_format = "468x60_as";
	google_ad_type = "text";
	google_ad_channel = "";
	google_color_border = "ffffff";
	google_color_bg = "ffffff";
	google_color_link = "000000";
	google_color_text = "000000";
	google_color_url = "000000";
	google_ui_features = "rc:6";
	//-->
	</script>
	<script type="text/javascript"
	src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
  </div>
  <div class="userinfo">

<?PHP

	/*
	We can't have this a standalone PHP script
	without it pre-rendering the values. So we
	are forced to include the PHP code here, and
	have it rendered when it is included in the
	template PHP page, ie: news.php, gallery.php
	*/

	echo	'<span style="float: left; position: absolute;">' .
		'<img style="height: 60px; margin-top: -10px;" alt="WAR LOGO" src="'.HOME_PATH.IMAGE_PATH.WAR_LOGO.'" />' .
		'</span>';

		

	$prompt ='<a href="'.FORUM_PATH.'ucp.php?mode=register">'.
			'Join the '.SITE_NAME.' Community</a> | '.
			'<a href="#" onclick="return hs.htmlExpand(this, { contentId: \'whyjoin\' } )" class="highslide">Why Join?</a>'.
			'<br /><span class="small">Already a Member? [ <a href="'.FORUM_PATH.'ucp.php?mode=login">Login</a> ]</span>';

	if( $user->data['session_time'] != $user->time_now )
	{
	   echo "<div style=\"padding-left: 60px; padding-top: 10px;\">".$prompt . "</div>\n"; 
	}
	else 
	{ 
	   echo "<div style=\"padding-left: 60px; padding-top: 10px;\">\n";
	   echo "Welcome to ".SITE_NAME." <strong>" . ucfirst($username) . "</strong> <br /><span class=\"small\">[ <a href=\"".FORUM_PATH."ucp.php?mode=logout&amp;sid=".$sid."\">Logout";

	   if( in_group($username, "writer") && $url != "admin.php" ){ 
	      echo "</a> | <a href=\"admin.php\">Admin Utilities</a> ]</div>\n"; }
	      else { echo "</a> ]</span></div>\n"; }
	}

?>
	
  </div>
</div>
</div>