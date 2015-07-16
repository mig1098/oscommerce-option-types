<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  //ROYALTY IDS BEGIN
  $royalty_active = false;
  $royalty_ids = array(29);
  //ROYALTY IDS END
  if (!isset($HTTP_GET_VARS['products_id'])) {
    tep_redirect(tep_href_link(FILENAME_DEFAULT));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);

  require(DIR_WS_INCLUDES . 'template_top.php');

  if ($product_check['total'] < 1) {
?>

<div class="contentContainer">
  <div class="contentText">
    <?php echo TEXT_PRODUCT_NOT_FOUND; ?>
  </div>

  <div style="float: right;">
    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link(FILENAME_DEFAULT)); ?>
  </div>
</div>

<?php
  } else {
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);

    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
      $products_price = '<del>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</del> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    } else {
      $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
    }

    if (tep_not_null($product_info['products_model'])) {
      $products_name = $product_info['products_name'] . '<br /><span class="smallText">[' . $product_info['products_model'] . ']</span>';
    } else {
      $products_name = $product_info['products_name'];
    }
?>

<?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>

<div>
  <h1 style="float: right;" class="lbl-price"><?php echo $products_price; ?></h1>
  <h1><?php echo $products_name; ?></h1>
</div>

<div class="contentContainer">
  <div class="contentText">

<?php
    if (tep_not_null($product_info['products_image'])) {
      $photoset_layout = '1';

      $pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$product_info['products_id'] . "' order by sort_order");
      $pi_total = tep_db_num_rows($pi_query);

      if ($pi_total > 0) {
        $pi_sub = $pi_total-1;

        while ($pi_sub > 5) {
          $photoset_layout .= 5;
          $pi_sub = $pi_sub-5;
        }

        if ($pi_sub > 0) {
          $photoset_layout .= ($pi_total > 5) ? 5 : $pi_sub;
        }
?>

    <div id="piGal">

<?php
        $pi_counter = 0;
        $pi_html = array();

        while ($pi = tep_db_fetch_array($pi_query)) {
          $pi_counter++;

          if (tep_not_null($pi['htmlcontent'])) {
            $pi_html[] = '<div id="piGalDiv_' . $pi_counter . '">' . $pi['htmlcontent'] . '</div>';
          }

          echo tep_image(DIR_WS_IMAGES . $pi['image'], '', '', '', 'id="piGalImg_' . $pi_counter . '"');
        }
?>

    </div>

<?php
        if ( !empty($pi_html) ) {
          echo '    <div style="display: none;">' . implode('', $pi_html) . '</div>';
        }
      } else {
?>

    <div id="piGal">
      <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name'])); ?>
    </div>

<?php
      }
    }
?>
<?php if(!in_array($HTTP_GET_VARS['products_id'],$royalty_ids)){ ?>
<script type="text/javascript">
$(function() {
    
    if($('#piGal').length > 0){ 
      $('#piGal').css({
        'visibility': 'hidden'
      });
    
      $('#piGal').photosetGrid({
        layout: '<?php echo $photoset_layout; ?>',
        width: '250px',
        highresLinks: true,
        rel: 'pigallery',
        onComplete: function() {
          $('#piGal').css({ 'visibility': 'visible'});
    
          $('#piGal a').colorbox({
            maxHeight: '90%',
            maxWidth: '90%',
            rel: 'pigallery'
          });
    
          $('#piGal img').each(function() {
            if(typeof $(this).attr('id') != 'undefined'){
                var imgid = ($(this).attr('id')).substring(9);
        
                if ( $('#piGalDiv_' + imgid).length ) {
                  $(this).parent().colorbox({ inline: true, href: "#piGalDiv_" + imgid });
                }
            }
          });
        }
      });
    }
    //test
    /*(function(){
        var _select = document.getElementsByTagName('select');
        var pattern = /\<select\sname="(.*?)"\>\<option\svalue="(.*?)".*\>(rdata)\<\/option\>\<\/select\>|\<select\sname="(.*?)"\>\<option\svalue="(.*?)".*\>(rimage)\<\/option\>\<\/select\>/ig;
        var _replace = '<input type="text" name="$1" value="number|one|23">';
        for(var i=0; i<_select.length; i++){
            console.log(i);
            var $select = _select[i];
            console.log($select.outerHTML);
            $select.outerHTML = $select.outerHTML.replace(pattern,_replace);
        }
        
    })();*/
});
</script>
<?php } ?>
<?php echo stripslashes($product_info['products_description']); ?>

