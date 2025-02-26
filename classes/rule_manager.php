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
 * @copyright  2025 Service Ecole Media <sem.web@edu.ge.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_categorybanner;

defined('MOODLE_INTERNAL') || die();

/**
 * Class for managing banner rules
 */
class rule_manager {
    /** @var string Prefix for rule settings in config */
    const RULE_PREFIX = 'rule_';
    
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
            if (preg_match('/^' . self::RULE_PREFIX . '(\d+)_category$/', $key, $matches)) {
                $index = $matches[1];
                if (isset($config->{self::RULE_PREFIX . $index . '_banner'})) {
                    $rules[] = array(
                        'id' => (int)$index,
                        'category' => (int)$config->{self::RULE_PREFIX . $index . '_category'},
                        'banner' => $config->{self::RULE_PREFIX . $index . '_banner'}
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
        
        if (isset($config->{self::RULE_PREFIX . $ruleid . '_category'}) && 
            isset($config->{self::RULE_PREFIX . $ruleid . '_banner'})) {
            return array(
                'id' => $ruleid,
                'category' => (int)$config->{self::RULE_PREFIX . $ruleid . '_category'},
                'banner' => $config->{self::RULE_PREFIX . $ruleid . '_banner'}
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

    /**
     * Save a rule
     *
     * @param int $ruleid Rule ID (-1 for new rule)
     * @param int $categoryid Category ID
     * @param string $banner Banner content
     * @return int The ID of the saved rule
     */
    public static function save_rule($ruleid, $categoryid, $banner) {
        if ($ruleid < 0) {
            $ruleid = self::get_next_rule_id();
        }
        
        set_config(self::RULE_PREFIX . $ruleid . '_category', $categoryid, 'local_categorybanner');
        set_config(self::RULE_PREFIX . $ruleid . '_banner', $banner, 'local_categorybanner');
        
        // Clear cache
        \cache_helper::purge_by_event('local_categorybanner_rule_updated');
        
        return $ruleid;
    }

    /**
     * Delete a rule
     *
     * @param int $ruleid Rule ID to delete
     * @return bool True if rule was deleted
     */
    public static function delete_rule($ruleid) {
        $rule = self::get_rule($ruleid);
        if (!$rule) {
            return false;
        }
        
        unset_config(self::RULE_PREFIX . $ruleid . '_category', 'local_categorybanner');
        unset_config(self::RULE_PREFIX . $ruleid . '_banner', 'local_categorybanner');
        
        // Clear cache
        \cache_helper::purge_by_event('local_categorybanner_rule_updated');
        
        return true;
    }
}
