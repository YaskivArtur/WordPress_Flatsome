=== Shortcode Widget ===
Contributors: gagan0123
Donate Link: https://PayPal.me/gagan0123
Tags: Shortcode, Widget
Requires at least: 3.3
Requires PHP: 5.6
Tested up to: 6.2.2
Stable tag: 1.5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a text-like widget that allows you to write shortcode in it.

== Description ==

Adds a text-like widget that allows you to write shortcode in it. (Just whats missing in the default text widget)
To test the widget you can add the widget and use the shortcode "[shortcode_widget_test]", it will display "It works" on the frontend and this will confirm the widget does work.

== Installation ==

1. Add the plugin's folder in the WordPress' plugin directory.
1. Activate the plugin.
1. You are now ready to use the Shortcode Widget from the Widgets section.
1. To test the widget you can add the widget and use the shortcode "[shortcode_widget_test]", it will display "It works" on the frontend and this will confirm the widget does work.

== Screenshots ==
1. Shortcode Widget that can be found in Widgets section
2. Adding the widget to the sidebar
3. Widget with the output of the shortcode

== Changelog ==

= 1.5.3 =
* Strict PHPCS ruleset adherence.
* More documentation in widget class.
* Testing with WordPress 5.6

= 1.5.2 =
* Some PHPCS corrections, making code adhering to WordPress coding standards.
* Replaced strip_tags function with wp_strip_all_tags.

= 1.5.1 =
* Unescaped title back in the code as escaping it was creating issues with other plugins.

= 1.5 =
* Added icon and screenshots.
* Escaping some values that could have been overridden by the translations.
* Added pot file for translations.
* Change in calling of widget_text filter with new parameter that was added in WordPress 4.4.1

= 1.4 =
* Updated compatibility with WordPress 4.8
* Reversed the order of changelog.

= 1.3 =
* Minor bug fix.
* Changed tested up to version number.
* Made it translation ready, constant was being used for text domains, silly error, I know :)

= 1.2 =
* Corrections in text domain and added one more string as translatable.

= 1.1 =
* Reflecting the changes that have been done to the default text widget over the years.

= 1.0 = 
* Tested with WP 4.0

= 0.3 =
* Added a shortcode for testing the plugin '[shortcode_widget_test]'

= 0.2 =
* Added translation support.

= 0.1 =
* Added the shortcode widget.
