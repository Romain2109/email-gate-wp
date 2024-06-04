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

        // Récupérer les options CSS enregistrées
        $title_form = get_option('title_form', 'Veuillez entrer votre adresse email');
        $bg_btn_form = get_option('bg_btn_form', '#aba194');
        $bg_btn_hover_form = get_option('bg_btn_hover_form', '#a09486');
        $border_radius_form = get_option('border_radius_form', '6px');
        $text_color_input_form = get_option('text_color_input_form', '#000000');
        $text_color_title_form = get_option('text_color_title_form', '#ffffff');
        $bg_color_input_form = get_option('bg_color_input_form', '#ffffff');
        $bg_color_input_focus_form = get_option('bg_color_input_focus_form', '#f7f7f7');

        // Afficher le formulaire d'inscription avec CSS inclus
        echo '
        <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
       }
        body {
            font-family: "Roboto";
            font-size: 14px;
            background-size: 200% 100% !important;
            animation: move 10s ease infinite;
            transform: translate3d(0, 0, 0);
            background: linear-gradient(45deg, #49d49d 10%, #a2c7e5 90%);
            height: 100vh;
       }
        .user {
            width: 90%;
            max-width: 340px;
            margin: 10vh auto;
       }
        .user__header {
            text-align: center;
            opacity: 0;
            transform: translate3d(0, 500px, 0);
            animation: arrive 500ms ease-in-out 0.7s forwards;
       }
        .user__title {
            font-size: 14px;
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
        .form__input::placeholder{
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
            color: white;
            background: ' . $bg_btn_form . ';
            transition: 0.3s;
       }
        .btn:hover {
            background: ' . $bg_btn_hover_form . ';
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
        
        </style>
        ';

        echo '<div class="user">';
        echo '<header class="user__header">';
        echo '<img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3219/logo.svg" alt="" />';
        echo '<h1 class="user__title">' . $title_form . '</h1>';
        echo '</header>';
    
        echo '<form method="post" class="form">';
        echo '<div class="form__group">';
        echo '<input type="email" name="email_gate_email" placeholder="Email" class="form__input" required />';
        echo '<input type="hidden" name="redirect_to" value="' . esc_url($_SERVER['REQUEST_URI']) . '">';
        echo '<input class="btn" type="submit" value="Valider">';
        echo '</div>';
        echo '</form>';
        echo '</div>';
    }
}
?>
