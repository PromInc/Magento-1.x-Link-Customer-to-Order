<?php
class Prominc_PromincLinkOrder_Model_Mysql4_PromincLinkOrder extends Mage_Core_Model_Mysql4_Abstract
{


  protected function _construct() {
    $this->_init('prominclinkorder/order', 'entity_id');
  }


}