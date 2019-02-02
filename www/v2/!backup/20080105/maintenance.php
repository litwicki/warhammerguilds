<?
@include("inc/war-config.php");
# ---------------------------------------
# Connect to MySQL Server & WAR Database
# ---------------------------------------
if( $db_conn = mysql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD) )
	header("location: index.php");
if( $db_select = mysql_select_db(WAR_DB, $db_conn) ) 
	header("location: index.php");
?>

<html>
<head>
<title>WARGuilds - Website Maintenance</title>
</head>
<body>

<div style="text-align: center; width: 100%;">

<div style="text-align: left; width: 400px; margin: 4em auto;">
<img alt="WARGuilds Maintenance" src="war/img/war_maintenance.jpg" />
</div>

</div>

</body>
</html>