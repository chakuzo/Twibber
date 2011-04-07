<?php

/* DE Sprach Datei
 * @author Kurtextrem, Math-Board
 * @contact kurtextrem@gmail.com
 * Example:
 * %s ersetzt den content dazwischen, nützlich für "Vor X Minuten", oder "Du hast schon X mal gedipt."
 * Mehr dazu: http://de2.php.net/manual/de/function.sprintf.php
 */

$lang = array(

	/* lib/class/Twibber.class.php */
	'timezone' => 'Europe/Berlin',
	'prefix_error' => 'ERROR! Bitte ändern Sie in <config.inc.php> Zeile 9 zum richtigen Präfix!',
	'group_id_error' => 'ERROR! Bitte geben Sie in <config.inc.php> Zeile 19 / 20 eine ID an!',
	'mysql_connect_error' => 'Verbindungsfehler. Haben Sie <config.inc.php> Zeile 5-8 richtig ausgefüllt?',
	'mysql_wcf_connect_erorr' => 'Verbindungsfehler. Haben Sie <config.inc.php> Zeile 15-18 richtig ausgefüllt?',
	'date_just_now' => 'Gerade jetzt',
	'date_yesterday' => 'Gestern',
	'date_one_minute_ago' => 'Vor einer Minute',
	'date_one_houre_ago' => 'Vor einer Stunde',
	'date_one_day_ago' => 'Vor einem Tag',
	'date_one_week_ago' => 'Vor einer Woche',
	'date_one_month_ago' => 'Vor einem Monat',
	'date_one_year_ago' => 'Vor einem Jahr',
	'date_minutes_ago' => 'Vor %s Minuten',
	'date_hours_ago' => 'Vor %s Stunden',
	'date_days_ago' => 'Vor %s Tagen',
	'date_weeks_ago' => 'Vor %s Wochen',
	'date_months_ago' => 'Vor %s Monaten',
	'date_years_ago' => 'Vor %s Jahren',
	'comment' => 'Kommentieren',

	/* login.php */
	'false_pw_nick' => "Falsches Passwort / Falscher Benutzername. Versuchen Sie es <a href='index.php'>erneut</a>!",

	/* api.php */
	'success' => 'Erfolgreich!',
	'no_message' => "Bitte geben Sie eine Nachricht ein, bevor Sie 'Twibbern' klicken!",
	'message_too_long' => 'Nachricht zu lang!',
	'failure' => 'Fehler, irgendwas ging wohl schief!',
	'no_nick' => 'Kein Nickname angegeben. Bitte loggen Sie sich ein!',
	'gd_error' => 'Kann keinen neuen GD-Bild-Stream erzeugen.',
	'gd_last_twib' => "'s neuster Twibb:",
	'gd_date' => 'Am: ',

	/* index.php */
	'guest' => 'Gast',
	'copyright' => 'Twibber wird von WBBLite2.de entwickelt.',

	/* install/update.php */
	'nightly_ok' => 'Nightly Version installiert!',
	'update_fail' => 'Fehler beim Updaten auf den Nightly Build!',
	'update' => 'Update verfügbar!',
	'update_install' => 'Updates installieren',
	'update_notes' => 'Notizen:',
	'updated_from' => 'Geupdatet von',
	'updated_to' => 'zu',
	'no_update' => 'Kein Update verfügbar.',
	'no_action' => 'Keine valide Action!'
);

?>