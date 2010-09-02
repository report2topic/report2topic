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
 * The main report2topic++ class, handles all common stuff
 */
class report2topic_core
{
	/**@#+
	 * Some commonly used phpBB objects, by defining them here we don't need to
	 * globalize them all over the place.
	 */
	private $auth		= null;
	private $cache		= null;
	private $config		= array();
	private $db			= null;
	private $template	= null;
	private $user		= null;
	/**@#-*/

	/**
	 * @var report2topic_core Store an instance of this class.
	 */
	static private $instance = null;

	/**
	 * @var String Report post template
	 * @todo Replace by a fully configurable template
	 */
//Array
//(
//    [post_subject] => Welcome to phpBB3
//    [post_id] => 1
//    [user_id] => 2
//    [report_id] => 9
//    [report_closed] => 0
//    [report_time] => 1283461074
//    [report_text] =>
//    [reason_title] => warez
//    [reason_description] => The post contains links to illegal or pirated software.
//    [username] => Erik Frèrejean
//    [username_clean] => erik frèrejean
//    [user_colour] => AA0000
//)
	private $post_template = 'A new report has been made by %1$s, the report details are:.

[b]The report[/b]: <a href="%2$s">%3$s</a>
[b]Report time[/b]: %4$s
[b]Report reason[/b]: %5$s';

	/**
	 * Construct the main class
	 */
	private function __construct()
	{
		// Globalize some of the main phpBB objects
		global $auth, $cache, $config, $db, $template, $user;
		$this->auth		= &$auth;
		$this->cache	= &$cache;
		$this->config	= &$config;
		$this->db		= &$db;
		$this->template	= &$template;
		$this->user		= &$user;

		// If needed make constants out of the phpBB paths
		if (!defined('PHPBB_ROOT_PATH'))
		{
			global $phpbb_root_path;
			define('PHPBB_ROOT_PATH', $phpbb_root_path);
		}

		if (!defined('PHP_EXT'))
		{
			global $phpEx;
			define('PHP_EXT', $phpEx);
		}
	}

