<div style="margin-bottom: 10px; border-top: 2px solid #c0c0c0; padding: 10px;">
The admin utilities are meant to provide an easy-access way of managing the user contributed videos, and screenshots. 
<p>
<ol>
<li>Use the author search to find and administer videos by that specific author.</li>
<li>If you know the actual video you want to edit, then enter that video ID and edit specifically that video.</li>
<li>Lastly, enter a date and select all videos submitted <strong>after</strong> that date.</li>
</ol>
</p>
<p>
<strong><a href="admin.php?f=video&view=convert">View All Videos Awaiting Conversion</a></strong>
</p>

</div>

<div style="margin-bottom: 10px;">
<form method="get">
<input type="hidden" name="f" value="video" />

<label for="by_author">Search For Author</label>
<input id="by_author" type="text" name="by_author" />

<input type="submit" value="go" />
</form>
</div>

<div style="margin-bottom: 10px;">
<form method="get">
<input type="hidden" name="f" value="video" />
<label for="by_video_id">Search By Video ID</label>
<input id="by_video_id" type="text" name="by_video_id" />

<input type="submit" value="go" />
</form>
</div>