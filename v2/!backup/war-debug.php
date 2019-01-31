<?

if( $_GET['debug'] == "on" && WarAccessLevel($user->data['user_id'],1) )
{
	$debug_array = split('\|',$debug);
	echo "<div id=\"debug\">\n";
	echo "<h3>Debugging Enabled</h3>\n";
	echo "<ul class=\"ul-debugging\">\n";
	foreach($debug_array as $value) {
		if( strlen($value) > 0 ) {
			echo "<li>".$value."</li>\n";
		}
	}
	echo "</ul>\n";
	echo "</div>\n";
}

# ---------------
# LOG EVERYTHING
# ---------------
$debug = "\n\n" . $debug;
warCompleteLog($debug, $debug_log);
?>