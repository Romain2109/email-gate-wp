<?php
// Créer un menu pour l'administration du plugin
function eg_create_menu() {
    add_menu_page(
        'Email Gate', 
        'Email Gate', 
        'manage_options', 
        'eg-email-gate', 
        'eg_settings_page', 
        'dashicons-email'
    );
}
add_action('admin_menu', 'eg_create_menu');

function eg_settings_page() {
    ?>
    <div class="wrap">
        <h1>Email Gate Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('eg_settings_group');
            do_settings_sections('eg-email-gate');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function eg_settings_init() {
    register_setting('eg_settings_group', 'eg_allowed_emails');

    add_settings_section(
        'eg_settings_section', 
        'Allowed Emails', 
        'eg_settings_section_callback', 
        'eg-email-gate'
    );

    add_settings_field(
        'eg_allowed_emails', 
        'Emails', 
        'eg_allowed_emails_callback', 
        'eg-email-gate', 
        'eg_settings_section'
    );
}
add_action('admin_init', 'eg_settings_init');

function eg_settings_section_callback() {
    echo 'Enter the emails allowed to access the site.';
}

function eg_allowed_emails_callback() {
    $allowed_emails = get_option('eg_allowed_emails', '');
    echo '<textarea name="eg_allowed_emails" rows="10" cols="50" class="large-text code">' . esc_textarea($allowed_emails) . '</textarea>';
}
?>
