<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * textes en français pour le plugin category banner
 * 
 * Ailleurs dans le code, on utilisera par exemple 
 * get_string('rules', 'local_categorybanner')
 * qui récupérera le nom dans ce fichier local_categorybanner.php en français ou dans le fichier en anglais en fonction du contexte
 * 
 * NB: possible de mettre des "place holders", par exemple
 * Ici:
 * $string['sympa'] = 'Je suis {$adjectif} sympa';
 * 
 * get_string(
 *     'sympa',                    // Identifiant de la chaîne
 *     'local_categorybanner',     // Nom du plugin
 *     ['adjectif' => 'très']       // Paramètres optionnels pour placeholders
 * );
 *
 * @package    local_categorybanner
 * @copyright  2025 Service Ecole Media <sem.web@edu.ge.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Category Banner';
$string['rules'] = 'Règles pour Category Banner';
$string['rules_desc'] = 'Configurer les règles déterminant lesbannières à afficher pour des catégories spécifiques';
$string['no_rules'] = 'Aucune régle n\'a encore été créée. Cliquez sur le bouton "Ajouter une régle" ci-dessous pour créer votre première régle.';
$string['add_rule'] = 'Ajouter une nouvelle régle';
$string['edit_rule'] = 'Modifier la régle';
$string['delete_rule'] = 'Supprimer la régle';
$string['category'] = 'Catégorie';
$string['banner_content'] = 'Contenu de la bannière';
$string['unknown_category'] = 'Catégorie inconnue';
$string['rule_saved'] = 'Règle enregistrée avec succès';
$string['rule_deleted'] = 'Règle de bannière supprimée avec succès';
$string['confirm_delete'] = 'Êtes-vous sûr de vouloir supprimer cette règle de bannière ?';
$string['categorybanner:managebanner'] = 'Gérer les bannières de catégorie';

$string['actions'] = 'Actions';
