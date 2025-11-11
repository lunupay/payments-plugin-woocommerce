=== Lunu for WooCommerce - Cryptocurrencies Payment Gateway ===
Contributors: Lunu Solutions GmbH
Plugin Name: Lunu for WooCommerce - Cryptocurrencies Payment Gateway Addon
Plugin URI: https://lunu.io/plugins
Author: Lunu Solutions GmbH
Author URI: https://lunu.io
Tags: woocommerce, cryptocurrency, bitcoin, ethereum, payments, crypto gateway, blockchain, payment gateway
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.2
Stable tag: 2.0.0
WC requires at least: 3.0
WC tested up to: 8.0
License: MIT
License URI: https://opensource.org/licenses/MIT

Accept cryptocurrency payments in your WooCommerce store with Lunu Payment Gateway. Support for Bitcoin, Ethereum, and other major cryptocurrencies.

== Description ==

Lunu for WooCommerce is a powerful cryptocurrency payment gateway that allows you to accept payments in Bitcoin, Ethereum, and other major cryptocurrencies directly in your WooCommerce store.

**Why Choose Lunu?**

* **Secure Payments**: All transactions are secured by blockchain technology
* **Multiple Cryptocurrencies**: Accept Bitcoin, Ethereum, and many other cryptocurrencies
* **Easy Setup**: Simple configuration with just your App ID and API Secret
* **Real-time Processing**: Instant payment notifications and order updates
* **Sandbox Mode**: Test your integration safely before going live
* **No Hidden Fees**: Transparent pricing with no surprise charges
* **Professional Support**: Dedicated support team to help you succeed

**Features**

* Seamless WooCommerce integration
* Support for multiple cryptocurrencies
* Real-time payment status updates
* Automatic order status management
* Custom order statuses for awaiting confirmations
* Sandbox environment for testing
* Comprehensive logging for debugging
* Custom redirect URLs for success/cancel
* Mobile-responsive payment widget
* Multi-language support ready

**How It Works**

1. Customer selects crypto payment at checkout
2. Lunu payment widget displays cryptocurrency options
3. Customer completes payment using their crypto wallet
4. Payment is confirmed on the blockchain
5. Order status is automatically updated
6. Customer receives order confirmation

**Requirements**

