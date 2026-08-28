# Lunu Payment Gateway for WooCommerce

![Version](https://img.shields.io/badge/version-2.0.0-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)
![WordPress](https://img.shields.io/badge/wordpress-5.0%2B-blue.svg)
![WooCommerce](https://img.shields.io/badge/woocommerce-3.0%2B-purple.svg)

Accept cryptocurrency payments in your WooCommerce store with the Lunu Payment Gateway. Support for Bitcoin, Ethereum, and other major cryptocurrencies.

## Features

- ✅ **Secure Payments**: All transactions secured by blockchain technology
- ✅ **Multiple Cryptocurrencies**: Accept Bitcoin, Ethereum, and many others
- ✅ **Easy Setup**: Simple configuration with App ID and API Secret
- ✅ **Real-time Processing**: Instant payment notifications and order updates
- ✅ **Sandbox Mode**: Test your integration safely before going live
- ✅ **Custom Order Statuses**: Automatic status updates based on payment state
- ✅ **Debug Logging**: Comprehensive logging for troubleshooting
- ✅ **Mobile Responsive**: Works perfectly on all devices
- ✅ **WooCommerce Subscriptions**: Basic support for subscription products

## Requirements

- WordPress 5.0 or higher
- WooCommerce 3.0 or higher
- PHP 7.2 or higher
- SSL Certificate (HTTPS) recommended for production
- A Lunu account with API credentials

## Installation

### From WordPress Admin

1. Go to **Plugins > Add New**
2. Search for "Lunu for WooCommerce"
3. Click **Install Now** and then **Activate**

### Manual Installation

1. Download the plugin zip file
2. Go to **Plugins > Add New > Upload Plugin**
3. Choose the downloaded zip file and click **Install Now**
4. Click **Activate Plugin**

### From Source

1. Clone this repository or download the source code
2. Copy the `lunupayment-woocommerce` folder to your WordPress `wp-content/plugins/` directory
3. Go to **Plugins** in WordPress admin and activate "Lunu for WooCommerce"

## Configuration

### Getting Your API Credentials

1. Sign up for a Lunu account at [https://lunu.io](https://lunu.io)
2. Go to [Developer Options](https://console.lunu.io/developer-options)
3. Create a new Widget to get your App ID and API Secret

### Plugin Setup

1. In WordPress admin, go to **WooCommerce > Settings > Payments**
2. Click on **Lunu Payment Gateway**
3. Enable the payment method
4. Configure the following settings:

   - **Environment**: Choose Production or Sandbox
   - **App ID**: Enter your Lunu App ID
   - **API Secret**: Enter your Lunu API Secret
   - **Success Redirect URL** (Optional): Custom page after successful payment
   - **Cancel Redirect URL** (Optional): Custom page after payment cancellation
   - **Enable Lunu Gift** (Optional): For marketing partners only
   - **Enable Debug Logs** (Optional): For troubleshooting

5. Click **Save Changes**

### Testing

For testing payments without real cryptocurrency:

1. Set **Environment** to "Sandbox (Testing)"
2. Use sandbox credentials from your Lunu dashboard
3. Make a test purchase
4. Verify the order status updates correctly
5. Switch to "Production" when ready to go live

## API Credentials for Testing

While developing or testing locally, you can use these test credentials:

- **App ID**: `a63127be-6440-9ecd-8baf-c7d08e379dab`
- **API Secret**: `25615105-7be2-4c25-9b4b-2f50e86e2311`

⚠️ **Important**: These are for testing only. For production, get your own credentials from [console.lunu.io](https://console.lunu.io/developer-options)

⚠️ **Note**: If your test site is not publicly accessible from the Internet, payment status webhooks from Lunu will not reach your store, and order statuses won't update automatically. Use a public development server or tunneling service (like ngrok) for webhook testing.

## How It Works

1. Customer adds products to cart and proceeds to checkout
2. Customer selects "Pay with Crypto (by Lunu Pay)" as payment method
3. After clicking "Place Order", the Lunu payment widget appears
4. Customer selects their preferred cryptocurrency
5. Customer completes payment using their crypto wallet
6. Payment is confirmed on the blockchain
7. Order status is automatically updated via webhook
8. Customer and store owner receive confirmation

## Order Statuses

The plugin creates custom order statuses for cryptocurrency payments:

- **Pending**: Initial state when order is created
- **Awaiting Payment Confirmation**: Payment received, waiting for blockchain confirmations
- **Processing**: Payment fully confirmed, ready to fulfill
- **Cancelled**: Payment failed, expired, or was cancelled

## Troubleshooting

### Enable Debug Logging

1. Go to plugin settings
2. Enable **Enable Debug Logs**
3. Make a test transaction
4. View logs at **WooCommerce > Status > Logs**
5. Look for files starting with `lunupayment-woocommerce`

### Common Issues

**"Payment gateway not configured" error**
- Make sure you've entered valid App ID and API Secret
- Verify the credentials in your Lunu dashboard

**Order status not updating**
- Check that your site is publicly accessible for webhooks
- Verify debug logs for any errors
- Ensure your SSL certificate is valid

**Payment widget not loading**
- Check browser console for JavaScript errors
- Verify your App ID is correct
- Try clearing browser cache

## Security

This plugin implements several security best practices:

- HMAC signature verification for payment callbacks
- Secure API communication with OAuth
- Input sanitization and output escaping
- Password field for API Secret
- WooCommerce security standards compliance

## Support

- **Documentation**: [https://lunu.io/plugins](https://lunu.io/plugins)
- **Support Forum**: [WordPress.org Support](https://wordpress.org/support/plugin/lunupayment-woocommerce/)
- **Email**: Contact us through [lunu.io/contact](https://lunu.io/contact)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Changelog

### Version 2.0.0 (2025-10-10)

**Security Improvements**
- Implemented HMAC signature verification for payment callbacks
- Removed hardcoded test credentials from plugin
- Added proper input sanitization and output escaping
- Fixed REST API authentication vulnerability

**Code Quality**
- Replaced var_dump with WooCommerce logger
- Fixed undefined variable issues
- Improved error handling throughout
- Added proper WordPress coding standards

**Features**
- Added environment selector (Production/Sandbox)
- Changed API Secret to password field for better security
- Added admin notices for missing credentials
- Improved settings descriptions and labels
- Dynamic API endpoint selection based on environment

**Bug Fixes**
- Fixed version inconsistencies across files
- Fixed text domain issues
- Corrected callback endpoint URL generation

**Documentation**
- Complete readme.txt for WordPress.org
- Updated LICENSE copyright year
- Improved inline code documentation

### Version 1.0.0

- Initial release
- Support for major cryptocurrencies
- WooCommerce integration
- Real-time payment processing

## License

This plugin is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

Copyright (c) 2019-2025 Lunu Solutions GmbH - [https://lunu.io](https://lunu.io)

## Credits

Developed by [Lunu Solutions GmbH](https://lunu.io)
