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
 * Rule manager for category banner plugin
 *
 * @package    local_categorybanner
 * @copyright  2025 Your Name <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_categorybanner;

defined('MOODLE_INTERNAL') || die();

/**
 * Class for managing banner rules
 */
class rule_manager {
    /**
     * Get all banner rules
     *
     * @return array Array of rules, each containing category ID and banner content
     */
    public static function get_all_rules() {
        $rules = array();
        $config = get_config('local_categorybanner');
        
        // Scan through config to find all rules
        foreach ((array)$config as $key => $value) {
            if (preg_match('/^rule_(\d+)_category$/', $key, $matches)) {
                $index = $matches[1];
                if (isset($config->{'rule_' . $index . '_banner'})) {
                    $rules[] = array(
                        'id' => (int)$index,
                        'category' => (int)$config->{'rule_' . $index . '_category'},
                        'banner' => $config->{'rule_' . $index . '_banner'}
                    );
                }
            }
        }
        
        // Sort rules by ID
        usort($rules, function($a, $b) {
            return $a['id'] - $b['id'];
        });
        
        return $rules;
    }

    /**
     * Get a specific rule by ID
     *
     * @param int $ruleid The ID of the rule to get
     * @return array|null The rule data or null if not found
     */
    public static function get_rule($ruleid) {
        $config = get_config('local_categorybanner');
        
        if (isset($config->{'rule_' . $ruleid . '_category'}) && isset($config->{'rule_' . $ruleid . '_banner'})) {
            return array(
                'id' => $ruleid,
                'category' => (int)$config->{'rule_' . $ruleid . '_category'},
                'banner' => $config->{'rule_' . $ruleid . '_banner'}
            );
        }
        
        return null;
    }

    /**
     * Get banner content for a specific category
     *
     * @param int $categoryid The category ID to get banner for
     * @return string|null The banner content or null if no banner found
     */
    public static function get_banner_for_category($categoryid) {
        $rules = self::get_all_rules();
        
        foreach ($rules as $rule) {
            if ($rule['category'] == $categoryid) {
                return $rule['banner'];
            }
        }
        
        return null;
    }

    /**
     * Get the next available rule ID
     *
     * @return int The next available rule ID
     */
    public static function get_next_rule_id() {
        $rules = self::get_all_rules();
        if (empty($rules)) {
            return 0;
        }
        $max_id = max(array_column($rules, 'id'));
        return $max_id + 1;
    }
}
