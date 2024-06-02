<?php
/*
Plugin Name: Email Gate
Description: Plugin pour vérifier l'adresse email des visiteurs avant de donner accès au contenu du site.
Version: 1.0
Author: Votre Romain Voileaux - RVLX
GitHub Plugin URI: https://github.com/Romain2109/email-gate-wp
GitHub Branch: main
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Inclure les fichiers nécessaires
include_once plugin_dir_path(__FILE__) . 'includes/admin.php';
include_once plugin_dir_path(__FILE__) . 'includes/form.php';
include_once plugin_dir_path(__FILE__) . 'includes/verification.php';

// Démarrer la session
function eg_start_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'eg_start_session');
?>
