<?php
// Fonction pour récupérer la liste des adresses e-mail autorisées avec leurs identifiants
function get_allowed_emails_with_ids() {
    $emails = get_option('allowed_emails', array());
    $emails_with_ids = array();
    foreach ($emails as $key => $email) {
        $emails_with_ids[] = array(
            'id' => $key,
            'email' => $email
        );
    }
    return $emails_with_ids;
}

// Fonction pour afficher la page "Email Liste"
function email_list_admin_page() {
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
                $allowed_emails = array();
                foreach ($emails as $email) {
                    $allowed_emails[] = $email[0];
                }
                update_option('allowed_emails', $allowed_emails);
                echo '<div class="updated"><p>Liste d\'e-mails importée avec succès!</p></div>';
            } else {
                echo '<div class="error"><p>Erreur lors du téléchargement du fichier CSV.</p></div>';
            }
        }

        // Vérifier si un e-mail a été ajouté
        if (isset($_POST['add_email'])) {
            $new_email = sanitize_email($_POST['new_email']);
            if ($new_email) {
                $emails = get_option('allowed_emails', array());
                $last_key = !empty($emails) ? max(array_keys($emails)) : 0;
                $new_id = $last_key + 1;
                $emails[$new_id] = $new_email;
                update_option('allowed_emails', $emails);
                echo '<div class="updated"><p>Email ajouté avec succès!</p></div>';
            } else {
                echo '<div class="error"><p>Veuillez entrer une adresse e-mail valide.</p></div>';
            }
        }

        // Vérifier si un e-mail a été supprimé
        if (isset($_GET['delete_email'])) {
            $delete_email = sanitize_email($_GET['delete_email']);
            if ($delete_email) {
                $emails = get_option('allowed_emails', array());
                $key = array_search($delete_email, $emails);
                if ($key !== false) {
                    unset($emails[$key]);
                    $emails = array_values($emails);
                    update_option('allowed_emails', $emails);
                    echo '<div class="updated"><p>Email supprimé avec succès!</p></div>';
                }
            }
        }

        // Vérifier si des e-mails ont été sélectionnés pour la suppression groupée
        if (isset($_POST['delete_selected'])) {
            $selected_emails = isset($_POST['selected_emails']) ? $_POST['selected_emails'] : array();
            if (!empty($selected_emails)) {
                $emails = get_option('allowed_emails', array());
                foreach ($selected_emails as $email_id) {
                    if (isset($emails[$email_id])) {
                        unset($emails[$email_id]);
                    }
                }
                $emails = array_values($emails);
                update_option('allowed_emails', $emails);
                echo '<div class="updated"><p>E-mails sélectionnés supprimés avec succès!</p></div>';
            }
        }
        ?>
        <form method="post" enctype="multipart/form-data">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 10%;">Sélectionner</th>
                        <th style="width: 10%;">ID</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $emails = get_allowed_emails_with_ids();
                    foreach ($emails as $email) {
                        echo '<tr>';
                        echo '<td><input type="checkbox" name="selected_emails[]" value="' . esc_attr($email['id']) . '"></td>';
                        echo '<td>' . esc_html($email['id']) . '</td>';
                        echo '<td>' . esc_html($email['email']) . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <br>
            <input type="submit" name="delete_selected" value="Supprimer sélectionnés" class="button-primary">
        </form>
        <br>
        <h3>Ajouter un nouvel e-mail</h3>
        <form method="post">
            <input type="email" name="new_email" placeholder="Nouvel e-mail" required>
            <input type="submit" name="add_email" value="Ajouter" class="button-primary">
        </form>
        <br>
        <h3>Importer une liste d'e-mails depuis un fichier CSV</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="csv_file" accept=".csv" required>
            <input type="submit" name="import_csv" value="Importer" class="button-primary">
        </form>
    </div>
    <?php
}

// Fonction pour afficher la page "Formulaire"
function form_admin_page() {
    ?>
    <div class="wrap">
        <h1>Formulaire</h1>
        <p>Ici, vous pouvez gérer les paramètres du formulaire.</p>
        <!-- Ajoutez ici le contenu et la logique pour la gestion du formulaire -->
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
