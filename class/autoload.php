<?php
defined('MOODLE_INTERNAL') || die();

function block_kakiemon_list_autoload($classname) {
	global $CFG;

	if (strpos($classname, 'ver2\\kakiemon_list') === 0) {
		$classname = preg_replace('/^ver2\\\\kakiemon_list\\\\/', '', $classname);

		$classdir = $CFG->dirroot . '/blocks/kakiemon_list/class/';
		$path = $classdir . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
		if (is_readable($path)) {
			require $path;
		}
	}
}

spl_autoload_register('block_kakiemon_list_autoload');
