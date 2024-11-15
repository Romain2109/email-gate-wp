<?php
// Fonction pour récupérer la liste des adresses e-mail autorisées avec leurs identifiants et données supplémentaires
function get_allowed_emails_with_ids(): array {
    global $wpdb; // Ajout de la déclaration globale pour accéder à $wpdb
    $table_name = $wpdb->prefix . 'email_gate'; // Nom de la table

    // Récupérer tous les e-mails de la base de données
    $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
    $emails_with_ids = array();

    if ($results) {
        foreach ($results as $row) {
            $emails_with_ids[] = array(
                'id' => $row['id'],
                'email' => $row['email'],
                'date_added' => $row['date_added'],
                'expiration_date' => $row['expiration_date'],
                'connection_count' => $row['connection_count'],
                'last_connection' => $row['last_connection']
            );
        }
    }

    return $emails_with_ids;
}
  // Fonction pour supprimer les emails expirés
function email_gate_delete_expired_emails() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'email_gate'; // Remplacez par le nom réel de votre table
    $current_time = current_time('mysql'); // Récupérer l'heure actuelle au format MySQL

    // Supprimer les emails expirés
    $wpdb->query("DELETE FROM $table_name WHERE expiration_date < '$current_time'");
}

// Fonction pour afficher la page "Email Liste"
function email_list_admin_page() {
    global $wpdb; // Déclaration pour accéder à l'objet $wpdb
    ?>
    <div class="wrap">
        <h1>Email Gate Settings</h1>
        <h2>Liste des adresses e-mail autorisées</h2>
        <?php
        // Traitement de l'importation CSV
        if (isset($_POST['import_csv'])) {
            if ($_FILES['csv_file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
                $csv_file = $_FILES['csv_file']['tmp_name'];
                $emails = array_map('str_getcsv', file($csv_file));
                foreach ($emails as $email) {
                    $new_email = sanitize_email($email[0]);
                    if ($new_email) {
                        $wpdb->insert(
                            $wpdb->prefix . 'email_gate',
                            array(
                                'email' => $new_email,
                                'date_added' => current_time('mysql'),
                                'expiration_date' => null, // Pas de date d'expiration
                                'connection_count' => 0
                            )
                        );
                    }
                }
                echo '<div class="updated"><p>Liste d\'e-mails importée avec succès!</p></div>';
            } else {
                echo '<div class="error"><p>Erreur lors du téléchargement du fichier CSV.</p></div>';
            }
        }

// Vérifier si un e-mail a été ajouté
if (isset($_POST['add_email'])) {
    $new_email = sanitize_email($_POST['new_email']);
    $validity_period = isset($_POST['validity_period']) ? intval($_POST['validity_period']) : 0;
    $validity_unit = sanitize_text_field($_POST['validity_unit']);

    // Valeur par défaut pour la date d'expiration
    $expiration_date = null; // Initialement NULL

    // Vérifier si un nouvel e-mail est fourni
    if ($new_email) {
        // Si une période de validité est spécifiée et valide
        if ($validity_period > 0) {
            $expiration_timestamp = current_time('timestamp');
            if ($validity_unit === 'days') {
                $expiration_timestamp = strtotime("+{$validity_period} days", $expiration_timestamp);
            } elseif ($validity_unit === 'months') {
                $expiration_timestamp = strtotime("+{$validity_period} months", $expiration_timestamp);
            } elseif ($validity_unit === 'years') {
                $expiration_timestamp = strtotime("+{$validity_period} years", $expiration_timestamp);
            } elseif ($validity_unit === 'hours') {
                $expiration_timestamp = strtotime("+{$validity_period} hours", $expiration_timestamp);
            }
            $expiration_date = date('Y-m-d H:i:s', $expiration_timestamp); // Définir la date d'expiration si valide
        }

        // Insérer dans la base de données
        $wpdb->insert(
            $wpdb->prefix . 'email_gate',
            array(
                'email' => $new_email,
                'date_added' => current_time('mysql'),
                'expiration_date' => $expiration_date, // NULL si pas de date d'expiration
                'connection_count' => 0 // Compteur de connexions initialisé à 0
            )
        );
        echo '<div class="updated"><p>Email ajouté avec succès!</p></div>';
    } else {
        echo '<div class="error"><p>Veuillez entrer une adresse e-mail valide.</p></div>';
    }
}

        // Vérifier si des e-mails ont été sélectionnés pour la suppression groupée
        if (isset($_POST['delete_selected'])) {
            $selected_emails = isset($_POST['selected_emails']) ? $_POST['selected_emails'] : array();
            if (!empty($selected_emails)) {
                foreach ($selected_emails as $email_id) {
                    $wpdb->delete($wpdb->prefix . 'email_gate', array('id' => intval($email_id))); // Suppression par ID
                }
                echo '<div class="updated"><p>E-mails sélectionnés supprimés avec succès!</p></div>';
            } else {
                echo '<div class="error"><p>Aucun e-mail sélectionné pour la suppression.</p></div>';
            }
        }
        ?>
        <form method="post" enctype="multipart/form-data">
        <style>
        .wp-list-table th,
        .wp-list-table td {
            padding: 8px 12px;
            text-align: left;
        }

        .column-select,
        .column-id {
            width: 1%; /* Force the column to fit the content */
            white-space: nowrap; /* Prevents content from wrapping */
        }

        .wp-list-table {
            width: 100%; /* Ensure the table takes full width */
            table-layout: auto; /* Use default table layout */
        }

        .email-form {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .delete-button {
            margin-top: 20px !important;
            background-color: red !important;
            color: #ffffff !important;
            border-color: red !important;
        }
        </style>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th class="column-select"><input type="checkbox" id="select-all" /></th>
                        <th class="column-id">ID</th>
                        <th>Email</th>
                        <th>Date d'ajout</th>
                        <th>Date d'expiration</th>
                        <th>Temps restant</th>
                        <th>Connexions</th>
                        <th>Dernière connexion</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    $emails = get_allowed_emails_with_ids();
    foreach ($emails as $email) {
        $current_time = current_time('timestamp');
        $remaining_time_display = 'Pas d\'expiration'; // Valeur par défaut si pas d'expiration

        // Vérifier si la date d'expiration existe
        if ($email['expiration_date']) {
            $expiration_time = strtotime($email['expiration_date']);
            $remaining_time = $expiration_time - $current_time;

            // Formater le temps restant
            if ($remaining_time > 0) {
                $years_remaining = floor($remaining_time / (365 * 24 * 60 * 60));
                $months_remaining = floor(($remaining_time % (365 * 24 * 60 * 60)) / (30 * 24 * 60 * 60));
                $days_remaining = floor(($remaining_time % (30 * 24 * 60 * 60)) / (24 * 60 * 60));
                $hours_remaining = floor(($remaining_time % (24 * 60 * 60)) / (60 * 60));

                if ($years_remaining > 0) {
                    $remaining_time_display = sprintf('%d an%s', $years_remaining, $years_remaining > 1 ? 's' : '');
                } elseif ($months_remaining > 0) {
                    $remaining_time_display = sprintf('%d mois', $months_remaining);
                } elseif ($days_remaining > 0) {
                    $remaining_time_display = sprintf('%d jour%s', $days_remaining, $days_remaining > 1 ? 's' : '');
                } elseif ($hours_remaining > 0) {
                    $remaining_time_display = sprintf('%d heure%s', $hours_remaining, $hours_remaining > 1 ? 's' : '');
                } else {
                    $remaining_time_display = 'Moins d\'une heure';
                }
            } else {
                $remaining_time_display = 'Expiré';
            }
        }

        // Formater l'affichage de la dernière connexion
        $last_connection_display = $email['last_connection'] 
            ? date('d/m/Y H:i:s', strtotime($email['last_connection'])) 
            : 'Jamais';

        echo '<tr>';
        echo '<td class="column-select"><input type="checkbox" name="selected_emails[]" value="' . esc_html($email['id']) . '" /></td>'; // Colonne de sélection
        echo '<td class="column-id">' . esc_html($email['id']) . '</td>';
        echo '<td>' . esc_html($email['email']) . '</td>';
        echo '<td>' . esc_html(date('d/m/Y H:i:s', strtotime($email['date_added']))) . '</td>';
        echo '<td>' . esc_html($email['expiration_date'] ? date('d/m/Y H:i:s', strtotime($email['expiration_date'])) : 'Aucune') . '</td>';
        echo '<td>' . esc_html($remaining_time_display) . '</td>';
        echo '<td>' . esc_html($email['connection_count']) . '</td>';
        echo '<td>' . esc_html($last_connection_display) . '</td>';
        echo '</tr>';
    }
    ?>
</tbody>
            </table>
            <input type="submit" name="delete_selected" value="Supprimer les e-mails sélectionnés" class="button button-secondary delete-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer les e-mails sélectionnés ?');" />
        </form>

        <form method="post">
            <div class="email-form">
            <h2>Ajouter un nouvel e-mail</h2>
            <p>Laissez la période de validité vide pour que l’email n’expire jamais. </p>
                <label for="new_email">Nouvelle adresse e-mail :</label>
                <input type="email" id="new_email" name="new_email" required />
                <br /><br />
                <label for="validity_period">Période de validité :</label>
                <input type="number" id="validity_period" name="validity_period" min="1"/>
                <select name="validity_unit" id="validity_unit">
                    <option value="days">Jours</option>
                    <option value="months">Mois</option>
                    <option value="years">Années</option>
                    <option value="hours">Heures</option>
                </select>
                <br /><br />
                <input type="submit" name="add_email" value="Ajouter" class="button button-primary" />
            </div>
        </form>

        <div class="email-form">
            <h3>Importer un fichier CSV :</h3>
            <p>Lors de l’importation d’un fichier CSV, les emails n’expireront jamais.</p>
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="csv_file" accept=".csv" required />
                <br /><br />
                <input type="submit" name="import_csv" value="Importer" class="button button-primary" />
            </form>
        </div>
    </div>

    <script>
        // Script pour la sélection/désélection de tous les e-mails
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="selected_emails[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
    <?php
}