<?php
    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {
?>

    <p><?php echo TEXT_PRODUCT_OPTIONS; ?></p>

    <p>
<?php
      //$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name, popt.products_options_type, popt.products_options_length, popt.products_options_comment from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_order, popt.products_options_name");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        // - Zappo - Option Types v2 - Include option_types.php - Contains all Option Types, other than the original Drowpdown...
        include(DIR_WS_MODULES . 'option_types.php');
        if ($Default == true) {
            $products_options_array = array();
            $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");
            while ($products_options = tep_db_fetch_array($products_options_query)) {
              $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
              if ($products_options['options_values_price'] != '0') {
                $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
              }
            }
    
            if (is_string($HTTP_GET_VARS['products_id']) && isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
              $selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
            } else {
              $selected_attribute = false;
            }
    ?>
          <strong><?php echo $products_options_name['products_options_name'] . ':'; ?></strong><br /><?php echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute); ?><br />
<?php
        }
      }
?>
    </p>

<?php
    }
?>

    <div style="clear: both;"></div>

<?php
    if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
?>

    <p style="text-align: center;"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?></p>

<?php
    }
?>

  </div><!--EXT contentText CLASS-->
  <div class="contentTextAfter"> </div>

<?php
    $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and reviews_status = 1");
    $reviews = tep_db_fetch_array($reviews_query);
?>

  <div class="buttonSet">
    <span class="buttonAction"><?php echo '<span>qty: </span>' . tep_draw_input_field('quantity[]', '1', 'size="5" id="quantity"') . ' ' .tep_draw_hidden_field('products_id[]', $product_info['products_id']) . tep_draw_button(IMAGE_BUTTON_IN_CART, 'cart', null, 'primary'); ?></span>
    
    <?php echo tep_draw_button(IMAGE_BUTTON_REVIEWS . (($reviews['count'] > 0) ? ' (' . $reviews['count'] . ')' : ''), 'comment', tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params())); ?>
  </div>

<?php
    if ((USE_CACHE == 'true') && empty($SID)) {
      echo tep_cache_also_purchased(3600);
    } else {
      include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
    }
?>

</div>

</form>
<!--
------------------------
 ROYALTY SCRIPT BEGIN
