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

		// Some adm hooks
		if (defined('ADMIN_START'))
		{
			$phpbb_hook->register(array('template', 'display'), 'hook_report2topic::add_forum_options');
		}
	}

	/**
	 * Modify the manage forums pages in the ACP by adding some
	 * additional template stuff.
	 * @param	phpbb_hook	$phpbb_hook	The phpBB hook object
	 * @return	void
	 */
	static public function add_forum_options(phpbb_hook $phpbb_hook)
	{
		global $mode, $module_id, $update;
		$fid = request_var('f', 0);

		if ($module_id != 'forums' || $mode != 'manage' || $update)
		{
			return;
		}

		// The forum that is being editted is the current default
		// report destination forum. Don't show this!
		if ($fid == self::$r2t_core->config['r2t_dest_forum'])
		{
			self::$r2t_core->template->assign_var('S_CAN_SET_R2T_DEST_FORUM', false);
			return;
		}

		// Need to grep some data from the db here
		$default_name	= '';
		$selected_id	= 0;
		$sql = 'SELECT forum_id, forum_name, r2t_report_forum
			FROM ' . FORUMS_TABLE . '
			WHERE ' . self::$r2t_core->db->sql_in_set('forum_id', array($fid, self::$r2t_core->config['r2t_dest_forum']));
		$result	= self::$r2t_core->db->sql_query($sql);
		while ($forum = self::$r2t_core->db->sql_fetchrow($result))
		{
			if ($forum['forum_id'] == $fid)
			{
				$selected_id = $forum['r2t_report_forum'];
			}
			else
			{
				$default_name = $forum['forum_name'];
			}
		}

		// Assign our additional vars
		self::$r2t_core->template->assign_vars(array(
			'S_CAN_SET_R2T_DEST_FORUM'		=> true,
			'S_R2T_DEFAULT_DEST'			=> $default_name,	// Default forum as set in the ACP page
			'S_R2T_DEFAULT_DEST_OPTIONS'	=> make_forum_select($selected_id, false, true, true),
		));
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

		// Load the MODs ACP language files
		self::$r2t_core->user->add_lang('mods/report2topic++/report2topic_common');

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