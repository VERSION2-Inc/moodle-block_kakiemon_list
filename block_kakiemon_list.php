<?php
defined('MOODLE_INTERNAL') || die();

use ver2\kakiemon_list\keb;
use ver2\kakiemon\ke;
use ver2\kakiemon\ke_page;

class block_kakiemon_list extends block_base {
	const PAGE_NUM = 5;

	/**
	 *
	 * @var keb
	 */
	private $keb;
	/**
	 *
	 * @var ke_page
	 */
	private $pagedb;

	public function init() {
		$this->title = get_string('pluginname', 'block_kakiemon_list');
	}

	/**
	 *
	 * @return \stdClass
	 */
	public function get_content() {
		global $CFG, $PAGE, $OUTPUT, $COURSE;

		if (!isloggedin()) {
			return null;
		}

		if ($this->content) {
			return $this->content;
		}

		require_once $CFG->dirroot . '/blocks/kakiemon_list/locallib.php';

		$this->keb = new keb($COURSE->id);
		$this->pagedb = new ke_page();

		$o = '';

		$o .= $this->get_page_list('my');
		$o .= $this->get_page_list('other');
		$o .= $this->get_page_list('best');
		$o .= $this->get_page_list('worst');

		$this->content = (object)array(
				'text' => $o,
				'footer' => ''
		);

		return $this->content;
	}

	/**
	 *
	 * @param string $mode
	 * @return string
	 */
	private function get_page_list($mode) {
		global $OUTPUT;

		switch ($mode) {
			case 'my':
				$heading = keb::str('mypages');
				$pages = $this->pagedb->mine(self::PAGE_NUM);
				$count = $this->pagedb->count_mine();
				$moretext = keb::str('seexmorepages', $count - self::PAGE_NUM);
				break;
			case 'other':
				$heading = keb::str('otherspages');
				$pages = $this->pagedb->others(self::PAGE_NUM);
				$count = $this->pagedb->count_others();
				$moretext = keb::str('seexmorepages', $count - self::PAGE_NUM);
				break;
			case 'best':
				$heading = keb::str('bestpages');
				$pages = $this->pagedb->most_liked();
				$moretext = keb::str('seemore');
				break;
			case 'worst':
				$heading = keb::str('worstpages');
				$pages = $this->pagedb->most_disliked();
				$moretext = keb::str('seemore');
				break;
		}

		$o = '';
		$o .= $OUTPUT->heading($heading, 3);

		$dateformat = get_string('strftimedate', 'langconfig');

		$o .= \html_writer::start_tag('ul', array('class' => 'pagelist'));
		foreach ($pages as $page) {
			$cm = get_coursemodule_from_instance('kakiemon', $page->kakiemon, 0, false, MUST_EXIST);
			$url = new \moodle_url('/mod/kakiemon/page_view.php', array(
					'id' => $cm->id,
					'page' => $page->id
			));
			$link = $OUTPUT->action_link($url, $page->name);
			$link .= ' (' .userdate($page->timemodified, $dateformat) .')';
			$o .= \html_writer::tag('li', $link);
		}
		$o .= \html_writer::end_tag('ul');

		if ($mode == 'best' || $mode == 'worst' || $count > self::PAGE_NUM) {
			$url = $this->keb->url('pages', array('mode' => $mode));
			$o .= $OUTPUT->container(
					$OUTPUT->action_link($url, $moretext),
					'seemore');
		}

		return $o;
	}
}
