# Magento 1.x Link Customer to Order

Some customers forget that they have an account with your website and/or forget to login prior to making a purchase.  By default, Magento does not offer a method to link the customer to their order if they were to contact you.

The Magento extension allows you to make that connection with the simple click of a button.

## Features
Link a customer to an order which has no customer associated to it (guest order)

Once linked, the order is connected in the Magento Admin and in the customers order history

A preview of the customer information and order information are displayed prior to making the link to allow the site administrator verify that the connection is correct.

Automated checks to ensure error free use:
- Verify email address entered belongs to a customer
- Verify that customer found via the email address is regsistered to the selected store
- Verify the store selected matches the store the customer is registered to as well as the store the order came from
- Verify that the order number entered exists
- Verify that the order is not already associated to a customer

Multiple entry methods
- Manual Entry
  - Manually enter store, customer email address and order number.
- Order Link
  - From the Edit Order page in the Magento Admin, click the Link Order button to have the order number pre-populated.
- Customer Link
  - From the Customer view page in the Magento Admin, click the Link Customer button to have the customers email address and store pre-populated


## Developed and tested on
- Magento 1.6.2
- Magento 1.14.2.0

## Installation
- Install the files in the *app* directory into their respective directories on your server.
- Clear the Magento Cache

**As always, it is best practice to test this extension on a development server prior to adding to your live site to ensure there are no errors or conflicts.  This code is provided for free and the author of this code is not responsible or liable for any negative effects that may be incurred by using this code for your own purposes.**

## Behind the scenes
While the majority of the code in this extension is for the user interface and error checking, the actual action of linking a customer to an order is a few simple lines.  This blog posts explains the background of what the code is ultimatley doing. [Magento – Associate order with user account when customer checked out as guest](http://promincproductions.com/blog/magento-associate-order-user-account-customer-checked-guest/)
