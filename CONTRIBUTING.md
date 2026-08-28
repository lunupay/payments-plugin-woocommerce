# Contributing to Lunu Payment Gateway for WooCommerce

Thank you for your interest in contributing to the Lunu Payment Gateway for WooCommerce! We welcome contributions from the community.

## How to Contribute

### Reporting Bugs

If you find a bug, please create an issue on GitHub with:

- A clear and descriptive title
- Steps to reproduce the issue
- Expected behavior
- Actual behavior
- Screenshots (if applicable)
- WordPress version
- WooCommerce version
- PHP version
- Plugin version

### Suggesting Enhancements

We're always looking for ways to improve! Please create an issue with:

- A clear and descriptive title
- Detailed description of the proposed enhancement
- Use cases and examples
- Any relevant mockups or examples

### Pull Requests

1. Fork the repository
2. Create a new branch (`git checkout -b feature/your-feature-name`)
3. Make your changes
4. Follow the coding standards (see below)
5. Test your changes thoroughly
6. Commit your changes (`git commit -am 'Add some feature'`)
7. Push to the branch (`git push origin feature/your-feature-name`)
8. Create a Pull Request

## Coding Standards

### WordPress Coding Standards

This plugin follows [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/):

- Use tabs for indentation
- Use spaces in function calls: `function_name( $param )`
- Use single quotes for strings unless variables are needed
- Always escape output: `esc_html()`, `esc_attr()`, `esc_url()`
- Always sanitize input: `sanitize_text_field()`, `sanitize_email()`, etc.
- Use WordPress functions when available

### PHP Standards

- PHP 7.2+ compatible code
- Use type hints where appropriate
- Document all functions with PHPDoc comments
- Keep functions focused and single-purpose
- Handle errors gracefully

### Security

- Always validate and sanitize user input
- Always escape output
- Use nonces for form submissions
- Use prepared statements for database queries
- Never expose sensitive information in logs
- Follow WordPress security best practices

### Testing

Before submitting a pull request:

- Test with latest WordPress version
- Test with latest WooCommerce version
- Test in both PHP 7.2 and PHP 8.0+
- Test with debugging enabled
- Test both Production and Sandbox modes
- Verify no PHP warnings or errors

## Development Setup

1. Clone the repository
2. Set up a local WordPress development environment
3. Install WooCommerce
4. Copy the plugin to `wp-content/plugins/`
5. Activate the plugin
6. Configure with Sandbox credentials for testing

## Code Review Process

1. All submissions require review
2. We may suggest changes or improvements
3. Once approved, we'll merge your contribution
4. Your contribution will be included in the next release

## Questions?

If you have questions about contributing, feel free to:

- Open an issue for discussion
- Contact us at [lunu.io/contact](https://lunu.io/contact)

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

Thank you for contributing to make cryptocurrency payments more accessible!

