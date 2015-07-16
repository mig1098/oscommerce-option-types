<?php
/*
  $Id: option_types.php 2009-06-01 $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2009 AvanOsch for http://shop.crystalcopy.nl

  Released under the GNU General Public License
*/

 // - Zappo - Option Types v2 - Use some easy shorter names for products_options_name values
	$Default = false;  // Set this value to true if current option is Default (drowpdown) (see below)
	$ProdOpt_ID = $products_options_name['products_options_id'];
  $ProdOpt_Name = $products_options_name['products_options_name'];
  $ProdOpt_Comment = $products_options_name['products_options_comment'];
  $ProdOpt_Length = $products_options_name['products_options_length'];
  $products_attribs_query = tep_db_query("select distinct options_values_id, options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . (int)tep_db_input($product_info['products_id']) . "' and options_id = '" . $ProdOpt_ID . "' order by products_options_sort_order");
  $products_attribs_array = tep_db_fetch_array($products_attribs_query);
	// Get Price for Option Values (Except for Multi-Options (Like Dropdown and Radio))
  if ($products_attribs_array['options_values_price'] != '0') {
    $tmp_html_price = ' (' . $products_attribs_array['price_prefix'] . $currencies->display_price($products_attribs_array['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
  } else {
	  $tmp_html_price = '';
	}
	switch ($products_options_name['products_options_type']) {
    case OPTIONS_TYPE_TEXT:
      $tmp_html = '<input type="text" name="id[' . TEXT_PREFIX . $ProdOpt_ID . ']" id="id[' . TEXT_PREFIX . $ProdOpt_ID . ']" size="' . $ProdOpt_Length .'" maxlength="' . $ProdOpt_Length . '"
                             value="' . $cart->contents[$HTTP_GET_VARS['products_id']]['attributes_values'][$ProdOpt_ID] .'"';
      if (OPTIONS_TYPE_PROGRESS == 'Text' || OPTIONS_TYPE_PROGRESS == 'Both') {
        $tmp_html .= 'onKeyDown="textCounter(this,\'progressbar_'. $ProdOpt_ID . '\',' . $ProdOpt_Length . ')"
                               onKeyUp="textCounter(this,\'progressbar_'. $ProdOpt_ID . '\',' . $ProdOpt_Length . ')"
                               onFocus="textCounter(this,\'progressbar_'. $ProdOpt_ID . '\',' . $ProdOpt_Length . ')"> &nbsp; ' . $ProdOpt_Comment . $tmp_html_price .
                              '<div id="counterbar_'. $ProdOpt_ID . '" class="bar"><div id="progressbar_'. $ProdOpt_ID . '" class="progress"></div></div>
                               <script>textCounter(document.getElementById("id[' . TEXT_PREFIX . $ProdOpt_ID . ']"),"progressbar_' . $ProdOpt_ID . '",' . $ProdOpt_Length . ',"counterbar_'. $ProdOpt_ID . '")</script>';
      } else {
        $tmp_html .= '>' . $ProdOpt_Comment . $tmp_html_price;
      } ?>
      <tr>
      <td class="main"><?php echo $ProdOpt_Name . ' :'; ?></td>
      <td class="main"><?php echo $tmp_html;  ?></td>
      </tr> <?php
    break;

    case OPTIONS_TYPE_TEXTAREA:
      $tmp_html = '<textarea wrap="soft" rows="5" name="id[' . TEXT_PREFIX . $ProdOpt_ID . ']" 
                             id="id[' . TEXT_PREFIX . $ProdOpt_ID . ']"';
      if (OPTIONS_TYPE_PROGRESS == 'TextArea' || OPTIONS_TYPE_PROGRESS == 'Both') {
        $tmp_html .= 'onKeyDown="textCounter(this,\'progressbar_'. $ProdOpt_ID . '\',' . $ProdOpt_Length . ')"
                                onKeyUp="textCounter(this,\'progressbar_'. $ProdOpt_ID . '\',' . $ProdOpt_Length . ')"
                                onFocus="textCounter(this,\'progressbar_'. $ProdOpt_ID . '\',' . $ProdOpt_Length . ')">' . 
                                $cart->contents[$HTTP_GET_VARS['products_id']]['attributes_values'][$ProdOpt_ID] . '</textarea>
                                <div id="counterbar_'. $ProdOpt_ID . '" class="bar"><div id="progressbar_'. $ProdOpt_ID . '" class="progress"></div></div>
                                <script>textCounter(document.getElementById("id[' . TEXT_PREFIX . $ProdOpt_ID . ']"),"progressbar_' . $ProdOpt_ID . '",' . $ProdOpt_Length . ',"counterbar_'. $ProdOpt_ID . '")</script>';
      } else {
        $tmp_html .= '>' . $cart->contents[$HTTP_GET_VARS['products_id']]['attributes_values'][$ProdOpt_ID] . '</textarea>';
      } ?>
      <tr>
        <td class="main" colspan="2"><b><?php echo $ProdOpt_Name . ' :</b><br>' . $ProdOpt_Comment . ' ' . $tmp_html_price; ?></b></td>
      </tr>
      <tr>
        <td class="main" colspan="2"><?php echo $tmp_html;  ?></td>
      </tr> <?php
    break;

    case OPTIONS_TYPE_RADIO:
      $tmp_html = '<table>';
      $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$product_info['products_id'] . "' and pa.options_id = '" . $ProdOpt_ID . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $languages_id . "' order by pa.products_options_sort_order");
      while ($products_options_array = tep_db_fetch_array($products_options_query)) {
        if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$ProdOpt_ID]) && ($products_options_array['products_options_values_id'] == $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$ProdOpt_ID])) {
          $checked = true;
        } else {
          $checked = false;
        }
        $tmp_html .= '<tr><td class="main">';
        $tmp_html .= tep_draw_radio_field('id[' . $ProdOpt_ID . ']', $products_options_array['products_options_values_id'], $checked);
        $tmp_html .= $products_options_array['products_options_values_name'];
        if ($products_options_array['options_values_price'] != '0') {
          $tmp_html .= ' (' . $products_options_array['price_prefix'] . $currencies->display_price($products_options_array['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .')&nbsp';
        }
        $tmp_html .= '</tr></td>';
      }
      $tmp_html .= '</table>'; ?>
      <tr>
        <td class="main"><?php echo $ProdOpt_Name . ' :<br><small>' . $ProdOpt_Comment . '</small>'; ?></td>
        <td class="main"><?php echo $tmp_html;  ?></td>
      </tr> <?php
    break;

    case OPTIONS_TYPE_CHECKBOX:
      if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$ProdOpt_ID])) {
        $checked = true;
      } else {
        $checked = false;
      }
      $tmp_html = tep_draw_checkbox_field('id[' . $ProdOpt_ID . ']', $products_attribs_array['options_values_id'], $checked) . ' &nbsp; ';
      $tmp_html .= $ProdOpt_Comment ;
      $tmp_html .= $tmp_html_price; ?>
      <tr>
        <td class="main"><?php echo $ProdOpt_Name . ' :'; ?></td>
        <td class="main"><?php echo $tmp_html;  ?></td>
      </tr> <?php
    break;

    case OPTIONS_TYPE_FILE:
      $number_of_uploads++;
  		//BOF - Zappo - Option Types v2 - Added dropdown with previously uploaded files
			if ($old_uploads == true) unset($uploaded_array);
      $uploaded_array[] = array('id' => '', 'text' => TEXT_NONE);
      $uploaded_files_query = tep_db_query("select files_uploaded_name from " . TABLE_FILES_UPLOADED . " where sesskey = '" . tep_session_id() . "' or customers_id = '" . (int)$customer_id . "'");
      while ($uploaded_files = tep_db_fetch_array($uploaded_files_query)) {
        $uploaded_array[] = array('id' => $uploaded_files['files_uploaded_name'], 'text' => $uploaded_files['files_uploaded_name'] . ($tmp_html_price ? ' - ' . $tmp_html_price : ''));
				$old_uploads = true;
			}
      $tmp_html = '<input type="file" name="id[' . TEXT_PREFIX . $ProdOpt_ID . ']">' .         // File field with new upload
      tep_draw_hidden_field(UPLOAD_PREFIX . $number_of_uploads, $ProdOpt_ID);    // Hidden field with number of this upload (for this product)
			$tmp_html .= $tmp_html_price;
			if	($old_uploads == true) $tmp_html .= '<br>' . tep_draw_pull_down_menu(TEXT_PREFIX . UPLOAD_PREFIX . $number_of_uploads, $uploaded_array, $cart->contents[$HTTP_GET_VARS['products_id']]['attributes_values'][$ProdOpt_ID]);
	    //EOF - Zappo - Option Types v2 - Added dropdown with previously uploaded files ?>
      <tr>
        <td class="main"><?php echo $ProdOpt_Name . ' :' . (($old_uploads == true) ? '<br>' . TEXT_PREV_UPLOADS . ': ' : ''); ?></td>
        <td class="main"><?php echo $tmp_html;  ?></td>
      </tr> <?php
    break;

