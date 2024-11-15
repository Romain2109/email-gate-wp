<?php
// Enregistrer les paramètres de style du formulaire
function email_gate_settings_init() {
    // Enregistrement des paramètres de style
    $options = [
        'logo_form',
        'title_form',
        'bg_btn_form',
        'bg_btn_hover_form',
        'text_btn_hover_form',
        'text_btn_form',
        'border_radius_form',
        'text_color_input_form',
        'text_color_title_form',
        'text_size_title_form',
        'bg_color_input_form',
        'bg_color_input_focus_form',
        'color1_gradient_form',
        'color2_gradient_form',
        'logo_width_form',
        'logo_height_form'
    ];

    foreach ($options as $option) {
        register_setting('email_gate_form_options_group', $option);
    }

    // Section des paramètres de style
    add_settings_section(
        'email_gate_form_settings_section',
        'Paramètres de Style',
        'email_gate_form_settings_section_callback',
        'email_gate_form_settings'
    );

    // Champs pour le logo
    add_settings_field(
        'logo_form',
        'Logo du formulaire',
        'logo_form_render',
        'email_gate_form_settings',
        'email_gate_form_settings_section'
    );
    add_settings_field(
        'logo_width_form',
        'Largeur du logo',
        'logo_width_form_render',
        'email_gate_form_settings',
        'email_gate_form_settings_section'
    );
    add_settings_field(
        'logo_height_form',
        'Hauteur du logo',
        'logo_height_form_render',
        'email_gate_form_settings',
        'email_gate_form_settings_section'
    );

    // Section des paramètres du titre
    add_settings_section(
        'email_gate_title_settings_section',
        'Paramètres du Titre',
        'email_gate_title_settings_section_callback',
        'email_gate_form_settings'
    );
    add_settings_field(
        'title_form',
        'Titre du formulaire',
        'title_form_render',
        'email_gate_form_settings',
        'email_gate_title_settings_section'
    );
    add_settings_field(
        'text_color_title_form',
        'Couleur du titre',
        'text_color_title_form_render',
        'email_gate_form_settings',
        'email_gate_title_settings_section'
    );
    add_settings_field(
        'text_size_title_form',
        'Taille du texte du titre',
        'text_size_title_form_render',
        'email_gate_form_settings',
        'email_gate_title_settings_section'
    );

    // Section des paramètres du bouton
    add_settings_section(
        'email_gate_button_settings_section',
        'Paramètres du Bouton',
        'email_gate_button_settings_section_callback',
        'email_gate_form_settings'
    );
    add_settings_field(
        'bg_btn_form',
        'Couleur de fond du bouton',
        'bg_btn_form_render',
        'email_gate_form_settings',
        'email_gate_button_settings_section'
    );
    add_settings_field(
        'bg_btn_hover_form',
        'Couleur de fond du bouton au survol',
        'bg_btn_hover_form_render',
        'email_gate_form_settings',
        'email_gate_button_settings_section'
    );
    add_settings_field(
        'text_btn_form',
        'Couleur du texte du bouton',
        'text_btn_form_render',
        'email_gate_form_settings',
        'email_gate_button_settings_section'
    );
    add_settings_field(
        'text_btn_hover_form',
        'Couleur du texte du bouton au survol',
        'text_btn_hover_form_render',
        'email_gate_form_settings',
        'email_gate_button_settings_section'
    );

    // Section des paramètres des champs de saisie
    add_settings_section(
        'email_gate_input_settings_section',
        'Paramètres des Champs de Saisie',
        'email_gate_input_settings_section_callback',
        'email_gate_form_settings'
    );
    add_settings_field(
        'text_color_input_form',
        'Couleur du texte dans les champs',
        'text_color_input_form_render',
        'email_gate_form_settings',
        'email_gate_input_settings_section'
    );
    add_settings_field(
        'bg_color_input_form',
        'Couleur de fond du champ',
        'bg_color_input_form_render',
        'email_gate_form_settings',
        'email_gate_input_settings_section'
    );
    add_settings_field(
        'bg_color_input_focus_form',
        'Couleur de fond du champ au focus',
        'bg_color_input_focus_form_render',
        'email_gate_form_settings',
        'email_gate_input_settings_section'
    );

    // Section des paramètres de dégradé et bordure
    add_settings_section(
        'email_gate_gradient_settings_section',
        'Paramètres de Dégradé et Bordure',
        'email_gate_gradient_settings_section_callback',
        'email_gate_form_settings'
    );
    add_settings_field(
        'color1_gradient_form',
        'Couleur 1 du dégradé de fond',
        'color1_gradient_form_render',
        'email_gate_form_settings',
        'email_gate_gradient_settings_section'
    );
    add_settings_field(
        'color2_gradient_form',
        'Couleur 2 du dégradé de fond',
        'color2_gradient_form_render',
        'email_gate_form_settings',
        'email_gate_gradient_settings_section'
    );
    add_settings_field(
        'border_radius_form',
        'Bordure arrondie du formulaire',
        'border_radius_form_render',
        'email_gate_form_settings',
        'email_gate_gradient_settings_section'
    );
}
add_action('admin_init', 'email_gate_settings_init');

