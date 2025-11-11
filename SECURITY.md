# Security Policy

## Supported Versions

We actively support the following versions with security updates:

| Version | Supported          |
| ------- | ------------------ |
| 2.0.x   | :white_check_mark: |
| < 2.0   | :x:                |

## Security Features

The Lunu Payment Gateway for WooCommerce implements several security measures:

### Payment Processing
- HMAC SHA-256 signature verification for all webhook callbacks
- Secure OAuth-based API authentication
- TLS/SSL encryption for all API communications
- Payment amount verification before order completion

### Data Protection
- API Secret stored securely using password field type
- No sensitive data logged (emails are masked in logs)
- Proper sanitization of all user inputs
- Proper escaping of all outputs
- No hardcoded credentials in the codebase

### WordPress Integration
- Follows WordPress security best practices
- Uses WordPress nonce for form submissions
- Implements proper REST API authentication
- Uses WooCommerce security standards

### Access Control
- Admin-only access to plugin settings
- Secure webhook endpoint with signature verification
- IP whitelisting support (when configured with Lunu)

## Reporting a Vulnerability

We take security seriously. If you discover a security vulnerability, please follow these steps:

### DO NOT

- Do not open a public GitHub issue
- Do not disclose the vulnerability publicly before it's been addressed

### DO

1. **Email us directly** at security@lunu.io with:
   - Description of the vulnerability
   - Steps to reproduce
   - Potential impact
   - Any suggested fixes (if you have them)

2. **Wait for confirmation** - We'll acknowledge your report within 48 hours

3. **Coordinate disclosure** - We'll work with you on:
   - Fixing the vulnerability
   - Testing the fix
   - Coordinating public disclosure timing
   - Crediting you for the discovery (if desired)

## Response Timeline

- **Initial Response**: Within 48 hours
- **Status Update**: Within 7 days
- **Fix Target**: Within 30 days for critical issues
- **Release**: As soon as fix is tested and verified

## Security Best Practices for Users

### Installation & Configuration

1. **Always use the latest version** of the plugin
2. **Use strong, unique credentials**:
   - Never share your API Secret
   - Rotate credentials if compromised
   - Use different credentials for Sandbox and Production

3. **Enable HTTPS**:
   - Use a valid SSL certificate
   - Force HTTPS for admin and checkout pages

4. **Secure your WordPress installation**:
   - Keep WordPress and WooCommerce updated
   - Use strong admin passwords
   - Enable two-factor authentication
   - Limit login attempts
   - Regular backups

### Production Environment

1. **Disable debug logging** in production (unless troubleshooting)
2. **Restrict access** to plugin settings (admin users only)
3. **Monitor webhook endpoint** for suspicious activity
4. **Review logs regularly** for unusual patterns
5. **Test in Sandbox** before deploying to production

### API Credentials

1. **Never commit credentials** to version control
2. **Never share credentials** in support requests
3. **Rotate credentials** periodically
4. **Use Sandbox credentials** for development
5. **Revoke credentials** immediately if compromised

## Known Security Considerations

### Webhook Endpoint

The webhook endpoint `/wp-json/lunu/payment/v1/notify` is public by design (to receive payment notifications), but:

- All requests are verified using HMAC signatures
- Invalid requests are rejected with error codes
- Requests without proper payment data are rejected
- All data is logged for audit purposes (when logging enabled)

### Server Requirements

For maximum security:
- PHP 7.4+ recommended (7.2 minimum)
- OpenSSL extension enabled
- HTTPS with valid certificate
- Server-side firewall configured

### Payment Data

- Customer email addresses are sent to Lunu for payment notifications
- Order amounts and IDs are transmitted securely
- No payment card data is ever stored or transmitted by this plugin
- All cryptocurrency transactions are handled by Lunu's secure infrastructure

## Security Changelog

### Version 2.0.0 (2025-10-10)

**Critical Security Fixes:**
- Implemented HMAC signature verification for webhooks
- Removed hardcoded test credentials
- Fixed REST API authentication vulnerability
- Added proper input sanitization
- Added proper output escaping
- Secured credential storage

**Improvements:**
- API Secret now uses password field type
- Added validation for required credentials
- Improved error handling and logging
- Better separation of environments

## Compliance

This plugin is designed to help you comply with:

- PCI DSS (Payment Card Industry Data Security Standard) - by not handling card data
- GDPR (General Data Protection Regulation) - minimal personal data collection
- WordPress.org Plugin Guidelines
- WooCommerce Extension Guidelines

## Questions?

For security-related questions that are not vulnerabilities:
- Email: security@lunu.io
- General support: support@lunu.io

Thank you for helping keep Lunu Payment Gateway secure!

