# Category Banner Plugin for Moodle

Ce plugin permet d'afficher une bannière personnalisée sur les pages de cours en fonction de leur catégorie.

## Fonctionnalités

- Affichage d'une bannière HTML personnalisable pour chaque catégorie de cours
- Configuration simple via l'interface d'administration de Moodle
- La bannière s'affiche sur la page principale du cours et toutes ses pages associées (participants, notes, etc.)
- Support du HTML et des styles CSS inline dans le contenu de la bannière

## Installation

1. Téléchargez le plugin
2. Copiez le dossier 'categorybanner' dans le répertoire /local/ de votre installation Moodle
3. Visitez la page des notifications d'administration pour terminer l'installation

## Configuration

1. Accédez à Administration du site > Plugins > Category Banner
2. Pour chaque catégorie, vous pouvez définir le contenu HTML de la bannière
3. Laissez le champ vide pour ne pas afficher de bannière pour une catégorie donnée

## Structure du code

### Fichiers principaux
- `lib.php` : Fonctions principales du plugin
  - `local_categorybanner_before_standard_top_of_body_html()` : Affichage de la bannière
  - `local_categorybanner_is_course_layout()` : Vérifie si une page est liée à un cours
  - `local_categorybanner_render_banner()` : Génère le HTML de la bannière
- `version.php` : Version et dépendances du plugin
- `settings.php` : Configuration et menu d'administration

### Classes (dans /classes/)
- `rule_manager.php` : Gestion des règles de bannière
  - Constante `RULE_PREFIX` pour les clés de configuration
  - Méthodes pour lire, sauvegarder et supprimer les règles
- `admin_setting_categorybanner_rules.php` : Interface d'administration des règles
- `form/edit_rule.php` : Formulaire d'édition des règles

### Base de données (dans /db/)
- `access.php` : Définition des capacités utilisateur
- `events.php` : Définition des événements de cache

### Interface
- `edit.php` : Page d'édition des règles
- `styles.css` : Styles CSS pour la bannière
- `lang/en/local_categorybanner.php` : Chaînes de langue

## Pages où la bannière s'affiche

La bannière s'affiche sur toutes les pages avec les layouts suivants :
- 'course' : Page principale du cours
- 'incourse' : Activités et ressources du cours
- 'report' : Pages de rapports
- 'admin' : Pages d'administration du cours
- 'coursecategory' : Pages de catégories de cours

## Format de la bannière

La bannière est affichée dans un conteneur avec la classe CSS `local-categorybanner-notification`. Par défaut, elle utilise le style de notification "info" de Moodle.

Exemple de contenu HTML :
```html
<div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border: 1px solid #f5c6cb; border-radius: 4px;">
    Message important concernant ce cours
</div>
```

## Cache

Le plugin utilise le système de cache de Moodle :
- Les règles sont mises en cache pour optimiser les performances
- Le cache est purgé automatiquement lors de la modification d'une règle via l'événement 'local_categorybanner_rule_updated'

## Sécurité

- Seuls les utilisateurs avec la capacité 'local_categorybanner:managebanner' peuvent gérer les règles
- Le contenu HTML des bannières est filtré par Moodle pour la sécurité

## Licence

Ce plugin est distribué sous licence GNU GPL v3 ou ultérieure.
