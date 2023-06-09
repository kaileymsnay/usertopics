<?php
/**
 *
 * User Topics extension for the phpBB Forum Software package
 *
 * @copyright (c) 2023, Kailey Snay, https://www.snayhomelab.com/
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kaileymsnay\usertopics\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * User Topics event listener
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \phpbb\language\language */
	protected $language;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/**
	 * Constructor
	 *
	 * @param \phpbb\language\language  $language
	 * @param string                    $root_path
	 * @param string                    $php_ext
	 */
	public function __construct(\phpbb\language\language $language, $root_path, $php_ext)
	{
		$this->language = $language;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;
	}

	public static function getSubscribedEvents()
	{
		return [
			'core.user_setup'	=> 'user_setup',

			'core.memberlist_prepare_profile_data'	=> 'memberlist_prepare_profile_data',
		];
	}

	/**
	 * Load common language files
	 */
	public function user_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'kaileymsnay/usertopics',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function memberlist_prepare_profile_data($event)
	{
		$user_data = $event['data'];

		$user_id = $user_data['user_id'];
		$event->update_subarray('template_data', 'U_SEARCH_TOPICS', append_sid("{$this->root_path}search.$this->php_ext", ['author_id' => (int) $user_id, 'sr' => 'topics']));
	}
}
