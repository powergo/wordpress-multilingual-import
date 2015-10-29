<?php
/*
Plugin Name: Multilingual Import
Plugin URI:  https://github.com/resulto-admin/wordpress-multilingual-import
Description: Sets the language and associates posts imported by WP ALL Import with WPML.
Version:     1.0.1
Author:      Resulto Developpement Web Inc.
Author URI:  http://www.resulto.ca
License:     GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
*/

define('WP_ALL_IMPORT_WPML_DEFAULT_ID_META', 'id');
define('WP_ALL_IMPORT_WPML_DEFAULT_LANGUAGE_META', 'language');

// WP ALL Import Hook
add_action('pmxi_saved_post', 'post_saved', 10, 1);

function post_saved($id) {
    if (defined('WP_ALL_IMPORT_WPML_ID_META') && WP_ALL_IMPORT_WPML_ID_META) {
        $id_meta_field = WP_ALL_IMPORT_WPML_ID_META;
    } else {
        $id_meta_field = WP_ALL_IMPORT_WPML_DEFAULT_ID_META;
    }

    if (defined('WP_ALL_IMPORT_WPML_LANGUAGE_META') && WP_ALL_IMPORT_WPML_LANGUAGE_META) {
        $language_meta_field = WP_ALL_IMPORT_WPML_LANGUAGE_META;
    } else {
        $language_meta_field = WP_ALL_IMPORT_WPML_DEFAULT_LANGUAGE_META;
    }

    $translated_id = $id;
    $default_language = apply_filters('wpml_default_language', NULL );;
    $post = get_post($translated_id);
    $post_type = get_post_type($translated_id);
    $wpml_post_type = apply_filters( 'wpml_element_type', $post_type );

    $internal_id = get_post_meta($translated_id, $id_meta_field, true);
    $language = get_post_meta($translated_id, $language_meta_field, true);

    $posts = NULL;
    if ($language != $default_language) {

        $posts = get_posts(array(
            'numberposts' => -1,
            'post_type' => $post_type,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => $language_meta_field,
                    'value' => $default_language,
                    'compare' => '=',
                ),
                array(
                    'key' => $id_meta_field,
                    'value' => $internal_id,
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
    do_action( 'wpml_set_element_language_details', $set_language_args );
}

?>
