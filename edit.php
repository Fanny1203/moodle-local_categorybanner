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
 * Edit banner rule
 *
 * @package    local_categorybanner
 * @copyright  2025 Service Ecole Media <sem.web@edu.ge.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/local/categorybanner/classes/form/edit_rule.php');
require_once($CFG->dirroot . '/local/categorybanner/classes/rule_manager.php');

// Check permissions
require_login();
require_capability('local_categorybanner:managebanner', context_system::instance());

$action = required_param('action', PARAM_ALPHA);
$ruleid = optional_param('rule', -1, PARAM_INT);

$returnurl = new moodle_url('/admin/settings.php', array('section' => 'local_categorybanner'));

// Setup admin page
admin_externalpage_setup('local_categorybanner_edit');
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/categorybanner/edit.php', array('action' => $action)));
$PAGE->navbar->add(get_string('pluginname', 'local_categorybanner'), $returnurl);
$PAGE->navbar->add(get_string($action === 'edit' ? 'edit_rule' : 'add_rule', 'local_categorybanner'));
$PAGE->set_title(get_string($action === 'edit' ? 'edit_rule' : 'add_rule', 'local_categorybanner'));
$PAGE->set_heading($PAGE->title);

$mform = new \local_categorybanner\form\edit_rule($PAGE->url);

if ($action === 'edit' && $ruleid >= 0) {
    $rule = \local_categorybanner\rule_manager::get_rule($ruleid);
    if ($rule) {
        $mform->set_data(array(
            'rule' => $ruleid,
            'action' => 'edit',
            'category' => $rule['category'],
            'banner' => array(
                'text' => $rule['banner'],
                'format' => FORMAT_HTML
            )
        ));
    }
} else {
    $mform->set_data(array(
        'action' => $action,
        'rule' => -1
    ));
}

if ($mform->is_cancelled()) {
    redirect($returnurl);
} else if ($data = $mform->get_data()) {
    // Save the rule using rule manager
    $ruleid = \local_categorybanner\rule_manager::save_rule(
        $data->rule,
        $data->category,
        $data->banner['text']
    );
    
    redirect($returnurl, get_string('rule_saved', 'local_categorybanner'), null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
echo $OUTPUT->heading($PAGE->title);
$mform->display();
echo $OUTPUT->footer();
