<h1>WARGuilds Video Upload</h1>
<p>Use the form below to complete uploading your video. Without the details below your video will not be added to the WARGuilds network.</p>

<form method="post" action="upload.php?file=<?PHP echo FILE_NAME; ?>&details=complete">
<label for="title">Video Title</label>
<div class="war-input">
<input id="title" type="text" name="title" size="30" />
</div>

<label for="video_description">Video Caption</label>
<div class="war-input">
<input id="video_description" type="text" name="video_description" size="30" />
</div>

<label for="video_category">Video Category</label>
<div class="war-input">
<select id="video_category" name="video_category">
<?
$sql = "select * from war_video_categories where category_id not like '6' order by category_id asc";
$result = mysql_query($sql);
while( $row = mysql_fetch_array($result) ){ ?>
<option value="<? echo $row['category_id']; ?>"><? echo $row['category_name']; ?></option>
<? } ?>
?>
</select>
</div>

<p>
<label for="display_guild">Display Guild Name?</label> 
<input id="display_guild" name="display_guild" type="checkbox" />
</p>

<input class="btn" name="submit" type="submit" id="submit_btn" value="" /><p></p>
</form>