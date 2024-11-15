# Email Gate WP

**Email Gate WP** est un plugin WordPress conçu pour gérer l'accès au contenu en restreignant les utilisateurs selon une liste d'adresses e-mail autorisées. Il inclut des fonctionnalités avancées pour le suivi et la gestion des accès utilisateurs.

## Fonctionnalités

- **Restriction d'accès basée sur une liste autorisée** : Autorisez uniquement les utilisateurs dont les adresses e-mail figurent dans une liste définie.
- **Gestion des adresses e-mail** :
  - Ajoutez, modifiez ou supprimez des adresses e-mail via une interface d'administration conviviale.
  - Importez des adresses e-mail en masse via un fichier CSV.
- **Suivi des utilisateurs** :
  - Comptez le nombre de connexions par e-mail.
  - Suivez la date de dernière connexion pour chaque utilisateur.
- **Gestion des périodes de validité** :
  - Configurez une date d'expiration pour chaque adresse e-mail (en jours, mois, années ou heures).
  - Affichez le temps restant avant l'expiration d'une adresse.
- **Interface conviviale** :
  - Liste complète avec pagination des e-mails autorisés et leurs détails (ID, date d'ajout, date d'expiration, connexions, etc.).
  - Options de suppression groupée.

## Installation

1. Téléchargez le dépôt ou clonez-le via Git :
    ```bash
    git clone https://github.com/Romain2109/email-gate-wp.git
    ```

2. Téléversez le dossier `email-gate-wp` dans le répertoire `/wp-content/plugins/`.
3. Activez le plugin via le menu "Extensions" dans WordPress.

## Utilisation

1. Accédez à la page des réglages du plugin dans l'administration WordPress.
2. Ajoutez ou importez les adresses e-mail autorisées.
3. Configurez les périodes de validité et autres paramètres nécessaires.
4. Utilisez la liste pour visualiser les statistiques et gérer les utilisateurs.

## Requis

- WordPress 5.0 ou supérieur.
- PHP 7.0 ou supérieur.


---

**Email Gate WP** facilite la gestion des accès et la collecte d'adresses e-mail sur votre site WordPress, tout en fournissant des outils avancés de suivi et d'administration.
