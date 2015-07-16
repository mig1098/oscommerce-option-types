<?php
/*
  $Id: attributeManagerInstant.class.php,v 1.0 21/02/06 Sam West$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  
  Copyright � 2006 Kangaroo Partners
  http://kangaroopartners.com
  osc@kangaroopartners.com
*/

class attributeManagerInstant extends attributeManager {
	
	/**
	 * @access private
	 */
	var $intPID;
	
	/**
	 * __construct() assigns pid, calls the parent construct, registers page actions
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $intPID int
	 * @return void
	 */
	function attributeManagerInstant($intPID) {
		
		parent::attributeManager();
		
		$this->intPID = (int)$intPID;
		
		$this->registerPageAction('addAttributeToProduct','addAttributeToProduct');
		$this->registerPageAction('addOptionValueToProduct','addOptionValueToProduct');
		$this->registerPageAction('addNewOptionValueToProduct','addNewOptionValueToProduct');
		$this->registerPageAction('removeOptionFromProduct','removeOptionFromProduct');
		$this->registerPageAction('removeOptionValueFromProduct','removeOptionValueFromProduct');
		// QT Pro Plugin
		$this->registerPageAction('RemoveStockOptionValueFromProduct','RemoveStockOptionValueFromProduct');
		$this->registerPageAction('AddStockToProduct','AddStockToProduct');
		// QT Pro Plugin
		$this->registerPageAction('update','update');
		$this->registerPageAction('updateProductStockQuantity','updateProductStockQuantity');
		
// - Zappo - Option Types v2 - Placed Back & Adjusted Sort order from original AttributeManager (Needed this after all ;D) 
			$this->registerPageAction('moveOption','moveOption');
			$this->registerPageAction('moveOptionValue','moveOptionValue');
		
//----------------------------
// Change: Add download attributes function for AM
// @author Urs Nyffenegger ak mytool
// Function: register PageActions for Download options
//-----------------------------

		$this->registerPageAction('addDownloadAttributeToProduct','addDownloadAttributeToProduct');
		$this->registerPageAction('updateDownloadAttributeToProduct','updateDownloadAttributeToProduct');
		$this->registerPageAction('removeDownloadAttributeToProduct','removeDownloadAttributeToProduct');
//----------------------------
// EOF Change: download attributes for AM
//-----------------------------


	}
	
	//----------------------------------------------- page actions

	/**
	 * Adds the selected attribute to the current product
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function addAttributeToProduct($get) {
		
		$this->getAndPrepare('option_id', $get, $optionId);
		$this->getAndPrepare('option_value_id', $get, $optionValueId);
		$this->getAndPrepare('price', $get, $price);
		$this->getAndPrepare('prefix', $get, $prefix);
		$this->getAndPrepare('sortOrder', $get, $sortOrder);
		
		if((empty($price))||($price=='0')){
			$price='0.0000';
		}else{
			if((empty($prefix))||($prefix==' ')){
				$prefix='+';
			}
		}
		if(empty($prefix)){
			$prefix=' ';
		}

		$data = array(
			'products_id' => $this->intPID,
			'options_id' => $optionId,
			'options_values_id' => $optionValueId,
			'options_values_price' => $price,
			'price_prefix' => $prefix
		);
// - Zappo - Option Types v2 - Placed Back & Adjusted Sort order from original AttributeManager (Needed this after all ;D)  
	  // changes by mytool
		// get highest sort order value
		$insertIndex = -1;
		$result = $this -> getSortedProductAttributes( AM_FIELD_OPTION_VALUE_SORT_ORDER );
		// search for the current Sort Order where the new value needs to be added
		$i = -1;
		while ( list($key, $val) = each($result) ) {
   		$i++;
   		if( $val['options_id'] == $optionId ){
   			$insertIndex = $i;
   		}
   	}

		// if InsertIndex is still -1 then this is a new option and will be added at the end
		if($insertIndex > -1){
			$i = -1;
			$newArray = array();

			for ($n=0; $n < count($result) ; $n++){
				$i++;
 				if( $i == $insertIndex ){
          $i++;
   			  $data[AM_FIELD_OPTION_VALUE_SORT_ORDER] = $i;
  				$newArray[$i] = $result[$n]; 
  			} else {
  				$result[$n][AM_FIELD_OPTION_VALUE_SORT_ORDER] = $i; 
   				$newArray[$i] = $result[$n]; 
   			}
   		}

			$this->updateSortedProductArray($newArray);

		} else {
			$lastrow = end($result);
	  	$data[AM_FIELD_OPTION_VALUE_SORT_ORDER] = (int)$lastrow[AM_FIELD_OPTION_VALUE_SORT_ORDER] + 1;
		}
		// EO mytool

		amDB::perform(TABLE_PRODUCTS_ATTRIBUTES, $data);
	}
	
	/**
	 * Adds an existing option value to a product
	 * @see addAttributeToProduct()
	 */
	function addOptionValueToProduct($get) {
		$this->addAttributeToProduct($get);
	}
	
