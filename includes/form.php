<?php
function eg_create_verification_page() {
    $page = array(
        'post_title'    => 'Email Verification',
        'post_content'  => '[eg_email_verification_form]',
        'post_status'   => 'publish',
        'post_type'     => 'page',
    );

    // Vérifier si la page existe déjà
    if (!get_page_by_title('Email Verification')) {
        wp_insert_post($page);
    }
}
register_activation_hook(__FILE__, 'eg_create_verification_page');

function eg_display_email_form() {
    if (!is_user_logged_in() && !isset($_SESSION['email_verified'])) {
        echo '<form method="post" action="' . esc_url($_SERVER['REQUEST_URI']) . '">
            <p>
                <label for="email">Email:</label>
                <input type="email" name="eg_email" required>
            </p>
            <p>
                <input type="submit" name="eg_submit" value="Verify">
            </p>
        </form>';
    }
}

function eg_verify_email() {
    if (isset($_POST['eg_email'])) {
        $email = sanitize_email($_POST['eg_email']);
        $allowed_emails = get_option('eg_allowed_emails', '');
        $allowed_emails_array = array_map('trim', explode("\\n", $allowed_emails));

        if (in_array($email, $allowed_emails_array)) {
            $_SESSION['email_verified'] = true;
            // Rediriger vers la page initialement demandée
            $redirect_url = !empty($_SESSION['initial_request']) ? $_SESSION['initial_request'] : home_url();
            unset($_SESSION['initial_request']);
            wp_redirect($redirect_url);
            exit;
        } else {
            echo '<p>Email not authorized.</p>';
        }
    }
}
add_shortcode('eg_email_verification_form', 'eg_display_email_form');

function eg_save_initial_request() {
    if (!is_user_logged_in() && !isset($_SESSION['email_verified']) && !is_page('email-verification')) {
        $_SESSION['initial_request'] = home_url($_SERVER['REQUEST_URI']);
        wp_redirect(home_url('/email-verification'));
        exit;
    }
}
add_action('template_redirect', 'eg_save_initial_request');
?>
