<? 
include("war-open.php");
include("war/html/war-header.tpl");

?>

<div id="war-content">
		<div id="war-left"><div style="margin: 10px;"><? @include("war/html/war-left.tpl"); ?></div></div>
		
		<div id="war-center">
			<div style="padding: 2em; margin: auto;">
			<!-- Begin Content -->

			<? 

				# --------------------------------
				# Include the WAR Welcome Message
				# --------------------------------
				@include("war/html/war-welcome.tpl");
				
				# --------------------------------
				# Page specific content here
				# --------------------------------
				
				# ---------------------------------
				# Debugging And Data Logging Code
				# ---------------------------------
				@include("war-debug.php");
			?>

			<!-- End Content -->
			</div>
		</div>
	</div>

	<div id="war-footer"><div style="margin: 10px;"><? @include("war/html/war-footer.tpl"); ?></div></div>

</div>
</div>

</body>
</html>

<!-- Begin Page Close Code -->
<? @include("inc/war-close.php"); ?>
<!-- End Page Close Code -->