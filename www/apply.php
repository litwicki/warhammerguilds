<?PHP
include("includes/config.php");
include("includes/functions.php");
include("includes/session.php");

##########################################################################################

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

$error = array();
$valid = "y";

##########################################################################################

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

	<h1><?PHP echo SITE_NAME; ?> Application Form (Demo)</h1>
	<p>This is simply a demo of the application system for the WARGuilds software. The application below will give you an idea of what the processing is like, and how the system works in general. Once an application is submitted to the database, it is viewable through a web-interface that is password protected to members of your configured high-level groups.</p>

	<?PHP
		if( isset($_POST['submit']) )
		{
			$real_name = $_POST['real_name'];
			$email = $_POST['email'];
			$age = $_POST['age'];
			$char_race = $_POST['char_race'];
			$char_class = $_POST['char_class'];
			$char_name = $_POST['char_name'];

			// timestamp of form execution as an integer
			$date = mktime( date('g,i,s,m,d,Y') );

			// users ip address to log
			$ip = $_SERVER[REMOTE_ADDR];

			if( $real_name == "" || $email == "" || $age == "" || $char_name == "" )
			{ $error[] = "Please fill in all fields"; $valid = "n"; }

			// is the email entered in the format a-z@a-z.com (allow "-" and "_" chars)?
			if( !valid_email($email) ) { $error[] = "Invalid Email"; $valid = "n"; }

			// is the age entered a number, at most 3 digits?
			if( !is_numeric($age) ) { $error[] = "Please enter a valid age"; $valid = "n"; }

			// remove html and minor sql attacks for both text fields
			$reasons_to_join = preg_replace('/<.*?>/is','', $reasons_for_joining);
			$reasons_to_join = preg_replace('/[\"|\']/is','', $reasons_for_joining);
			$reasons_to_join = str_replace("=","->",$reasons_for_joining);

			$self_description = preg_replace('/<.*?>/is','', $self_description);
			$self_description = preg_replace('/[\"|\']/is','', $self_description);
			$self_description = str_replace("=","->",$self_description);
			if( $valid == "y" )
			{
				$sql = "INSERT INTO ".FORUM_DB.".war_applications VALUES(".
					"'','$real_name','$email','$age','$char_race','$char_class','$char_name',".
					"'$reasons_for_joining','$self_description','$date','$ip')";

				$result = mysql_query($sql);
			}
			elseif( $valid == "n" )
			{
				echo "<h2>Application Errors</h1>\n";
				echo "<ol>\n";
				foreach($error as $value){
					echo "<li class=\"error\">".$value."</li>\n";
				}
				echo "</ol>\n";
				echo "<p></p>\n";
			}
		}
		@include(HOME_PATH . "template/html/apply_form.tpl");
	?>

	</td>

  </tr>
</table>
<!-- END CONTENT -->

<?PHP @include(HOME_PATH . "template/html/footer.tpl"); ?>

<?PHP mysql_close($conn); ?>