* WordPress 5.0 or higher
* WooCommerce 3.0 or higher
* PHP 7.2 or higher
* A Lunu account with API credentials (get yours at [console.lunu.io](https://console.lunu.io/developer-options))

**Getting Started**

1. Install and activate the plugin
2. Go to WooCommerce > Settings > Payments > Lunu Payment Gateway
3. Enable the payment method
4. Enter your App ID and API Secret from your Lunu dashboard
5. Save settings and start accepting crypto payments!

**Need Help?**

* [Documentation](https://lunu.io/plugins)
* [Support Forum](https://wordpress.org/support/plugin/lunupayment-woocommerce/)
* [Contact Us](https://lunu.io/contact)

== Installation ==

**Automatic Installation**

1. Log in to your WordPress admin panel
2. Go to Plugins > Add New
3. Search for "Lunu for WooCommerce"
4. Click "Install Now" and then "Activate"

**Manual Installation**

1. Download the plugin zip file
2. Log in to your WordPress admin panel
3. Go to Plugins > Add New > Upload Plugin
4. Click "Choose File" and select the downloaded zip file
5. Click "Install Now" and then "Activate Plugin"

**Configuration**

1. Go to WooCommerce > Settings > Payments
2. Click on "Lunu Payment Gateway" to configure
3. Enable the payment method
4. Enter your Lunu App ID and API Secret
   * Get your credentials at [https://console.lunu.io/developer-options](https://console.lunu.io/developer-options)
   * For testing, use Sandbox mode
5. (Optional) Configure custom redirect URLs
6. (Optional) Enable debug logging if you need to troubleshoot
7. Click "Save Changes"

**Testing**

1. Set Environment to "Sandbox (Testing)" in the settings
2. Use the test credentials provided in your Lunu dashboard
3. Make a test purchase to verify everything works
4. Switch to "Production" when ready to go live

== Frequently Asked Questions ==

= What cryptocurrencies are supported? =

Lunu supports Bitcoin (BTC), Ethereum (ETH), and many other major cryptocurrencies. The exact list depends on your Lunu account configuration. Check your Lunu dashboard for the complete list.

= Do I need a Lunu account? =

Yes, you need a Lunu account to use this plugin. Sign up for free at [https://lunu.io](https://lunu.io) and get your API credentials from the developer options.

= Are there any fees? =

Lunu charges a small transaction fee for processing payments. Check our pricing page at [https://lunu.io/pricing](https://lunu.io/pricing) for current rates.

= How long does it take for payments to be confirmed? =

Payment confirmation times vary by cryptocurrency. Bitcoin typically takes 10-60 minutes, while Ethereum takes 1-5 minutes. Your order will be automatically updated when the payment is confirmed on the blockchain.

= Can I test the plugin before going live? =

Yes! Use the Sandbox environment in the plugin settings to test payments without using real cryptocurrency. You'll need sandbox API credentials from your Lunu dashboard.

= What happens if a payment fails? =

If a payment fails or expires, the order status will be automatically updated to "Cancelled" and the customer will be notified. They can then try again or use a different payment method.

= Is this plugin secure? =

Yes! The plugin uses industry-standard security practices including HMAC signature verification, secure API communication, and proper data sanitization. All transactions are secured by blockchain technology.

= Can I customize the redirect URLs? =

Yes, you can set custom redirect URLs for successful payments and cancellations in the plugin settings.

= Does this work with WooCommerce Subscriptions? =

The plugin has basic support for WooCommerce Subscriptions. Note that cryptocurrency payments are one-time transactions, so recurring subscription payments require manual renewal.

= Where can I find the logs? =

If debug logging is enabled, logs can be found in WooCommerce > Status > Logs. Look for files starting with "lunupayment-woocommerce".

= Can I accept regular payments alongside crypto? =

Yes! This plugin works alongside all other WooCommerce payment gateways. Customers can choose their preferred payment method at checkout.

= What if my question isn't answered here? =

Visit our [support forum](https://wordpress.org/support/plugin/lunupayment-woocommerce/) or contact us directly through [lunu.io/contact](https://lunu.io/contact).

== Screenshots ==

1. Payment gateway settings page
2. Customer checkout with crypto payment option
3. Lunu payment widget interface
4. Order confirmation page
5. Custom order status for awaiting confirmations

== Changelog ==

= 2.0.0 - 2025-10-10 =
* Major security improvements
  - Implemented HMAC signature verification for payment callbacks
  - Removed hardcoded test credentials
  - Added proper input sanitization and output escaping
  - Fixed REST API authentication
* Code quality improvements
  - Replaced var_dump with WooCommerce logger
  - Fixed undefined variable issues
  - Improved error handling
  - Added proper WordPress coding standards
* Feature improvements
  - Added environment selector (Production/Sandbox)
  - Changed API Secret to password field for better security
  - Added admin notices for missing credentials
  - Improved settings descriptions and labels
  - Dynamic API endpoint based on environment
* Bug fixes
  - Fixed version inconsistencies
  - Fixed text domain issues
  - Corrected callback endpoint URL generation
* Documentation
  - Complete readme.txt with all required sections
  - Updated LICENSE copyright year
  - Improved inline code documentation

= 1.0.0 =
* Initial release
* Support for major cryptocurrencies
* WooCommerce integration
* Real-time payment processing
* Custom order statuses

== Upgrade Notice ==

= 2.0.0 =
IMPORTANT SECURITY UPDATE: This version includes critical security fixes. Please update immediately and reconfigure your API credentials in the plugin settings. The environment detection has been changed from automatic to manual selection - please review your settings after updating.

== Additional Info ==

**Privacy Policy**

This plugin connects to the Lunu payment processing service at lunu.io to process cryptocurrency payments. When a customer makes a payment:

* Order information (amount, currency, order ID) is sent to Lunu servers
* Customer email address is sent for payment confirmation
* Payment status updates are received via webhook

For more information about how Lunu handles data, please see the [Lunu Privacy Policy](https://lunu.io/privacy).

**Support**

For support, please use the [WordPress.org support forum](https://wordpress.org/support/plugin/lunupayment-woocommerce/) or contact us at [lunu.io/contact](https://lunu.io/contact).

**Contributing**

Developers can contribute to the source code on our [GitHub repository](https://github.com/lunusolutions/lunu-plugin-woocommerce).

**Credits**

Developed by [Lunu Solutions GmbH](https://lunu.io)
