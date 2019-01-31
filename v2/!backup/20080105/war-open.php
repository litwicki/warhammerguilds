<?PHP

$debug_on = 1;

# ------------------
# INCLUDE FUNCTIONS
# ------------------
include("inc/war-functions.php");
# --------------------
# INCLUDE CONFIG FILE
# --------------------
include("inc/war-config.php");

# ---------------------------------------
# Connect to MySQL Server & WAR Database
# ---------------------------------------
if( !$db_conn = mysql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD) ) 
{ 
	$debug .= "MySQL Connection *FAILED*|"; 
	header("location: maintenance.php");
}
else
	{ $debug .= "MySQL Connection *SUCCESS*|"; }

if( !$db_select = mysql_select_db(WAR_DB, $db_conn) ) 
{ 
	$debug .= "Connection to [".WAR_DB."] FAILED!|"; 
	header("location: maintenance.php");
}
else
	{ $debug .= "Connection to [".WAR_DB."] SUCCESS!|"; }

# ----------------------------------------------------
# LOGGING STUFF
# ----------------------------------------------------
$urgent_log = "/home/thezdin/public_html/logs/UNAUTHORIZED__" . date('Ymd') . ".log";
$admin_log = "/home/thezdin/public_html/logs/ADMIN__" . date('Ymd') . ".log";
$debug_log = "/home/thezdin/public_html/logs/DEBUG__" . date('Ymd') . ".log";
$video_log = "/home/thezdin/public_html/logs/VIDEO__" . date('Ymd') . ".log";

$date_now = DateNow();

# --------------------------------
# GET THE TIME TO RENDER THE PAGE
# --------------------------------
$starttime = microtime();
$startarray = explode(" ", $starttime);
$starttime = $startarray[1] + $startarray[0];

# --------------------------
# phpBB3 Session Management
# --------------------------
define('IN_PHPBB', true);
$phpbb_root_path = 'forums/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

$user->session_begin();
$auth->acl($user->data);
$user->setup();

# -------------
# DEBUG TOGGLE
# -------------
if( $_GET['debug'] == "on" ){ $debugging = 1;} 
else { $debugging = 0; }

# -------------
# ADMIN TOGGLE
# -------------
if( $_GET['admin'] == "on" ){ $admin_on = 1; }
else { $admin_on = 0; }

if( WarAccessLevel($user->data['user_id'],1) || WarAccessLevel($user->data['user_id'],2) ) { $is_admin = 1; } 

# ----------------------------------------------------
# BUILD QUERYSTRING LINKS FOR VARIOUS FUNCTIONALITIES
# ----------------------------------------------------
if( preg_match('/.*?\?.*/i',selfURL()) )
{
	if( $admin_on == 0 )
		$admin_link = $_SERVER['REQUEST_URI'] . "&amp;admin=on";
	if( $debugging == 0 )
		$debug_link = $_SERVER['REQUEST_URI'] . "&amp;debug=on";
}
else 
{
	if( $admin_on == 0 )
		$admin_link = $_SERVER['REQUEST_URI'] . "?admin=on";
	if( $debugging == 0 )
		$debug_link = $_SERVER['REQUEST_URI'] . "?debug=on";
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

# -------------------------
# Set Initial Debug Values
# -------------------------
$debug .= "Logging all data to: <code>".$debug_log."</code>|";
$debug .= "Timestamp: <code>".date('l dS \of F Y h:i:s A')."</code>|";
$debug .= "File Accessed: <code>" . $_SERVER["REQUEST_URI"] . "</code>|";
$debug .= $_SERVER['HTTP_USER_AGENT'] . "|";
$debug .= "User IP Address: <code>" . $_SERVER['REMOTE_ADDR'] . "</code>|";
$debug .= "Username: " . $user->data['username'] . "|";

$url = selfURL();


# Is this user special? This will allow access to limited areas later on.
if( WarAccessLevel($user->data['user_id'],1) || WarAccessLevel($user->data['user_id'],2) || WarAccessLevel($user->data['user_id'],3) || WarAccessLevel($user->data['user_id'],5) ) 
{ $special_user = 1; $max_vid_size = 157286400; }
else { $max_vid_size = 104857600; }

# -----------------------------------------------------
# GET ADMIN/MODERATOR EMAIL ADDRESSES FOR EMAIL ALERTS
# -----------------------------------------------------
$admin_emails = GetAdminEmails();

?>