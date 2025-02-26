# Category Banner Plugin for Moodle

Ce plugin permet d'afficher une bannière personnalisée sur les pages de cours en fonction de leur catégorie.

## Fonctionnalités

- Affichage d'une bannière HTML personnalisable pour chaque catégorie de cours
- Configuration simple via l'interface d'administration de Moodle
- La bannière s'affiche sur la page principale du cours et toutes ses pages associées
- Support du HTML et des styles CSS inline dans le contenu de la bannière

## Installation

1. Téléchargez le plugin
2. Copiez le dossier 'categorybanner' dans le répertoire /local/ de votre installation Moodle
3. Visitez la page des notifications d'administration pour terminer l'installation

## Configuration

1. Accédez à Administration du site > Plugins > Plugins locaux > Category Banner
2. Pour chaque catégorie, vous pouvez définir le contenu HTML de la bannière
3. Laissez le champ vide pour ne pas afficher de bannière pour une catégorie donnée

## Format de la bannière

Par défaut, la bannière utilise le format suivant :

```html
<div style="background-color: red; color: white; font-size: 20px; padding: 10px; text-align: center;">
    CE COURS VA ÊTRE SUPPRIMÉ PROCHAINEMENT CAR VOUS L'AVEZ MIS DANS LA CATEGORIE POUBELLE
</div>
```

Vous pouvez personnaliser ce format en modifiant le HTML et les styles CSS selon vos besoins.

## Licence

Ce plugin est distribué sous licence GNU GPL v3 ou ultérieure.
