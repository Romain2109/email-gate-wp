<?php
/*
Plugin Name: Email Gate
Description: Plugin pour vérifier l'adresse email des visiteurs avant de donner accès au contenu du site.
Version: 2.1
Author: Romain Voileaux - RVLX
Author URI: https://romain-voileaux.fr
GitHub URI: https://github.com/Romain2109/email-gate-wp
*/

// Inclure les fichiers nécessaires
require_once plugin_dir_path(__FILE__) . 'includes/admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/form.php';
require_once plugin_dir_path(__FILE__) . 'includes/verification.php';

// Définir la constante du slug du plugin
define('EMAIL_GATE_PLUGIN_SLUG', 'email-gate-wp');

// Vérification de la mise à jour
add_filter('pre_set_site_transient_update_plugins', 'email_gate_check_for_updates');

function email_gate_check_for_updates($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }

    $plugin_slug = EMAIL_GATE_PLUGIN_SLUG;
    $github_repo = 'Romain2109/email-gate-wp';
    $github_response = wp_remote_get("https://api.github.com/repos/{$github_repo}/releases/latest");

    if (is_wp_error($github_response)) {
        return $transient;
    }

    $github_data = json_decode(wp_remote_retrieve_body($github_response));
    $latest_version = $github_data->tag_name;

    if (isset($transient->checked[$plugin_slug]) && version_compare($transient->checked[$plugin_slug], $latest_version, '<')) {
        $plugin_info = array(
            'new_version' => $latest_version,
            'package' => $github_data->zipball_url,
            'url' => 'https://github.com/Romain2109/email-gate-wp',
        );
        $transient->response[$plugin_slug] = (object) $plugin_info;
    }

    return $transient;
}
