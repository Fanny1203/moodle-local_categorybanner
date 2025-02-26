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
 * Admin setting for managing banner rules
 *
 * @package    local_categorybanner
 * @copyright  2025 Your Name <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Admin setting for managing banner rules
 */
class admin_setting_categorybanner_rules extends admin_setting {
    /**
     * Constructor
     */
    public function __construct() {
        $this->nosave = true;
        parent::__construct('local_categorybanner_rules',
                          get_string('rules', 'local_categorybanner'),
                          get_string('rules_desc', 'local_categorybanner'), '');
    }

    /**
     * Always returns true
     *
     * @return bool Always returns true
     */
    public function get_setting() {
        return true;
    }

    /**
     * Always returns true
     *
     * @return bool Always returns true
     */
    public function get_defaultsetting() {
        return true;
    }

    /**
     * Never write settings
     *
     * @param mixed $data
     * @return string Always returns an empty string
     */
    public function write_setting($data) {
        // Never write settings.
        return '';
    }

    /**
     * Output the banner rules management interface
     *
     * @param mixed $data
     * @param string $query
     * @return string Returns an HTML string
     */
    public function output_html($data, $query='') {
        global $OUTPUT;
        
        $return = '';
        
        // Debug: Show all plugin config
        //$return .= html_writer::tag('pre', 'Plugin Config: ' . print_r(get_config('local_categorybanner'), true));
        
        // Get rules using rule manager
        $rules = \local_categorybanner\rule_manager::get_all_rules();
        
        // Debug: Show rules array
        //$return .= html_writer::tag('pre', 'Rules Array: ' . print_r($rules, true));
        
        if (empty($rules)) {
            $return .= html_writer::tag('p', get_string('no_rules', 'local_categorybanner'));
        } else {
            // Create table
            $table = new html_table();
            $table->head = array(
                get_string('category', 'local_categorybanner'),
                get_string('banner_content', 'local_categorybanner'),
                get_string('actions')
            );
            $table->attributes['class'] = 'generaltable';
            $table->data = array();
            
            foreach ($rules as $rule) {
                $category = core_course_category::get($rule['category'], IGNORE_MISSING);
                $categoryname = $category ? $category->get_formatted_name() : get_string('unknown_category', 'local_categorybanner');
                
                // Action buttons
                $buttons = array();
                
                // Edit button
                $editurl = new moodle_url('/local/categorybanner/edit.php', array(
                    'action' => 'edit',
                    'rule' => $rule['id'],
                    'sesskey' => sesskey()
                ));
                $buttons[] = html_writer::link(
                    $editurl,
                    $OUTPUT->pix_icon('t/edit', get_string('edit')),
                    array('title' => get_string('edit'))
                );
                
                // Delete button
                $deleteurl = new moodle_url('/admin/settings.php', array(
                    'section' => 'local_categorybanner',
                    'action' => 'delete',
                    'rule' => $rule['id'],
                    'sesskey' => sesskey()
                ));
                $buttons[] = html_writer::link(
                    $deleteurl,
                    $OUTPUT->pix_icon('t/delete', get_string('delete')),
                    array(
                        'title' => get_string('delete'),
                        'onclick' => 'return confirm("' . get_string('confirm_delete', 'local_categorybanner') . '");'
                    )
                );
                
                $table->data[] = array(
                    $categoryname,
                    format_text($rule['banner'], FORMAT_HTML),
                    implode(' ', $buttons)
                );
            }
            
            $return .= html_writer::table($table);
        }
        
        // Add rule button
        $addurl = new moodle_url('/local/categorybanner/edit.php', array(
            'action' => 'add',
            'sesskey' => sesskey()
        ));
        
        $return .= html_writer::div(
            html_writer::link(
                $addurl,
                get_string('add_rule', 'local_categorybanner'),
                array('class' => 'btn btn-primary')
            ),
            'my-3'
        );
        
        return $return;
    }
}
