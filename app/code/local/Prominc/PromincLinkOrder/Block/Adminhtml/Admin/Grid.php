<?php
class Prominc_PromincLinkOrder_Block_Adminhtml_Admin_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

  /**
  *
  */
  public function __construct() {
    parent::__construct();
    $this->setId('prominclinkorderAdminhtmlAdminGrid');
    $this->setTemplate('prominclinkorder/admin/find.phtml');
    $this->setData('store', Mage::registry('store') )
      ->setData('email', Mage::registry('email') )
      ->setData('order_id', Mage::registry('order_id') );
    $this->setUseAjax(false);
  }


}
