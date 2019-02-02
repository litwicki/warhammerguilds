<?
if( $user->data['session_time'] == $user->time_now )
{ ?>
<p style="text-align: center;">
<a href="upload.php"><img src="template/images/upload_videos_btn.jpg" border="0" alt="Upload Your Videos!" /></a>
</p>
<? } ?>

<div class="pagination">

<? if($page != 1){ ?>
<a href="<? echo $link."p=$prev"; ?>"><img alt="previous" src="<? echo HOME_PATH.IMAGE_PATH.PREV_ICON; ?>" /></a>
<? } ?>

<? 
if($page <= $total_pages)
{ 
   $next = ($page + 1);
   echo "<a href=\"".$link."p=".$next."\"><img alt=\"next\" src=\"".HOME_PATH.IMAGE_PATH.NEXT_ICON."\" /></a>\n";
}
else
{
   //echo "No need for pagination\n";
}
?>
</div>