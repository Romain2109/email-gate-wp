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
            background: linear-gradient(45deg, ' . $color1_gradient_form . ' 10%, ' . $color2_gradient_form . ' 90%);
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
            font-size: ' . $text_size_title_form . ';
            margin-top : 20px;
            margin-bottom: -10px;
            font-weight: 500;
            color: ' . $text_color_title_form . ';
       }

       img{
            width: 15vh;
            height: auto;
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

       /* Media Queries for Responsiveness */
       @media (max-width: 768px) {
           .user {
               width: 80%;
           }
       
           .form__input,
           .btn {
               padding: 15px;
           }
       }
       
        </style>
        ';

        echo '<div class="user">';
        echo '<header class="user__header">';
        echo '<img src="' . esc_url(wp_get_attachment_url(get_option('logo_form'))) . '" alt="" />';
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
