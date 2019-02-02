<?

/*================================================================================= 
	UPLOAD_CONFIG_INC.PHP
	VERSION:1.0.2.0.5 for Xajax 0.5
	DESCRIPTION:  CONFIG FILE FOR  XAJAX UPLOAD : UPLOADER WITH PROGRESS BAR VERSION
	AUTHOR: JEREMY M. DILL
	REQUIREMENTS:  SEE BELOW
================================================================================= 
* Copyright (c) 2007 JEREMY M. DILL (trydobe.com)
* This work is licensed under a Creative Commons Attribution-Share Alike 3.0  License
* http://creativecommons.org/licenses/by-sa/3.0/
================================================================================= */
 
//----------------BEGIN CONFIGURATION----------------->
// UPLOADDIR SPECIFIES THE TEMP PATH ON THE SERVER FOR THE UPLOADED FILES TO REST UNTIL THEY ARE MOVED OR DELETED. 	REQUIRED FOR THE XAJAX FUNCTIONS.
	$uploadDir="/home/thezdin/public_html/tmp_files/";

// TMPDIRINCGI:  [0] FALSE OR [1] TRUE.
// SET TO 1 IF YOU ARE WORRIED ABOUT SECURITY AND DON'T WANT THIS PATH TO BE EXPOSED IN JS CODE. IF TRUE, YOU MUST SET THE TMP_DIR IN THE CGI SCRIPT ITSELF.
	$tmpDirInCGI=1; // IF 0, UPLOADDIR WILL BE PASSED TO CGI FILE IN QUERYSTRING
	
// PATH TO UPLOAD.CGI 	
	$cgiPath="upload.cgi";

// RELATIVE PATH FROM MAIN PAGE TO FOLDER WHERE XAJAX IS INSTALLED.
	$xajaxRoot="xajax"; //DO NOT INCLUDE "/" AFTER DIRECTORY
	
// RELATIVE PATH FROM MAIN PAGE TO A SECURE EMPTY HTML FILE.  ONLY REQUIRED IF THIS PAGE IS USING SSL.  PREVENTS WARNING MESSAGE IN IE ABOUT SECURE AND UNSECURE CONTENT.
	$blankHTMLPath="blank.html";
	
// MAX SIZE ALLOWED SIZE FOR UPLOADED FILE.   NOTE: THE PHP LIMITS (MENTIONED ABOVE) AND $high_max_upload (WHICH IS SET IN UPLOAD.CGI) SHOULD BE GREATER OR EQUAL TO $maxFilesize SET HERE.
// $maxFilesize = "150M";  // units in Megabytes [M], Kilobytes [K}, or bytes[B].  Examples: 10M, 200K, 4000B. 

// SHOW MAX FILE SIZE MESSAGE.  [0] FOR FALSE, [1] FOR TRUE
	$showMax=1;
	
// MAX NUMBER OF FILES
	$maxFiles=3;

//  FIRST FILE IS SUMBITTED AUTOMATICALLY ON SELECTION.  SEND BUTTON IS NOT DISPLAYED.  [0] FOR FALSE, [1] FOR TRUE
//  IF ENABLED, MAXFILES SETTING HAS NO EFFECT AND ONLY 1 FILE UPLOAD IS ALLOWED.
	$autoFirst=0;
		
// UPLOAD SPEED THROTTLE BETWEEN 1.0 AND 10.0.  10 IS FASTEST.  (ANYTHING LESS THAN 1.0 OR GREATER THAN 10.0 IS OUT OF RANGE).
	$uploadSpeed=10;

// FILETYPE SETTINGS
// EXTMODE SETS THE FILETYPES TO [0] EXCLUDE OR [1] INCLUDE ONLY MODE.  
	$extMode=1; //0 = EXCLUDE MODE
// 	ALLOWED  OR DISALLOWED FILE TYPES, COMMA SEPERATED LIST OF EXTENSIONS.  COMMENT OUT TO ALLOW ALL EXTENSIONS.  
	$fileTypes="avi,mpeg,mpg,mp4,wmv,mov";
	
// PROGRESS BAR REFRESH DELAY (IN MICROSECONDS).
	$refreshDelay="500000";
	
// START DELAY (IN MICROSECONDS).  INCREASE THIS TIME IF THE CGI SCRIPT SEEMS TO BE WORKING, BUT THE UPLOAD HANDLER APPEARS TO HAVE FAILED. 
//  IF THE AJAX TIMESOUT BEFORE THE FILE UPLOAD STREAM BEGINS,  THE CLIENT DIDNT WAIT LONG ENOUGH AND UPLOADING WILL APPEAR TO HAVE FAILED.
	$startDelay="1000000";
		
// TURN CGI DEBUGGING ON.  SET CGIDEBUG = 1 TO SEE CGI OUTPUT IN THE IFRAME.   ALSO OUTPUTS SOME HELPFUL PHP MESSAGES.
	$cgiDebug=0;

// PREVENT TIMEOUT ERRORS
	set_time_limit(100);

// SET MOST RESTRICTIVE MAXFILESIZE.
	//$max_file = min(return_bytes(ini_get('post_max_size')),return_bytes(ini_get('upload_max_filesize')),return_bytes(ini_get('memory_limit')),return_bytes($maxFilesize));
	$max_file = "104857600";

