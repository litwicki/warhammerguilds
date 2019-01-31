<script type="text/javascript" language="javascript"> function submitform(){ document.category_form.submit(); }</script>

<div style="display: block;">

<div style="float: left; display: inline; padding: 2px;">
<form name="categories" method="get" action="<? echo $_SERVER['REQUEST_URI']; ?>">
<select class="cat_select" name="c" id="video_category" onchange="categories.submit();">
<option value="-1" selected="selected">Browse by Category</option>
<?
$sql = "select * from ".WAR_DB.".war_video_categories where category_id < 6 order by category_id asc";
$result = mysql_query($sql);
while( $category = mysql_fetch_array($result) ){
	echo "<option value=\"".$category['category_id']."\">".$category['category_name']."</option>\n";
}
?>
</select>
</form>
</div>

<div style="padding: 1px;">
<form name="users" method="get" action="<? echo $_SERVER['REQUEST_URI']; ?>">
<input class="cat_author" name="s" type="text" value="search by username" /> <input class="cat_btn" type="submit" value="Go!" />
</form>
</div>

</div>