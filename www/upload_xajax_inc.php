<?
/*================================================================================= 
UPLOAD_XAJAX_INC.PHP
 VERSION:1.0.2.0.5 FOR XAJAX 0.5
 DESCRIPTION:  XAJAX FUNCTIONS FOR XAJAX UPLOAD : UPLOADER WITH PROGRESS BAR VERSION
 AUTHOR: JEREMY M. DILL
 REQUIREMENTS:  SEE BELOW
================================================================================= 
* Copyright (c) 2007 JEREMY M. DILL (trydobe.com)
* This work is licensed under a Creative Commons Attribution-Share Alike 3.0  License
* http://creativecommons.org/licenses/by-sa/3.0/
* AUTHOR'S NOTE: THANKS TO THE OTHER DEVELOPERS OUT THERE WHO ESTABLISHED THE FOUNDATIONS OF THIS WORK.  
									BITS AND PIECES OF CONTRIBUTIONS FROM VARIOUS SOURCES INCLUDING THE FOLLOWING:
									http://www.raditha.com/megaupload/
									http://lists.geeklog.net/pipermail/geeklog-cvs/2005-June/000525.html
									http://labs.beffa.org/w2box/demo/
									http://tomas.epineer.se/archives/3
									http://jszen.blogspot.com/2005/05/secure-iframe-gotcha.html
									http://obokaman.obolog.com/mensaje/1596
									http://www.robertnyman.com/2005/11/07/the-ultimate-getelementsbyclassname/
									(sorry if any of these links become invalid)								
================================================================================= */

/*<<< BEGIN XAJAX FUNCTIONS ---*/
// GENERATE RANDOM SID
function getSid(){
	$objResponse = new xajaxResponse();	
	$sid = md5(uniqid(rand()).date("YmdHis"));
	$objResponse->script("sid='".$sid."';");
	return $objResponse;
}//close function
$xajax->registerFunction("getSid");

function uploadHandler($sid,$recursion){
	global $startDelay,$refreshDelay,$cgiDebug,$uploadDir;
	$objResponse = new xajaxResponse();	
	$exts = array("_flength","_postdata","_err","_signal","_qstring");
	if (!empty($sid))
		{
			if($recursion<0){// PROCESS WAS CANCELLED BY USER
					foreach($exts as $ext) {
						if(file_exists($uploadDir.$sid.$ext)) {
							unlink($uploadDir.$sid.$ext);
						}
					}
					$objResponse->assign("okstatus","innerHTML","<b>Upload was killed.</b>");
					$objResponse->assign("okstatus","className", "warning");
					$objResponse->assign("okstatus","style.display", "block");
					$objResponse->assign("load_bar","style.width", "0%");
					$objResponse->assign("progress_bar","style.display", "none");
					$objResponse->script("startOver();");
					return $objResponse;	
			} elseif (file_exists ( $uploadDir.$sid."_err" ) ){// THERE IS AN ERROR.  GAME OVER.
					$mes = file_get_contents($uploadDir.$sid."_err");
					$objResponse->assign("okstatus","innerHTML","<b>Failed! </b>".$mes);
					$objResponse->assign("okstatus","className", "warning");
					$objResponse->assign("okstatus","style.display", "block");
					$objResponse->assign("load_bar","style.width", "0%");
					$objResponse->assign("progress_bar","style.display", "none");
					$objResponse->script("startOver();");
					return $objResponse;
			} elseif ( file_exists ( $uploadDir.$sid."_signal" ) )	{// FINISHED
				// PUT FILE INFORMATION INTO ARRAY.
				$qstr = file_get_contents($uploadDir.$sid."_qstring");
				parse_str($qstr);
				// AT THIS POINT, YOU HAVE 3 ARRAYS SET FOR 3 PROPERTIES OF EACH FILE UPLOADED.
				//  $file['name'][0], $file['size'][0],$file['tmp_name'][0] ARE PROPERTIES OF THE FIRST FILE.
				//  $file['name'][1], $file['size'][1],$file['tmp_name'][1] ARE PROPERTIES OF THE SECOND FILE.
				//  ....ETC.
				// NOTE: $file['tmp_name'] IS THE FULL PATH OF THE FILE INCLUDING THE UPLOAD DIR.  (EXAMPLE: "/var/www/staging/files/uploadtmp/IZsCKzFXEL")
				//			   $file['name'] IS THE ORIGINAL NAME OF THE FILE (EXAMPLE: "05 - Herald! Frankenstein.mp3")
				//			   $file['size'] IS THE SIZE IN BYTES OF THE FILE (EXAMPLE: "1138816")
			// CALL FILEACTION FUNCTION DEFINED IN CONFIG FILE.	
			$resp=fileAction($file);
				// CLEANUP FILES.
				foreach($exts as $ext) {
					if(file_exists($uploadDir.$sid.$ext)) {
						unlink($uploadDir.$sid.$ext);
					}
				}
					if (!empty($resp['script'])) $objResponse->script($resp['script']);
					if (!empty($resp['msg'])) {
						$objResponse->assign("okstatus","innerHTML",$resp['msg']);
						$objResponse->assign("okstatus","className", "notice");
						$objResponse->assign("okstatus","style.display", "block");
					}
					$objResponse->assign("load_bar","style.width", "0%");
					$objResponse->assign("progress_bar","style.display", "none");
					$objResponse->script("startOver();");
					return $objResponse;		
			} elseif ( file_exists ( $uploadDir.$sid."_postdata" ) ) {// UPLOAD IS IN PROGRESS
				usleep($refreshDelay);
				$total_size 	= file_get_contents ( $uploadDir.$sid."_flength" );
				$loaded_size 	= filesize ( $uploadDir.$sid."_postdata" );
				$percent_loaded= round ( $loaded_size / $total_size * 100 );
				$objResponse->assign("load_bar","style.width", $percent_loaded."%");
				$objResponse->assign("loadtext","innerHTML","Uploading: ".format_size($loaded_size)."/".format_size($total_size));
				$objResponse->assign("progress_bar","style.display", "block");
				$objResponse->script("getProgress();");
				return $objResponse;		
			}	else	{	//NOT STARTED YET
				if ($recursion<5){ // WE WILL GIVE IT 5 LOOPS TO GET STARTED.
					usleep($startDelay);
					for ($i;strlen($dots)<$recursion; $dots.=".");
					$objResponse->assign("loadtext","innerHTML","Preparing Upload.".$dots);
					if ($cgiDebug){
						if(file_exists ($uploadDir))	$objResponse->assign("okstatus","innerHTML","Streaming ".$sid."_postdata" );
						 else 	$objResponse->assign("okstatus","innerHTML","PHP is not able to read the temp dir!! - (".$uploadDir.")");
						$objResponse->assign("okstatus","style.display", "block");
						$objResponse->assign("okstatus","className", "warning");
					}
					$objResponse->script("getProgress();");
				} else { // IF TOO MANY LOOPS, KICK OUT SCRIPT.
					$objResponse->assign("loadtext","innerHTML","<b>Upload Failed to begin.</b>Your browser may not support this feature.");
				}
				return $objResponse;
		} // if file exists
	 } else	{ //NO SID FOUND
			$objResponse->assign("okstatus","innerHTML","<b>Failed! </b>Please refresh your browser and try again.");
			$objResponse->assign("okstatus","className", "warning");
			return $objResponse;
	 } 
} ///close function
$xajax->registerFunction("uploadHandler");
/*--- END XAJAX FUNCTIONS >>>*/
?>