	/**
	 * Adds a new option value to the database then assigns it to the product
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function addNewOptionValueToProduct($get) {
		$returnInfo = $this->addOptionValue($get);
		$get['option_value_id'] = $returnInfo['selectedOptionValue'];
		$this->addAttributeToProduct($get);
	}
	
	/**
	 * Removes a specific option and its option values from the current product
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function removeOptionFromProduct($get) {
		$this->getAndPrepare('option_id',$get,$optionId);
		amDB::query("delete from ".TABLE_PRODUCTS_ATTRIBUTES." where options_id = '$optionId' and products_id = '$this->intPID'");
// - Zappo - Option Types v2 - Placed Back & Adjusted Sort order from original AttributeManager (Needed this after all ;D)  
		$this->updateSortOrder();
	}
	
	/**
	 * Removes a specific option value from a the current product
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function removeOptionValueFromProduct($get) {
		$this->getAndPrepare('option_id',$get,$optionId);
		$this->getAndPrepare('option_value_id',$get,$optionValueId);
		amDB::query("delete from ".TABLE_PRODUCTS_ATTRIBUTES." where options_id = '$optionId' and options_values_id = '$optionValueId' and products_id = '$this->intPID'");
// - Zappo - Option Types v2 - Removed Sort order from original AttributeManager (No more need for this) 
		$this->updateSortOrder();
	}
	
	
//----------------------------
// Change: Add download attributes function for AM
// @author Urs Nyffenegger ak mytool
// Function: Add, delete and edit Download options
//-----------------------------

	function updateDownloadAttributeToProduct($get) {
		$this->getAndPrepare('option_id',$get,$optionId);
		$this->getAndPrepare('option_value_id',$get,$optionValueId);
		$this->getAndPrepare('products_attributes_filename',$get,$products_attributes_filename);
		$this->getAndPrepare('products_attributes_maxdays',$get,$products_attributes_maxdays);
		$this->getAndPrepare('products_attributes_maxcount',$get,$products_attributes_maxcount);
		$this->getAndPrepare('products_attributes_id',$get,$products_attributes_id);

		amDB::query('update '.TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD.' SET products_attributes_filename=\'' .$products_attributes_filename .'\', products_attributes_maxdays = '.$products_attributes_maxdays.', products_attributes_maxcount='.$products_attributes_maxcount.' where products_attributes_id = '.$products_attributes_id );
	}
	
	function addDownloadAttributeToProduct($get) {
		$this->getAndPrepare('option_id',$get,$optionId);
		$this->getAndPrepare('option_value_id',$get,$optionValueId);
		$this->getAndPrepare('products_attributes_filename',$get,$products_attributes_filename);
		$this->getAndPrepare('products_attributes_maxdays',$get,$products_attributes_maxdays);
		$this->getAndPrepare('products_attributes_maxcount',$get,$products_attributes_maxcount);
		$this->getAndPrepare('products_attributes_id',$get,$products_attributes_id);

		amDB::query('insert into '.TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD.' (products_attributes_id, products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount) values('.$products_attributes_id.',\''.$products_attributes_filename.'\', '.$products_attributes_maxdays.', '.$products_attributes_maxcount.')');
	}
	
	function removeDownloadAttributeToProduct($get) {
		$this->getAndPrepare('option_id',$get,$optionId);
		$this->getAndPrepare('option_value_id',$get,$optionValueId);
		$this->getAndPrepare('products_attributes_id',$get,$products_attributes_id);

		amDB::query('delete from '.TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD.' where products_attributes_id='.$products_attributes_id );
	}
//----------------------------
// EOF Change: download attributes for AM
//-----------------------------

// - Zappo - Option Types v2 - Removed QTPro from original AttributeManager
	/**
	 * Updates the price and prefix in the products attribute table
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $get $_GET
	 * @return void
	 */
	function update($get) {
		
		$this->getAndPrepare('option_id', $get, $optionId);
		$this->getAndPrepare('option_value_id', $get, $optionValueId);
		$this->getAndPrepare('price', $get, $price);
		$this->getAndPrepare('prefix', $get, $prefix);
		$this->getAndPrepare('sortOrder', $get, $sortOrder);
		
		if((empty($price))||($price=='0')){
		  $price='0.0000';
		}else{
		  if((empty($prefix))||($prefix==' ')){
			$prefix='+';
		  }
		}
		
		$data = array( 
			'options_values_price' => $price,
			'price_prefix' => $prefix
		);
// - Zappo - Option Types v2 - Placed Back & Adjusted Sort order from original AttributeManager (Needed this after all ;D)  
		$data[AM_FIELD_OPTION_VALUE_SORT_ORDER] = $sortOrder;
		
		amDB::perform(TABLE_PRODUCTS_ATTRIBUTES,$data, 'update',"products_id='$this->intPID' and options_id='$optionId' and options_values_id='$optionValueId'");

	}
	
