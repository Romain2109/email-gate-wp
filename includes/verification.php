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
        // Vérifier si l'utilisateur est connecté
        if (is_user_logged_in()) {
            return; // L'utilisateur est connecté, ne pas rediriger
        }

        // Vérifier si l'email est déjà dans la session
        if (!isset($_SESSION['email_gate_email'])) {
            // Charger et afficher le formulaire
            include plugin_dir_path(__FILE__) . 'form.php';
            email_gate_form(); // Appel à la fonction qui affiche le formulaire
            exit; // Terminer le script pour ne pas afficher le reste de la page
        }
    }
    add_action('template_redirect', 'email_gate_redirect');
}
?>