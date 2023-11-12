# M2Commerce: Magento 2 Delete Orders

## Summary
The module helps you to delete you older or unwanted orders. There should be a way to deal with massive orders as it frustrates admin to organize them, Delete Orders extension is developed to help store admins simplify this task. The module helps delete orders, invoices, shipments and credit memos from the backend but keeps the retire data safe. The job can be done in the blink of an eye with mass deleting feature and ability to delete all.

- Delete mass orders
- Ability to delete all
- Delete related data safely

## Configuration

There are several configuration options for this extension, which can be found at **STORES > Configuration > Commerce Enterprise > Delete Orders**.


## Installation
### Magento® Marketplace

This extension will also be available on the Magento® Marketplace when approved.

1. Go to Magento® 2 root folder
2. Require/Download this extension:

   Enter following commands to install extension.

   ```
   composer require m2commerce/delete-orders"
   ```

   Wait while composer is updated.

   #### OR

   You can also download code from this repo under Magento® 2 following directory:

    ```
    app/code/M2Commerce/DeleteOrders
    ```    

3. Enter following commands to enable the module:

   ```
   php bin/magento module:enable M2Commerce_DeleteOrders
   php bin/magento setup:upgrade
   php bin/magento setup:di:compile
   php bin/magento cache:clean
   php bin/magento cache:flush
   ```

4. If Magento® is running in production mode, deploy static content:

   ```
   php bin/magento setup:static-content:deploy
   ```
