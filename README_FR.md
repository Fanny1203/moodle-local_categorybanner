# Category Banner Plugin for Moodle

Ce plugin permet d'afficher des bannières personnalisées en haut des pages de cours en fonction de leur catégorie.

## Fonctionnalités

- Définition de bannières personnalisées pour des catégories de cours spécifiques
- Option de bannière globale pour afficher un message partout
- Application des bannières aux sous-catégories en un clic
- Support de bannières multiples : si plusieurs règles s'appliquent à un cours, tous les messages sont affichés avec un séparateur
- Éditeur de texte riche pour le contenu des bannières
- Gestion facile via l'interface d'administration de Moodle

## Installation

1. Copiez le dossier categorybanner dans le répertoire /local de votre installation Moodle
2. Visitez la page des notifications pour terminer l'installation
3. Configurez le plugin via Administration du site > Plugins > Plugins locaux > Category Banner

## Utilisation

1. Accédez à la page d'administration du plugin
2. Cliquez sur "Ajouter une règle" pour créer une bannière
3. Choisissez entre :
   - Une catégorie spécifique (et optionnellement ses sous-catégories)
   - Toutes les pages (bannière globale qui apparaît partout)
4. Saisissez le contenu de votre bannière avec l'éditeur de texte riche
5. Enregistrez la règle

Plusieurs règles peuvent s'appliquer au même cours. Par exemple :
- Une bannière globale qui apparaît sur toutes les pages
- Une bannière spécifique à une catégorie
- Une bannière de catégorie parente qui s'applique aux sous-catégories

Toutes les bannières applicables seront affichées en séquence, séparées par une ligne horizontale.

## Permissions

Le plugin utilise la capacité 'local/categorybanner:managebanner' pour contrôler qui peut gérer les règles de bannières.

## Support

Pour tout problème ou suggestion, veuillez contacter :
Service École Média <sem.web@edu.ge.ch>

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

## Architecture du plugin

Le plugin suit une architecture modulaire claire avec une séparation des responsabilités entre les différents fichiers :

### Composants principaux

#### 1. Interface d'administration (settings.php)
- Point d'entrée pour l'intégration avec le système d'administration de Moodle
- Crée la page de paramètres dans le menu d'administration
- Gère la suppression des règles
- Enregistre la page d'édition externe dans le système
- Maintenu séparément de edit.php pour suivre l'architecture standard de Moodle

#### 2. Interface d'édition (edit.php)
- Page dédiée à l'édition d'une règle spécifique
- Gère l'affichage du formulaire d'édition
- Valide et sauvegarde les données du formulaire
- Séparé de settings.php pour :
  - Maintenir une séparation claire des responsabilités
  - Permettre la réutilisation potentielle
  - Améliorer la maintenabilité
  - Suivre les conventions Moodle

#### 3. Gestionnaire de règles (rule_manager.php)
- Couche logique métier pour la gestion des règles
- Fournit une interface claire entre les données et l'interface utilisateur
- Utilisé par settings.php et edit.php pour :
  - Fournir les données pour l'interface d'administration
  - Gérer la création et la mise à jour des règles
  - Assurer une gestion cohérente des données

#### 4. Interface d'administration personnalisée (admin_setting_categorybanner_rules.php)
- Extension de admin_setting pour créer une interface sur mesure
- Agit comme couche de présentation
- Crée l'interface HTML pour la gestion des règles
- Intègre les fonctionnalités d'édition et de suppression

Cette architecture modulaire permet :
- Une maintenance plus facile
- Une meilleure séparation des responsabilités
- Une réutilisation potentielle des composants
- Une conformité avec les standards de développement Moodle

## Licence

Ce plugin est distribué sous licence GNU GPL v3 ou ultérieure.
