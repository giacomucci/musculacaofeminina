# Changelog

All notable changes to this project will be documented in this file.

## [50.0.7]

### Added

- Added placeholders replacement in Model/Observer::getAjax() to increase compatibility with 3rd party modules injecting inline JS code

## [50.0.6]

### Fixed

- Minor checkout updates related to version 50.0.5

## [50.0.5]

### Refactored

- Refactored purchase.phtml template and shifted most of the logic into Helper/Datalayer

### Added

- Added new custom event = ec_order_purchase_get_after to allow 3rd parties to modify purchase push

## [50.0.4]

### Added

- Added Facebook Pixel Advanced Matching Parameters

## [50.0.3]

### Fixed

- Fixed broken coupon cancel functionality due to method override in cart.phtml

## [50.0.2]

### Added

- Extended compatibility for AJAX based cart buttons in categories

## [50.0.1]

### Added

- New GTM trigger - Event Equals Cookie Consent Granted to ease the GDRP configuration

## [50.0.0]

### Added

- Added GDPR compatibility for addToCart, productClick events

## [40.0.9]

### Fixed

- Monor updates

### Added

- GDRP now applicable to Facebook Pixel Code. Cookie Consent must be explicitly granted for Facebook Pixel tracking to work

## [40.0.8]

### Added

- NEW: Product-scoped coupon tracking

### Fixed

- Increased default GDRP Cookie lifetime to 1 month.

## [40.0.7]

### Fixed

- Remove API debug

## [40.0.6]

### Added

- Added ts (shipping) parameter to refunds

### Fixed

- Wrong tax amount for refunds

## [40.0.5]

### Fixed

- Wrong qty for partial refunds (second fix)

## [40.0.4]

### Fixed

- Fixed Wrong UA-ID assigned to refunds (in multi-store configuration)
- Fixed Wrong refund QTY (in case of partial refunds)

## [40.0.3]

### Added

- Built-in Cookie Consent Directive / GDRP frienldy/compliance

## [40.0.2]

### Fixed

- Minor performance improvements in categories

## [40.0.1]

### Added

- Ability to select "brand" attrbute from Preferences configuration section
- Added brand map to reduce brand calls

### Fixed

- Added missing step 1 tracking for logged customers. Step sequence change to: 

[1,2,3,4,5,6] (in case of non-logged customer, guest/new)
[  2,3,4,5,6] (in case of logged customer)

to 

[1,2,3,4,5,6] (in case of non-logged customer, guest/new)
[1,2,3,4,5,6] (in case of logged customer 

(Step labels in GA should be updated accordingly)


## [40.0.0]

### Fixed

- Bug when using slash within SKU (Smartview port affected)

## [30.0.9]

### Fixed

- Minor code cleanup

## [30.0.8]

### Fixed

- Added a 200 ms timeout for tracking checkout options to prevent a rare cases of race condition with setShipping/setPayment methods

## [30.0.7]

### Fixed

- Wrong window.google_tag_params.ecomm_pagetype in categories (Magento EE with FPC enabled). Used 'other' instead of 'category'

## [30.0.6]

- Added ec_get_visitor_data event to allow for visitor modification
- Added ec_get_general_data event to allow for general dataLayer[] pushes

## [30.0.5]

### Fixed

- Removed all instances of deprecated __() translator function to increase compatibility with Wordpress integrations

## [30.0.4]

### Fixed

- Extended support for Unicode characters (Greek, Arabic etc.)
- Fixed wrong event for Smart viewport

## [30.0.3]

### Fixed

- Fixed Uncaught Error: Call to a member function getId() when adding product to cart directly from whishlist

## [30.0.2]

### Added

- "Add to cart" tracking from Wishlist

### Fixed

- Added missing "Remove from cart" tracking for Downloadable/Virtual products.

## [30.0.1]

### Added

- Caching of JSON push data for detail pages and categories to overcome FPC related issues (Magento Enterprise mainly)

## [30.0.0]

### Added

- Cookie based privateData to cope with heavily cached setups

### Fixed

## [21.0.9]

### Fixed

- Minor code cleanup

## [21.0.8]

### Fixed

- Added quotes to google_conversion_id to support the new string based conversion ids e.g. AW-1000000
- Added checkout option check to prevent null related errors in checkout step option tracking
- Added improved JSON parse for attributes (to support older versions of jQuery) and >= 3.x

## [21.0.7]

### Fixed

- Removed a broken functionality related to "Use jQuery() on" handler in categories "Add to cart" tracking

## [21.0.6]

### Fixed

- Fixed wrong store ID for offline orders

## [21.0.5]

### Fixed

- Smart viewport offset related errors

## [21.0.4]

- Monor updates

## [21.0.3]

### Added

- Added event ec_get_detail_data_after to allow for detail push data modification

### Changed

- Refactored and standatized all custom dispatched events e.g. / Add product reference in attrubute * related attributes

content_experiment 				- Allows 3rd part modules to modify current running experiment
ec_checkout_products_get_after 	- Allows 3rd part modules to modify products [] pushed at checkout
ec_order_products_get_after 	- Allows 3rd part modules to modify products [] pushed at purchase
ec_get_impression_data_after 	- Allows 3rd part modules to modify products [] pushed at category impressions
ec_get_detail_data_after 		- Allows 3rd part modules to modify products [] pushed at detail page
ec_get_add_attributes 			- Allows 3rd part modules to modify/add custom attributes tracked with "addToCart" event
ec_get_remove_attributes 		- Allows 3rd part modules to modify/add custom attributes tracked with "removeFromCart" event
ec_get_click_attributes 		- Allows 3rd part modules to modify/add custom attributes tracked with "productClick" event
ec_get_wishlist_attributes 		- Allows 3rd part modules to modify/add custom attributes tracked with "addToWishlist" event
ec_get_compare_attributes 		- Allows 3rd part modules to modify/add custom attributes tracked with "addToCompare" event

## [21.0.2]

### Fixed

- Missing value parameter in InitiateCheckout (Facebook Pixel)

## [21.0.1]

### Added

- Newsletter subscription tracking (event based)

## [21.0.0]

### New branch

## [20.0.9]

### Added

- Affiliation tracking (based on URI parameter). Affiliation parmater is automatically converted to product scoped dimension and passed with impression, click/add/remove events, transactions etc.

## [20.0.8]

### Added

- Extended API to allow for creating Universal Analytics tag automatically

## [20.0.5]

### Changed

- Code cleanup

### Added

- Added a new API configuration section related to Google Measurement Protocol and offline order tracking

## [20.0.4]

- Fixed Product list attribution from cart additions from categories

## [20.0.0]

- Added fallback to collections with no toolbars

## [19.0.9]

###Fixed

- Fixed ecomm_ price discrepancies / Added Incl. tax on all instances

## [19.0.8]

###Fixed

- Wrong tax calculation for bundle items in AdWords Dynamic Remarketing

## [19.0.7] 

###Changed

- Minor updates
- Cleanup

## [19.0.6] 

###Changed

- Minor updates

## [19.0.5]

### Fixed

- Fixed potential XSS vulnerability in search results

## [19.0.4]

### Fixed

Remove from cart was not working for configurable products (in some versions of Magento)

## [19.0.3]

### Added

"Add to wishlist" tracking from categories