<?php
if (!function_exists('email_gate_form')) {
    function email_gate_form() {
        if (isset($_POST['email_gate_email'])) {
            $submitted_email = sanitize_email($_POST['email_gate_email']);
            global $wpdb;
            $table_name = $wpdb->prefix . 'email_gate'; // Remplacez par le nom réel de votre table

            // Récupérer les emails autorisés depuis la base de données
            $allowed_emails = $wpdb->get_col("SELECT email FROM $table_name");

            if (in_array($submitted_email, $allowed_emails)) {
                // Incrémenter le compteur de connexion
                $wpdb->query($wpdb->prepare(
                    "UPDATE $table_name SET connection_count = connection_count + 1, last_connection = NOW() WHERE email = %s",
                    $submitted_email
                ));

                $_SESSION['email_gate_email'] = $submitted_email;
                $redirect_url = isset($_POST['redirect_to']) ? esc_url($_POST['redirect_to']) : home_url();
                wp_redirect($redirect_url);
                exit;
            } else {
                echo '<div id="error-flash" class="error"><span>Email invalide ! </span></div>';
            }
            // Fermez la session après usage
            session_write_close();
        }

        // Récupérer les options CSS enregistrées
        $color1_gradient_form = get_option('color1_gradient_form', '#49d49d');
        $color2_gradient_form = get_option('color2_gradient_form', '#a2c7e5');
        $title_form = get_option('title_form', 'Veuillez entrer votre adresse email');
        $bg_btn_form = get_option('bg_btn_form', '#aba194');
        $bg_btn_hover_form = get_option('bg_btn_hover_form', '#a09486');
        $text_btn_hover_form = get_option('text_btn_hover_form', '#000000');
        $border_radius_form = get_option('border_radius_form', '6px');
        $text_color_input_form = get_option('text_color_input_form', '#000000');
        $text_color_title_form = get_option('text_color_title_form', '#ffffff');
        $text_size_title_form = get_option('text_size_title_form', '14px');
        $bg_color_input_form = get_option('bg_color_input_form', '#ffffff');
        $bg_color_input_focus_form = get_option('bg_color_input_focus_form', '#f7f7f7');
        $text_btn_form = get_option('text_btn_form', '#ffffff');
        $logo_width_form = get_option('logo_width_form', '15vh');
        $logo_height_form = get_option('logo_height_form', 'auto');

        // Afficher le formulaire d'inscription avec CSS inclus
        echo '
        <style>
            * {
                margin: 0;
                box-sizing: border-box;
            }
            body {
                display: flex;
                justify-content: center;
                font-family: "Roboto";
                font-size: 14px;
                background-size: 200% 100% !important;
                animation: move 10s ease infinite;
                transform: translate3d(0, 0, 0);
                background: linear-gradient(45deg, ' . $color1_gradient_form . ' 10%, ' . $color2_gradient_form . ' 90%);
            }
            .container {
                align-content: center;
                width: 100%;
            }
            .user {
                width: 90%;
                max-width: 340px;
                margin: 0 auto;
            }
            .user__header {
                display: flex;
                justify-content: center;
                align-items: center;
                text-align: center;
                flex-direction: column;
                opacity: 0;
                transform: translate3d(0, 500px, 0);
                animation: arrive 500ms ease-in-out 0.7s forwards;
            }
            .user__title {
                font-size: ' . $text_size_title_form . ';
                margin-top: 20px;
                margin-bottom: -10px;
                font-weight: 500;
                color: ' . $text_color_title_form . ';
            }
            .form {
                margin-top: 40px;
                border-radius: ' . $border_radius_form . ';
                overflow: hidden;
                opacity: 0;
                transform: translate3d(0, 500px, 0);
                animation: arrive 500ms ease-in-out 0.9s forwards;
            }
            .form--no {
                animation: NO 1s ease-in-out;
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
            .form__input {
                background: ' . $bg_color_input_form . ';
                display: block;
                width: 100%;
                padding: 20px;
                font-family: "Roboto";
                -webkit-appearance: none;
                border: 0;
                outline: 0;
                transition: 0.3s;
                color: ' . $text_color_input_form . ';
            }
            .form__input::placeholder {
                color: black;
            }
            .form__input:focus {
                background: ' . $bg_color_input_focus_form . ';
            }
            .btn {
                display: block;
                width: 100%;
                padding: 20px;
                font-family: "Roboto";
                -webkit-appearance: none;
                outline: 0;
                border: 0;
                color: ' . $text_btn_form . ';
                background: ' . $bg_btn_form . ';
                transition: 0.3s;
            }
            .btn:hover {
                background: ' . $bg_btn_hover_form . ';
                color: ' . $text_btn_hover_form . ';
            }
            @keyframes NO {
                from, to {
                    -webkit-transform: translate3d(0, 0, 0);
                    transform: translate3d(0, 0, 0);
                }
                10%, 30%, 50%, 70%, 90% {
                    -webkit-transform: translate3d(-10px, 0, 0);
                    transform: translate3d(-10px, 0, 0);
                }
                20%, 40%, 60%, 80% {
                    -webkit-transform: translate3d(10px, 0, 0);
                    transform: translate3d(10px, 0, 0);
                }
            }
            @keyframes arrive {
                0% {
                    opacity: 0;
                    transform: translate3d(0, 50px, 0);
                }
                100% {
                    opacity: 1;
                    transform: translate3d(0, 0, 0);
                }
            }
            @keyframes move {
                0% {
                    background-position: 0 0;
                }
                50% {
                    background-position: 100% 0;
                }
                100% {
                    background-position: 0 0;
                }
            }
            .error {
                position: absolute;
                background-color: white;
                padding: 20px;
                border-radius: 6px;
                margin-top: 1%;
                margin-left: 1%;
                margin-right: 1%;
                width: 98%;
            }
            .error > span {
                font-weight: 500;
            }
        </style>
        ';

        echo '
        <div class="container">
            <div class="user">
                <header class="user__header">
                    <img src="' . esc_url(wp_get_attachment_url(get_option('logo_form'))) . '" alt="Logo" style="width: ' . esc_attr($logo_width_form) . '; height: ' . esc_attr($logo_height_form) . ';" />
                    <h1 class="user__title">' . $title_form . '</h1>
                </header>
                <form method="post" class="form">
                    <div class="form__group">
                        <input type="email" name="email_gate_email" placeholder="Email" class="form__input" required />
                        <input type="hidden" name="redirect_to" value="' . esc_url($_SERVER['REQUEST_URI']) . '">
                        <input class="btn" type="submit" value="Valider">
                    </div>
                </form>
            </div>
        </div>';
    }
}
?>