<?PHP
$debug_on = 1;
/**
*
* @package phpBB3
* @version $Id: viewtopic.php,v 1.513 2007/11/06 00:05:53 kellanved Exp $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
include("inc/war-config.php");
include("inc/war-functions.php");
if( !$db_conn = mysql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD) || !$db_select = mysql_select_db(WAR_DB, $db_conn) ) { header("location: maintenance.php"); }

define('IN_PHPBB', true);
$phpbb_root_path = 'forums/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
$imgEx = '.jpg';
include($phpbb_root_path . 'common.' . $phpEx);

$user->session_begin();
$auth->acl($user->data);
$user->setup();

// logging stuff
$urgent_log = "/home/thezdin/public_html/logs/UNAUTHORIZED__" . date('Ymd') . ".log";
$admin_log = "/home/thezdin/public_html/logs/ADMIN__" . date('Ymd') . ".log";
$debug_log = "/home/thezdin/public_html/logs/DEBUG__" . date('Ymd') . ".log";
$video_log = "/home/thezdin/public_html/logs/VIDEO__" . date('Ymd') . ".log";

# GET THE TIME TO RENDER THE PAGE
$starttime = microtime();
$startarray = explode(" ", $starttime);
$starttime = $startarray[1] + $startarray[0];
 

// is this user an admin?
$admin_grp = array(4,5,7);
$sql = "SELECT * FROM war_user_group WHERE user_id=".$user->data['user_id'];
$result = $db->sql_query($sql);
while( $row = $db->sql_fetchrow($result) )
{
	if( ( in_array($row['group_id'], $admin_grp) ) ){
		$is_admin = TRUE;
	}
}

if( $is_admin )
{
	$template->assign_var('S_ADMIN', TRUE);

	// is admin functionality enabled?
	if( $_GET['admin'] == "on" )
	{
		$admin_link = $_SERVER['REQUEST_URI']; 
		$admin_link = str_replace("?admin=on","", $admin_link); 
		$admin_link = str_replace("&admin=on","",$admin_link); 

		$template->assign_vars(array(
			'S_ADMIN_ON'		=>		TRUE,
			'U_ADMIN_LINK'		=>		$admin_link
		));
	}
	else
	{
		if( preg_match('/.*?\?.*/i',$_SERVER['REQUEST_URI']) ){
			$admin_link = $_SERVER['REQUEST_URI'] . "&amp;admin=on";
		}else{
			$admin_link = $_SERVER['REQUEST_URI'] . "?admin=on";
		}

		$template->assign_vars(array(
			'S_ADMIN_OFF'		=>		TRUE,
			'U_ADMIN_LINK'		=>		$admin_link,
		));
	}
}

# -----------------------------
# SORT LOGIC FOR CONTENT PAGES
# -----------------------------
if( preg_match('/.*?\?sort.*/i',selfURL()) )
{
	$url = selfURL();
	$url = str_replace("?sort=asc","",$url);
	$url = str_replace("?sort=desc","",$url);

	if( $_GET['sort'] == "asc" ) {
		$sort_link = $url . "?sort=desc";
		$sort_img = "icon_arrow_down.gif";
	} else {
		$sort_link = $url . "?sort=asc";
		$sort_img = "icon_arrow_up.gif";
	}
}
elseif( preg_match('/.*?\&sort.*/i',selfURL()) )
{
	$url = selfURL();
	$url = str_replace("&sort=asc","",$url);
	$url = str_replace("&sort=desc","",$url);

	if( $_GET['sort'] == "asc" ) {
		$sort_link = $url . "&sort=desc";
		$sort_img = "icon_arrow_down.gif";
	} else {
		$sort_link = $url . "&sort=asc";
		$sort_img = "icon_arrow_up.gif";
	}
}
elseif( preg_match('/.*?\?.*/i',selfURL()) )
{
	$url = selfURL();

	if( $_GET['sort'] == "asc" ) {
		$sort_link = $url . "&sort=desc";
		$sort_img = "icon_arrow_down.gif";
	} else {
		$sort_link = $url . "&sort=asc";
		$sort_img = "icon_arrow_up.gif";
	}
}
else
{
	$sort_link = $url . "?sort=asc";
	$sort_img = "icon_arrow_up.gif";
}
/*
# Is this user a special rank? This will allow access to limited areas later on.
if( WarAccessLevel($user->data['user_id'],1) || WarAccessLevel($user->data['user_id'],2) || WarAccessLevel($user->data['user_id'],3) || WarAccessLevel($user->data['user_id'],5) ) 
{ 
	$special_user = true; 
	$max_vid_size = 157286400; 
}
else 
{ 
	$max_vid_size = 104857600; 
}
*/
?>