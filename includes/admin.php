<?php
// admin.php

// Fonction pour récupérer la liste des adresses e-mail autorisées
function get_allowed_emails() {
    // Vous devez implémenter la logique pour récupérer la liste des adresses e-mail autorisées
    // Par exemple, vous pouvez les récupérer à partir de la base de données
    // Remplacez ceci par votre logique de récupération des e-mails
    $emails = get_option('allowed_emails', array());
    return $emails;
}

// Fonction pour afficher la page d'administration "Email Gate"
function email_gate_admin_page() {
    ?>
    <div class="wrap">
        <h1>Email Gate Settings</h1>
        <h2>Liste des adresses e-mail autorisées</h2>
        <?php
        // Vérifier si un e-mail a été ajouté
        if (isset($_POST['add_email'])) {
            $new_email = sanitize_email($_POST['new_email']);
            if ($new_email) {
                // Ajouter l'e-mail à la liste
                $emails = get_allowed_emails();
                $emails[] = $new_email;
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
                // Supprimer l'e-mail de la liste
                $emails = get_allowed_emails();
                $key = array_search($delete_email, $emails);
                if ($key !== false) {
                    unset($emails[$key]);
                    update_option('allowed_emails', $emails);
                    echo '<div class="updated"><p>Email supprimé avec succès!</p></div>';
                }
            }
        }
        ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Récupérer la liste des e-mails autorisés
                $emails = get_allowed_emails();
                foreach ($emails as $email) {
                    echo '<tr><td>' . esc_html($email) . '</td>';
                    echo '<td><a href="?page=email-gate&delete_email=' . urlencode($email) . '">Supprimer</a></td></tr>';
                }
                ?>
            </tbody>
        </table>
        <br>
        <h3>Ajouter un nouvel e-mail</h3>
        <form method="post">
            <input type="email" name="new_email" placeholder="Nouvel e-mail" required>
            <input type="submit" name="add_email" value="Ajouter" class="button-primary">
        </form>
    </div>
    <?php
}

// Enregistrer la page d'administration avec le nouveau pictogramme
function email_gate_add_admin_page() {
    add_menu_page(
        'Email Gate',           // Titre de la page
        'Email Gate',           // Texte du menu
        'manage_options',       // Capacité requise pour accéder au menu
        'email-gate',           // Slug de la page
        'email_gate_admin_page',// Fonction pour afficher la page
        'dashicons-groups'      // Icône du menu (pictogramme de plusieurs personnes)
    );
}
add_action('admin_menu', 'email_gate_add_admin_page');
