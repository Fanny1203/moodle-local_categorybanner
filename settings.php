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
 * Plugin settings
 *
 * @package    local_categorybanner
 * @copyright  2025 Your Name <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/classes/admin_setting_categorybanner_rules.php');
require_once(__DIR__ . '/classes/rule_manager.php');

if ($hassiteconfig) {
    // Check if we need to delete a rule
    $action = optional_param('action', '', PARAM_ALPHA);
    $ruleid = optional_param('rule', -1, PARAM_INT);
    
    if ($action === 'delete' && $ruleid >= 0 && confirm_sesskey()) {
        $rule = \local_categorybanner\rule_manager::get_rule($ruleid);
        if ($rule) {
            unset_config('rule_' . $ruleid . '_category', 'local_categorybanner');
            unset_config('rule_' . $ruleid . '_banner', 'local_categorybanner');
            cache_helper::purge_by_event('local_categorybanner_rule_updated');
            \core\notification::success(get_string('rule_deleted', 'local_categorybanner'));
            redirect(new moodle_url('/admin/settings.php', array('section' => 'local_categorybanner')));
        }
    }

    // Create the settings page
    $settings = new admin_settingpage('local_categorybanner', get_string('pluginname', 'local_categorybanner'));
    $ADMIN->add('localplugins', $settings);

    // Add rules management interface
    $settings->add(new admin_setting_categorybanner_rules());

    // Add external page for editing rules in a hidden section
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_categorybanner_edit',
        get_string('edit_rule', 'local_categorybanner'),
        new moodle_url('/local/categorybanner/edit.php'),
        'local_categorybanner:managebanner',
        true  // Hidden from menu
    ));
}
