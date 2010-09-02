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

/**
* @package module_install
*/
class acp_report2topic_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_report2topic',
			'title'		=> 'ACP_REPORT2TOPIC',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'config'	=> array('title' => 'ACP_REPORT2TOPIC_CONFIG', 'auth' => '', 'cat' => array('ACP_REPORT2TOPIC')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}