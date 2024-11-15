<?php
// Fonction d'activation pour créer la table de la base de données
function email_gate_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'email_gate'; // nom de la table
    $charset_collate = $wpdb->get_charset_collate();

    // SQL pour créer la table avec une nouvelle colonne pour la date de la dernière connexion
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(255) NOT NULL,
        date_added datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        expiration_date datetime DEFAULT NULL,
        connection_count int DEFAULT 0 NOT NULL,
        last_connection datetime DEFAULT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Enregistrer la fonction d'activation
register_activation_hook(__FILE__, 'email_gate_create_table');