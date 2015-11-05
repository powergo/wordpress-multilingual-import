<?php
/*
Plugin Name: Multilingual Import
Plugin URI:  https://github.com/resulto-admin/wordpress-multilingual-import
Description: Sets the language and associates posts imported by WP ALL Import with WPML.
Version:     1.0.2
Author:      Resulto Developpement Web Inc.
Author URI:  http://www.resulto.ca
License:     GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
*/

include 'rapid-addon.php';

function set_language($post_id, $language, $common_id, $slug) {
    $translated_id = $post_id;
    $default_language = apply_filters('wpml_default_language', NULL );;
    $post = get_post($translated_id);
    $post_type = get_post_type($translated_id);
    $wpml_post_type = apply_filters( 'wpml_element_type', $post_type );

    $posts = NULL;
    if ($language != $default_language) {
        $posts = get_posts(array(
            'numberposts' => -1,
            'post_type' => $post_type,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_multilingual_entry_language',
                    'value' => $default_language,
                    'compare' => '=',
                ),
                array(
                    'key' => '_multilingual_entry_common_id',
                    'value' => $common_id,
                    'compare' => '=',
                )
            )
        ));
    }

    $original_id = -1;
    $original_post = NULL;

    if ($posts) {
        // We found a post with the same common id
        $original_post = $posts[0];
        $original_id = $original_post->ID;
        $args = array('element_id' => $original_id, 'element_type' => $post_type );
        $default_language_info = apply_filters( 'wpml_element_language_details', null, $args );
    }

    $set_language_args = array(
        'element_id'    => $translated_id,
        'element_type'  => $wpml_post_type,
        'language_code'   => $language,
    );
    if ($original_id != -1) {
        // Create the association between two posts
        $set_language_args['trid'] = $default_language_info->trid;
        $set_language_args['source_language_code'] = $default_language;
    }

    // Update language
    do_action( 'wpml_set_element_language_details', $set_language_args );

    update_slug($post, $slug, $language);

}

function update_slug($post_object, $slug, $language) {

    // The post slug was changed (because of duplicate)
    if ($post_object->post_name != $slug) {
        wp_update_post(array(
            'ID' => $post_object->ID,
            'post_name' => $slug // do your thing here
        ));
    }
}

$multilingual_import_addon = new RapidAddon('Multilingual Import Add-On', 'multilingual_import_addon');

$multilingual_import_addon->add_field('multilingual_import_language', 'Entry Language', 'text');
$multilingual_import_addon->add_field('multilingual_import_common_id', 'Entry Common ID', 'text');

$multilingual_import_addon->set_import_function('multilingual_addon_import');

// display a dismissable notice warning the user to install WP All Import to use the add-on.
// Customize the notice text by passing a string to admin_notice(), i.e. admin_notice("XYZ Theme recommends you install WP All Import (free | pro)")
$multilingual_import_addon->admin_notice();

// the add-on will run for all themes/post types if no arguments are passed to run()
$multilingual_import_addon->run();


function multilingual_addon_import($post_id, $data, $import_options, $articleData) {

	global $multilingual_import_addon;


    $language = $data['multilingual_import_language'];
    $common_id = $data['multilingual_import_common_id'];

	if ($multilingual_import_addon->can_update_meta('_multilingual_entry_language', $import_options)) {
        echo 'Updating META';
		update_post_meta($post_id, '_multilingual_entry_language', $language);
	}

	if ($multilingual_import_addon->can_update_meta('_multilingual_entry_common_id', $import_options)) {
		update_post_meta($post_id, '_multilingual_entry_common_id', $common_id);
	}


    set_language($post_id, $language, $common_id, $articleData['post_name']);

    // TODO Save current language and restore it at the end of the entry import...
    //$current_language = $sitepress->get_current_language();
    global $sitepress;
    // We do this so taxonomies are imported in their correct language.
    $sitepress->switch_lang($language);
}



?>
