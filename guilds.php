<?PHP

include("includes/config.php");
include("includes/functions.php");
include("includes/session.php");

##################################################
##################################################
##												##
##  SACRIFICE GUILD WARHAMMER WEBSITE			##
##  Developed with intent to provide code		##
##  to other WAR related guilds, WITH the		##
##  maintained Copyright and credit to			##
##  SACRIFICE & Thezdin. PLEASE respect the		##
##  hundreds of hours of work and retain all	##
##  comments and Copyrights.					##
##												##
##  SACRIFICE - http://www.sacrificeguild.org	##
##  Thezdin - http://www.thezdin.com/			##
##												##
##################################################
##################################################

$url = explode( "?", basename($_SERVER['REQUEST_URI']) ); $url = $url[0]; 
if( $user->data['is_registered'] )
{ 
	$user_id = $user->data['user_id'];
	$sid = $user->data['session_id'];
	$sql = 'select * from war_users where user_id = '.$user_id.' LIMIT 0, 1 ';
	$result = mysql_query($sql);
	   while( $user = mysql_fetch_array($result) )
	   { $user_group = $user['group_id']; $username = $user['username']; }
}

@include_once(HOME_PATH . "template/html/header.tpl");

?>
<!-- END HEADER -->

<!-- BEGIN CONTENT -->
<table border="0" cellspacing="0">
  <tr>

	<td class="left_column">
	<?PHP @include(HOME_PATH . "template/html/side.tpl"); ?>
	</td>

	<td class="right_column">
	<!-- WARGuilds Users List -->
	<h1>WARGuilds Users</h1>
	<p>Below is a list of guild websites using the WARGuilds Network.</p>
	<? 
		
		$sql = "SELECT * from ".WAR_DB.".war_profile_fields_data ORDER BY pf_guildname ASC";
		$result = mysql_query($sql);
		$num = mysql_num_rows($result);

		while( $guild = mysql_fetch_array($result) )
		{
			$member_sql = "select username from war_users where user_id=".$guild['user_id'];
			$member_result = mysql_query($member_sql);
			while( $row = mysql_fetch_array($member_result) ){ $user2 = $row['username']; }
			if( in_group($user2, "member") ){ $websites .= strtolower(fix_url($guild['pf_guildsite'])) . "|"; }
		}
		
		if( $num > 0 ){ echo "<ol>\n"; }

		$website_array = preg_split("/\|/is",$websites);
		$unique_websites = array_unique($website_array);
		foreach($unique_websites as $guild_website)
		{
			if( strlen($guild_website) > 50 ) {
				$guild_clicky = substr($guild_website,0,50);
				$guild_clicky = $guild_clicky . "..."; }
			else {
				$guild_clicky = $guild_website; }

			if( strlen($guild_website) > 0 ) {
				echo "<li><a href=\"".$guild_website."\">".$guild_clicky."</a></li>\n";	}
		}
		if( $num > 0 ){ echo "</ol>\n"; }
		
	?>
	<!-- END WARGuilds Users List -->
	</td>

  </tr>
</table>
<!-- END CONTENT -->

<?PHP @include(HOME_PATH . "template/html/footer.tpl"); ?>

<?PHP mysql_close($conn); ?>