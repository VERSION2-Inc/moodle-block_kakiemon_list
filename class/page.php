<?php
namespace ver2\kakiemon_list;

defined('MOODLE_INTERNAL') || die();

abstract class page {
	/**
	 *
	 * @var \moodle_url
	 */
	protected $url;
	/**
	 *
	 * @var \core_renderer
	 */
	protected $output;
	/**
	 *
	 * @var \stdClass
	 */
	protected $course;

	/**
	 *
	 * @param string $url
	 */
	public function __construct($url) {
		global $DB, $PAGE, $OUTPUT;

		$courseid = required_param('course', PARAM_INT);
		$this->course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

		$this->url = new \moodle_url($url, array('course' => $courseid));
		$this->output = $OUTPUT;

		require_login($courseid);

		$PAGE->set_url($this->url);
		$PAGE->set_title($this->course->fullname);
		$PAGE->set_heading($this->course->fullname);
	}

	public abstract function execute();

	/**
	 *
	 * @param string $file
	 */
	public static function execute_new($file) {
		$page = new static(self::path_to_url($file));
		$page->execute();
	}

	/**
	 *
	 * @param string $file
	 * @return string
	 */
	protected static function path_to_url($file) {
		global $CFG;

		if (strpos($file, $CFG->dirroot) === false) {
			die;
		}

		$url = substr($file, strlen($CFG->dirroot));
		$url = strtr($url, '\\', '/');

		return $url;
	}
}
