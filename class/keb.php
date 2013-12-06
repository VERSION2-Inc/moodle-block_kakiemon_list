<?php
namespace ver2\kakiemon_list;

defined('MOODLE_INTERNAL') || die();

class keb {
	const COMPONENT = 'block_kakiemon_list';
	const PLUGIN_DIR = '/blocks/kakiemon_list/';

	/**
	 *
	 * @var int
	 */
	public $courseid;

	/**
	 *
	 * @param int $courseid
	 */
	public function __construct($courseid) {
		$this->courseid = $courseid;
	}

	/**
	 *
	 * @param string|\moodle_url $url
	 * @param array $params
	 * @return \moodle_url
	 */
	public function url($url, array $params = array()) {
		if ($url instanceof \moodle_url) {
			return new \moodle_url($url, $params);
		}

		if (!preg_match(',/$,', $url) && strpos(basename($url), '.') === false) {
			$url .= '.php';
		}
		if ($url[0] != '/') {
			$url = self::PLUGIN_DIR.$url;
		}

		$params = array('course' => $this->courseid) + $params;

		return new \moodle_url($url, $params);
	}

	/**
	 *
	 * @param string $identifier
	 * @param string|\stdClass $a
	 * @return string
	 */
	public static function str($identifier, $a = null) {
		return get_string($identifier, self::COMPONENT, $a);
	}
}
