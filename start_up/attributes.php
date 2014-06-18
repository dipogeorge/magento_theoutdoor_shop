<?php 

/*

 * Developer: Dipo George

 * Function: Update Configurable Product

 * @category    The Outdoor Shop

 * @package     Configurable Product Update

 * @copyright   Copyright (c) 2014 The Outdoor Shop. (http://www.theoutdoorshop.com)

 */

    ini_set('max_execution_time',0);

    require_once ("../app/Mage.php");

    Mage::init();

    umask(0);

# we are receiving the size and the color attributes here, and it's options
# as the separate array
 
$att_size = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product','size');
//$att_sizes->__getAttList('size');
$att_color = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product','color');
//$att_colors = $this->__getAttList('color');


echo '<pre>';
//print_r($att_size);
print_r($att_color);
//echo $att_sizes->__getAttList('size');
exit;

//$mpr = <creation of the configurable product as in the example above>;
//$sizes = <get the sizes array from import file>;
//$colors = <get the colors array from import file>;
$size_values = $color_values = $all_child_products = array();
foreach($sizes as $size)
  foreach($colors as $color) {
    $variantcode = $productcode.'_'.$color['option_code'].'_'.$size['option_name'];
    //$pr = <creation of the child product here>;

# we have to create the new option in the attribute and re-create 
# our cache array if there are no appropriate option in the cache
    if (!isset($att_sizes[$size['option_name']])) $att_sizes = $this->__addAtt($att_size->getId(), 'size', $size['option_name']);
    if (!isset($att_colors[$color['option_name']])) $att_colors = $this->__addAtt($att_color->getId(), 'color', $color['option_name']);

# preparing of the sub-products array 
    $all_child_products[$pr->getId()] = array(
      $sv = array('attribute_id' => $att_size->getData('attribute_id'), 'label' => $size['option_name'], 'value_index' => $att_sizes[$size['option_name']], 'pricing_value' => 0, 'is_percent' => 0),
      $cv = array('attribute_id' => $att_color->getData('attribute_id'), 'label' => $color['option_name'], 'value_index' => $att_colors[$color['option_name']], 'pricing_value' => 0, 'is_percent' => 0),
      );
# and in the save time we are preparing the arrays for the 
# configurable product values.
      $size_values[] = $sv;
      $color_values[] = $cv;

# we are set the color and size for the child product,
# and we have make it by attribute id
      $pr->setData('size', $att_sizes[$size['option_name']]);
      $pr->setData('color', $att_colors[$color['option_name']]);

      $pr->save();
  }
# special configuration actions
$mpr->setConfigurableProductsData($all_child_products);
$mpr->setConfigurableAttributesData($tmp = array(
  array_merge($att_size->getData(), array('label' => '', 'values' => $size_values)),
  array_merge($att_color->getData(), array('label' => '', 'values' => $color_values))
));
$mpr->setCanSaveConfigurableAttributes(true);
$mpr->save();

$pr->save();


?>