<?php
class Prominc_PromincLinkOrder_Adminhtml_Prominclinkorder_ProminclinkorderadminController extends Mage_Adminhtml_Controller_Action
{


  protected $_modelName = 'admin';


  /**
  *
  */
  protected function _initAction()
  {
    $this->loadLayout()
       ->_setActiveMenu('promincparentmenu/prominclinkorder');
    return $this;
  }


  /**
  * Display a form to enter in the required information to make a link
  */
  public function indexAction()
  {
    /* Get parameters */
    $store     = $this->getRequest()->getParam('s');
    $email     = $this->getRequest()->getParam('e');
    $order_id     = $this->getRequest()->getParam('o');

    /* Register parameters */
    Mage::register('store', $store);
    Mage::register('email', $email);
    Mage::register('order_id', $order_id);

    $this->_initAction()
      ->_addContent($this->getLayout()->createBlock('prominclinkorder/adminhtml_' . $this->_modelName))
      ->renderLayout();
  }



  /**
  * Preview the customer to order relationship
  * Display customer and order information for manual review
  * Do some automated checks to ensure this association is allowed/ok
  */
  public function previewAction()
  {
    /* Get parameters */
    $store     = $this->getRequest()->getParam('s');
    $email     = $this->getRequest()->getParam('e');
    $order_id     = $this->getRequest()->getParam('o');
    $saved     = $this->getRequest()->getParam('saved');

    /* Register parameters */
    Mage::register('store', $store);
    Mage::register('email', $email);
    Mage::register('order', Mage::getModel('sales/order')->loadByIncrementId( $order_id ) );
    Mage::register('order_id', $order_id);
    Mage::register('saved', $saved);

    /* Load page */
    $this->_initAction();
    $this->_addContent($this->getLayout()->createBlock('prominclinkorder/adminhtml_' . $this->_modelName . '_preview'));
    $this->renderLayout();
  }


  /**
  * Update the database with the customer to order association
  * Redirects to the preview page with success message
  */
  public function processAction()
  {
    /* Get parameters */
    $cid     = $this->getRequest()->getParam('cid');
    $oid     = $this->getRequest()->getParam('oid');
    $email     = $this->getRequest()->getParam('c_e');

    $customer = Mage::getModel('customer/customer')->load($cid);
    
    $tablesToSave = 3;
    $tablesSaved = 0;

    /* Update Data to table: sales_flat_order */
    $model_order  = Mage::getModel('prominclinkorder/PromincLinkOrder');
    $model_order->load( $oid );
    $model_order->setCustomerId( $cid );
    $model_order->setCustomerIsGuest( 0 );
    if( $customer->getGroupId() ) {
        $model_order->setCustomerGroupId( $customer->getGroupId() );
    }
    if( $model_order->save() ) { $tablesSaved += 1; }

    /* Update Data to table: sales_flat_order_grid */
    $model_orderGrid  = Mage::getModel('prominclinkorder/PromincLinkOrderGrid');
    $model_orderGrid->load( $oid );
    $model_orderGrid->setCustomerId( $cid );
    if( $model_orderGrid->save() ) { $tablesSaved += 1; }

    /* Update Data to table: sales_flat_order_address */
    $model_orderAddress  = Mage::getModel('prominclinkorder/PromincLinkOrderAddress');
    $model_orderAddress->load( $oid );
    $model_orderAddress->setCustomerId( $cid );
    if( $model_orderAddress->save() ) { $tablesSaved += 1; }

    /* Render messages */
    if( $tablesSaved == $tablesToSave ) {
      Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('prominclinkorder')->__('This Customer has been linked to this Order.'));
    } else {
      Mage::getSingleton('adminhtml/session')->addError(Mage::helper('prominclinkorder')->__('An error occured in linking this Customer to the Order.'));
    }

    /* Redirect to preview page with success message */
    $this->_redirect('*/*/preview', array('e' => $email, 'o' => $model_order->getIncrementId(), 's' => $model_order->getStoreId(), 'saved' => '1'));
    return;
  }


  /**
  * Bug fix from patch SUPEE-6285
  */
  protected function _isAllowed() {
    return Mage::getSingleton('admin/session')->isAllowed( 'prominclinkorder/link_customer_to_order' );
  }


}
