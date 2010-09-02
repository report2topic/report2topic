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