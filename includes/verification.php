<?php
function eg_start_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'eg_start_session');

function eg_check_email_verification() {
    if (!is_user_logged_in() && !isset($_SESSION['email_verified'])) {
        // Si l'utilisateur n'est pas vérifié, rediriger vers la page de vérification
        wp_redirect(home_url('/email-verification'));
        exit;
    }
}
add_action('template_redirect', 'eg_check_email_verification');
?>
