<?php
/**
 *
 * @package report2topic++
 * @copyright (c) 2010 report2topic++ http://github.com/report2topic
 * @author Erik Frèrejean ( N/A ) http://www.erikfrerejean.nl
 * @author David King (imkingdavid) http://www.phpbbdevelopers.net
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'ACP_REPORT2TOPIC'					=> 'report2topic',
	'ACP_REPORT2TOPIC_CONFIG'			=> 'report2topic configuration',
	'ACP_REPORT2TOPIC_CONFIG_EXPLAIN'	=> 'The main report2topic++ configuration.',
	'ACP_REPORT2TOPIC_CONFIG_SUCCESS'	=> 'The main configuration has been updated successfully!',

	'R2T_DEST_FORUM'			=> 'Destination forum',
	'R2T_DEST_FORUM_EXPLAIN'	=> 'Enter the forum ID that will be used to post reports to. <br /><strong style="color: #f00;">Note:</strong> This will be replaced with a more flexible forum based system later on!',
));