// Callbacks pour les sections et champs
function email_gate_form_settings_section_callback() {
    echo 'Modifiez les styles de votre formulaire ici.';
}

function email_gate_title_settings_section_callback() {
    echo 'Ajustez les paramètres liés au titre de votre formulaire.';
}

function email_gate_button_settings_section_callback() {
    echo 'Configurez l\'apparence de vos boutons.';
}

function email_gate_input_settings_section_callback() {
    echo 'Personnalisez les champs de saisie de votre formulaire.';
}

function email_gate_gradient_settings_section_callback() {
    echo 'Définissez les dégradés et la bordure de votre formulaire.';
}

// Rendu des champs
function logo_form_render() {
    $logo_id = get_option('logo_form');
    echo '<input type="hidden" id="logo-id" name="logo_form" value="' . esc_attr($logo_id) . '">';
    echo '<div id="logo-preview" class="logo-preview" style="max-width: 250px; max-height: 100px; overflow: hidden;">'; // Limite la taille
    if ($logo_id) {
        echo '<img src="' . esc_url(wp_get_attachment_url($logo_id)) . '" alt="Logo" style="max-width: 100%; max-height: 100%; height: auto; width: auto;">'; // Ajuste l'image
    } else {
        echo 'Aucun logo sélectionné';
    }
    echo '</div>';
    echo '<button id="upload-logo-button" class="button">Sélectionner un logo</button>';
}

function logo_width_form_render() {
    $value = get_option('logo_width_form', '15vh');
    echo '<input type="text" name="logo_width_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function logo_height_form_render() {
    $value = get_option('logo_height_form', 'auto');
    echo '<input type="text" name="logo_height_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function title_form_render() {
    $value = get_option('title_form', 'Veuillez entrer votre adresse email');
    echo '<input type="text" name="title_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function bg_btn_form_render() {
    $value = get_option('bg_btn_form', '#aba194');
    echo '<input type="color" name="bg_btn_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function bg_btn_hover_form_render() {
    $value = get_option('bg_btn_hover_form', '#a09486');
    echo '<input type="color" name="bg_btn_hover_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function text_btn_form_render() {
    $value = get_option('text_btn_form', '#ffffff');
    echo '<input type="color" name="text_btn_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function text_btn_hover_form_render() {
    $value = get_option('text_btn_hover_form', '#000000');
    echo '<input type="color" name="text_btn_hover_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function border_radius_form_render() {
    $value = get_option('border_radius_form', '6px');
    echo '<input type="text" name="border_radius_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function text_color_input_form_render() {
    $value = get_option('text_color_input_form', '#000000');
    echo '<input type="color" name="text_color_input_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function text_color_title_form_render() {
    $value = get_option('text_color_title_form', '#ffffff');
    echo '<input type="color" name="text_color_title_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function text_size_title_form_render() {
    $value = get_option('text_size_title_form', '14px');
    echo '<input type="text" name="text_size_title_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function bg_color_input_form_render() {
    $value = get_option('bg_color_input_form', '#ffffff');
    echo '<input type="color" name="bg_color_input_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function bg_color_input_focus_form_render() {
    $value = get_option('bg_color_input_focus_form', '#f7f7f7');
    echo '<input type="color" name="bg_color_input_focus_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function color1_gradient_form_render() {
    $value = get_option('color1_gradient_form', '#49d49d');
    echo '<input type="color" name="color1_gradient_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function color2_gradient_form_render() {
    $value = get_option('color2_gradient_form', '#a2c7e5');
    echo '<input type="color" name="color2_gradient_form" value="' . esc_attr($value) . '" class="regular-text ltr">';
}

function email_gate_admin_scripts() {
    wp_enqueue_media();
    wp_enqueue_script('email-gate-admin-script', plugin_dir_url(__FILE__) . 'admin.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'email_gate_admin_scripts');
?> 