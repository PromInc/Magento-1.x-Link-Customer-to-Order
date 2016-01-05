## 1.0.0 - 2016-01-05
###Initial Release
### Features
- Link a customer to an order which has no customer associated to it (guest order)
  - Once linked, the order is connected in the Magento Admin and in the customers order history
- A preview of the customer information and order information are displayed prior to making the link to allow the site administrator verify that the connection is correct
- Automated checks to ensure error free use:
  - Verify email address entered belongs to a customer
  - Verify that customer found via the email address is regsistered to the selected store
  - Verify the store selected matches the store the customer is registered to as well as the store the order came from
  - Verify that the order number entered exists
  - Verify that the order is not already associated to a customer
- Multiple entry methods
  - Manual Entry
    - Manually enter store, customer email address and order number.
  - Order Link
    - From the Edit Order page in the Magento Admin, click the Link Order button to have the order number pre-populated.
  - Customer Link
    - From the Customer view page in the Magento Admin, click the Link Customer button to have the customers email address and store pre-populated