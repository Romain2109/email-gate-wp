<?php
// Démarrer la session PHP
if (!function_exists('email_gate_start_session')) {
    function email_gate_start_session() {
        if (!session_id()) {
            session_start();
        }
    }
    add_action('init', 'email_gate_start_session');
}

// Rediriger les utilisateurs non autorisés vers le formulaire
if (!function_exists('email_gate_redirect')) {
    function email_gate_redirect() {
        if (!isset($_SESSION['email_gate_email'])) {
            // Charger et afficher le formulaire
            include plugin_dir_path(__FILE__) . 'form.php';
            email_gate_form();
            exit;
        }
    }
    add_action('template_redirect', 'email_gate_redirect');
}
?>
