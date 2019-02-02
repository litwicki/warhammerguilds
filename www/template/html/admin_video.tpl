<form id="convert_video" name="convert_video" action="<? echo $action_link; ?>" method="post">
<div style="margin-top: 10px; border-top: 2px solid #ccc; padding: 20px;">

<?
$english_filesize = round( (($video['filesize'] / 1024) / 1024), 2 ) . " MB";
if( $video['display_online'] == 1 ) { 
   $on_off = "hide_online";
   $on_off_lbl = "Take Offline";
}
else { 
   $on_off = "display_online"; 
   $on_off_lbl = "Display Online"; }

if( isset($_GET['by_video_id']) ) {  $span_on_id = " style=\"color: #ff0000;\""; }
if( isset($_GET['by_author']) ) {  $span_on_author = " style=\"color: #ff0000;\""; }
if( isset($_GET['by_date']) ) {  $span_on_date = " style=\"color: #ff0000;\""; }

?>

<? if( in_group($username, "admin") ) { ?>
<div style="display: block; margin-bottom: 4px; font-size: 9pt; color: #444; width: 100px; float: right;">
<span class="small">Delete</span> <input class="check" type="checkbox" name="delete" value="<? echo $video['video_id']; ?>" />
</div>
<? } ?>

<div style="display: block; margin-bottom: 4px; font-size: 9pt; color: #444;">
<span<? echo $span_on_id; ?>><strong>VIDEO ID - <? echo $video['video_id']; ?></strong></span>
</div>

<div style="display: block; margin-bottom: 4px; font-size: 9pt; color: #444;">
<strong>Filename</strong> - <? echo $video['original_filename'].".".$video['original_extension']." (".$english_filesize . ")"; ?>
</div>

<div style="display: block; margin-bottom: 4px; font-size: 9pt; color: #444;">
<span<? echo $span_on_author; ?>><strong>Author</strong> - <? echo $video['author']; ?></span>
</div>

<div style="display: block; margin-bottom: 4px; font-size: 9pt; color: #444;">
<span<? echo $span_on_date; ?>><strong>Date</strong> - <? echo date('m/d/Y (h:ia)',$video['date_submitted']); ?></span>
</div>

<div style="display: block; margin-bottom: 4px; font-size: 9pt; color: #444;">
<div class="war-input"><input id="title" type="text" name="title" value="<? echo $video['title']; ?>" size="30" /></div>
</div>

<div style="display: block; margin-bottom: 4px; font-size: 9pt; color: #444;">
<div class="textarea"><textarea name="description" rows="5" cols="30"><? echo $video['description']; ?></textarea></div>
</div>

<? if( $video['is_converted'] == 0 ) { ?>
<div style="display: block; margin-bottom: 4px; 9pt; color: #444;">
<input class="check" type="checkbox" name="convert" value="<? echo $video['video_id']; ?>" /> <span class="small">Convert</span>
</div>
<? } ?>

<div style="display: block; margin-bottom: 4px; font-size: 9pt; color: #444;">
<input class="check" type="checkbox" name="<? echo $on_off; ?>" value="<? echo $video['video_id']; ?>" /> <span class="small"><? echo $on_off_lbl; ?></span>
</div>

<div style="display: block; margin-bottom: 4px; font-size: 9pt; color: #444;">
<input class="check" type="checkbox" name="edit" value="<? echo $video['video_id']; ?>" /> <span class="small">Edit Title/Description</span>
</div>
</div>

<input type="submit" name="video_submit" class="btn" id="video_submit" value="" />

</form>