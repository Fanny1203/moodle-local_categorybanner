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
 * This class serves as the core business logic layer for managing banner rules. It provides
 * a clean separation between data management and the user interface. The class is responsible for:
 * - Retrieving all banner rules from the configuration
 * - Getting specific rules by ID
 * - Finding banners for specific categories
 * - Managing rule storage and retrieval
 *
 * This class is used by both settings.php and edit.php to:
 * - Provide data for the admin interface (settings.php)
 * - Handle rule creation and updates (edit.php)
 * - Maintain consistent data access across the plugin
 *
 * @package    local_categorybanner
 * @copyright  2025 Service Ecole Media <sem.web@edu.ge.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_categorybanner;

defined('MOODLE_INTERNAL') || die();

/**
 * Class for managing banner rules
 * 
 * Rules are stored in configuration and are identified by a unique ID. For instance it could look like:
 * 
 * plugin               | name            | value
 * --------------------|-----------------|---------------------------------
 * local_categorybanner | rule_1_category | "5"
 * local_categorybanner | rule_1_banner   | "<div>Bannière Sciences</div>"
 * local_categorybanner | rule_1_apply_to_subcategories | "1"
 * local_categorybanner | rule_2_category | "8"
 * local_categorybanner | rule_2_banner   | "<div>Bannière Histoire</div>"
 * local_categorybanner | rule_2_apply_to_subcategories | "0"
 * local_categorybanner | rule_3_category | "12"
 * local_categorybanner | rule_3_banner   | "<div>Bannière Langues</div>"
 * local_categorybanner | rule_3_apply_to_subcategories | "1"
 * 
 */
class rule_manager {
    /** @var string Prefix for rule settings in config */
    const RULE_PREFIX = 'rule_';
    
    /** @var int Category ID for global banner */
    const GLOBAL_BANNER_CATEGORY = -1;
    
    /**
     * Get all banner rules
     * 
     * Each rule is an associative array containing the following keys:
     * - 'id': The ID of the rule
     * - 'category': The category ID the rule applies to
     * - 'banner': The HTML content of the banner
     * - 'apply_to_subcategories': Whether the banner applies to subcategories (boolean)
     * 
     * Exemple of returned array:
     * 
     * [
     *     [
     *         'id' => 1,
     *         'category' => 5,
     *         'banner' => '<div>Bannière Sciences</div>',
     *         'apply_to_subcategories' => true
     *     ],
     *     [
     *         'id' => 2,
     *         'category' => 8,
     *         'banner' => '<div>Bannière Histoire</div>',
     *         'apply_to_subcategories' => false
     *     ],
     *     [
     *         'id' => 3,
     *         'category' => 12,
     *         'banner' => '<div>Bannière Langues</div>',
     *         'apply_to_subcategories' => true
     *     ]
     * ]
     *
     * @return array Array of rules, each containing category ID, banner content and apply to subcategories
     */
    public static function get_all_rules() {
        $rules = array();
        $config = get_config('local_categorybanner');
        
        // Scan through config to find all rules
        foreach ((array)$config as $key => $value) {
            if (preg_match('/^' . self::RULE_PREFIX . '(\d+)_category$/', $key, $matches)) {
                $index = $matches[1];
                if (isset($config->{self::RULE_PREFIX . $index . '_banner'}) && isset($config->{self::RULE_PREFIX . $index . '_apply_to_subcategories'})) {
                    $rules[] = array(
                        'id' => (int)$index,
                        'category' => (int)$config->{self::RULE_PREFIX . $index . '_category'},
                        'banner' => $config->{self::RULE_PREFIX . $index . '_banner'},
                        'apply_to_subcategories' => !empty($config->{self::RULE_PREFIX . $index . '_apply_to_subcategories'})
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
     * (usefull for instance to delete or update an existing rule)
     *
     * @param int $ruleid The ID of the rule to get
     * @return array|null The rule data or null if not found
     */
    public static function get_rule($ruleid) {
        $config = get_config('local_categorybanner');
        
        if (isset($config->{self::RULE_PREFIX . $ruleid . '_category'}) && 
            isset($config->{self::RULE_PREFIX . $ruleid . '_banner'}) && 
            isset($config->{self::RULE_PREFIX . $ruleid . '_apply_to_subcategories'})) {
            return array(
                'id' => $ruleid,
                'category' => (int)$config->{self::RULE_PREFIX . $ruleid . '_category'},
                'banner' => $config->{self::RULE_PREFIX . $ruleid . '_banner'},
                'apply_to_subcategories' => !empty($config->{self::RULE_PREFIX . $ruleid . '_apply_to_subcategories'})
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
        global $DB;
        $rules = self::get_all_rules();
        $applicable_banners = array();
        
        foreach ($rules as $rule) {
            // Check for global banner rule first
            if ($rule['category'] == self::GLOBAL_BANNER_CATEGORY) {
                $applicable_banners[] = $rule['banner'];
            }
            // Check for exact category match
            else if ($rule['category'] == $categoryid) {
                $applicable_banners[] = $rule['banner'];
            }
            // Check if rule applies to subcategories (only if not already matched)
            else if ($rule['apply_to_subcategories']) {
                // Get the path of the current category
                $category = $DB->get_record('course_categories', array('id' => $categoryid), 'path');
                if ($category) {
                    $path_parts = explode('/', trim($category->path, '/'));
                    // If the rule's category is in the path, this is a parent category
                    if (in_array($rule['category'], $path_parts)) {
                        $applicable_banners[] = $rule['banner'];
                    }
                }
            }
        }
        
        if (empty($applicable_banners)) {
            return null;
        }
        
        // Combine all applicable banners with a separator
        return implode('<hr class="categorybanner-separator" />', $applicable_banners);
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
     * @param bool $apply_to_subcategories Whether to apply the banner to subcategories
     * @return int The ID of the saved rule
     */
    public static function save_rule($ruleid, $categoryid, $banner, $apply_to_subcategories = false) {
        if ($ruleid < 0) {
            $ruleid = self::get_next_rule_id();
        }
        
        set_config(self::RULE_PREFIX . $ruleid . '_category', $categoryid, 'local_categorybanner');
        set_config(self::RULE_PREFIX . $ruleid . '_banner', $banner, 'local_categorybanner');
        set_config(self::RULE_PREFIX . $ruleid . '_apply_to_subcategories', $apply_to_subcategories ? 1 : 0, 'local_categorybanner');
        
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
        unset_config(self::RULE_PREFIX . $ruleid . '_apply_to_subcategories', 'local_categorybanner');
        
        // Clear cache
        \cache_helper::purge_by_event('local_categorybanner_rule_updated');
        
        return true;
    }
}