//	FOR DEBUGGING OVERALL MAX FILESIZE LIMIT
	#echo "<br />post:".return_bytes(ini_get('post_max_size'));
	#echo "<br />upload_max:".return_bytes(ini_get('upload_max_filesize'));
	#echo "<br />memory_limit:".return_bytes(ini_get('memory_limit'));
	#echo "<br />max setting:".return_bytes($maxFilesize);
	#echo "<br />most restrictive setting:".$max_file;

// FUNCTION FILEACTION ($FILEARR)
/*	
	DESCRIPTION:  DETERMINES WHAT TO DO WITH THE UPLOADED FILES.
	WHEN THE UPLOAD COMPLETES, FILEACTION() WILL BE CALLED AND THE $FILEARR WILL BE PASSED TO IT.
	YOU MUST CUSTOMIZE THIS FUNCTION TO PERFORM WHATEVER ACTION YOU WISH TO TAKE ON THE FILES BEING UPLOADED.
	PARAMETER: $FILEARR = MULTIDIMENSIONAL ARRAY WITH THE FOLLOWING PROPERTIES.
	$FILEARR CONTAINS 3 VALUES FOR 3 PROPERTIES OF EACH FILE UPLOADED.
	$fileArr['name'][0], $fileArr['size'][0],$fileArr['tmp_name'][0] ARE PROPERTIES OF THE FIRST FILE.
	$fileArr['name'][1], $fileArr['size'][1],$fileArr['tmp_name'][1] ARE PROPERTIES OF THE SECOND FILE.
	....ETC.
	NOTE: $fileArr['tmp_name'] IS THE FULL PATH OF THE TEMP FILE INCLUDING THE UPLOAD DIR.  (EXAMPLE: "/var/www/staging/files/uploadtmp/IZsCKzFXEL")
	$fileArr['name'] IS THE ORIGINAL NAME OF THE FILE (EXAMPLE: "05 - Herald! Frankenstein.mp3")
	$fileArr['size'] IS THE SIZE IN BYTES OF THE FILE (EXAMPLE: "1138816")
	RETURN ARRAY : $resp['script']=JAVASCRIPT TO RUN ON COMPLETION OF FILE UPLOAD.
	$resp['msg']= MESSAGE TO DISPLAY IN OK STATUS BOX AFTER FILE UPLOAD. 
*/
	function fileAction($fileArr){
			$x=0;
			while (!empty($fileArr['name'][$x]))
			{
				$original_file = $fileArr['name'][$x];
				$filename = rename_upload($original_file); // Convert filename to username-timestamp.[ext]
				$original_filename = "/home/thezdin/public_html/files/".$filename;
				rename($fileArr['tmp_name'][$x],$original_filename);
				$x++;
##########################################################################################################
		}
		$resp['script'].="parent.location='?file=$filename'";
	  return $resp;
	}

if ($tmpDirInCGI != 1)	$jsOut =  "uploadDir='".$uploadDir."';";
$jsOut.= "cgiPath='".$cgiPath."';"
				."maxFile=".$max_file.";"
				."maxNumFiles=".$maxFiles.";"
				."autoFirst=".$autoFirst.";"
				."cgiDebug=".$cgiDebug.";"
				."uploadSpeed=".$uploadSpeed.";"
				."filetypes='".$fileTypes."';"
				."extMode=".$extMode.";"
				."blankHTMLPath='".$blankHTMLPath."';"
				."showMax=".$showMax.";";
//-----------END JAVASCRIPT---------///
//-----------BEGIN FORM GUTS---------/// 
$frmOut="";

//-----------END FORM GUTS---------/// 

// INITIAL FORM SETUP JS FUNCTION CALL.
$onload.="startOver();";
/*  RECOMMEND IMPLEMENTING THIS IN  THE PHP FOOTER BY DOING THE FOLLOWING: 
	if(!empty($onload)) echo "<script type='text/javascript'>".$onload."</script>";
	OR USE YOUR OWN ONLOAD METHOD
*/
//----------------END CONFIGURATION--------------------->

/*<<< BEGIN REGULAR FUNCTIONS ---*/
// CONVERSION FUNCTION
	function return_bytes($val) {
		$val = trim($val);
		if (empty($val)) return pow(1024,3);
		$last = strtolower($val{(strlen($val)-1)});
		switch($last) {
			case 'g':
			$val *= 1024;
			case 'm':
			$val *= 1024;
			case 'k':
			$val *= 1024;
		}
		return $val;
	}

// FORMAT FILE SIZE
	function format_size($size){
			// Measure & Number of decimals
			$measures = array (
				0 => array ( "B", 0 ),
				1 => array ( "KB", 0 ),
				2 => array ( "MB", 1 ),
				3 => array ( "GB", 2 ),
				4 => array ( "TB", 2 )
			);
			$file_size = $size;
			for ( $i = 0; $file_size >= 1024; $i++ ) 
				$file_size = $file_size / 1024;
			$file_size = number_format ( $file_size, $measures[$i][1] );
			return $file_size." ".$measures[$i][0];
	}	
/*--- END REGULAR FUNCTIONS >>>*/

function rename_upload($filename) {

	$filename = strtolower($filename);
	$ext = split("[/\\.]", $filename);
	$n = count($ext)-1;
	$ext = $ext[$n];
	$filename = str_replace($ext,'',$filename);
	$filename = str_replace('.','',$filename);
	$timestamp = mktime( date('g,i,s,m,d,Y') );
	$new_file = $filename."__".$timestamp.".".$ext;
	return $new_file;
}

?>
