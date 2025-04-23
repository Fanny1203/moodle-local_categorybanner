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
        global $CFG, $PAGE;
        
        $mform = $this->_form;
        
        // Category selector
        $categories = \core_course_category::make_categories_list();
        // Add "Global banner" option at the top
        $categories = array(\local_categorybanner\rule_manager::GLOBAL_BANNER_CATEGORY => get_string('global_banner', 'local_categorybanner')) + $categories;
        $mform->addElement('select', 'category', get_string('category', 'local_categorybanner'), $categories);
        $mform->setType('category', PARAM_INT);
        $mform->addRule('category', get_string('required'), 'required', null, 'client');
        $mform->setDefault('category', \local_categorybanner\rule_manager::GLOBAL_BANNER_CATEGORY); // Set "Global banner" as default
        
        // Banner content
        $mform->addElement('editor', 'banner', get_string('banner_content', 'local_categorybanner'), array('rows' => 10));
        $mform->setType('banner', PARAM_RAW);
        $mform->addRule('banner', get_string('required'), 'required', null, 'client');
        
        // Apply to subcategories
        $mform->addElement('advcheckbox', 'apply_to_subcategories', get_string('apply_to_subcategories', 'local_categorybanner'), '', array('group' => 1), array(0, 1));
        $mform->setType('apply_to_subcategories', PARAM_BOOL);
        $mform->setDefault('apply_to_subcategories', 0);
        
        // Add JavaScript to handle category selection
        $PAGE->requires->js_amd_inline("
            require(['jquery'], function($) {
                var categorySelect = $('#id_category');
                var subcategoriesCheckbox = $('#id_apply_to_subcategories');
                var subcategoriesLabel = subcategoriesCheckbox.closest('.form-check').find('label');
                var GLOBAL_BANNER_VALUE = " . \local_categorybanner\rule_manager::GLOBAL_BANNER_CATEGORY . ";
                
                function handleCategoryChange() {
                    if (categorySelect.val() == GLOBAL_BANNER_VALUE) {
                        subcategoriesCheckbox.prop('disabled', true);
                        subcategoriesCheckbox.prop('checked', true);
                        subcategoriesLabel.addClass('text-muted');
                    } else {
                        subcategoriesCheckbox.prop('disabled', false);
                        subcategoriesLabel.removeClass('text-muted');
                    }
                }
                
                categorySelect.on('change', handleCategoryChange);
                handleCategoryChange(); // Initial state
            });
        ");
        
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
