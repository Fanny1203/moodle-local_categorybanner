<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Test script to view banner rules
 *
 * @package    local_categorybanner
 * @copyright  2025 Your Name <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

// Check permissions
require_login();
require_capability('moodle/category:manage', context_system::instance());

// Setup page
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/categorybanner/test_rules.php'));
$PAGE->set_title(get_string('pluginname', 'local_categorybanner') . ': ' . 'Test Rules');
$PAGE->set_heading($PAGE->title);

echo $OUTPUT->header();
echo $OUTPUT->heading('Banner Rules');

// Get and display all rules
$rules = \local_categorybanner\rule_manager::get_all_rules();

if (empty($rules)) {
    echo html_writer::tag('p', 'No rules found.');
} else {
    $table = new html_table();
    $table->head = array('Rule ID', 'Category ID', 'Banner Content');
    $table->data = array();
    
    foreach ($rules as $rule) {
        $table->data[] = array(
            $rule['id'],
            $rule['category'],
            format_text($rule['banner'], FORMAT_HTML)
        );
    }
    
    echo html_writer::table($table);
}

// Show raw config for debugging
echo $OUTPUT->heading('Raw Configuration', 3);
echo html_writer::start_tag('pre');
print_r(get_config('local_categorybanner'));
echo html_writer::end_tag('pre');

echo $OUTPUT->footer();
