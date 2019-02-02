<h1>WARGuilds Video Upload</h1>
<p>Use the form below to upload your WAR Video. Please make sure to read our <a href="tos.php">terms of service</a> before uploading. After your file has completed uploading you will be forwarded to a second form where you can add the title, description, and category of your video. If you have any questions or issues uploading your file please <a href="forums/">visit the WARGuilds forums</a>.</p>
<!-- UPLOAD FORM -->
<div class="uld">

<div style="position: absolute; margin-left: 320px; margin-top: 40px;" class="small">
<div style="display:block;">
<div id="loadtext" class="tinyfont"></div>
<div id="okstatus" class="notice" style="display:none;"></div>
</div>
<ul id="file_list">	
	<!--list of filenames  will be dynamically inserted here--> 
</ul>
</div>

<form name="war" id="file_upload" enctype="multipart/form-data" action="upload.php" method="post" >

<div id="progress_bar" class="progress_bar" >
  <div class="progress">
	<div class="progress_box"></div>
  </div>
</div>

<div id="status-bar-wrapper">
  <div id="status-bar-spacer">
	<div id="load_bar" class="bar">
	<div class="bar-war">
	  <div class="inner-war-bar"></div>
	</div>
	</div>
  </div>
</div>
<br />
<div id="file_inputs">
	<!--hidden file inputs will be dynamically inserted here-->
</div>

<div class="buttons">
<input class="upload button" type="button" id="uldSubmit" name="uldSubmit" onclick="postIt();" disabled="disabled" />
<input class="cancel button" type="button" id="uldCancel" name="uldCancel" onclick="cancel();" />
</div>

<div class="small" style="margin-top: 40px;">
<div id="moreinfo1" class="more">Maximum size for all uploaded files is <?PHP echo format_size($max_file); ?></div>
<div id="moreinfo2" class="more">Continue browsing to upload multiple files.</div>
</div>


<iframe src="foo.html" name="destination0" id="destination0" height="0" width="0" frameborder="0"></iframe>
</form>
</div>
<!-- END UPLOAD FORM -->