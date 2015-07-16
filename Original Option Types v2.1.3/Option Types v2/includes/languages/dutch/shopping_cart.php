<?php
/*
  $Id: shopping_cart.php,v 1.13 2002/04/05 20:24:02 project3000 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Inhoud winkelwagen');
define('HEADING_TITLE', 'Inhoud van de winkelwagen');
define('TABLE_HEADING_REMOVE', 'Verwijderen?');
define('TABLE_HEADING_QUANTITY', 'Aantal');
define('TABLE_HEADING_MODEL', 'Artikel model');
define('TABLE_HEADING_PRODUCTS', 'Artikel(en)');
//BOF - Zappo - Option Types v2 - ONE LINE - Added for new column in Cart...
define('TABLE_HEADING_OPTIONS', 'Geselecteerde Optie(s)');
define('TABLE_HEADING_TOTAL', 'Totaal');
define('TEXT_CART_EMPTY', 'Uw winkelwagen is leeg!');
define('SUB_TITLE_SUB_TOTAL', 'Sub-totaal:');
define('SUB_TITLE_TOTAL', 'Totaal:');

define('OUT_OF_STOCK_CANT_CHECKOUT', 'Artikelen gemarkeerd met ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' zijn niet voorradig.<br>Verander het aantal van het product gemarkeerd met (' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '). Dank u.');
define('OUT_OF_STOCK_CAN_CHECKOUT', 'Artikelen gemarkeerd met ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' zijn niet voorradig.<br>U kunt het artikel toch kopen. Het zal verstuurd worden zodra het binnen is.');

define('TEXT_ALTERNATIVE_CHECKOUT_METHODS', '- OF -');
?>