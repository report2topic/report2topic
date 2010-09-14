<?php
/**
 *
 * @package report2topic++
 * @copyright (c) 2010 report2topic++ http://github.com/report2topic
 * @author Erik Frèrejean ( N/A ) http://www.erikfrerejean.nl
 * @author David King (imkingdavid) http://www.phpbbdevelopers.net
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'ACP_REPORT2TOPIC'					=> 'report2topic++',
	'ACP_REPORT2TOPIC_CONFIG'			=> 'report2topic++ configuratie',
	'ACP_REPORT2TOPIC_CONFIG_EXPLAIN'	=> 'De globale report2topic++ configuratie.',
	'ACP_REPORT2TOPIC_CONFIG_SUCCESS'	=> 'De globale configuratie is successvol aangepast!',
	'ACP_REPORT2TOPIC_PM_CONFIG'		=> 'PM melding configuratie.',

	'FORUM_NOT_EXISTS'	=> 'Het verzochte forum (%1$s) bestaat niet!',

	'PARSE_SIG'	=> 'Laat onderschrift zien',

	'R2T_DEST_FORUM'				=> 'Standaard forum',
	'R2T_DEST_FORUM_EXPLAIN'		=> 'Selecteer het forum dat als <strong>standaard</strong> bestemming voor de meldings berichten gebruikt zal worden. Je kan deze optie op een forum basis overschrijven.',
	'R2T_PM_DEST_FORUM'				=> 'PM melding bestemmings forum',
	'R2T_PM_DEST_FORUM_EXPLAIN'		=> 'Selecteer het forum dat zal worden gebruikt om PM meldings berichten naar toe te posten.',
	'R2T_PM_TITLE'					=> 'PM melding bericht titel',
	'R2T_PM_TITLE_EXPLAIN'			=> 'Here kun je bepalen welke bericht titel er gebruikt zal worden voor PM medlings berichten. Het <strong>is</strong> mogelijk om de "tokens" in de titel te gebruiken.',
	'R2T_PM_TEMPLATE'				=> 'PM template',
	'R2T_PM_TEMPLATE_EXPLAIN'		=> 'Hier kun je bepalen hoe de PM meldings berichten worden opgesteld. Door het gebruik van onderstaande "tokens" kun je bepaalde informatie toevoegen/verwijderen.',
	'R2T_POST_TITLE'				=> 'Bericht melding titel',
	'R2T_POST_TITLE_EXPLAIN'		=> 'Here kun je bepalen welke bericht titel er gebruikt zal worden voor PM medlings berichten. Het <strong>is</strong> mogelijk om de "tokens" in de titel te gebruiken.',
	'R2T_POST_TEMPLATE'				=> 'Post template',
	'R2T_POST_TEMPLATE_EXPLAIN'		=> 'Hier kun je bepalen hoe de PM meldings berichten worden opgesteld. Door het gebruik van onderstaande "tokens" kun je bepaalde informatie toevoegen/verwijderen.',
	'R2T_SELECT_DEST_FORUM'			=> 'Report destination forum',
	'R2T_SELECT_DEST_FORUM_EXPLAIN'	=> 'Select here the forum to which reports that are made in this forum will be posted. If not selected the default forum will be used.',

	'TOKEN'				=> 'Token',
	'TOKENS'			=> 'Tokens',
	'TOKENS_EXPLAIN'	=> 'Tokens zijn tijdelijke aanduidingen voor verschillende soorten informatie. Deze tokens worden door hun eigenlijke tekst <em>(zie hieronder)</em> vervangen voordat het bericht is geplaatst.<br /><br /><strong>Hou in gedachte dan alleen onderstaande tokens gebruikt kunnen worden in een meldings bericht.</strong>',
	'TOKEN_DEFINITION'	=> 'Waar zal het mee vervangen worden?',
));