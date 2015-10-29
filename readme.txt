=== Multilingual Import ===
Contributors: bartdag-resulto
Tags: multilingual
Requires at least: 4
Tested up to: 4.3.1
Stable tag: trunk
License: GPL v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.en.html

Sets the language and associates posts imported by WP ALL Import with WPML.

== Description ==
Sets the language and associates posts imported by WP ALL Import with WPML.
Currently, language is always explicitly set with WPML if the post is in a
secondary language, and posts are associated when they are in a secondary
language. For example, if your main language is French and your secondary
language is English, you must import your French posts first and your English
posts second, otherwise, the English posts won't be linked with the French
posts (the language will be correctly set though). This plugin assumes that
your import file contains an attribute/column specifying the language and an
attribute/column with an identifier that is common across all languages.

== Installation ==
1. Upload the entire `multilingual-import` folder to the
`/wp-content/plugins/` directory.
2. Activate the plugin through the \'Plugins\' menu in WordPress.
3. Declare two constants in wp-config.php to specify the meta fields
containing the language and the common identifier:
  `define('WP_ALL_IMPORT_WPML_LANGUAGE_META', 'language');
  
  `define('WP_ALL_IMPORT_WPML_ID_META', 'common_id');`

== Changelog ==
= 1.0.0 =

* release date: October 26th 2015
* Initial release
