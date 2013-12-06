<?php
namespace ver2\kakiemon_list;

use ver2\kakiemon\ke;

require_once '../../config.php';
require_once $CFG->dirroot . '/blocks/kakiemon_list/locallib.php';
require_once $CFG->libdir . '/tablelib.php';

class page_pages extends page {
	public function execute() {
		$this->view();
	}

	private function view() {
		$mode = required_param('mode', PARAM_ALPHA);
		
		echo $this->output->header();
		
		echo $this->output->heading(keb::str('kakiemonpagelist'));
		echo $this->output->heading(keb::str(''), 3);

		$this->print_page_table();

		echo $this->output->footer();
	}

	private function print_page_table() {
		global $DB;

		$from = '
				FROM {'.ke::TABLE_PAGES.'} p
					JOIN {'.ke::TABLE_MOD.'} k ON p.kakiemon = k.id
					JOIN {user} u ON p.userid = u.id
				';
		$where = '
				WHERE k.course = :course
				';
		$params = array(
				'course' => $this->course->id
		);

		$sql = '
				SELECT COUNT(*)
				'
				.$from.$where;
		$count = $DB->count_records_sql($sql, $params);

		$table = new \flexible_table('pages');
		$table->define_baseurl($this->url);
		$columns = array('name', 'likes', 'fullname', 'userid');
		$headers=$columns;
		$table->define_columns($columns);
		$table->define_headers($headers);
		$table->pagesize(10, $count);
		$table->useridfield = 'userid';
		$table->setup();

		$sqw=$table->get_sql_where();
		$limit = ' LIMIT '.$table->get_page_start().','.$table->get_page_size();
		$sql = '
				SELECT p.*,
					u.id userid, u.lastname, u.firstname
				'
				.$from.$where
				.'
				ORDER BY p.timemodified DESC
				'
		.$limit;
		$pages = $DB->get_records_sql(
				$sql,
				$params
		);
		foreach ($pages as $page) {
			$row = array(
					$page->name,
					$page->likes,
					$page->userid,
					$page->userid
			);
			$table->add_data($row);
		}
		$table->finish_output();
	}
}

page_pages::execute_new(__FILE__);
