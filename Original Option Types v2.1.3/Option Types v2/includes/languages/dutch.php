<?php
/*
  $Id: Dutch.php,v 1.124 2003/07/11 09:03:49 jan0815 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'nl_NL'
// on FreeBSD try 'nl_NL.ISO_8859-1'
// on Windows try 'nl' or 'Dutch'
// @setlocale(LC_TIME, 'nl_NL.ISO_8859-1');
// @setlocale (LC_TIME, 'Dutch');

   @setlocale(LC_TIME, 'nl_NL.ISO_8859-1' , 'Dutch');

define('DATE_FORMAT_SHORT', '%d.%m.%Y');        // this is used for strftime()
define('DATE_FORMAT_LONG', '%A, %d %B %Y');     // this is used for strftime()
define('DATE_FORMAT', 'd/m/Y');                 // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY      // Nederlandse notering
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
  }
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'EUR');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="LTR" lang="nl"');

// charset for web pages and emails
define('CHARSET', 'iso-8859-1');

// page title
define('TITLE', STORE_NAME);

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Klantgegevens invoeren');
define('HEADER_TITLE_MY_ACCOUNT', 'Uw klantgegevens');
define('HEADER_TITLE_CART_CONTENTS', 'Inhoud winkelwagen');
define('HEADER_TITLE_CHECKOUT', 'Afrekenen');
define('HEADER_TITLE_TOP', 'Hoofdpagina');
define('HEADER_TITLE_CATALOG', 'Winkel');
define('HEADER_TITLE_LOGOFF', 'Uitloggen');
define('HEADER_TITLE_LOGIN', 'Inloggen');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', 'bezoeken sinds');

// text for gender
define('MALE', 'Man');
define('FEMALE', 'Vrouw');
define('MALE_ADDRESS', 'Dhr.');
define('FEMALE_ADDRESS', 'Mevr.');

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd/mm/jjjj');

// categories box text in includes/boxes/categories.php
define('BOX_HEADING_CATEGORIES', 'Categorie&euml;n');

// manufacturers box text in includes/boxes/manufacturers.php
define('BOX_HEADING_MANUFACTURERS', 'Fabrikanten');

// whats_new box text in includes/boxes/whats_new.php
define('BOX_HEADING_WHATS_NEW', 'Nieuwe producten');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_HEADING_SEARCH', 'Snel zoeken');
define('BOX_SEARCH_TEXT', 'Gebruik trefwoorden om een artikel te vinden.');
define('BOX_SEARCH_ADVANCED_SEARCH', 'Uitgebreid zoeken');

// specials box text in includes/boxes/specials.php
define('BOX_HEADING_SPECIALS', 'Aanbiedingen');

// reviews box text in includes/boxes/reviews.php
define('BOX_HEADING_REVIEWS', 'Beoordelingen');
define('BOX_REVIEWS_WRITE_REVIEW', 'Schrijf een beoordeling over dit artikel!');
define('BOX_REVIEWS_NO_REVIEWS', 'Er zijn geen beoordelingen over dit artikel. Wees de eerste!');
define('BOX_REVIEWS_TEXT_OF_5_STARS', '%s van 5 sterren!');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_HEADING_SHOPPING_CART', 'Winkelwagen');
define('BOX_SHOPPING_CART_EMPTY', '<center>Uw winkelwagen<br>is leeg</center>');

// order_history box text in includes/boxes/order_history.php
define('BOX_HEADING_CUSTOMER_ORDERS', 'Eerdere bestellingen');

// best_sellers box text in includes/boxes/best_sellers.php
define('BOX_HEADING_BESTSELLERS', 'Meest verkocht');
define('BOX_HEADING_BESTSELLERS_IN', 'Meest verkocht in de categorie<br>&nbsp;&nbsp;');

// notifications box text in includes/boxes/products_notifications.php
define('BOX_HEADING_NOTIFICATIONS', 'Notificaties');
define('BOX_NOTIFICATIONS_NOTIFY', 'Houd mij op de hoogte van updates voor <b>%s</b>');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', 'Houd mij <b>niet</b> op de hoogte van updates voor <b>%s</b>');

// manufacturer box text
define('BOX_HEADING_MANUFACTURER_INFO', 'Informatie over de fabrikant');
define('BOX_MANUFACTURER_INFO_HOMEPAGE', '%s Website');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', 'Andere artikelen');

// languages box text in includes/boxes/languages.php
define('BOX_HEADING_LANGUAGES', 'Talen');

// currencies box text in includes/boxes/currencies.php
define('BOX_HEADING_CURRENCIES', 'Valuta');

// information box text in includes/boxes/information.php
define('BOX_HEADING_INFORMATION', 'Informatie');
define('BOX_INFORMATION_PRIVACY', 'Privacy verklaring');
define('BOX_INFORMATION_CONDITIONS', 'Algemene voorwaarden');
define('BOX_INFORMATION_SHIPPING', 'Verzenden & retourneren');
define('BOX_INFORMATION_CONTACT', 'Neem contact op');

// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_HEADING_TELL_A_FRIEND', 'Vertel een vriend(in)');
define('BOX_TELL_A_FRIEND_TEXT', 'Vertel een bekende over dit artikel.');

// checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'Verzendinformatie');
define('CHECKOUT_BAR_PAYMENT', 'Betaalinformatie');
define('CHECKOUT_BAR_CONFIRMATION', 'Bevestiging');
define('CHECKOUT_BAR_FINISHED', 'Gereed!');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Selecteer');
define('TYPE_BELOW', 'Type hieronder');

// javascript messages
define('JS_ERROR', 'Er hebben zich fouten voorgedaan tijdens het verwerken van het formulier!\nMaak de volgende aanpassingen:\n\n');
define('JS_REVIEW_TEXT', '* De \'Beoordelings tekst\' Moet minimaal ' . REVIEW_TEXT_MIN_LENGTH . ' tekens lang zijn.\n');
define('JS_REVIEW_RATING', '* Geef het artikel een beoordelingscijfer.\n');
define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Selecteer de wijze van betaling voor uw bestelling.\n');
define('JS_ERROR_SUBMITTED', 'Dit formulier is al verzonden. Klik op OK en wacht tot de verwerking van deze bestelling klaar is.');
define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Selecteer de wijze van betaling voor uw bestelling.');
define('CATEGORY_COMPANY', 'Bedrijfsgegevens');
define('CATEGORY_PERSONAL', 'Persoonlijke gegevens');
define('CATEGORY_ADDRESS', 'Uw adres');
define('CATEGORY_CONTACT', 'Contact informatie');
define('CATEGORY_OPTIONS', 'Opties');
define('CATEGORY_PASSWORD', 'Uw wachtwoord');

define('ENTRY_COMPANY', 'Bedrijfsnaam:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', 'Man/Vrouw:');
define('ENTRY_GENDER_ERROR', 'Uw keuze.');
define('ENTRY_GENDER_TEXT', ' * ');
define('ENTRY_FIRST_NAME', 'Voornaam:');
define('ENTRY_FIRST_NAME_ERROR', 'Uw voornaam moet minimaal' . ENTRY_FIRST_NAME_MIN_LENGTH . 'tekens lang zijn.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Achternaam:');
define('ENTRY_LAST_NAME_ERROR', 'Uw achternaam moet minimaal' . ENTRY_LAST_NAME_MIN_LENGTH . ' tekens lang zijn.');
define('ENTRY_LAST_NAME_TEXT', ' * ');

define('ENTRY_DATE_OF_BIRTH', 'Geboortedatum:');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Voer de geboortedatum als volgt in: (voorb. 24/12/1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', ' *(bijv. 24/12/1970)');

define('ENTRY_EMAIL_ADDRESS', 'E-mail adres:');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Uw e-mail adres moet minimaal ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' tekens lang zijn.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Het lijkt erop dat uw e-mailadres onjuist is!');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Uw e-mailadres bestaat al!');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Straatnaam + huisnummer:');
define('ENTRY_STREET_ADDRESS_ERROR', 'De straatnaam moet minimaal' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' tekens lang zijn.');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', 'Woonwijk:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '*');
define('ENTRY_POST_CODE', 'Postcode:');
define('ENTRY_POST_CODE_ERROR', 'Uw postcode moet minimaal' . ENTRY_POSTCODE_MIN_LENGTH . ' tekens lang zijn.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'Woonplaats:');
define('ENTRY_CITY_ERROR', 'Uw woonplaats moet minimaal ' . ENTRY_CITY_MIN_LENGTH . ' tekens lang zijn.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'Provincie:');
define('ENTRY_STATE_ERROR', '');
define('ENTRY_STATE_ERROR_SELECT', 'Selecteer een provincie uit het keuzemenu.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_COUNTRY_ERROR', 'Kies een land uit het keuzemenu.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Telefoonnummer:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Uw telefoonnummer moet minimaal ' . ENTRY_TELEPHONE_MIN_LENGTH . ' tekens lang zijn.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Faxnummer:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');

define('ENTRY_NEWSLETTER', 'Nieuwsbrief:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'Ingeschreven');
define('ENTRY_NEWSLETTER_NO', 'Niet ingeschreven');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Wachtwoord:');
define('ENTRY_PASSWORD_ERROR', 'Uw wachtwoord moet minimaal ' . ENTRY_PASSWORD_MIN_LENGTH . ' tekens lang zijn.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'Beide wachtwoorden moet hetzelfde zijn.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Wachtwoord bevestigen:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Huidig wachtwoord:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Uw wachtwoord moet minimaal ' . ENTRY_PASSWORD_MIN_LENGTH . ' tekens lang zijn.');
define('ENTRY_PASSWORD_NEW', 'Nieuw wachtwoord:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Uw nieuwe wachtwoord moet minimaal ' . ENTRY_PASSWORD_MIN_LENGTH . ' tekens lang zijn.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'Beide wachtwoorden moeten hetzelfde zijn.');
define('PASSWORD_HIDDEN', '--Verborgen--');

define('FORM_REQUIRED_INFORMATION', '* Verplicht');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Pagina(s) gevonden:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Artikel <b>%d</b> tot en met <b>%d</b> (van <b>%d</b> artikelen)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Bestelling <b>%d</b> tot en met <b>%d</b> (van <b>%d</b> bestellingen)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Beoordelingen <b>%d</b> tot en met <b>%d</b> (van <b>%d</b> beoordelingen)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Nieuwe artikelen <b>%d</b> tot en met <b>%d</b> (van <b>%d</b> nieuwe artikelen)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Aanbieding <b>%d</b> tot en met <b>%d</b> (van <b>%d</b> aanbiedingen)');
define('PREVNEXT_TITLE_FIRST_PAGE', 'Eerste pagina');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Vorige pagina');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Volgende pagina');
define('PREVNEXT_TITLE_LAST_PAGE', 'Laatste pagina');
define('PREVNEXT_TITLE_PAGE_NO', 'Pagina %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Vorige reeks van %d pagina\'s');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Volgende reeks van %d pagina\'s');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;Eerste');
define('PREVNEXT_BUTTON_PREV', '[&lt;&lt;&nbsp;Vorige]');
define('PREVNEXT_BUTTON_NEXT', '[Volgende&nbsp;&gt;&gt;]');
define('PREVNEXT_BUTTON_LAST', 'Laatste&gt;&gt;');
define('IMAGE_BUTTON_ADD_ADDRESS', 'Adres toevoegen');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Adresboek');
define('IMAGE_BUTTON_BACK', 'Terug');
define('IMAGE_BUTTON_BUY_NOW', 'Bestel NU');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Adres veranderen');
define('IMAGE_BUTTON_CHECKOUT', 'Kassa');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Bevestigen');
define('IMAGE_BUTTON_CONTINUE', 'Volgende');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Verder winkelen');
define('IMAGE_BUTTON_DELETE', 'Verwijderen');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Klantgegevens bewerken');
define('IMAGE_BUTTON_HISTORY', 'Eerdere bestellingen');
define('IMAGE_BUTTON_LOGIN', 'Inloggen');
define('IMAGE_BUTTON_IN_CART', 'In winkelwagen');
define('IMAGE_BUTTON_NOTIFICATIONS', 'Notificaties');
define('IMAGE_BUTTON_QUICK_FIND', 'Snel zoeken');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Verwijder notificaties');
define('IMAGE_BUTTON_REVIEWS', 'Beoordelingen');
define('IMAGE_BUTTON_SEARCH', 'Zoeken');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Verzendopties');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Vertel een vriend(in)');
define('IMAGE_BUTTON_UPDATE', 'Bijwerken');
define('IMAGE_BUTTON_UPDATE_CART', 'Winkelwagen bijwerken');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Beoordeling schrijven');
define('SMALL_IMAGE_BUTTON_DELETE', 'Verwijderen');
define('SMALL_IMAGE_BUTTON_EDIT', 'Bewerken');
define('SMALL_IMAGE_BUTTON_VIEW', 'Bekijken');

define('ICON_ARROW_RIGHT', 'Meer');
define('ICON_CART', 'In de winkelwagen doen');
define('ICON_ERROR', 'Fout');
define('ICON_SUCCESS', 'Succes');
define('ICON_WARNING', 'Waarschuwing');

define('TEXT_GREETING_PERSONAL', 'Welkom terug <span class="greetUser">%s !</span> Wilt u de <a href="%s"><u>nieuwe artikelen</u></a> zien die beschikbaar zijn?');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>Als u niet %s bent, <a href="%s"><u>log uzelf in</u></a> met uw registratiegegevens.</small>');
define('TEXT_GREETING_GUEST', 'Welkom <span class="greetUser">Bezoeker !</span><BR>Wilt u <a href="%s"><u>inloggen</u></a>? Of wilt u zich als nieuwe klant <a href="%s"><u>Aanmelden</u></a> ?<BR>Kijkt u rustig rond, u hoeft alleen in te loggen of u als nieuwe klant aan te melden wanneer u besluit een bestelling te plaatsen ! Uw winkelwagen wordt automatisch overgenomen nadat u inlogt of zich als nieuwe klant aanmeldt.');
define('TEXT_SORT_PRODUCTS', 'Sorteer artikelen ');
define('TEXT_DESCENDINGLY', 'Aflopend');
define('TEXT_ASCENDINGLY', 'Oplopend');
define('TEXT_BY', ' volgens ');
define('TEXT_REVIEW_BY', 'van %s');
define('TEXT_REVIEW_WORD_COUNT', '%s woorden');
define('TEXT_REVIEW_RATING', 'Waardering: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', 'Datum toegevoegd: %s');
define('TEXT_NO_REVIEWS', 'Er zijn geen beoordelingen over dit artikel.');
define('TEXT_NO_NEW_PRODUCTS', 'Er zijn geen nieuwe artikelen.');
define('TEXT_UNKNOWN_TAX_RATE', 'Onbekend belastingstarief');
define('TEXT_REQUIRED', '<span class="errorText">Verplicht</span>');
define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> Kan de email niet via de opgegeven SMTP server versturen. Controleer de php.ini instellingen en maak eventueel de nodige aanpassingen.</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Waarschuwing: Installatie directory bestaat nog: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install. Verwijder deze directory voor veiligheids overwegingen.');
define('WARNING_CONFIG_FILE_WRITEABLE', 'Waarschuwing: Ik kan het configuratie bstand: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php beschrijven. Dit is een potentieel risico - Stel de permissies van de gebruiker goed in (CHMOD).');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Waarschuwing: De sessies directory bestaat niet: ' . tep_session_save_path() . '. Sessies zullen niet werken totdat de directory is aangemaakt.');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Waarschuwing: Ik kan niet naar de sessies directory schrijven: ' . tep_session_save_path() . '. Sessies zullen niet werken totdat de user permissies goed zijn gezet.');
define('WARNING_SESSION_AUTO_START', 'Waarschuwing: session.auto_start staat aan - zet deze uit in php.ini en hetstart de web server.');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Waarschuwing: De download artikelen directory bestaat niet: ' . DIR_FS_DOWNLOAD . '. Download artikelen zal niet werken totdat deze directory is aangemaakt.');

define('TEXT_CCVAL_ERROR_INVALID_DATE', 'De verloopdatum ingevuld voor de CreditCard is niet juist.<br>Controleer de datum en probeer het opnieuw.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'Het ingevulde nummer van de CreditCard is niet geldig.<br>Controleer het nummer en probeer het opnieuw.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'De eerste vier ingevulde cijfers van de CreditCard zijn: %s<br>Als dit correct is, dan accepteren wij dit type CreditCard niet.<br>Als het niet klopt, probeer het opnieuw.');

define('FOOTER_TEXT_BODY', 'Copyright &copy; ' . date('Y') . ' <a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME . '</a><br>Powered by <a href="http://www.oscommerce.com" target="_blank">osCommerce</a>');

//BOF - Zappo - Option Types v2 - File upload error strings
define('ERROR_FILETYPE_NOT_ALLOWED', 'Fout:  bestandstype is niet toegestaan.');
define('WARNING_NO_FILE_UPLOADED', 'Waarschuwing:  er is geen bestand geupload.');
define('SUCCESS_FILE_SAVED_SUCCESSFULLY', 'Succesvol:  bestand is succesvol opgeslagen.');
define('ERROR_FILE_NOT_SAVED', 'Fout:  bestand is niet opgeslagen.');
define('ERROR_DESTINATION_NOT_WRITEABLE', 'Fout:  bestemming is niet schrijfbaar.');
define('ERROR_DESTINATION_DOES_NOT_EXIST', 'Fout:  bestemming bestaat niet.');
// EOF - Zappo - Option Types v2 - File upload error strings
?>