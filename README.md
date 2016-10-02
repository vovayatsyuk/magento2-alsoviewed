# Also Viewed Products

Also Viewed Products - is the products recommendation module. Products
suggestions are based on automatically collected anonymous browsing history of
other customers.

### How it works?

Every time the client is looking at some product, module update relations
between this and other viewed products. Day after day relations count are grows
and they become more accurate.

### Facts

 -  100% Open Source
 -  High Perfomance
 -  Sales booster module
 -  Works great with third-party themes and modules

### Additional features

 -  Show products recommendations based on:
    -  Currently viewed product
    -  Recently viewed products
    -  Recently compared products
    -  Shopping cart items

### Installation

```bash
composer config repositories.vovayatsyuk/alsoviewed vcs git@github.com:vovayatsyuk/magento2-alsoviewed.git
composer require vovayatsyuk/alsoviewed
bin/magento module:enable Vovayatsyuk_Alsoviewed
bin/magento setup:upgrade
```
