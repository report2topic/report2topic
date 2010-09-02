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
 * report2topic++ hook class.
 * This class handles all hook action required for this MOD
 */
abstract class hook_report2topic
{
	/**
	 * @var report2topic_core Instance of the report2topic core
	 */
	static private $r2t_core = null;

	/**
	 * Register all hooks
	 * @param	phpbb_hook	$phpbb_hook	The phpBB hook object
	 * @return	void
	 */
	static public function init(phpbb_hook $phpbb_hook)
	{
		$phpbb_hook->register('phpbb_user_session_handler', 'hook_report2topic::setup');
		$phpbb_hook->register(array('template', 'display'), 'hook_report2topic::overrule_report');
	}

	/**
	 * Setup the report2topic++ MOD, load all required stuff
	 * @param	phpbb_hook	$phpbb_hook	The phpBB hook object
	 * @return	void
	 */
	static public function setup(phpbb_hook $phpbb_hook)
	{
		// Load the core
		if (!class_exists('report2topic_core'))
		{
			global $phpbb_root_path, $phpEx;
			require("{$phpbb_root_path}includes/mods/report2topic++/report2topic_core.{$phpEx}");
		}
		self::$r2t_core = report2topic_core::getInstance();

		// Load the MODs ACP language file
		if (defined('ADMIN_START'))
		{
			self::$r2t_core->user->add_lang('mods/report2topic++/report2topic_acp');
		}
	}

	/**
	 * Overrule all links to phpBB's report.php, and replace them with links
	 * to report2topic.php
	 * @param	phpbb_hook	$phpbb_hook	The phpBB hook object
	 * @return	void
	 */
	static public function overrule_report(phpbb_hook $phpbb_hook)
	{
		global $template, $user;

		// Reports in viewtopic
		if ($user->page['page_name'] == 'viewtopic.' . PHP_EXT && !empty($template->_tpldata['postrow']))
		{
			// Before viewtopic is displayed replace the report.php links
			foreach ($template->_tpldata['postrow'] as $row => $data)
			{
				if (!empty($template->_tpldata['postrow'][$row]['U_REPORT']))
				{
					$template->_tpldata['postrow'][$row]['U_REPORT'] = str_replace('report.' . PHP_EXT, 'report2topic.' . PHP_EXT, $template->_tpldata['postrow'][$row]['U_REPORT']);
				}
			}
		}

		if ($user->page['page_name'] == 'ucp.' . PHP_EXT && !empty($template->_tpldata['.'][0]['U_REPORT']))
		{
			$template->_tpldata['.'][0]['U_REPORT'] = str_replace('report.' . PHP_EXT, 'report2topic.' . PHP_EXT, $template->_tpldata['.'][0]['U_REPORT']);
		}
	}
}

hook_report2topic::init($phpbb_hook);