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
 * Form for editing banner rules
 *
 * @package    local_categorybanner
 * @copyright  2025 Your Name <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_categorybanner\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Form for editing banner rules
 */
class edit_rule extends \moodleform {
    /**
     * Form definition
     */
    public function definition() {
        global $CFG;
        
        $mform = $this->_form;
        
        // Category selector
        $categories = \core_course_category::make_categories_list();
        $mform->addElement('select', 'category', get_string('category', 'local_categorybanner'), $categories);
        $mform->setType('category', PARAM_INT);
        $mform->addRule('category', get_string('required'), 'required', null, 'client');
        
        // Banner content
        $mform->addElement('editor', 'banner', get_string('banner_content', 'local_categorybanner'), array('rows' => 10));
        $mform->setType('banner', PARAM_RAW);
        $mform->addRule('banner', get_string('required'), 'required', null, 'client');
        
        // Add rule index for editing
        $mform->addElement('hidden', 'rule', -1);
        $mform->setType('rule', PARAM_INT);
        
        // Add action
        $mform->addElement('hidden', 'action', 'add');
        $mform->setType('action', PARAM_ALPHA);
        
        // Add section
        $mform->addElement('hidden', 'section', 'local_categorybanner');
        $mform->setType('section', PARAM_TEXT);
        
        $this->add_action_buttons();
    }
    
    /**
     * Validate the form data
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        
        if (empty($data['category'])) {
            $errors['category'] = get_string('required');
        }
        
        if (empty($data['banner']['text'])) {
            $errors['banner'] = get_string('required');
        }
        
        return $errors;
    }
}
