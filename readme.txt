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

Currently, posts are only associated when they are in a secondary language.
For example, if your main language is French and your secondary language is
English, you must import your French posts first and your English posts
second, otherwise, the English posts won't be linked with the French posts
(the language will be correctly set though).

This plugin assumes that your import file contains an attribute/column
specifying the language and an attribute/column with an identifier that is
common across all languages.

== Installation ==

1. Upload the entire `multilingual-import` folder to the
    `/wp-content/plugins/` directory.
2. Activate the plugin through the \'Plugins\' menu in WordPress.
3. When creating an import, specify in the Entry Language field the column
    where the language of the row is, then specify in the Common ID field the
    column where the id common to all translated rows is.

== Screenshots ==

1. The section to fill when creating an import. The Entry Language is the
column where the language of the row is specified. The Common ID is the column
where the id common to all translated rows is specified.

== Changelog ==
= 1.0.2 =

* release date: November 5th 2015
* Now being released as a wp all import addon so that import of taxonomies and
  slugs can be correctly imported in the appropriate language.

= 1.0.1 =

* release date: October 29th 2015
* Explicitly sets the language of a post even if the language is the default
  one.

= 1.0.0 =

* release date: October 26th 2015
* Initial release
