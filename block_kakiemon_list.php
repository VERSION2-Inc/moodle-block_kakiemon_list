<?php
defined('MOODLE_INTERNAL') || die();

class block_kakiemon_list extends block_base {
	public function init() {
		$this->name = 'list';
	}

	/**
	 *
	 * @return boolean[]
	 */
	public function applicable_formats() {
		return array(
				'course' => true
		);
	}

	/**
	 *
	 * @return \stdClass
	 */
	public function get_content() {
		return '';
	}
}
