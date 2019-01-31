<?php

@include("config.php");

##########################################
##										##
##  This displays the most recently		##
##  submitted screenshot, with some		##
##  modifications for lightbox and		##
##  PHP to display the image and its	##
##  effects properly for all browsers	##
##										##
##########################################

$sql = 'select * from '.SCREENSHOTS_TABLE.' where id > (select max(id)-1 from '.SCREENSHOTS_TABLE.') ORDER BY id DESC';
$result = mysql_query($sql);
if( mysql_num_rows($result) > 0 )
{
	while( $row = mysql_fetch_array($result) )
	{
		echo "\n<div class=\"dropshadow\">\n<div class=\"innerbox\">\n";
		echo "<a id=\"screenshot\" href=\"".$row['filename']."\" class=\"highslide\" onclick=\"return hs.expand(this, {captionId: 'caption'})\">\n";
		echo "<img src=\"".$row['thumbnail']."\" alt=\"Screenshot Gallery\" title=\"Click to Enlarge\" /></a>\n";
		echo "<br /><div class=\"small caption\">".$row['caption']."</div>\n";
		echo "<div class='highslide-caption' id='caption'>\n\t".$row['caption']."\n</div>\n";
		echo "</div>\n</div>\n";
	}
}
else
{
	echo "<div class=\"dropshadow\">\n";
	echo "  <div class=\"innerbox\">\n";
	echo "    <p><span class=\"medium\">No Screenshots Available!</span></p>\n";
	echo "  </div>\n";
	echo "</div>\n";
}
?>