# Category Banner Plugin for Moodle

This plugin allows you to display a custom banner on course pages based on their category.

## Features

- Display a customizable HTML banner for each course category
- Simple configuration through Moodle's administration interface
- Banner displays on the main course page and all associated pages (participants, grades, etc.)
- Support for HTML and inline CSS styles in banner content

## Installation

1. Download the plugin
2. Copy the 'categorybanner' folder to the /local/ directory of your Moodle installation
3. Visit the administration notifications page to complete the installation

## Configuration

1. Go to Site Administration > Plugins > Category Banner
2. For each category, you can define the HTML content of the banner
3. Leave the field empty to not display a banner for a given category

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
