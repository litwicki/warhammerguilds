<div style="padding: 10px; margin-bottom: 20px; border-bottom: 1px solid #c0c0c0;">
<strong><span style="font-size: 12pt;"><? echo $title; ?> (<? echo $category_name; ?>)</span></strong><br />
</div>


<div id="video_player">
<!-- start of flv player -->
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8"
width="480" height="360"
id="theMediaPlayer">
<param name="movie" value="video_player.swf">
<param name="quality" value="high">
<param name="bgcolor" value="#ffffff">
<param name="allowfullscreen" value="true">
<param name="allowScriptAccess" value="always">
<param name="flashvars" value="file=files/flv/<? echo $file; ?>&logo=template/images/icons/war_icon_mini.png&overstretch=true&autostart=false&showfsbutton=false&image=files/preview/<? echo $large_thumbnail; ?>">
<embed type="application/x-shockwave-flash" 
pluginspage="http://www.macromedia.com/go/getflashplayer" 
width="480" height="360" bgcolor="#ffffff" 
name="warguilds"
src="video_player.swf"
flashvars="file=files/flv/<? echo $file; ?>&logo=template/images/icons/war_icon_mini.png&overstretch=true&autostart=false&showfsbutton=false&image=files/preview/<? echo $large_thumbnail; ?>">
</embed>
</object>
<!-- end of flv player -->
</div>

<!-- Video Details -->
<div>
<strong>Author:</strong> <? echo $author; ?> [ <a href="video.php?u=<? echo $author; ?>">View All</a> ]<br />
<strong>Date:</strong> <? echo $date_submitted; ?><br />
<strong>Views:</strong> <? echo $total_views; ?><br />
<p><strong>Description:</strong> <? echo $description; ?></p>
</span>
</div>
<!-- End Video Details -->