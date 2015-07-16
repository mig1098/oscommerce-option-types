<?php
/*
  $Id: attributeManagerConfig.class.php,v 1.0 21/02/06 Sam West$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  
  Copyright © 2006 Kangaroo Partners
  http://kangaroopartners.com
  osc@kangaroopartners.com
*/

require_once('attributeManager/classes/amDB.class.php');
require_once('attributeManager/includes/attributeManagerSessionFunctions.inc.php');

if(file_exists('attributeManager/languages/'.$_SESSION['language'].'/attributeManager.php'))
 include_once('attributeManager/languages/'.$_SESSION['language'].'/attributeManager.php');
else
 include_once('attributeManager/languages/'.'english'.'/attributeManager.php');

class attributeManagerConfig {
	
	var $arrConfig = array();
	
	function attributeManagerConfig() {
		
		
		/**
		 * Default admin interface language id
		 */
		$this->add('AM_DEFAULT_LANGUAGE_ID',$GLOBALS['languages_id']);
		
		/**
		 * Default admin interface template order
		 */
		$this->add('AM_DEFAULT_TEMPLATE_ORDER','123');
		
		/**
		 * Dont update the database untill the untill the end of the product addition process
		 */
		$this->add('AM_ATOMIC_PRODUCT_UPDATES', false);
		
		
		/**
		 * Use attribute templates?
		 * 
		 */
		$this->add('AM_USE_TEMPLATES',true);
		
		
		/**
		 * Template Table names
		 */
		$this->add('AM_TABLE_TEMPLATES','am_templates');
		$this->add('AM_TABLE_ATTRIBUTES_TO_TEMPLATES','am_attributes_to_templates');
		
// - Zappo - Option Types v2 - Removed QTPro, and Placed Back & Adjusted Sort order from original AttributeManager (Needed this after all ;D) 
		/**
		 * Sort order tables
		 */
		$this->add('AM_FIELD_OPTION_SORT_ORDER','products_options_order'); // Sort column on Products_options table
		$this->add('AM_FIELD_OPTION_VALUE_SORT_ORDER','products_options_sort_order'); // Sort column on product_attributes table	
		
		/**
		 * Password for the session var - doesn't matter what it is. Mix it up if you feel like it :)
		 */
		$this->add('AM_VALID_INCLUDE_PASSWORD','asdfjkasdadfadsff');
		
		/**
		 * Variable names - Shouldn't need editing unless there are conflicts
		 */
		$this->add('AM_SESSION_VAR_NAME','am_session_var'); // main var for atomic
		$this->add('AM_SESSION_CURRENT_LANG_VAR_NAME','am_current_lang_session_var'); // current interface lang
		$this->add('AM_SESSION_CURRENT_TEMPLATE_ORDER','am_current_template_order'); // current template order
		$this->add('AM_SESSION_VALID_INCLUDE','am_valid_include'); // variable set on categories.php to make sure attributeManager.php has been included
// - Zappo - Option Types v2 - Removed Sort order from original AttributeManager (No more need for this) 
		$this->add('AM_SESSION_TEMPLATES_INSTALL_CHECKED','am_templates_checked');
		$this->add('AM_ACTION_GET_VARIABLE', 'amAction'); // attribute manager get variable name
		$this->add('AM_PAGE_ACTION_NAME','pageAction'); // attribute manager parent page action e.g. new_product
		
		/** 
		 * Install templates if not already done so 
		 */
		$this->installTemplates();
// - Zappo - Option Types v2 - Removed Sort order from original AttributeManager (No more need for this) 
	}
	
	function load() {
		if(0 !== count($this->arrConfig))
			foreach($this->arrConfig as $key => $value)
				define($key, $value);
	}
	
	function getValue($key) {
		if(array_key_exists($key, $this->arrConfig))
			return $this->arrConfig[$key];
		return false;
	}
	
	function add($key, $value) {
		$this->arrConfig[$key] = $value;
	}
	
	function installTemplates() {
// - Zappo - Option Types v2 - Removed Sort order from original AttributeManager (No more need for this) 
		if($this->getValue('AM_USE_TEMPLATES')) {
									 
			amDB::query("CREATE TABLE IF NOT EXISTS ".$this->getValue('AM_TABLE_TEMPLATES')." (
					`template_id` INT( 5 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`template_name` VARCHAR( 255 ) NOT NULL)");
			amDB::query("CREATE TABLE IF NOT EXISTS ".$this->getValue('AM_TABLE_ATTRIBUTES_TO_TEMPLATES')." (
					`template_id` INT( 5 ) UNSIGNED NOT NULL ,
					`options_id` INT( 5 ) UNSIGNED NOT NULL ,
					`option_values_id` INT( 5 ) UNSIGNED NOT NULL ,
					`price_prefix` char(1) default '+',
					`options_values_price` decimal(15,4) default 0,
					`products_options_sort_order` int default 0,
					INDEX ( `template_id` ))");
			// Check if the user is updating from the older version
			$install_price_prefix=true;
			$install_options_values_price=true;

			// Fetch database Fields
			$attributeFields = amDB::query("SHOW COLUMNS FROM ". $this->getValue(AM_TABLE_ATTRIBUTES_TO_TEMPLATES));
			while($field = amDB::fetchArray($attributeFields)) 
				$fields[] = $field['Field'];
			
			if( !in_array('price_prefix',$fields) ){
				amDB::query("ALTER TABLE ".$this->getValue('AM_TABLE_ATTRIBUTES_TO_TEMPLATES')." ADD(`price_prefix` char(1) default '+')");
			}
			if( !in_array('options_values_price',$fields) ){
				amDB::query("ALTER TABLE ".$this->getValue('AM_TABLE_ATTRIBUTES_TO_TEMPLATES')." ADD(`options_values_price` decimal(15,4) default 0)");
			}
// - Zappo - Option Types v2 - Removed Sort order from original AttributeManager (No more need for this) 
			// register the checked session so that this check is only done once per session
			amSessionRegister('AM_SESSION_TEMPLATES_INSTALL_CHECKED',true);
// - Zappo - Option Types v2 - Removed Sort order installation from original AttributeManager (No more need for this) 
				}
	}
}

$config = new attributeManagerConfig();
$config->load();

?>