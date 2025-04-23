# Category Banner Plugin for Moodle

This plugin allows you to display custom banners at the top of course pages based on their category.

## Features

- Define custom banners for specific course categories
- Global banner option to display a message on all pages
- Apply banners to subcategories with a single click
- Multiple banners support: if several rules apply to a course, all messages are displayed with a separator
- Rich text editor for banner content
- Easy management through Moodle's admin interface

## Installation

1. Copy the categorybanner folder to your Moodle's /local directory
2. Visit the notifications page to complete the installation
3. Configure the plugin through Site administration > Plugins > Local plugins > Category Banner

## Usage

1. Go to the plugin's administration page
2. Click "Add new rule" to create a banner
3. Choose between:
   - A specific category (and optionally its subcategories)
   - Every pages (global banner that appears everywhere)
4. Enter your banner content using the rich text editor
5. Save the rule

Multiple rules can apply to the same course. For example:
- A global banner that appears on all pages
- A category-specific banner
- A parent category banner that applies to subcategories

All applicable banners will be displayed in sequence, separated by a horizontal line.

## Permissions

The plugin uses the capability 'local/categorybanner:managebanner' to control who can manage banner rules.

## Support

For any issues or suggestions, please contact:
Service Ecole Media <sem.web@edu.ge.ch>

## Code Structure

### Main Files
- `lib.php`: Main plugin functions
  - `local_categorybanner_before_standard_top_of_body_html()`: Banner display
  - `local_categorybanner_is_course_layout()`: Checks if a page is course-related
  - `local_categorybanner_render_banner()`: Generates banner HTML
- `version.php`: Plugin version and dependencies
- `settings.php`: Configuration and administration menu

### Classes (in /classes/)
- `rule_manager.php`: Banner rule management
  - `RULE_PREFIX` constant for configuration keys
  - Methods for reading, saving, and deleting rules
- `admin_setting_categorybanner_rules.php`: Rules administration interface
- `form/edit_rule.php`: Rule editing form

### Database (in /db/)
- `access.php`: User capabilities definition
- `events.php`: Cache events definition

### Interface
- `edit.php`: Rules editing page
- `styles.css`: CSS styles for the banner
- `lang/en/local_categorybanner.php`: Language strings

## Pages Where the Banner Appears

The banner appears on all pages with the following layouts:
- 'course': Main course page
- 'incourse': Course activities and resources
- 'report': Report pages
- 'admin': Course administration pages
- 'coursecategory': Course category pages

## Banner Format

The banner is displayed in a container with the CSS class `local-categorybanner-notification`. By default, it uses Moodle's "info" notification style.

Example HTML content:
```html
<div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb; border-radius: 4px;">
    Important message about this course
</div>
```

## Cache

The plugin uses Moodle's caching system:
- Rules are cached to optimize performance
- Cache is automatically purged when a rule is modified via the 'local_categorybanner_rule_updated' event

## Security

- Only users with the 'local_categorybanner:managebanner' capability can manage rules
- Banner HTML content is filtered by Moodle for security

## Plugin Architecture

The plugin follows a clear modular architecture with separation of concerns between different files:

### Main Components

#### 1. Administration Interface (settings.php)
- Entry point for Moodle administration system integration
- Creates settings page in administration menu
- Handles rule deletion
- Registers external editing page in the system
- Maintained separately from edit.php to follow Moodle's standard architecture

#### 2. Editing Interface (edit.php)
- Dedicated page for editing a specific rule
- Handles edit form display
- Validates and saves form data
- Separated from settings.php for:
  - Clear separation of responsibilities
  - Potential reusability
  - Improved maintainability
  - Following Moodle conventions

#### 3. Rule Manager (rule_manager.php)
- Business logic layer for rule management
- Provides clear interface between data and UI
- Used by settings.php and edit.php to:
  - Provide data for admin interface
  - Handle rule creation and updates
  - Ensure consistent data management

#### 4. Custom Administration Interface (admin_setting_categorybanner_rules.php)
- Extends admin_setting to create custom interface
- Acts as presentation layer
- Creates HTML interface for rule management
- Integrates editing and deletion functionality

This modular architecture allows for:
- Easier maintenance
- Better separation of concerns
- Potential component reuse
- Compliance with Moodle development standards

## License

This plugin is distributed under the GNU GPL v3 or later license.