------------------------
-->
<?php
 if(in_array($HTTP_GET_VARS['products_id'],$royalty_ids) && $royalty_active==true){
 ?>
 <script type="text/javascript">
    var ll = ['<?php echo tep_href_link('ext/royalty/royalty.css'); ?>','<?php echo tep_href_link('ext/royalty/jquery.Jcrop.css'); ?>'];
    var jj = ['<?php echo tep_href_link('ext/royalty/jquery.Jcrop.js'); ?>','<?php echo tep_href_link('ext/royalty/mig.crop.js'); ?>','<?php echo tep_href_link('ext/royalty/royalty.utils.js'); ?>','<?php echo tep_href_link('ext/royalty/jquery.grid-a-licious.min.js'); ?>'];
    for(var i=0; i<ll.length; i++){
        var l1 = document.createElement("link");
        l1.type = "text/css";l1.rel = "stylesheet";l1.href = ll[i];document.getElementsByTagName('head')[0].appendChild(l1);  
    };
    for(var i=0; i<jj.length; i++){
        var j1 = document.createElement("script");
        j1.type = "text/javascript";j1.src = jj[i];document.getElementsByTagName('head')[0].appendChild(j1); 
    }
    (function(){
        var css = '.jcrop-vline,.jcrop-hline {background: #ffffff url(<?php echo tep_href_link('ext/royalty/jcrop.gif'); ?>);';
        var st = document.createElement("style");
        st.type = "text/css";
        st.appendChild(document.createTextNode(css));
        document.getElementsByTagName('head')[0].appendChild(st);
    })();
    
    // hide price product
    document.getElementsByClassName('lbl-price')[0].style.display = "none";
    
(function($){
    waitToLoad=function(time,callback){
        //console.log(time);
        if(time==0){
            return false;
        };
        if(typeof Royalty == 'undefined'){
            setTimeout(function(){
                waitToLoad(time-1,callback);
            },time);
        }else{
            callback();
        }
    };
    waitToLoad(100,function(){
          //INIT IMAGES  
          //images.vars = {domain:'{{ shop.domain }}',url:'{{ shop.url }}',email:'{{ shop.email }}'};//for shopify store file
          
          Royalty.product.vars = {
            domain:'http://apolo2.mybigcommerce.com',
            url:'http://apolo2.mybigcommerce.com',
            imgUrl:{getLatest:'//apolomultimedia.us/oscommerce-2.3.4/catalog/royalty_osc/pages/json-latest-images',
                    getData  :'//apolomultimedia.us/oscommerce-2.3.4/catalog/royalty_osc/pages/image-info?image_id',
                    search   :'//apolomultimedia.us/oscommerce-2.3.4/catalog/royalty_osc/pages/json-search?keyword',
                    catsearch:'//apolomultimedia.us/oscommerce-2.3.4/catalog/royalty_osc/pages/json-catsearch?keyword',
                    bgproduct:'//apolomultimedia.us/oscommerce-2.3.4/catalog/royalty_osc/pages/json-product',
                    shorten  :'//apolomultimedia.us/oscommerce-2.3.4/catalog/royalty_osc/pages/ushorten-set'
            },
            properties:{img_url:'#rimage',img_coords:'#rcoords',img_data:'#rdata',credits:'#rcredits'},
            email:'programmer1@apolomultimedia.com',// ADMIN EMAIL
            offset:'.jcrop-holder', //not used
            crop_content:'#cropper-content',
            target:'img#target',
            ini:true,
            variant_id:(function(){
                var id = $('#product-select option:selected').val();
                if(id){
                    return id;
                }else{
                    return 0;
                }
            })(),
            img_content:'#royalty-images .row',
            searchinput:'#csearch',
            realsize:{w:'#real-width',h:'#real-height'},
            creditProduct:{
                id:'30',
                qty:1,
                price:2,
                properties:{},
                callback:function(){//when set new measures
                    var q = parseInt(Royalty.product.vars.creditProduct.qty);
                    var p = parseFloat(Royalty.product.vars.creditProduct.price);
                    var r = p*q;
                    var c = Royalty.product.vars.creditProduct.properties.credits;
                    var l = Royalty.product.vars.creditProduct.properties.label;
                    $(Royalty.product.vars.properties.credits).val(c+'|'+l);
                    $('#image-price-preview').html('<small>$'+r.toFixed(2)+'</small>');
                }
            }
          };
          
          //bigcommerce product data
          Royalty.product.bgcredit=function(callback){
              var Url=Royalty.product.vars.imgUrl.bgproduct;
              params = {domain:(Royalty.product.vars.domain).replace(/http:\/\/|https:\/\//,''),product_id:Royalty.product.vars.creditProduct.id};
              var credit = 0;
               $.ajax({
                  url :Url,
                  data: params,
                  type: 'GET',
                  async: false,
                  jsonpCallback: 'jsonp_callback2',
                  contentType: "application/json",
                  dataType: 'jsonp',
                  success: function(r) {
                      if(typeof callback === 'function'){
                        callback(r);
                      }
                  },
                  error:function(jqXHR){
                    alert(jqXHR);
                  }
              });
          }
          //console.log(Product);
          Royalty.product.bgproduct=function(callback){
              var Url=Royalty.product.vars.imgUrl.bgproduct;
              params = {domain:(Royalty.product.vars.domain).replace(/http:\/\/|https:\/\//,''),product_id:Product.id};
              $.ajax({
                           type: 'GET',
                           url: Url,
                           data: params,
                           async: false,
                           jsonpCallback: 'jsonp_callback2',
                           contentType: "application/json",
                           dataType: 'jsonp',
                           success: function(r) {
                               if(r.bool == false){ alert(r.response); return false; }
                               $('.product_title').val(r.product.name);
                               $('.royalty_prod_id').val(r.product.id);
                               $('#unit-price').val(r.product.price);
                               for(var i=0; i < (r.product.options).length; i++){
                                   $('#'+r.product.options[i].display_name).attr('name','attribute['+r.product.options[i].id+']');
                               }
                               if(typeof callback === 'function'){
                                   callback();
                               }
                              //console.log(r);
                           },
                           error: function(jqXHR,textStatus,errorThrown ) {
                                //$('#mig-msg').hide();
                                alert(jqXHR+' '+textStatus+' '+errorThrown);
                           }
              });
          }
          //cropper
          var product_price = "<?php echo preg_replace('/[^0-9.]/i','',$products_price); ?>";
          $crop = new cropper({
              ids:{target:'#target',coords:'#coords',realsize:{w:'#real-width',h:'#real-height'},label:{w:'.mxw',h:'.mxh',mw:'.minw',mh:'.minh'}},
              plabel: {label:'#price-preview',inputQty:'#quantity'},
              imgcoords:Royalty.product.vars.properties.img_coords,
              price: parseFloat(product_price),
              currency: '$',
              measure:{type:'#measure_type',_default:'inch'},
              ranges:{minwidth:'1',minheight:'1',maxwidth:'100',maxheight:'100'} 
          });
          
          //get images from app shopify
          
          Royalty.product.osc_html = function(){
              var args = arguments[0] || {};
              var content = args.content;
              var callback = args.callback;
              var contentContainer = args.contentC;
              var inputs = args.inputs;
              var cartUrl = 'http://localhost/oscommerce-2.3.4/';//OSCOMMERCE BASE_URL
              var url = document.URL;
              return {
                  addhtml:function(){
                      /*hide input qty*/
                      $(inputs.quantity).prev().filter('span').hide();
                      $(inputs.quantity).hide();
                      //
                       var img = '<div class="left-content">' +
                                     '<div id="cropper-content" class="span6">' +
                                        '<img src="" id="target" alt="" data="demo_files/abstractzen120500024.jpg"/>' +
                                     '</div>' +
                                 '</div>';
                       $(content).html(img);
                       var properties = '<!--ROYALTY HTML-->'+
                            '<div class="dimensions">'+
                            '<div class="clabel-1"><label>width:</label></div> '+
                            '<div class="clabel-2"><input type="text" id="real-width" value="" />'+
                                '<div style="display: inline-block;line-height: 14px;">'+
                                    '<span class="mxw"></span>'+
                                    '<span class="minw"></span>'+
                                '</div>'+
                            '</div>'+
                            '<div class="clabel-1"><label>height:</label></div> <div class="clabel-2"><input type="text" id="real-height" value="" />'+
                                '<div style="display: inline-block;line-height: 14px;">'+
                                    '<span class="mxh"></span>'+
                                    '<span class="minh"></span>'+
                                '</div>'+
                            '</div>'+
                            '<div class="clabel-1"><span>Unit: </span></div>'+
                            '<div class="clabel-2">'+
                            '<select id="measure_type" class="select" style="width:80px;padding:0px; padding-top:2px;">'+
                                '<option value="inch" selected="selected">inch</option>'+
                                '<option value="cm">cm</option>'+												
                            '</select>'+
                            '</div>'+
                          '</div>'+
                          '<div class="purchase">'+
                              '<div>'+
                              '<span>Service Price: </span>'+
                              '<h2 id="price-preview" class="price"></h2>'+
                              '</div>'+
                              '<div>'+
                              '<span>Image Price: </span>'+
                              '<h2 id="image-price-preview" class="price"></h2>'+
                              '</div>'+
                          '</div>'+
                          '<input type="hidden" id="unit-price" value="0" />'+
                          '<input type="hidden" id="rimage" name="id[txt_8]" class="properties[image]" value="" />'+
                          '<input type="hidden" id="rdata" name="id[txt_9]" class="properties[data]" value="" />'+
                          '<input type="hidden" id="rcoords" name="id[txt_11]" class="properties[coords]" value="" />'+
                          '<input type="hidden" id="rcredits" name="id[txt_12]" class="properties[credits]" value="" />'+
                          '<!--ROYALTY HTML END-->';
                       $(content).append(properties);
      
                       var loadimages = '<!--ROYALTY LOAD IMAGES-->'+
                            '<div class="clearfix"></div>'+
                            '<div id="royalty-images">'+
                                '<!--BEGIN SEARCH-->'+
                                '<div id="search-content" class="clearfix">'+
                                '<div class="r-loader" style="display: none; position:absolute; width:100%; text-align: center;"><img src="<?php echo tep_href_link('ext/royalty/ajax-loader.gif'); ?>" data-url="<?php echo tep_href_link('ext/royalty/ajax-loader.gif'); ?>" /></div>'+
                                    '<div class="" style="float: right;">'+
                                        '<input type="text" id="csearch" value="" />'+
                                        '<input type="button" id="csearch-input" class="btn" value="Search" />'+
                                    '</div>'+
                                '</div>'+
                                '<!-- END SEARCH-->'+
                                '<div class="row"><!--images from royalty--></div>'+
                            '</div>'+
                            '<div id="load-total" style="display: block;">'+
                                '<div class="r-total"><img src="<?php echo tep_href_link('ext/royalty/ajax-loader.gif'); ?>" data-url="<?php echo tep_href_link('ext/royalty/ajax-loader.gif'); ?>" /></div>'+
                                '<div class="bck-total"></div>'+
                            '</div>'+
                            '<!--ROYALTY LOAD IMAGES-->';
                       $(contentContainer).after(loadimages);
                       
                       var frm = '<form id="coords"'+
                            'class="coords"'+
                            'onsubmit="return false;"'+
                            'action="http://example.com/post.php" style="display: none;">'+
                            '<div class="inline-labels">'+
                            '<label>X1 <input type="text" size="4" id="x1" name="x1" /></label>'+
                            '<label>Y1 <input type="text" size="4" id="y1" name="y1" /></label>'+
                            '<label>X2 <input type="text" size="4" id="x2" name="x2" /></label>'+
                            '<label>Y2 <input type="text" size="4" id="y2" name="y2" /></label>'+
                            '<label>W <input type="text" size="4" id="w" name="w" /></label>'+
                            '<label>H <input type="text" size="4" id="h" name="h" /></label>'+
                            '</div>'+
                          '</form>';
                       $('form').after(frm);
                  },
                  createImageInputs:function(_form,data){
                       var inputs = '<input type="text" name="products_id[]" value="'+data.id+'" />'+
                           '<input type="text" name="quantity[]" value="'+data.qty+'" />';
                       var content = document.createElement('div');//'<div class="add_img_price"></div>';
                       content.setAttribute('class','add_img_price');
                       
                       if($('.add_img_price').length > 0){
                           $('.add_img_price').html(inputs);
                       }else{
                           $(_form).append(content);
                           $('.add_img_price').html(inputs);
                       }
                  },
                  sendAdditionalProduct:function(data,callback){
                    var path = 'product_info.php?products_id='+data.id+'&action=add_product';
                    params = {products_id:[data.id],quantity:[data.qty]};
                    //console.log(cartUrl+path);
                    $.ajax({
                        url:path,
                        data:params,
                        type:'POST',
                        async: false,
                        success: function(r) {
                            if(typeof callback === 'function'){
                                callback(r);
                            }
                        },
                        error:function(){
                            
                        }
                    });
                  },
                  init:function(){
                        this.addhtml();
                        if(typeof callback === 'function'){
                            callback();
                        }
                  }
              };
              
          };
          Royalty.product.osc_html({
               content:'.contentTextAfter',//contentText
               contentC:'.contentContainer',
               inputs:{quantity:'#quantity'},
               callback:function(){
                   $('.contentText').html('');//CLEAR PRODUCT DESCRIPTION DIV
                   Royalty.product.init(); //INIT  
               }
          }).init();
          /*
          Royalty.product.bgproduct(function(){ 
              $crop.m.updatePrice(parseFloat($('#unit-price').val()));
              Royalty.product.init(); 
          });
          */
          //Search
          $('#csearch-input').click(function(){
                          var keyword = $('#csearch').val();
                          if(keyword != ''){
                              Royalty.product.search('#royalty-images .row',keyword);
                          }
          });
          $('#csearch').keypress(function(e){
              var keyword = $(this).val();
              if(e.which == 13){
                  Royalty.product.search('#royalty-images .row',keyword);
              }
          });
          //-->
          jMiglioUtil.onresize('.r-total');
          //validate digits
          $('#real-width, #real-height').keypress(function(event){
              var value = $(this).val();
              var keycode = event.which;
              var id = ('#'+$(this).attr('id'));
              if(jMiglioUtil.withKeyEvent(keycode,value,id)){
                    event.preventDefault();
              }
          })
          //OSCOMMERCE PRODUCT INFO SUBMIT
          var bool=true;
          $('form[name=cart_quantity]').on('submit',function(e){
              
              //Royalty.product.osc_html().createImageInputs('form[name=cart_quantity]',Royalty.product.vars.creditProduct);
              if(bool==true){
                  e.preventDefault();
                  Royalty.product.osc_html().sendAdditionalProduct(Royalty.product.vars.creditProduct,function(){
                       bool=false
                       $('form[name=cart_quantity]').submit();
                  });
              }
          });

          
          
          //$('#productDetailsAddToCartForm').removeAttr("disabled");
            $(document).off('click','.AddCartButton').on('click','.AddCartButton',function(e){
                Royalty.product.setLineProperties();
                var qty = Royalty.product.vars.creditProduct.qty;
                var id  = Royalty.product.vars.creditProduct.id;
                Royalty.product.vars.creditProduct.properties.pair = $(Royalty.product.vars.properties.img_data).val();
                var properties = Royalty.product.vars.creditProduct.properties;
                //console.log(properties);
                //console.log(Royalty.product.vars.creditProduct);
                Royalty.product.bgcredit(function(credit){
                    //console.log(credit);
                    if(credit.bool == false){ alert('Error:'+credit.response); return false; }
                    RoyaltyUtils.addItem(qty,id,properties,credit.product.options,function(){
                        $('#productDetailsAddToCartForm').submit();
                    });
                });
                
                
            });
    });
})(jQuery);
</script>
<?php   
 }
?>
<!--
--------------------
 ROYALTY SCRIPT END 
--------------------
-->
<?php
  }

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
