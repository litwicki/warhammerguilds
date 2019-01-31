<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>WARGuilds | The Ultimate Warhammer Online Resource!</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name="keywords" content="<?PHP echo SITE_KEYWORDS; ?>" />
<meta name="description" content="<?PHP echo SITE_DESCRIPTION; ?>" />

<link rel="favico" href="favico.ico" />
<link rel="stylesheet" type="text/css" href="<?PHP echo HOME_PATH; ?>template/css/style.css" media="all" />
<link rel="stylesheet" type="text/css" href="<?PHP echo HOME_PATH; ?>template/css/highslide.css" media="all" />
<link rel="stylesheet" type="text/css" href="<?PHP echo HOME_PATH; ?>template/css/xajax_upload.css" media="all" />

<?PHP 
if( $url == "upload.php" ) 
{ 
	$xajax->printJavascript("xajax");
	echo "\n<script type='text/javascript' src='template/js/xajax_upload.js'></script>\n";
	echo "\n<script type='text/javascript'>".$jsOut."</script>\n";
}
?>

<script language="javascript" type="text/javascript">
	<!--
	if ((screen.width <= 1152)){
	document.write('<link rel="stylesheet" href="template/css/small.css" />');
	}
	//-->
</script>

<script language="javascript" type="text/javascript" src="template/js/highslide.js"></script>
<script language="javascript" type="text/javascript" src="template/js/highslide-html.js"></script>

<script type="text/javascript">    
    hs.graphicsDir = 'images/graphics/';
    hs.outlineType = 'rounded-white';
    hs.outlineWhileAnimating = true;
</script>

<script type="text/javascript" src="template/js/swfobject.js"></script>
<script type="text/javascript" src="template/js/war.js"></script>

<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
<script type="text/javascript">
	_uacct = "UA-2431670-1";
	urchinTracker();
</script>

<?PHP
if( isset($_GET['s']) && $_GET['s'] == $sid )
echo '<script type="text/javascript" language="javascript">window.onload = function () { document.getElementById(\'popup\').onclick(); }</script>';
?>

<!-- FIX IE6 Stuff -->
<!--[if IE 6]>
<link rel="stylesheet" type="text/css" href="<?PHP echo HOME_PATH; ?>template/css/ie.css" media="all" />
<script defer type="text/javascript" src="<?PHP echo HOME_PATH; ?>template/js/pngfix.js"></script>
<![endif]-->
<!-- END IE6 Stuff -->

<title><?PHP print(SITE_NAME . " | " . PAGE_TITLE); ?></title>
</head>
<body>

<div id="center">
<div id="wrapper">

<a id="popup" href="#" onclick="return hs.htmlExpand(this, { contentId: 'upload-success', align: 'center' } )" class="highslide"></a>

<div class="highslide-html-content" id="upload-status">
   <div class="popup">
	<div style="height:20px; padding: 2px; float: right;">
	    <a href="#" onclick="return hs.close(this)" class="control"><img alt="close" src="<?PHP echo HOME_PATH.IMAGE_PATH."icons/x_24b.png"; ?>" /></a>
	    <a href="#" onclick="return false" class="highslide-move control"><img style="position: absolute; right: 30px; width: 575px; height: 500px;" alt="Move" src="<?PHP echo HOME_PATH.IMAGE_PATH."spacer.png"; ?>" /></a>
	</div>
	<div class="popup_body">
	<h2>Upload Completed!</h2>
	<p>Thank you for your interest in the WARGuilds community!</p>
	<p>Remember, your video will not appear online immediately.<br />It usually takes about an hour to process and convert your file(s), so please be patient.</p>
	<p><span style="font-style: italic">WARGuilds Community Staff</span></p>
	</div>
   </div>
</div>

<table id="warhammer">

<tr>
<td class="header">
<div style="margin-left: auto; margin-right: auto; width: 100%;">
<a href="<?PHP echo HOME_PATH; ?>"><img alt="<?PHP echo SITE_NAME; ?>" class="spacer" src="<?PHP HOME_PATH; ?>template/images/blank.gif" /></a>
</div>
</td>
</tr>
<tr>
<td style="height: 50px; padding: 0px; margin: 0px; border: none;">
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
	<td class="nav"><a href="<?PHP echo HOME_PATH; ?>index.php">NEWS</a></td>
	<td class="nav"><a href="<?PHP echo HOME_PATH; ?>forums/">COMMUNITY</a></td>
	<td class="nav"><a href="<?PHP echo HOME_PATH; ?>video.php">VIDEOS</a></td>
	<td class="nav"><a href="<?PHP echo HOME_PATH; ?>gallery.php">GALLERY</a></td>
	<td class="nav"><a href="<?PHP echo HOME_PATH; ?>ranks.php">RANKINGS</a></td>
	<td class="nav"><a href="<?PHP echo HOME_PATH; ?>ui.php">WAR INTERFACE</a></td>
	<?PHP 
	if( in_group($username, "DISABLED"))
	{ echo "\n<td class=\"nav\"><a href=\"".HOME_PATH."download.php\">DOWNLOAD</a></td>\n";  }
	else { echo "\n<td class=\"nav\">&nbsp;</td>\n"; }
	?>
    </tr>
  </table>
</td>
</tr>
<tr>
<td class="content">

	<div id="user_info">
		<?PHP @include(HOME_PATH."/template/html/user.tpl"); ?>
	</div>

