<?
require("upload_config_inc.php");
require($xajaxRoot."/xajax.inc.php");
$xajax = new xajax();
require("upload_xajax_inc.php");
$xajax->processRequest();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Xajax_Upload DEMO</title>

<? 
if (!empty($xajax)) $xajax->printJavascript($xajaxRoot);// output the xajax javascript. This must be called between the head tags
echo "<script type='text/javascript' src='template/js/xajax_upload.js'></script>";
echo "<script type='text/javascript'>".$jsOut."</script>";
?>

<link rel="stylesheet" type="text/css" href="template/css/xajax_upload.css" media="all" />
</head>
<body>
<!-- UPLOAD FORM -->

<div class="small" style="height: 40px;">
<div id="moreinfo1" class="more">Maximum size for all uploaded files is <?PHP echo format_size($max_file); ?></div>
<div id="moreinfo2" class="more">Continue browsing to upload multiple files.</div>
</div>

<div class="uld">

<div style="float: right; padding-right: 80px; padding-top: 50px;" class="small">
<ul id="file_list">	
	<!--list of filenames  will be dynamically inserted here--> 
</ul>
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

<form id="file_upload" enctype="multipart/form-data" action="" method="post" >

<div id="file_inputs">
	<!--hidden file inputs will be dynamically inserted here-->
</div>

<div id="progress_bar" class="progress_bar" >
  <div class="progress">
	<div class="progress_box"></div>
  </div>
</div>

<div style="display:block;">
<div id="loadtext" class="tinyfont"></div>
<div id="okstatus" class="notice" style="display:none;"></div>
</div>

<div class="buttons">
<input class="upload button" type="button" id="uldSubmit" name="uldSubmit" onclick="postIt();" disabled="disabled" />
<input class="cancel button" type="button" id="uldCancel" name="uldCancel" onclick="cancel();" />
</div>

<iframe src="foo.html" name="destination0" id="destination0" height="0" width="0" frameborder="0"></iframe>
</form>
</div>
<!-- END UPLOAD FORM -->
<?
// +++++STEP 10: RUN THE ONLOAD SCRIPT TO CREATE THE INITIAL UPLOAD FORM ELEMENT SOMEWHERE IN YOUR FOOTER.
	if(!empty($onload)) echo "<script type='text/javascript'>".$onload."</script>";
?>
</body>
</html>