	/**
	 * Get instance of the core class, acts as an singleton loader
	 * @return report2topic_core Instance of this class
	 */
	static public function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new report2topic_core();
		}

		return self::$instance;
	}

	/**
	 * A new report is created, create the report topic
	 * @param	Integer	$pm_id		ID of the reported PM
	 * @param	Integer	$post_id	ID of the reported post
	 * @return	void
	 */
	public function submit_report_post($pm_id = 0, $post_id = 0)
	{
		// Fetch the report data
		$report_data = array();
		if ($pm_id > 0)
		{
			$report_data = $this->get_pm_report($pm_id);
			$i_mcp = 'pm_reports';
			$mode_mcp = 'pm_report_details';
		}
		else if ($post_id > 0)
		{
			$report_data = $this->get_post_report($post_id);
			$i_mcp = 'reports';
			$mode_mcp = 'report_details';
		}
		else
		{
			// No report, shouldn't happen but hey ;)
			return;
		}

		$backlink = append_sid(PHPBB_ROOT_PATH . 'mcp.' . PHP_EXT, array('i' => $i_mcp, 'mode' => $mode_mcp, 'r' => $report_data['report_id']));

		// Get the message parser
		if (!class_exists('parse_message'))
		{
			global $phpbb_root_path, $phpEx;	//	<!-- Required otherwise the message parser whines
			require PHPBB_ROOT_PATH . 'includes/message_parser.' . PHP_EXT;
		}

		if (!function_exists('get_username_string'))
		{
			require PHPBB_ROOT_PATH . 'includes/functions_content.' . PHP_EXT;
		}

		// Prepare the post
		$subject = (!empty($report_data['post_subject'])) ? censor_text($report_data['post_subject']) : censor_text($report_data['message_subject']);
		$post = sprintf($this->post_template,
						get_username_string('full', $report_data['user_id'], $report_data['username'], $report_data['user_colour']),
						$backlink,
						$subject,
						$this->user->format_date($report_data['report_time']),
						$report_data['reason_title']);

		// Load the message parser
		$report_parser = new parse_message($post);

		// Parse the post
		$report_parser->parse(true, true, true);

		// Set all the post data
		$poll_data = array();
		$post_data = array(
			'forum_id'	=> $this->config['r2t_dest_forum'],    // The forum ID in which the post will be placed. (int)
			'topic_id'	=> 0,    // Post a new topic or in an existing one? Set to 0 to create a new one, if not, specify your topic ID here instead.
			'icon_id'	=> false,    // The Icon ID in which the post will be displayed with on the viewforum, set to false for icon_id. (int)

			// Defining Post Options
			'enable_bbcode'		=> true, // Enable BBcode in this post. (bool)
			'enable_smilies'	=> true, // Enabe smilies in this post. (bool)
			'enable_urls'       => true, // Enable self-parsing URL links in this post. (bool)
			'enable_sig'        => true, // Enable the signature of the poster to be displayed in the post. (bool)

			// Message Body
			'message'		=> $report_parser->message,     // Your text you wish to have submitted. It should pass through generate_text_for_storage() before this. (string)
			'message_md5'	=> md5($report_parser->message),// The md5 hash of your message

			// Values from generate_text_for_storage()
			'bbcode_bitfield'	=> $report_parser->bbcode_bitfield,    // Value created from the generate_text_for_storage() function.
			'bbcode_uid'		=> $report_parser->bbcode_uid,     // Value created from the generate_text_for_storage() function.

			// Other Options
			'post_edit_locked'	=> 1,        // Disallow post editing? 1 = Yes, 0 = No
			'topic_title'		=> $subject, // Subject/Title of the topic. (string)

			// Email Notification Settings
			'notify_set'	=> false,        // (bool)
			'notify'		=> false,        // (bool)
			'post_time'		=> 0,        // Set a specific time, use 0 to let submit_post() take care of getting the proper time (int)
			'forum_name'	=> '',       // For identifying the name of the forum in a notification email. (string)

			// Indexing
			'enable_indexing' => true,     // Allow indexing the post? (bool)
		);

		// And finally submit
		if (!function_exists('submit_post'))
		{
			require PHPBB_ROOT_PATH . 'includes/functions_posting.' . PHP_EXT;
		}
		submit_post('post', $post_data['topic_title'], '', POST_NORMAL, $poll_data, $post_data);
	}

	/**
	 * Get the report data of this reported PM
	 * @param	Integer	$pm_id ID of the reported PM
	 * @return	Array	The report data
	 */
	private function get_pm_report($pm_id) {
		$sql = 'SELECT pm.message_subject, r.post_id, r.user_id, r.report_id, r.report_closed, report_time, r.report_text, rr.reason_title, rr.reason_description, u.username, u.username_clean, u.user_colour
			FROM (' . PRIVMSGS_TABLE . ' pm, ' . REPORTS_TABLE . ' r, ' . REPORTS_REASONS_TABLE . ' rr, ' . USERS_TABLE . " u)
			WHERE r.pm_id = {$pm_id}
				AND rr.reason_id = r.reason_id
				AND r.user_id = u.user_id
				AND r.post_id = 0
				AND pm.msg_id = r.pm_id
			ORDER BY report_closed ASC";
		$result = $this->db->sql_query_limit($sql, 1);
		$report = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $report;
	}

	/**
	 * Get the report data of this reported post
	 * @param	Integer	$post_id	ID of the reported post
	 * @return	Array	The report data
	 */
	private function get_post_report($post_id) {
		$sql = 'SELECT p.post_subject, r.post_id, r.user_id, r.report_id, r.report_closed, report_time, r.report_text, rr.reason_title, rr.reason_description, u.username, u.username_clean, u.user_colour
			FROM (' . POSTS_TABLE . ' p, ' . REPORTS_TABLE . ' r, ' . REPORTS_REASONS_TABLE . ' rr, ' . USERS_TABLE . " u)
			WHERE r.post_id = {$post_id}
				AND rr.reason_id = r.reason_id
				AND r.user_id = u.user_id
				AND r.pm_id = 0
				AND p.post_id = r.post_id
			ORDER BY report_closed ASC";
		$result = $this->db->sql_query_limit($sql, 1);
		$report = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $report;
	}


	//-- Magic methods
	/**
	 * Is triggered when your class instance (and inherited classes) does not contain the member or method name
	 * @param	String	$name	The name of the requested member
	 * @return	mixed	Value of the requested member
	 */
	public function __get($name)
	{
		// phpBB objects are returned always
		if (in_array($name, array('auth', 'cache', 'config', 'db', 'template', 'user')))
		{
			return $this->$name;
		}
	}
}