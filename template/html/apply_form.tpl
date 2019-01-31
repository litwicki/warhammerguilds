<div class="hr"></div>

<div id="form">
<form action="apply.php" method="post">

	<label for="realname">Real Name</label>
	<input id="realname" type="text" name="real_name" size="40" />

	<label for="age">Age</label>
	<input id="age" type="text" name="age" size="10" />

	<label for="email">Email</label>
	<input id="email" type="text" name="email" size="50" />

	<label for="charname">Character Name</label>
	<input id="charname" type="text" name="char_name" size="25" />

	<label for="char_info">Character Race &amp; Class</label>

	<select name="char_race">
	<option>Dwarf</option>
	<option>Empire</option>
	<option>High Elf</option>
	<option>Greenskin</option>
	<option>Chaos</option>
	<option>Dark Elf</option>
	</select>

	<label for="reasons_for_joining">Why do you want to join <?PHP echo SITE_NAME; ?>?</label>
	<textarea id="reasons_for_joining" name="reasons_for_joining" rows="6" cols="60"></textarea>

	<label for="self_description">Describe yourself to us.</label>
	<textarea id="self_description" name="self_description" rows="6" cols="60"></textarea>

	<p><input name="submit" type="Submit" id="submit_btn" value="" class="btn" /></p>

</form>
</div>