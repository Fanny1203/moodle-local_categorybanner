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
 * Installation script for category banner plugin
 *
 * @package    local_categorybanner
 * @copyright  2025 Your Name <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Custom installation procedure
 */
function xmldb_local_categorybanner_install() {
    global $CFG;
    
    // Add the edit page to admin tree
    $entry = new admin_externalpage(
        'local_categorybanner_edit',
        get_string('edit_rule', 'local_categorybanner'),
        new moodle_url('/local/categorybanner/edit.php'),
        'local/categorybanner:managebanner'
    );
    
    // Register the page
    $ADMIN->add('localplugins', $entry);
    
    return true;
}
