<?php
defined('MOODLE_INTERNAL') || die();

use ver2\kakiemon_list\keb;
use ver2\kakiemon\ke;
use ver2\kakiemon\ke_page;

class block_kakiemon_list extends block_base {
	const PAGE_NUM = 5;

	private $pagedb;
	private $keb;

	public function init() {
		$this->title = get_string('pluginname', 'block_kakiemon_list');
	}

	/**
	 *
	 * @return \stdClass
	 */
	public function get_content() {
		global $CFG, $PAGE, $OUTPUT, $COURSE;

		if ($this->content) {
			return $this->content;
		}

		require_once $CFG->dirroot . '/blocks/kakiemon_list/locallib.php';

		$this->keb = new keb($COURSE->id);

		$o = '';

		$this->pagedb = new ke_page();

		$o .= $this->get_page_list('my');
		$o .= $this->get_page_list('other');
		$o .= $this->get_page_list('best');
		$o .= $this->get_page_list('worst');

		// TODO fast modinfo検討

// 		// マイページ
// 		$o .= $OUTPUT->heading(keb::str('mypages'), 3);
// 		$pagedb = new ke_page();
// 		$pages = $pagedb->mine(self::PAGE_NUM);
// 		$o .= \html_writer::start_tag('ul');
// 		foreach ($pages as $page) {
// 			$cm = get_coursemodule_from_instance('kakiemon', $page->kakiemon, 0, false, MUST_EXIST);
// 			$url = new \moodle_url('/mod/kakiemon/page_view.php', array(
// 					'id' => $cm->id,
// 					'page' => $page->id
// 			));
// 			$link = $OUTPUT->action_link($url, $page->name);
// 			$link .= ' (' .userdate($page->timemodified, $dateformat) .')';
// 			$o .= \html_writer::tag('li', $link);
// 		}
// 		$o .= \html_writer::end_tag('ul');
// 		$count = $pagedb->count_mine();
// 		if ($count > self::PAGE_NUM) {
// 			$url = $keb->url('pages', array('mode' => 'my'));
// 			$o .= $OUTPUT->container(
// 					$OUTPUT->action_link($url, keb::str('seexmorepages', $count - self::PAGE_NUM)),
// 					'seemore');
// 		}

// 		// 他のページ
// 		$o .= $OUTPUT->heading(keb::str('otherspages'));
// 		$pagedb = new ke_page();
// 		$pages = $pagedb->others(self::PAGE_NUM);
// 		$o .= \html_writer::start_tag('ul');
// 		foreach ($pages as $page) {
// 			$cm = get_coursemodule_from_instance('kakiemon', $page->kakiemon, 0, false, MUST_EXIST);
// 			$url = new \moodle_url('/mod/kakiemon/page_view.php', array(
// 					'id' => $cm->id,
// 					'page' => $page->id
// 			));
// 			$link = $OUTPUT->action_link($url, $page->name);
// 			$link .= ' (' .userdate($page->timemodified, $dateformat) .')';
// 			$o .= \html_writer::tag('li', $link);
// 		}
// 		$o .= \html_writer::end_tag('ul');
// 		$count = $pagedb->count_others();
// 		if ($count > self::PAGE_NUM) {
// 			$o .= $OUTPUT->action_link('', keb::str('seexmorepages', $count - self::PAGE_NUM));
// 		}

// 		$strseemore = keb::str('seemore');

// 		// ベストランキング
// 		$o .= $OUTPUT->heading(keb::str('bestpages'));
// 		$pagedb = new ke_page();
// 		$pages = $pagedb->most_liked();
// 		$o .= \html_writer::start_tag('ul');
// 		foreach ($pages as $page) {
// 			$cm = get_coursemodule_from_instance('kakiemon', $page->kakiemon, 0, false, MUST_EXIST);
// 			$url = new \moodle_url('/mod/kakiemon/page_view.php', array(
// 					'id' => $cm->id,
// 					'page' => $page->id
// 			));
// 			$link = $OUTPUT->action_link($url, $page->name).' ('.$page->cnt.ke::str('like').')';
// 			$o .= \html_writer::tag('li', $link);
// 		}
// 		$o .= \html_writer::end_tag('ul');
// 		$url = new \moodle_url('/blocks/kakiemon_list/pages.php', array(
// 				'mode' => 'best'
// 		));
// 		$o .= $OUTPUT->action_link($url, $strseemore, null, array('class' => 'seemore'));

// 		// ワーストランキング
// 		$o .= $OUTPUT->heading(keb::str('worstpages'));
// 		$pagedb = new ke_page();
// 		$pages = $pagedb->most_disliked();
// 		$o .= \html_writer::start_tag('ul');
// 		foreach ($pages as $page) {
// 			$cm = get_coursemodule_from_instance('kakiemon', $page->kakiemon, 0, false, MUST_EXIST);
// 			$url = new \moodle_url('/mod/kakiemon/page_view.php', array(
// 					'id' => $cm->id,
// 					'page' => $page->id
// 			));
// 			$link = $OUTPUT->action_link($url, $page->name).' ('.$page->cnt.ke::str('dislike').')';
// 			$o .= \html_writer::tag('li', $link);
// 		}
// 		$o .= \html_writer::end_tag('ul');
// 		$url = new \moodle_url('/blocks/kakiemon_list/pages.php', array(
// 				'mode' => 'best'
// 		));
// 		$o .= $OUTPUT->action_link($url, $strseemore, null, array('class' => 'seemore'));

		$this->content = (object)array(
				'text' => $o,
				'footer' => ''
		);

		return $this->content;
	}

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
