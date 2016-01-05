<?php
class Prominc_PromincLinkOrder_Model_Adminhtml_Observer
{


    /**
    * Add button on Admin Order View page to link this order to a customer
    *
    * @param mixed $observer
    * @return Prominc_PromincLinkOrder_Model_Adminhtml_Observer
    */
    public function addButtonLinkToCustomer($observer) {
        $block = Mage::app()->getLayout()->getBlock('sales_order_edit');
        if (!$block){
            return $this;
        }
        $order = Mage::registry('current_order');
        if( $order->getEntityId() ) {
          if( ! $order->getCustomerId() ) {
            $url = Mage::helper("adminhtml")->getUrl(
                "*/prominclinkorder_prominclinkorderadmin",
                array('o'=>$order->getIncrementId())
            );
            $block->addButton('cygtest_resubmit', array(
                  'label'     => Mage::helper('sales')->__('Link to Customer'),
                  'onclick'   => 'setLocation(\'' . $url . '\')',
                  'class'     => 'go'
            ), 0, 2);
            return $this;
          }
        }
    }


    /**
    * Add button on Admin Customer View page to link this order to an order
    *
    * @param mixed $observer
    * @return Prominc_PromincLinkOrder_Model_Adminhtml_Observer
    */
    public function addButtonLinkToOrder($observer) {
        $block = Mage::app()->getLayout()->getBlock('customer_edit');
        if (!$block){
            return $this;
        }
        $customer = Mage::registry('current_customer');
        $url = Mage::helper("adminhtml")->getUrl(
            "*/prominclinkorder_prominclinkorderadmin",
            array('e'=>$customer->getEmail(), 's'=>$customer->getStoreId())
        );
        $block->addButton('cygtest_resubmit', array(
              'label'     => Mage::helper('sales')->__('Link to Order'),
              'onclick'   => 'setLocation(\'' . $url . '\')',
              'class'     => 'go'
        ), 0, 25);
        return $this;
    }


}