	//----------------------------------------------- page actions end
	
	/**
	 * Returns all or the options and values in the database
	 * @access public
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @return array
	 */
	function getAllProductOptionsAndValues($reset = false) {
		if(0 === count($this->arrAllProductOptionsAndValues)|| true === $reset) {
			$this->arrAllProductOptionsAndValues = array();
			
			$allOptionsAndValues = $this->getAllOptionsAndValues();
//----------------------------
// Change: Add download attributes function for AM
// @author Urs Nyffenegger ak mytool
// Function: change query string to add the Download Table fields
//-----------------------------
			$queryString = "select pa.*, pad.products_attributes_filename, pad.products_attributes_maxdays, pad.products_attributes_maxcount from ".TABLE_PRODUCTS_ATTRIBUTES." as pa INNER JOIN ".TABLE_PRODUCTS_OPTIONS." po ON pa.options_id=po.products_options_id";  
			$queryString .= " LEFT JOIN ".TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad ON pa.products_attributes_id = pad.products_attributes_id";
			$queryString .= " where products_id = '$this->intPID' AND language_id=".(int)$this->getSelectedLanaguage()." order by ";
// - Zappo - Option Types v2 - Added Option Types v2 Sort Order, and Removed Sort order from original AttributeManager (No more need for this) 
      $queryString .= "products_options_order, pa.products_attributes_id";
//----------------------------
// EOF Change: download attributes for AM
//-----------------------------
			$query = amDB::query($queryString);
			
			$optionsId = null;
			while($res = amDB::fetchArray($query)) {
				if($res['options_id'] != $optionsId) {
					$optionsId = $res['options_id'];
					$this->arrAllProductOptionsAndValues[$optionsId]['name'] = $allOptionsAndValues[$optionsId]['name'];
				}
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['name'] = $allOptionsAndValues[$optionsId]['values'][$res['options_values_id']];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['price'] = $res['options_values_price'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['prefix'] = $res['price_prefix'];
//----------------------------
// Change: Add download attributes function for AM
// @author Urs Nyffenegger ak mytool
// Function: get the new Attributes
//-----------------------------
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['products_attributes_id'] = $res['products_attributes_id'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['products_attributes_filename'] = $res['products_attributes_filename'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['products_attributes_maxdays'] = $res['products_attributes_maxdays'];
				$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['products_attributes_maxcount'] = $res['products_attributes_maxcount'];
//----------------------------
// EOF Change: download attributes for AM
//-----------------------------
// - Zappo - Option Types v2 - Placed Back & Adjusted Sort order from original AttributeManager (Needed this after all ;D)  
  			$this->arrAllProductOptionsAndValues[$optionsId]['values'][$res['options_values_id']]['sortOrder'] = $res[AM_FIELD_OPTION_VALUE_SORT_ORDER];
			}
		}
		return $this->arrAllProductOptionsAndValues;
	}
	
	function moveOptionUp() {
		$this->moveOption();
	}
	
	function moveOptionDown() {
		$this->moveOption('down');
	}
	
	function moveOption($get) {
		
		$extraValues = $this->getExtraValues($get['gets']);
		$direction = $get['dir'];
		$changes = false;
		$newArray = array();
		
		// Get current State -- is this necessary? or could we take the getAllProductOptionsAndValues?? i'll see later
		$sortedArray = $this->getSortedProductAttributes( AM_FIELD_OPTION_SORT_ORDER );	

		// now create new array with the optionsID unique
		$i =  - 1;
		$firstRow = current($sortedArray);
		$start_ID = $firstRow['options_id'];
		
		reset($sortedArray);
		
		while ( list($key, $val) = each($sortedArray)) {

			if( $val['options_id'] != $start_ID ){
				$i =  - 1;
				$start_ID  = $val['options_id'];
			} 
			
			$i++;
			$optionsArray[ $val['options_id'] ][$i] = $val;
			
		}
		
		// get position so we can swap
		$positionArray = array_keys($optionsArray);
		$position = array_search( (int)$extraValues['option_id'], $positionArray);
		
		if($direction == 'up'){
		
			if( $position > 0 ){
				$changes = true;
				$prevItem = $positionArray[ $position - 1];
				$ThisItem = $positionArray[$position];
				$positionArray[$position] = $prevItem;
				$positionArray[$position - 1] = $ThisItem;
			}
		
		} else {
		
			if( $position <  ( count($positionArray)-1 ) ){
				$changes = true;
				$nextItem = $positionArray[ $position + 1];
				$ThisItem = $positionArray[$position];
				$positionArray[$position] = $nextItem;
				$positionArray[$position + 1] = $ThisItem;
			}
		
		}

		// set new Sortvalues 
		$i =  - 1;
		while ( list($key, $val) = each($positionArray)) {
			while ( list($okey, $oval) = each( $optionsArray[ $val ]) ) {
					$i++;
					$oval[AM_FIELD_OPTION_SORT_ORDER] = $i;
					$newArray[$i] = $oval;
			 }
		}

		// update Database
		if($changes){
			$this->updateSortedProductArray($newArray);
		}
	}
	
	function moveOptionValue($get) {
	
		$extraValues = $this->getExtraValues($get['gets']);
		$direction = $get['dir'];
		$changes = false;
		$sortedArray = array();
		$newArray = array();

		$sortedArray = $this->getSortedProductAttributes( AM_FIELD_OPTION_VALUE_SORT_ORDER );
		
		$i = -1;
		
		// filter array
		while ( list($key, $val) = each($sortedArray) ) {
   			if( $val['options_id'] == $extraValues['option_id'] ){
   				$i++;
   				$newArray[$val[AM_FIELD_OPTION_VALUE_SORT_ORDER]] = $val;
   			}
   		}

		// get first and Last Row, so we can determine lowest and higest Sort order value later
		reset($newArray);
		
		$first = current($newArray);
		$firstSortValue = (int)$first[AM_FIELD_OPTION_VALUE_SORT_ORDER];
		$lastSortValue = $firstSortValue + count($newArray) - 1;
		
		while ( list($key, $val) = each($newArray) ) {
   			if( $val['products_attributes_id'] == $extraValues['products_attributes_id'] ){
    				$startSort = $val[AM_FIELD_OPTION_VALUE_SORT_ORDER];
			}
		}
		
		if($direction == 'up'){
			// ceiling_ only change if its not the top item
			if ($startSort > (int)$firstSortValue ){
				$changes = true;
				$newArray[$startSort][AM_FIELD_OPTION_VALUE_SORT_ORDER] = (int)$startSort - 1;
				$newArray[$startSort-1][AM_FIELD_OPTION_VALUE_SORT_ORDER] = (int)$startSort;
			}
		}else{
			// ceiling only change if its not the bottom item
			if ( $startSort < (int)$lastSortValue ){
				$changes = true;
				$newArray[$startSort][AM_FIELD_OPTION_VALUE_SORT_ORDER] = (int)$startSort + 1;
				$newArray[$startSort+1][AM_FIELD_OPTION_VALUE_SORT_ORDER] = (int)$startSort;
			}
		}
		
		// update Database
		if($changes){
			$this->updateSortedProductArray($newArray);
		}
		
	}
	
	function getExtraValues($gets){
		$arrExtraValues = array();
		$valuePairs = array();
		
		if(strpos($gets,'|')) 
			$valuePairs = explode('|',$gets);
		else 
			$valuePairs[] = $gets;
		
		foreach($valuePairs as $pair)
			if(strpos($pair,':')) {
				list($extraKey, $extraValue) = explode(':',$pair);	
				$arrExtraValues[$extraKey] = $extraValue;
			}
			
		return $arrExtraValues;	
	}
	
	function getSortedProductAttributes( $sortfield ){
	
		$sortedArray = array();
	
// - Zappo - Option Types v2 - Removed Sort order from original AttributeManager (No more need for this)
		$queryString = "select products_attributes_id, options_id, products_options_sort_order" .
						" from ".TABLE_PRODUCTS_ATTRIBUTES.
						" where products_id=".$this->intPID;
						
/*		if( $optionsID > -1){			
			$queryString .=	" AND options_id=".$optionsID;
		}
*/			
		$queryString .=	" ORDER BY ".$sortfield." asc, options_id asc";
		
		$result = amDB::getAll($queryString);
		
		//$i = (int)$result[0][$sortfield];
		$i=0;
		
		while(list($key, $val) = each($result)) {
			// set the sorting new
			$val[AM_FIELD_OPTION_VALUE_SORT_ORDER] = $i;
			$sortedArray[$i] = $val;
			$i++;
		}
		
		return $sortedArray;
	}
	
	
	function updateSortedProductArray($newArray){
	
		reset($newArray);
		while ( list($key, $val) = each($newArray)) {
			if( !empty($val['products_attributes_id'] )){
				amDB::perform(TABLE_PRODUCTS_ATTRIBUTES,$val,'update','products_attributes_id = ' . $val['products_attributes_id'] );
			}
		}
	}
// - Zappo - Option Types v2 - Placed Back & Adjusted Sort order from original AttributeManager (Needed this after all ;D)  
	function updateSortOrder(){
		$newArray =  $this->getSortedProductAttributes( AM_FIELD_OPTION_VALUE_SORT_ORDER );
		$this->updateSortedProductArray( $newArray );
	}
}
?>