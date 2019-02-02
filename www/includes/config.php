<?PHP

define("WAR_VERSION","Beta 2.0");
##############################################################
##															##
##  WARHAMMER GUILDS	http://www.warhammerguilds.net		##
##	------------------------------------------------------	##
##	Available FREE under the following restrictions:		##
##	1.	All Copyright & Credit to WARHAMMER GUILDS			##
##		is maintained through the duration of your			##
##		website.											##
##	2.	You do not distribute the project in part			##
##		or whole. Please simply reference the WARHAMMER		##
##		GUILDS website listed above.						##
##	3.	If you would like to remove the credit notice(s)	##
##		and other references to WARHAMMER GUILDS you		##
##		can freely contact [ support@warhammerguilds.net ]	##
##		to discuss options/alternatives.					##
##															##
##############################################################

##############################################################
##															##
##	WARHAMMER GUILD CONFIGURATION							##
##	-----------------------------------------------------	##
##	Enter in the values for your website title, URL, etc.	##
##															##
##############################################################

$ADMIN_PASSWORD				="warguilds";

define("SITE_NAME",			"WARGUILDS");
define("PAGE_TITLE",		"Building WAR Communities");
define("SITE_DESCRIPTION",	"A website dedicated to providing community driven guild news, and resources");
define("SITE_KEYWORDS",		"Warhammer, WAR, Age of Reckoning, RvR, PvP, PvE, EA, EA Mythic, Mythic Entertainment, Warhammer Online, Warhammer Online Guild, Guilds, Guild");

define("SERVER_PATH",		"/var/www/docker-lamp-php-5/");
define("VIDEO_PATH",		"/var/www/docker-lamp-php-5/files/videos/");

define("HOME_PATH",			"http://www.warhammerguilds.net/");
define("FORUM_PATH",		"forums/");
define("IMAGE_PATH",		"template/images/");

DEFINE("FFMPEG_PATH",		"/usr/bin/ffmpeg/");
DEFINE("MENCODER_PATH",		"/usr/bin/mencoder/");
DEFINE("FLVTOOL_PATH",		"/usr/bin/flvtool2/");

##############################################################
##															##
##	WARHAMMER GUILDS IMAGE CONFIGURATION					##
##	-----------------------------------------------------	##
##	These are the defaults, but you can modify if needed.	##
##															##
##############################################################

@include("icon_config.php");

##############################################################
##															##
##	DATABASE CONFIGURATION VARIABLES						##
##	-----------------------------------------------------	##
##	You need to enter the custom values for YOUR own		##
##	MySQL Database and Server.								##
##															##
##############################################################

define("WAR_DB",			"warguilds");
define("DB_USERNAME",		"warguilds");
define("DB_PASSWORD",		"warguilds");
define("DB_HOST",			"localhost");

define("PHPBB_ROOT_PATH",	'/var/www/docker-lamp-php-5/forums/'); // Such as.. /home/user/public_html/phpbb3
define("POST_LIMIT",		10); // Total number of posts to display on news.php
define("POST_LENGTH",		200);

define("SCREENSHOTS_TABLE",	"war_screenshots");
define("VIDEOS_TABLE",		"war_videos");
define("APPLY_TABLE",		"war_applications");

define('IN_PHPBB',			true);

##############################################################
##															##
##	DO NOT MODIFY BELOW THIS POINT UNLESS YOU'RE CONFIDENT	##
##															##
##############################################################

$conn = mysql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD);
mysql_select_db(WAR_DB, $conn);

?>