//BOF - Zappo - Added Image Selector Option
    case OPTIONS_TYPE_IMAGE:
      $Image_Opticount_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id='" . (int)$product_info['products_id'] . "' and options_id ='" . (int)$ProdOpt_ID . "'");
      $Image_Opticount = tep_db_fetch_array($Image_Opticount_query);
      $Image_displayed = 0;
      $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$product_info['products_id'] . "' and pa.options_id = '" . (int)$ProdOpt_ID . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order");
      while ($products_options = tep_db_fetch_array($products_options_query)) {
        $pOptValName = $products_options['products_options_values_name'];
        $Image_displayed++;
        if ($products_options['options_values_price'] != '0') {
          $option_price = ' (' . $products_options['price_prefix'] . ' ' . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
        } else {
          $option_price = '';
        }
        $Image_Dropdown_ID = 'id[' . $ProdOpt_ID . ']';
        $Image_Name = (OPTIONS_TYPE_IMAGENAME == 'Name') ? $products_options['products_options_values_name'] : $products_options['products_options_values_id'];
        $Real_Image_Name = OPTIONS_TYPE_IMAGEPREFIX . $Image_Name . ((OPTIONS_TYPE_IMAGELANG == 'Yes') ? '_'.$languages_id : '') . '.jpg';
        if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$ProdOpt_ID]) && ($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$ProdOpt_ID] == $products_options['products_options_values_id'])) {
          $Image_Dropdown[$product_info['products_id']] .= '<option value="' . $products_options['products_options_values_id'] . '" SELECTED>' . $pOptValName . $option_price . '</option>';
          $First_ImageText[$product_info['products_id']] = '<img src="' . OPTIONS_TYPE_IMAGEDIR . $Real_Image_Name . '" alt="'.$pOptValName.'" title="'.$pOptValName.'">';
          $ImageText[$product_info['products_id']] .= '"<img src=\"' . OPTIONS_TYPE_IMAGEDIR . $Real_Image_Name . '\" alt=\"'.$pOptValName.'\" title=\"'.$pOptValName.'\">"';
        } else {
          $Image_Dropdown[$product_info['products_id']] .= '<option value="' . $products_options['products_options_values_id'] . '">' . $pOptValName . $option_price . '</option>';
          $ImageText[$product_info['products_id']] .= '"<img src=\"' . OPTIONS_TYPE_IMAGEDIR . $Real_Image_Name . '\" alt=\"'.$pOptValName.'\" title=\"'.$pOptValName.'\">"';
          if ($First_ImageText[$product_info['products_id']] == '' && $Image_displayed == 1) $First_ImageText[$product_info['products_id']] = '<img src="' . OPTIONS_TYPE_IMAGEDIR . $Real_Image_Name . '" alt="'.$pOptValName.'" title="'.$pOptValName.'">';
        }
        // BOF - Zappo - PreLoad the Images
        if ($Image_displayed == 1) echo '<div id="ImagePreload">';
        echo '<img src="' . OPTIONS_TYPE_IMAGEDIR . $Real_Image_Name . '" alt="'.$pOptValName.'" title="'.$pOptValName.'">';
        if ($Image_displayed != $Image_Opticount['total']) {
          $ImageText[$product_info['products_id']] .= ',';
        } else { // - Zappo - PreLoad the Images - Close Div...
					echo '</div>'; 
				}
				// EOF - Zappo - PreLoad the Images
      }
      $ImageSelector_Name = $ProdOpt_Name . ': <script language="JavaScript" type="text/JavaScript">var ImageText'.$product_info['products_id'] . ' = new Array(' . $ImageText[$product_info['products_id']] . ')</script>';
      $ImageSelector_Dropdown = '<select name="' . $Image_Dropdown_ID . '" onChange="document.getElementById(\'ImageSelect' . $product_info['products_id'] . '\').innerHTML=ImageText'.$product_info['products_id'].'[this.selectedIndex];">' . $Image_Dropdown[$product_info['products_id']] . '</select> ' . $ProdOpt_Comment;
      ?>
      <tr>
        <td class="main"><?php echo $ImageSelector_Name; ?></td>
        <td class="main"><?php echo $ImageSelector_Dropdown; ?></td>
      </tr>
      <tr>
        <td width="100%" class="main" colspan="2">
          <center><?php echo '<div class="main" id="ImageSelect' . $product_info['products_id'] . '">' . $First_ImageText[$product_info['products_id']] . '</div>';?></center>
        </td>
      </tr>
      <?php
    break;
//EOF - Zappo - Added Image Selector Option

    default:
    $Default = true;  // Set this value to check if current option is Default (drowpdown)
    // - Zappo - Option Types v2 - Default action is (standard) dropdown list. If something is not correctly set, we should always fall back to the standard.
  }
?>
