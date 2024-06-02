<?php
if (!function_exists('email_gate_form')) {
    function email_gate_form() {
        if (isset($_POST['email_gate_email'])) {
            $submitted_email = sanitize_email($_POST['email_gate_email']);
            $allowed_emails = get_option('allowed_emails', array());

            if (in_array($submitted_email, $allowed_emails)) {
                $_SESSION['email_gate_email'] = $submitted_email;
                $redirect_url = isset($_POST['redirect_to']) ? esc_url($_POST['redirect_to']) : home_url();
                wp_redirect($redirect_url);
                exit;
            } else {
                echo '<div class="error"><p>Vous n\'avez pas le droit d\'accéder à ce site.</p></div>';
            }
        }

        // Afficher le formulaire d'inscription avec CSS inclus
        echo '
        <style>
            html {
                height: 100%;
            }
            .email-gate-container {
                position: absolute;
                top: 50%;
                left: 50%;
                width: 400px;
                padding: 40px;
                transform: translate(-50%, -50%);
                background: rgba(0,0,0,.5);
                box-sizing: border-box;
                box-shadow: 0 15px 25px rgba(0,0,0,.6);
                border-radius: 10px;
            }
            .email-gate-container h2 {
                margin: 0 0 30px;
                padding: 0;
                color: #fff;
                text-align: center;
            }
            .email-gate-container .user-box {
                position: relative;
            }
            .email-gate-container .user-box input {
                width: 100%;
                padding: 10px 0;
                font-size: 16px;
                color: #fff;
                margin-bottom: 30px;
                border: none;
                border-bottom: 1px solid #fff;
                outline: none;
                background: transparent;
            }
            .email-gate-container .user-box label {
                position: absolute;
                top:0;
                left: 0;
                padding: 10px 0;
                font-size: 16px;
                color: #fff;
                pointer-events: none;
                transition: .5s;
            }
            .email-gate-container .user-box input:focus ~ label,
            .email-gate-container .user-box input:valid ~ label {
                top: -20px;
                left: 0;
                color: #03e9f4;
                font-size: 12px;
            }
            .email-gate-container form a {
                position: relative;
                display: inline-block;
                padding: 10px 20px;
                color: #03e9f4;
                font-size: 16px;
                text-decoration: none;
                text-transform: uppercase;
                overflow: hidden;
                transition: .5s;
                margin-top: 40px;
                letter-spacing: 4px;
            }
        </style>
        ';

        echo '<div class="email-gate-container">';
        echo '<h2>Veuillez entrer votre adresse email pour accéder au site</h2>';
        echo '<form method="post">';
        echo '<div class="user-box">';
        echo '<input type="email" name="email_gate_email" placeholder="Votre adresse email" required>';
        echo '<label>Email</label>';
        echo '<input type="hidden" name="redirect_to" value="' . esc_url($_SERVER['REQUEST_URI']) . '">';
        echo '<input type="submit" value="Valider">';
        echo '</div>';
        echo '</form>';
        echo '</div>';
    }
}


if (!function_exists('email_gate_enqueue_styles')) {
    // Enqueue styles
    function email_gate_enqueue_styles() {
        wp_enqueue_style('email-gate-form-style', plugin_dir_url(__FILE__) . 'form.css');
    }
    add_action('wp_enqueue_scripts', 'email_gate_enqueue_styles');
}
?>
