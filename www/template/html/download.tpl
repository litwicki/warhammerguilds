<h1>Download WARGuilds <?PHP echo WAR_VERSION; ?></h1>

<p>The download is currently restricted to registered users upon request. 
The control stages of the beta will attempt to solidify the validity of the software and allow for a more public release in the very near future.</p>


<?PHP

if( in_group($username, "member") || in_group($username, "writer") || in_group($username, "admin") )
{

	echo	'<div style="width: 300px; height: 20px; padding: 10px; margin-top: 20px; margin-bottom: 20px; border: 2px solid #c0c0c0; background-color: #d9d9d9; display: block;">'.
		'<img style="float: left; padding-right: 10px; margin-top: -6px; margin-left: -6px;" src="'.HOME_PATH.IMAGE_PATH.'icons/disk.png'.'" alt="DOWNLOAD!" />'.
		'<a href="#" onclick="return hs.htmlExpand(this, { contentId: \'download\' } )" class="highslide">DOWNLOAD WARGuilds'.WAR_VERSION.'</a>'.
		'</div>';

	echo	'<div class="highslide-html-content" id="download">'.
		'<div class="popup" style="height: 200px;">'.
		'<div style="height:20px; padding: 2px; float: right;">'.
			'<a href="#" onclick="return hs.close(this)" class="control"><img alt="close" src="'.HOME_PATH.IMAGE_PATH.'icons/x_24b.png'.'" /></a>'.
			'<a href="#" onclick="return false" class="highslide-move control">'.
			'<img style="position: absolute; right: 30px; width: 575px; height: 500px;" alt="Move" src="'.HOME_PATH.IMAGE_PATH.'spacer.png'.'" /></a>'.
		'</div>'.
		'<div class="popup_body">'.
			'<h2>Download WARGuilds<?PHP echo WAR_VERSION; ?></h2>'.
		'</div>'.
		'</div>'.
		'</div>';
}
?>

<h2>Want WARGuilds Free Hosting?</h2>
<p>Would you rather have a free hosted site on <a href="http://www.warhammerguilds.net">WARHAMMERGUILDS.NET</a> with the software included? <br /><br />Join the community and you'll find resources
available for you to have http://www.warhammerguilds.net/<strong>your_guild_name</strong>!</p>