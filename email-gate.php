<?php
/*
Plugin Name: Email Gate
Description: Plugin pour vérifier l'adresse email des visiteurs avant de donner accès au contenu du site.
Version: 1.0
Author: Romain Voileaux - RVLX
Author URI: https://romain-voileaux.fr
*/

// Inclure les fichiers nécessaires
require_once plugin_dir_path(__FILE__) . 'includes/admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/form.php';
require_once plugin_dir_path(__FILE__) . 'includes/verification.php';