<?php

// Fonction pour afficher la page "Formulaire" avec les options CSS
function form_admin_page() {
    ?>
    <div class="wrap">
        <h1>Formulaire</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('email_gate_form_options_group');
            do_settings_sections('email_gate_form_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Enregistrer les pages d'administration avec le nouveau pictogramme
function email_gate_add_admin_page() {
    // Ajouter un menu parent principal
    add_menu_page(
        'Email Gate',           // Titre de la page
        'Email Gate',           // Texte du menu
        'manage_options',       // Capacité requise pour accéder au menu
        'email-gate',           // Slug de la page
        '',                     // Fonction pour afficher la page (vide pour non cliquable)
        'dashicons-groups',     // Icône du menu (pictogramme de plusieurs personnes)
        6                       // Position
    );

    // Ajouter les sous-menus
    add_submenu_page(
        'email-gate',           // Slug de la page parente
        'Email Liste',          // Titre de la sous-page
        'Email Liste',          // Texte du sous-menu
        'manage_options',       // Capacité requise pour accéder au sous-menu
        'email-list',           // Slug de la sous-page
        'email_list_admin_page' // Fonction pour afficher la sous-page
    );

    add_submenu_page( 
        'email-gate',           // Slug de la page parente
        'Formulaire',           // Titre de la sous-page
        'Formulaire',           // Texte du sous-menu
        'manage_options',       // Capacité requise pour accéder au sous-menu
        'form-settings',        // Slug de la sous-page
        'form_admin_page'       // Fonction pour afficher la sous-page
    );

    // Supprimer le lien parent principal cliquable
    remove_submenu_page('email-gate', 'email-gate');
}
add_action('admin_menu', 'email_gate_add_admin_page');
?>