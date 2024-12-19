=== WPC Smart Wishlist for WooCommerce ===
Contributors: wpclever
Donate link: https://wpclever.net
Tags: woocommerce, wpc, wishlist, wait-list
Requires at least: 4.0
Tested up to: 6.7
Version: 4.9.6
Stable tag: 4.9.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WPC Smart Wishlist is a simple but powerful tool that can help your customer save products for buying later.

== Description ==

**WPC Smart Wishlist** is a powerful yet intuitive plugin for helping your customers manage their to-buy list and save favorite items for later purchase. This helps the purchase flow on your site become more fluent and convenient while saving quite a great amount of time on searching for products and adding them to cart for buyers.

= Live demo =

Visit our [live demo 01](https://demo.wpclever.net/woosw/ "live demo 01") or [live demo 02](https://demo.wpclever.net/wpcplant/ "live demo 02") to see how this plugin works.

= Features =

- Control the use of wishlist for unauthenticated users
- Smart display of product details: title, price, date of adding, stock status, product image preview, wishlist item count
- Easy purchase flow from adding, removing, checking out or closing the wishlist
- Enable/disable Auto-removal of products after adding to the cart
- Choose a page as the wishlist page
- Use the provided shortcode to display the wishlist on selected page
- Enable/disable wishlist sharing button
- Enable/disable copying of wishlist links for sharing
- Choose a wishlist type: button or link
- Edit the text for the wishlist button
- Choose an action triggered by wishlist button: display a message or open the product list
- Edit the text and action triggered after adding an item to the wishlist
- Add extra classes for action button/link
- Customize the position of wishlist button on archive and single page
- Choose categories that allow wishlist button
- Unlimited colors for wishlist popup
- Edit the destination link for the Continue Shopping button
- Choose a menu to add the wishlist menu
- Choose an action triggered by the wishlist menu
- RTL support for better displaying right-to-left languages
- Premium: Enable multiple wishlists per user
- Premium: Add note for each product
- Premium: Lifetime update and dedicated support
- Premium: Customization to match with your theme/site design

Newly added feature for management: It's now possible to see all wishlists that a product was included in and check out all wishlists created by a user.

= The Importance of Adding a Wishlist button =
Many store owners miss the opportunities for selling items for their current customers because they’re not offering Add to Wishlist button on their shop or single product page. On many occasions, buyers need time to rethink their needs and allowing them to add products to wishlist increase the possibility for buyers to purchase these items in the future. As it is truly convenient and speedy to add, remove, proceed to check out or continue shopping, buyers will find the whole purchase flow an enjoyable process. Thus, this improves the shopping experience for your customers. In addition, enabling Add to Wishlist button is helpful for buyers to save an Out-of-Stock product for purchasing when it is restocked at a later time. Shop owners can control the wishlist availability by enabling it for authenticated users only, hence, encourage more membership engagement from visitors. Never miss any chance to strengthen the bond with your customers with an Add to Wishlist button on every product page and shop page of your site.

= Product Details at a Glance =
The wishlist items are displayed in great detail so that buyers don’t need to browse the single product page for more information when the title, price, stock status, thumbnail image and date of adding to the wishlist are smartly arranged in the wishlist page or wishlist popup. Buyers can also see a counter showing how many items have been added to their wishlist: a notification for urging them to checkout or to manage the list by removing unwanted items. By keeping your customers on your site, store owners can increase the conversion rate when buyers revise the list and find something useful that they might have missed or forgotten to purchase before. Controlling the wishlist is intuitive because there is a button to remove any item from the list. Users take full control of actions triggered by the wishlist button when an item is already added to the list.

= Ultra-speedy Performance =
There’s nearly zero delay speed for this Smart Wishlist plugin when visitors perform any kind of actions: item addition or removal, closing the wishlist popup or open the wishlist page, it all happens immediately with precision. Wishlist popup also allows an overlay effect that keeps the popup opens while visitors can still scroll the background page until the Continue Shopping button or Close button is pressed. Our plugin is compatible with all WPClever plugins, most common WooCommerce add-ons and WordPress themes, so the flexibility is really high with smooth performance for your website. Smart Wishlist can work in similar ways with any product bundles, composite deals, bought together offers, grouped or force-sell products made with our plugins.

= Fully Customizable Wishlist =
It is possible for users to fully customize the WPC Smart Wishlist plugin to their preferences regarding the appearance, actions and links, type of wishlist, position of wishlist on different pages and even the text displayed for visitors. Premium users are able to add a Wishlist button to any menu that they want: handheld, primary, or secondary menu and customize the action triggered on these menus as well. They can even request the customization of wishlist to match the design scheme of their website for free.

= Great Flow for Advertising Your Products =
If you think that the purchase flow ends with the checkout of your customers, then you are just closing your own door to further advertise your products to other potential clients. WPC Smart Wishlist allows users to take advantage of networking by enabling wishlist sharing via social networks or copying product links to share to other customers. Great products will see a higher conversion rate and better traffic when they are easily shared via social networks. This keeps the flow on and on for new clients and draw more attention to the most widely favored products in your store. With the increase in UX flow on your site, the sales will definitely go up accordingly. This is all up to your intentional arrangement of wishlist buttons.

= Need more features? =

Please try other plugins from us:

- [WPC Smart Compare](https://wordpress.org/plugins/woo-smart-compare/ "WPC Smart Compare")
- [WPC Smart Quick View](https://wordpress.org/plugins/woo-smart-quick-view/ "WPC Smart Quick View")
- [WPC Fly Cart](https://wordpress.org/plugins/woo-fly-cart/ "WPC Fly Cart")
- [WPC Smart Messages](https://wordpress.org/plugins/wpc-smart-messages/ "WPC Smart Messages")
- [WPC Added To Cart Notification](https://wordpress.org/plugins/woo-added-to-cart-notification/ "WPC Added To Cart Notification")

== Installation ==

1. Please make sure that you installed WooCommerce
2. Go to plugins in your dashboard and select "Add New"
3. Search for "WPC Smart Wishlist", Install & Activate it
4. Go to settings page to choose position and effect as you want

== Frequently Asked Questions ==

= How to integrate with my theme? =

To integrate with a theme, please use bellow filter to hide the default buttons.

`add_filter( 'woosw_button_position_archive', '__return_false' );
add_filter( 'woosw_button_position_single', '__return_false' );`

After that, use the shortcode to display the button where you want.

`echo do_shortcode('[woosw id="{product_id}"]');`

Example:

`echo do_shortcode('[woosw id="99"]');`

== Changelog ==

= 4.9.6 =
* Fixed: Error on displaying price change

= 4.9.5 =
* Updated: Optimized the code
* Updated: Compatible with WP 6.7 & Woo 9.4

= 4.9.4 =
* Updated: Optimized the code

= 4.9.3 =
* Added: Option to open wishlist popup menu on My Account
* Fixed: Counter when having suggested products

= 4.9.2 =
* Added: Shortcode [woosw_link]

= 4.9.1 =
* Updated: Optimized the code
* Updated: Compatible with WP 6.6 & Woo 9.3

= 4.9.0 =
* Updated: Use WC_AJAX for frontend

= 4.8.9 =
* Added: Filter hook 'woosw_get_ids' and 'woosw_get_items'

= 4.8.8 =
* Added: Option to reload the count

= 4.8.7 =
* Fixed: Minor CSS/JS issues in backend

= 4.8.6 =
* Updated: Optimized the code
* Updated: Compatible with WP 6.5 & Woo 8.8

= 4.8.5 =
* Added: Dropdown to switch between owned wishlist
* Updated: Compatible with WP 6.5 & Woo 8.7

= 4.8.4 =
* Updated: Compatible with WP 6.4 & Woo 8.7

= 4.8.3 =
* Fixed: Minor CSS/JS issues

= 4.8.2 =
* Fixed: Add note on the wishlist page

= 4.8.1 =
* Updated: Compatible with WP 6.4 & Woo 8.5

= 4.8.0 =
* Updated: Use wp_date instead of date_i18n

= 4.7.9 =
* Updated: Optimized the code

= 4.7.8 =
* Added: Filter hooks 'woosw_page_share_html' & 'woosw_page_copy_html'

= 4.7.7 =
* Updated: Optimized the code
* Added: JS trigger 'woosw_refresh_data'

= 4.7.6 =
* Fixed: Minor CSS/JS issue for the backend
* Added: Filter hook 'woosw_button_rel'

= 4.7.5 =
* Added: Suggested products from WPC Smart Compare

= 4.7.4 =
* Updated: Compatible with WP 6.3 & Woo 8.0

= 4.7.3 =
* Updated: Optimized the code

= 4.7.2 =
* Fixed: CSRF vulnerability

= 4.7.1 =
* Fixed: Minor JS/CSS issues in the backend

= 4.7.0 =
* Added: Suggested products from related/upsells/cross-sells

= 4.6.9 =
* Fixed: Minor JS issue in the backend

= 4.6.8 =
* Added: Paging for previewing wishlists in the backend

= 4.6.7 =
* Added: "Above title" position

= 4.6.6 =
* Updated: Optimized the code

= 4.6.5 =
* Updated: Move wishlist menu on My Account page to before Logout

= 4.6.4 =
* Update: Use shortcode [woosw_list key="xyz"] to show wishlist products on any page

= 4.6.3 =
* Updated: Settings for notes

= 4.6.2 =
* Fixed: Remove draft/deleted products from wishlist

= 4.6.1 =
* Fixed: Minor CSS issue

= 4.6.0 =
* Added: Show price change message for each product (increase or decrease)

= 4.5.3 =
* Fixed: Menu icon on some browsers

= 4.5.2 =
* Added: Compatible with WPC Smart Messages for WooCommerce

= 4.5.1 =
* Fixed: 404 error on the wishlist page

= 4.5.0 =
* Fixed: Wishlist overview in website backend

= 4.4.5 =
* Fixed: Minor JS issue in the backend

= 4.4.4 =
* Added: Function 'get_settings' & 'get_setting'

= 4.4.3 =
* Updated: Optimized the code

= 4.4.2 =
* Updated: Optimized the code

= 4.4.1 =
* Added: Wishlist page on My Account

= 4.4.0 =
* Added: Icon for the button

= 4.3.2 =
* Fixed: Compatible with WPML

= 4.3.1 =
* Fixed: Minor JS for variable product

= 4.3.0 =
* Added: Filter hook 'woosw_fragments'

= 4.2.3 =
* Fixed: Notice on settings page

= 4.2.2 =
* Fixed: Can't change button position

= 4.2.1 =
* Fixed: Stock status of item product
* Added: Filter hook 'woosw_item_stock' & 'woosw_item_add_to_cart'

= 4.2.0 =
* Fixed: Minor JS/CSS issues

= 4.1.0 =
* Added: Position left/right for the popup

= 4.0.0 =
* Added: New message interface
* Updated: Optimized the code

...

= 1.0 =
* Released