<?php
class Prominc_PromincLinkOrder_Block_Adminhtml_Admin extends Mage_Adminhtml_Block_Widget_Grid_Container
{


  /**
  *
  */
  public function __construct()
  {
    $this->_blockGroup = 'prominclinkorder';
    $this->_controller = 'adminhtml_admin';
    $this->_headerText = Mage::helper('prominclinkorder')->__('Link Customer to Order');
    parent::__construct();
    $this->_removeButton('add');
  }


}