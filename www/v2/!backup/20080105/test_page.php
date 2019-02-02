<?php
/**
*
* @package phpBB3
* @version $Id: viewtopic.php,v 1.513 2007/11/06 00:05:53 kellanved Exp $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/

define('IN_PHPBB', true);
$phpbb_root_path = 'forums/';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

$user->session_begin();
$auth->acl($user->data);
$user->setup('viewforum');

$page_title = "Screenshot Gallery";

// build array of screenshot_ids
$sql = "SELECT * FROM war_screenshots";
$result = $db->sql_query($sql);
$num_img = $db->sql_affectedrows($sql);
$columns = 2;
$x = 0;
while( $row = $db->sql_fetchrow($result) )
{
	// every $cols images create a new row
	if($x % $columns == 0) { $s_new_row = true; }
	if(($x % $columns) == ($columns - 1) || ($x + 1) == $num_img) { $s_end_row = true; }

	$template->assign_block_vars('image',array(
		'DESCRIPTION'		=>		$row['description'],
		'VIEWS'				=>		$row['views'],
		'U_THUMBNAIL'		=>		date('Ymd',$row['date_submitted']).'/image__'.$row['screenshot_id'].'.jpg',
		'S_END_ROW'			=>		$s_end_row,
		'S_NEW_ROW'			=>		$s_new_row,
	));

	$s_new_row = false;
	$s_end_row = false;
	$x++;
}
$db->sql_freeresult($result);

// Output the page
page_header($page_title);

$template->set_filenames( array(
	'body' => 'war-screenshots.html')
);

page_footer();

?>