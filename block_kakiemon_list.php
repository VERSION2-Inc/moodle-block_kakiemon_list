<?php
defined('MOODLE_INTERNAL') || die();

use ver2\kakiemon_list\keb;
use ver2\kakiemon\ke;
use ver2\kakiemon\ke_page;

class block_kakiemon_list extends block_base {
	const PAGE_NUM = 5;

	public function init() {
		$this->title = get_string('pluginname', 'block_kakiemon_list');
	}

// 	/**
// 	 *
// 	 * @return boolean[]
// 	 */
// 	public function applicable_formats() {
// 		return array(
// 				'course' => true
// 		);
// 	}

	/**
	 *
	 * @return \stdClass
	 */
	public function get_content() {
		global $CFG, $OUTPUT, $COURSE;

		if ($this->content) {
			return $this->content;
		}

		require_once $CFG->dirroot . '/blocks/kakiemon_list/locallib.php';

		$keb = new keb($COURSE->id);

		$o = '';

		$o .= $OUTPUT->heading(keb::str('mypages'));
		$pagedb = new ke_page();
		$pages = $pagedb->mine(self::PAGE_NUM);
		$o .= \html_writer::start_tag('ul');
		foreach ($pages as $page) {
			$cm = get_coursemodule_from_instance('kakiemon', $page->kakiemon, 0, false, MUST_EXIST);
			$url = new \moodle_url('/mod/kakiemon/page_view.php', array(
					'id' => $cm->id,
					'page' => $page->id
			));
			$link = $OUTPUT->action_link($url, $page->name);
			$o .= \html_writer::tag('li', $link);
		}
		$o .= \html_writer::end_tag('ul');

		$o .= $OUTPUT->heading(keb::str('otherspages'));
		$pagedb = new ke_page();
		$pages = $pagedb->others(self::PAGE_NUM);
		$o .= \html_writer::start_tag('ul');
		foreach ($pages as $page) {
			$cm = get_coursemodule_from_instance('kakiemon', $page->kakiemon, 0, false, MUST_EXIST);
			$url = new \moodle_url('/mod/kakiemon/page_view.php', array(
					'id' => $cm->id,
					'page' => $page->id
			));
			$link = $OUTPUT->action_link($url, $page->name);
			$o .= \html_writer::tag('li', $link);
		}
		$o .= \html_writer::end_tag('ul');


		$o .= $OUTPUT->heading(keb::str('bestpages'));
		$pagedb = new ke_page();
		$pages = $pagedb->most_liked();
		$o .= \html_writer::start_tag('ul');
		foreach ($pages as $page) {
			$cm = get_coursemodule_from_instance('kakiemon', $page->kakiemon, 0, false, MUST_EXIST);
			$url = new \moodle_url('/mod/kakiemon/page_view.php', array(
					'id' => $cm->id,
					'page' => $page->id
			));
			$link = $OUTPUT->action_link($url, $page->name).' ('.$page->cnt.ke::str('like').')';
			$o .= \html_writer::tag('li', $link);
		}
		$o .= \html_writer::end_tag('ul');

		$o .= $OUTPUT->heading(keb::str('worstpages'));
		$pagedb = new ke_page();
		$pages = $pagedb->most_disliked();
		$o .= \html_writer::start_tag('ul');
		foreach ($pages as $page) {
			$cm = get_coursemodule_from_instance('kakiemon', $page->kakiemon, 0, false, MUST_EXIST);
			$url = new \moodle_url('/mod/kakiemon/page_view.php', array(
					'id' => $cm->id,
					'page' => $page->id
			));
			$link = $OUTPUT->action_link($url, $page->name).' ('.$page->cnt.ke::str('dislike').')';
			$o .= \html_writer::tag('li', $link);
		}
		$o .= \html_writer::end_tag('ul');

		$this->content = (object)array(
				'text' => $o,
				'footer' => ''
		);

		return $this->content;
	}

	private function get_page_list() {
		$o .= \html_writer::start_tag('ul');
		foreach ($pages as $page) {
			$cm = get_coursemodule_from_instance('kakiemon', $page->kakiemon, 0, false, MUST_EXIST);
			$url = new \moodle_url('/mod/kakiemon/page_view.php', array(
					'id' => $cm->id,
					'page' => $page->id
			));
			$link = $OUTPUT->action_link($url, $page->name);
			$o .= \html_writer::tag('li', $link);
		}
		$o .= \html_writer::end_tag('ul');
	}
}
