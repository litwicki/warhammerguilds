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

@include_once(HOME_PATH . "template/html/header.tpl");

?>
<!-- END HEADER -->

<!-- BEGIN CONTENT -->
<table id="content" border="0" cellspacing="0">
  <tr>

	<td class="left_column">
	<?PHP @include(HOME_PATH . "template/html/side.tpl"); ?>
	</td>

	<td class="right_column"><?PHP @include(HOME_PATH . "template/html/faq.tpl"); ?></td>

  </tr>
</table>
<!-- END CONTENT -->

<?PHP @include(HOME_PATH . "template/html/footer.tpl"); ?>

<?PHP mysql_close($conn); ?>