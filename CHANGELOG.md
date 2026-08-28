# Changelog

All notable changes to the Lunu Payment Gateway for WooCommerce will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2025-10-10

### Security
- **CRITICAL**: Implemented HMAC signature verification for payment webhook callbacks
- **CRITICAL**: Removed hardcoded test API credentials from plugin code
- **CRITICAL**: Fixed REST API authentication - permission callback now validates requests
- Added proper input sanitization throughout the plugin
- Added output escaping for all echoed content
- Changed API Secret field to password type for better security
- Implemented secure handling of sensitive credentials

### Added
- Environment selector (Production/Sandbox) in settings
- Admin notice when credentials are missing
- Validation for required App ID and API Secret fields
- Better error messages with proper escaping
- Debug logging using WooCommerce logger instead of file system
- Custom placeholder text for URL fields
- Improved field descriptions in settings
- Added version constant for consistent versioning

### Changed
- Environment detection now uses settings instead of hardcoded domain checks
- API endpoints dynamically selected based on environment setting
- Widget version dynamically selected based on environment
- Text domain changed to 'lunupayment-woocommerce' for consistency
- Callback endpoint now uses rest_url() for proper WordPress integration
- Improved settings field organization and labels
- Updated User-Agent header to use version constant
- All DEFINE statements changed to define() (lowercase) for consistency

### Fixed
- Fixed undefined variable `$payment_url` (changed to `$url`)
- Fixed version inconsistency (now consistently 2.0.0)
- Fixed text domain inconsistency throughout the plugin
- Removed var_dump() and file-based logging
- Removed commented-out code sections
- Fixed potential security vulnerabilities in REST API endpoint
- Corrected callback endpoint URL generation

### Removed
- Removed hardcoded development environment detection
- Removed direct file system logging (now uses WooCommerce logger)
- Removed commented admin_footer_text function
- Removed dependency on specific domain names for environment detection
- Removed default test credentials from form fields

### Documentation
- Complete readme.txt with all WordPress.org required sections
- Updated LICENSE copyright year (2019-2025)
- Comprehensive readme.md with installation and usage instructions
- Added CHANGELOG.md for version tracking
- Improved inline code documentation

## [1.0.0] - Initial Release

### Added
- Initial plugin release
- Support for major cryptocurrencies (Bitcoin, Ethereum, etc.)
- WooCommerce payment gateway integration
- Real-time payment processing
- Custom order statuses for crypto payments
- Payment widget integration
- Webhook notifications for payment updates
- Basic subscription support
- Lunu Gift support for marketing partners
- Success and cancel URL redirects

---

## Upgrade Notes

### Upgrading to 2.0.0

**IMPORTANT**: This is a major security update. Please follow these steps:

1. **Backup your site** before upgrading
2. Update the plugin through WordPress admin or manually
3. Go to **WooCommerce > Settings > Payments > Lunu Payment Gateway**
4. **Select your Environment** (Production or Sandbox)
5. **Re-enter your API credentials** (they will not have defaults anymore)
6. **Test a payment** in Sandbox mode before going live
7. If you enabled logging, logs are now in **WooCommerce > Status > Logs**

**Breaking Changes**:
- Default API credentials removed - you must enter your own
- Environment detection changed from automatic to manual selection
- Log file location changed from plugin directory to WooCommerce logs

**Security Notes**:
- If you're upgrading from version 1.x, your API credentials should still be saved
- The environment will default to "Production" - verify this is correct
- Review your webhook endpoint is accessible: `yoursite.com/wp-json/lunu/payment/v1/notify`

---

[2.0.0]: https://github.com/lunusolutions/lunu-plugin-woocommerce/releases/tag/v2.0.0
[1.0.0]: https://github.com/lunusolutions/lunu-plugin-woocommerce/releases/tag/v1.0.0

