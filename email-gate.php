<?php
/*
Plugin Name: Email Gate
Description: Plugin pour vérifier l'adresse email des visiteurs avant de donner accès au contenu du site.
Version: 3.4.1
Author: Romain Voileaux - RVLX
Author URI: https://romain-voileaux.fr
GitHub URI: https://github.com/Romain2109/email-gate-wp
*/

// Inclure les fichiers nécessaires
require_once plugin_dir_path(__FILE__) . 'includes/database.php'; // Inclure le fichier de gestion de la base de données
require_once plugin_dir_path(__FILE__) . 'includes/email.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-menu.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-form.php';
require_once plugin_dir_path(__FILE__) . 'includes/form.php';
require_once plugin_dir_path(__FILE__) . 'includes/verification.php';

// Enregistrer la fonction d'activation
register_activation_hook(__FILE__, 'email_gate_create_table'); // Appel direct à la fonction de création de table

// Activation du cron job lors de l'activation du plugin
register_activation_hook(__FILE__, 'email_gate_schedule_cron');

// Fonction pour programmer la tâche cron
function email_gate_schedule_cron() {
    if (!wp_next_scheduled('email_gate_cron_hook')) {
        wp_schedule_event(time(), 'hourly', 'email_gate_cron_hook'); // Planifie la tâche cron pour s'exécuter toutes les heures
    }
}

// Lier la tâche cron à la fonction de suppression
add_action('email_gate_cron_hook', 'email_gate_delete_expired_emails');

// Désactivation du cron job lors de la désactivation du plugin
register_deactivation_hook(__FILE__, 'email_gate_unschedule_cron');

function email_gate_unschedule_cron() {
    $timestamp = wp_next_scheduled('email_gate_cron_hook');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'email_gate_cron_hook');
    }
}