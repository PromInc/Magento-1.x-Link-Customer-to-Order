<?php
class Prominc_PromincLinkOrder_Block_Adminhtml_Admin_Preview extends Mage_Adminhtml_Block_Sales_Items_Abstract
{


  protected $_order;


  /**
  *
  */
  public function __construct() {
    parent::__construct();
    $this->setId('prominclinkorderAdminhtmlAdminPreview');

    if( Mage::registry('order') ) {
        $_order = $this->getOrder( Mage::registry('order') );
    }

    $this->addItemRender("default", "adminhtml/sales_order_view_items_renderer_default", "sales/order/view/items/renderer/default.phtml");

    $this->setTemplate('prominclinkorder/admin/preview.phtml')
      ->setData('store', Mage::registry('store') )
      ->setData('email', Mage::registry('email') )
      ->setData('order_id', Mage::registry('order_id') )
      ->setData('saved', Mage::registry('saved') )
      ;
  }


  /**
  * Retrieve order items collection
  *
  * @return unknown
  */
  public function getItemsCollection()
  {
    return $this->getOrder()->getItemsCollection();
  }


  /**
  * Get customer object
  */
  public function getCustomer() {
    return Mage::getModel("customer/customer")
      ->setWebsiteId( $this->getData( 'store' ) )
      ->loadByEmail( $this->getData('email') );
  }


  /**
  * Get customer object by ID
  *
  * @param mixed $id
  * @return Mage_Core_Model_Abstract
  */
  public function getCustomerById($id) {
    return Mage::getModel('customer/customer')->load($id);;
  }


  /**
  * Get customer group name by customer group ID number
  *
  * @param mixed $id
  */
  public function getGroupName($id) {
    if( $id ) {
      return Mage::getModel('customer/group')
        ->load($id)
        ->getCustomerGroupCode();
    }
  }


  /**
  * Get store name by store ID
  *
  * @param int $id
  */
  public function getStoreName($id) {
    if( $id ) {
      return Mage::getModel('core/store')->load($id)->getName();
    }
  }


  /**
  * Get customer address HTML output address ID
  *
  * @param mixed $addressId
  * @param mixed $format (billing, shipping)
  */
  public function getAddressHtml( $addressId, $format="billing" ) {
    $address = Mage::getModel('customer/address')->load( $addressId )->getData();

    $addressHtml = '<address class="box-right">' .
      '<strong>Default ' . ucfirst( $format ) . ' Address</strong><br>' .
      $address['firstname'] . ' ' . $address['lastname'] . '<br>' .
      $address['street'] . '<br>' .
      $address['city'] . ', ' . $address['region'] . ', ' . $address['postcode'] . ' ' . $address['country_id'] . '<br>' .
      'T: ' . $address['telephone'] . '<br>' .
    '</address>';

    return $addressHtml;
  }


  /**
  * Check if a link between the customer and order can be made.
  *
  * Based on various criteria, a link may not be allowed,
  * an appropriate message will be returned in that case.
  *
  * If a link can be made, a button is returned to allow the
  * user to complete the link process.
  *
  * @Retrun array
  *              canLink bool If a link can be made or not
  *              explanation array HTML strings with error or success messages
  */
  public function canMakeLink() {
    $canLink = array( 'canLink' => NULL, 'explanation' => array() );
    $customer = $this->getCustomer();
    $order = $this->getOrder();

    if( $customer->getIsGuest() == 1 ) {
      /* Error Checking | If customer has account */
      $accountURI = 'customer/account/login/';
      $accountUrl = Mage::app()->getStore($customer->getStoreId())->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, true).$accountURI;
      $canLink['canLink'] = false;
      $canLink['explanation'][] = '<p>The customer is a <b>Guest</b> and thus does not have an account.</p><p>Linking an order to a <b>Guest</b> is not possible.  The customer will need to create an account proior to making this link.</p><p>Ask the customer to create an account first by visiting this page: <a href="'.$accountUrl.'" target="_blank">'.$accountUrl.'</a></p>';
    }

    if( $customer->getIsGuest() != 1 && $order->getCustomerId() ) {
      /* Error Checking | Order is already linked to a customer */
      $existingCustomer = $this->getCustomerById( $order->getCustomerId() );
      $existingOrderUrl = Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/view/order_id/'.$order->getEntityId());
      $existingCustomerUrl = Mage::helper('adminhtml')->getUrl('adminhtml/customer/edit/id/'.$order->getCustomerId());
      $canLink['canLink'] = false;
      $canLink['explanation'][] = '<p>This <a href="'.$existingOrderUrl.'" target="_blank">order</a> is already linked to a customer.</p><p><b>Currently Linked Customer:</b><br><a href="'.$existingCustomerUrl.'" target="_blank">'.$existingCustomer->getFirstname().' '.$existingCustomer->getLastname().' (Customer ID:'.$existingCustomer->getEntityId().')</a></p>';
    }

    if( $customer->getIsGuest() != 1 && $customer->getStoreId() != $order->getStoreId() ) {
      /* Error Checking | Order Store and Customer Store Do Not Match */
      $searchForLinkUrl = Mage::helper('adminhtml')->getUrl('*/prominclinkorder_prominclinkorderadmin/', array('s' => $this->getData('store'), 'e' => $this->getData('email'), 'o' => $this->getData('order')));
      $canLink['canLink'] = false;
      $canLink['explanation'][] = '<p>The <b>Store</b> for this customer account does not match the store on the order.</p><p>Reset the link parameters to ensure the store for the customer search matches the store of the order.</p><p><a href="'.$searchForLinkUrl.'">Update Search</a></p>';
    }

    if( is_null( $canLink['canLink'] ) ) {
      $makeLinkUrl = Mage::helper('adminhtml')->getUrl('*/prominclinkorder_prominclinkorderadmin/process/', array('cid' => $customer->getEntityId(), 'oid' => $order->getEntityId(), 'c_e' => $customer->getEmail()));
      $canLink['canLink'] = true;
      $canLink['explanation'][] = '<p>This customer can be linked to this order.</p><p>To finalize processing this link, click the button below.</p><p><a href="'.$makeLinkUrl.'"><span class="form-button">Process Link</span></a></p>';
    }

    return $canLink;
  }


}