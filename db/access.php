<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = array(
		'block/kakiemon_list:addinstance' => array(
				'captype' => 'write',
				'contextlevel' => CONTEXT_BLOCK,
				'archetypes' => array(
						'editingteacher' => CAP_ALLOW,
						'manager' => CAP_ALLOW
				),
				'clonepermissionsfrom' => 'moodle/site:manageblocks'
		)
);
