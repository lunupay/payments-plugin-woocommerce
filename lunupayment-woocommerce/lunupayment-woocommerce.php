<?php
/**
* Plugin Name: Lunu for WooCommerce - Lunu Cryptocurrencies Payment Gateway Addon
* Plugin URI: https://lunu.io/plugins
* Description: Cryptocurrencies Payment Gateway plugin.
* Version: 2.0
* Author: Lunu Solutions GmbH https://lunu.io
* Author URI: https://lunu.io/plugins/
* Text Domain: lunu-pay
*/

DEFINE('LUNUPAYMENT_SERVER_NAME', $_SERVER['SERVER_NAME']);


$LUNUPAYMENT_DEV = strpos(LUNUPAYMENT_SERVER_NAME, 'dev.lunu.io') !== false;
$LUNUPAYMENT_RC = strpos(LUNUPAYMENT_SERVER_NAME, 'rc.lunu.io') !== false;
$LUNUPAYMENT_TESTING = strpos(LUNUPAYMENT_SERVER_NAME, 'testing.lunu.io') !== false;
$LUNUPAYMENT_SANDBOX = strpos(LUNUPAYMENT_SERVER_NAME, 'sandbox.lunu.io') !== false;

DEFINE('LUNUPAYMENT_PROCESSING_VERSION', (
 $LUNUPAYMENT_DEV
    ? 'api.dev'
    : (
      $LUNUPAYMENT_RC
        ? 'api.rc'
        : (
          $LUNUPAYMENT_TESTING
            ? 'api.testing'
            : (
              $LUNUPAYMENT_SANDBOX
                ? 'api.sandbox'
                : 'api'
            )
        )
    )
));


DEFINE('LUNUPAYMENT_WIDGET_VERSION', (
  $LUNUPAYMENT_DEV
    ? 'beta'
    : (
      $LUNUPAYMENT_RC
        ? 'rc'
        : (
          $LUNUPAYMENT_TESTING
            ? 'testing'
            : ($LUNUPAYMENT_SANDBOX ? 'sandbox' : 'alpha')
        )
    )
));

DEFINE('LUNUPAYMENT_PAYMENT_CALLBACK_ENDPOINT', 'https://' . LUNUPAYMENT_SERVER_NAME . '/wp-json/lunu/payment/v1/notify');

DEFINE('LUNUPAYMENT_STATUS_PENDING', 'pending');
DEFINE('LUNUPAYMENT_STATUS_PAID', 'paid');
DEFINE('LUNUPAYMENT_STATUS_FAILED', 'failed');
DEFINE('LUNUPAYMENT_STATUS_EXPIRED', 'expired');
DEFINE('LUNUPAYMENT_STATUS_CANCELED', 'canceled');
DEFINE('LUNUPAYMENT_STATUS_AWAITING_CONFIRMATION', 'awaiting_payment_confirmation');

DEFINE('LUNUPAYMENT_WC_STATUS_AWAITING_CONFIRMATION_WP', 'lunu-awaiting');
DEFINE('LUNUPAYMENT_WC_STATUS_AWAITING_CONFIRMATION', 'wc-lunu-awaiting');
DEFINE('LUNUPAYMENT_WC_STATUS_PROCESSING', 'wc-processing');
DEFINE('LUNUPAYMENT_WC_STATUS_CANCELED', 'wc-cancelled');


if (!defined('ABSPATH')) exit; // Exit if accessed directly
if (
  !function_exists('lunupayment_wc_gateway_load')
  && !function_exists('lunupayment_wc_action_links')
) {

  DEFINE('LUNUPAYMENTWC', 'lunupayment-woocommerce');

  if (!defined('LUNUPAYMENTWC_AFFILIATE_KEY')) {
    DEFINE('LUNUPAYMENTWC_AFFILIATE_KEY', 'lunupayment');
    add_action('plugins_loaded', 'lunupayment_wc_gateway_load', 20);
    add_filter('plugin_action_links', 'lunupayment_wc_action_links', 10, 2);
    add_filter('plugin_row_meta', 'lunupayment_wc_plugin_meta', 10, 2);
  }


  function lunupayment_wc_new_order_statuses() {
    register_post_status(LUNUPAYMENT_WC_STATUS_AWAITING_CONFIRMATION, array(
      'label' => 'Awaiting payment confirmation',
      'public' => true,
      'exclude_from_search' => false,
      'show_in_admin_all_list' => true,
      'show_in_admin_status_list' => true,
      'label_count' => _n_noop('Awaiting payment confirmation', 'Awaiting payment confirmation', 'woocommerce')
    ));
  }

  function lunupayment_wc_order_statuses($order_statuses) {
    $order_statuses = array_merge($order_statuses, array());
    $order_statuses[LUNUPAYMENT_WC_STATUS_AWAITING_CONFIRMATION] = 'Awaiting payment confirmation';
    return $order_statuses;
  }

  // New order status AFTER woo 2.2
  add_action('init', 'lunupayment_wc_new_order_statuses');
  add_filter('wc_order_statuses', 'lunupayment_wc_order_statuses');


  function lunu_payment_awaiting($order) {
    $order->payment_complete();
    $order->set_status(
      LUNUPAYMENT_WC_STATUS_AWAITING_CONFIRMATION,
      'Payment awaiting blockchain confirmation via Lunu service<br>'
    );
    $order->save();
  }

  function getUrlEndpoint() {
    return 'https://' . LUNUPAYMENT_PROCESSING_VERSION . '.lunu.io/api/v1/payments/';
  }


  function lunupayment_wc_action_links($links, $file) {
    static $this_plugin;

    if (!class_exists('WC_Payment_Gateway')) return $links;

    if (false === isset($this_plugin) || true === empty($this_plugin)) {
      $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
      $settings_link = '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=wc_gateway_lunupayment') . '">' . __('Settings', LUNUPAYMENTWC) . '</a>';
      array_unshift($links, $settings_link);
    }

    return $links;
  }


  function lunupayment_wc_plugin_meta($links, $file) {

    if (
      strpos($file, 'lunupayment-woocommerce.php') !== false
      && class_exists('WC_Payment_Gateway')
      && defined('lunupayment')
    ) {

      // Set link for Reviews.
      $new_links = array(
        '<a
          style="color:#0073aa"
          href="https://wordpress.org/support/plugin/lunupayment-woocommerce/reviews/?filter=5"
          target="_blank"
        >
          <span class="dashicons dashicons-thumbs-up"></span> ' . __('Vote!', LUNUPAYMENTWC) . '
        </a>',
      );

      $links = array_merge($links, $new_links);
    }

    return $links;
  }


  function lunupayment_wc_gateway_load() {
    // WooCommerce required
    if (!class_exists('WC_Payment_Gateway') || class_exists('WC_Gateway_LunuPayment')) return;

    add_filter('woocommerce_payment_gateways', 'lunupayment_wc_gateway_add');

    // add LunuPayment gateway
    function lunupayment_wc_gateway_add($methods) {
      if (!in_array('WC_Gateway_LunuPayment', $methods)) {
        $methods[] = 'WC_Gateway_LunuPayment';
      }
      return $methods;
    }

    // Payment Gateway WC Class
    class WC_Gateway_LunuPayment extends WC_Payment_Gateway {
      private $app_id = '';
      private $api_secret = '';
      private $success_url = '';
      private $cancel_url = '';
      private $coupon_code_prefix = '';
      private $lunu_gift_enabled = false;
      private $lunu_logs_enabled = true;

      public function __construct() {

        $this->id = 'lunupayments';
        $this->method_title = __('Pay with Crypto (by Lunu Pay)', LUNUPAYMENTWC);
        $this->method_description = 'Secure payments with virtual currency.';
        $this->has_fields = false;
        $this->supports = array('subscriptions', 'products');

        $enabled =
          (
            LUNUPAYMENTWC_AFFILIATE_KEY == 'lunupayment'
            && $this->get_option('enabled') === ''
          )
          || $this->get_option('enabled') == 'yes'
          || $this->get_option('enabled') == '1'
          || $this->get_option('enabled') === true;

        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();
        $this->lunupayment_settings();

        $this->icon = apply_filters('woocommerce_lunupayments_icon', plugins_url("/images/logo.svg", __FILE__));

        // Hooks
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        add_action('woocommerce_thankyou_lunupayments', array($this, 'cryptocoin_payment'));


        // Subscriptions
        if (class_exists('WC_Subscriptions_Order')) {
          add_action('woocommerce_subscription_unable_to_update_status', array($this, 'unable_to_update_subscription_status'), 10, 3);
        }


        if (
          isset($_GET["page"])
          && isset($_GET["section"])
          && $_GET["page"] == "wc-settings"
          && $_GET["section"] == "wc_gateway_lunupayment"
        ) {
          add_action('admin_footer_text', array(&$this, 'admin_footer_text'), 25);
        }

        return true;
      }

      public function lunu_log($message, $val = null) {
        if (!$this->lunu_logs_enabled) {
          return;
        }
        ob_start();
        var_dump($val);
        file_put_contents(
          __DIR__ . '/logs/lunu_log.txt',
          date('Y-m-d H:i:s') . ' ' . $message . ' ' . ob_get_clean() . PHP_EOL,
          FILE_APPEND
        );
      }

      private function lunupayment_settings() {
        // Define user set variables
        $this->enabled = trim($this->get_option('enabled'));
        $this->app_id = trim($this->get_option('app_id'));
        $this->api_secret = trim($this->get_option('api_secret'));
        $this->success_url = trim($this->get_option('success_url'));
        $this->cancel_url = trim($this->get_option('cancel_url'));
        $this->coupon_code_prefix = trim($this->get_option('coupon_code_prefix'));
        $this->lunu_gift_enabled = trim($this->get_option('lunu_gift_enabled')) === 'yes';
        $this->lunu_logs_enabled = trim($this->get_option('lunu_logs_enabled')) === 'yes';

        // Re-check
        if (!$this->title) {
          $this->title = __('Pay with Crypto (by Lunu Pay)', LUNUPAYMENTWC);
        }
        return true;
      }


      public function init_form_fields() {
        $this->form_fields = array(
          'enabled' => array(
            'title' => __('Enable/Disable', LUNUPAYMENTWC),
            'type' => 'checkbox',
            'default' => (LUNUPAYMENTWC_AFFILIATE_KEY == 'lunupayment' ? 'yes' : 'no'),
            'label' => __("Enable Payments by Cryptocurrencies in WooCommerce", LUNUPAYMENTWC)
          ),
          'app_id' => array(
            'title' => __('App ID', LUNUPAYMENTWC),
            'type' => 'text',
            'default' => '8ce43c7a-2143-467c-b8b5-fa748c598ddd'
          ),
          'api_secret' => array(
            'title' => __('API Secret', LUNUPAYMENTWC),
            'type' => 'text',
            'default' => 'f1819284-031e-42ad-8832-87c0f1145696'
          ),
          'success_url' => array(
            'title' => __('Redirect Url if success', LUNUPAYMENTWC),
            'type' => 'text',
            'default' => '',
            'description' => __('Redirect to another page after payment is received. For example, http://yoursite.com/thank_you.php', LUNUPAYMENTWC) . "<br/><br/><br/><br/><br/>"
          ),
          'cancel_url' => array(
            'title' => __('Redirect Url if cancel', LUNUPAYMENTWC),
            'type' => 'text',
            'default' => '',
            'description' => __('Redirect to another page after payment is canceled. For example, http://yoursite.com/we_very_wait_you.php', LUNUPAYMENTWC) . "<br/><br/><br/><br/><br/>"
          ),
          'lunu_gift_enabled' => array(
            'title' => __('Enable Lunu Gift', LUNUPAYMENTWC),
            'type' => 'checkbox',
            'default' => 'no',
            'label' => __("Enable payments by Lunu Gifts if you marketing partners of Lunu", LUNUPAYMENTWC)
          ),
          'lunu_logs_enabled' => array(
            'title' => __('Enable logs', LUNUPAYMENTWC),
            'type' => 'checkbox',
            'default' => 'yes',
            'label' => ''
          )
        );

        return true;
      }

      /*
      // Admin footer page text
      public function admin_footer_text() {
        return sprintf(__("If you like <b>Lunu Cryptocurrencies Gateway for WooCommerce</b> please leave us a %s rating on %s. A huge thank you from Lunu in advance!", LUNUPAYMENTWC),
        "<a href='https://wordpress.org/support/view/plugin-reviews/lunupayment-woocommerce?filter=5#postform' target='_blank'>&#9733;&#9733;&#9733;&#9733;&#9733;</a>",
        "<a href='https://wordpress.org/support/view/plugin-reviews/lunupayment-woocommerce?filter=5#postform' target='_blank'>WordPress.org</a>");
      }
      */

      // Forward to WC Checkout Page
      public function process_payment($order_id) {
        global $woocommerce;

        $this->lunu_log('process_payment', array(
          'order_id' => $order_id,
        ));

        // New Order
        $order = new WC_Order($order_id);
        // Mark as pending (we're awaiting the payment)
        $order->update_status('pending', __('Awaiting payment notification from Lunu', LUNUPAYMENTWC));

        // Remove cart
        $woocommerce->cart->empty_cart();

        // Return redirect
        return array(
          'result' => 'success',
          'redirect' => $this->get_return_url($order)
        );
      }

      // WC Order Checkout Page
      public function cryptocoin_payment($order_id) {

        $order = new WC_Order($order_id);

        $order_id = (true === version_compare(WOOCOMMERCE_VERSION, '3.0', '<')) ? $order->id : $order->get_id();
        $order_status = (true === version_compare(WOOCOMMERCE_VERSION, '3.0', '<')) ? $order->status : $order->get_status();
        $post_status = (true === version_compare(WOOCOMMERCE_VERSION, '3.0', '<')) ? $order->post_status : get_post_status($order_id);
        $userID = (true === version_compare(WOOCOMMERCE_VERSION, '3.0', '<')) ? $order->user_id : $order->get_user_id();
        $currency = (true === version_compare(WOOCOMMERCE_VERSION, '3.0', '<')) ? $order->order_currency : $order->get_currency();
        $amount = floatval((true === version_compare(WOOCOMMERCE_VERSION, '3.0', '<')) ? $order->order_total : $order->get_total());
        $orderData = $order->get_data();
        $shipping_amount = floatval($orderData['shipping_total']);

        $success_url = $this->success_url;
        $cancel_url = $this->cancel_url;
        if (empty($cancel_url)) {
          $cancel_url = '/cart/';
        }
        $lunu_gift_enabled = $this->lunu_gift_enabled;

        if ($order_status == "cancelled" || $post_status == "wc-cancelled") {

          if (!empty($cancel_url)) {
            echo "<script>window.location.href = '" . $cancel_url . "';</script>";
          }
          echo '<br><h2>' . __('Information', LUNUPAYMENTWC) . '</h2>' . PHP_EOL;

          if (time() > strtotime(get_post_meta($order_id, '_lunupayment_expires', true))) {
            echo "<div class='woocommerce-error'>"
              . __("Order expired. If you have already paid order - communicate with Support.", LUNUPAYMENTWC)
            . "</div><br>";
          } else {
            echo "<div class='woocommerce-error'>"
              . __("This order's status is 'Cancelled' - it cannot be paid for. Please contact us if you need assistance.", LUNUPAYMENTWC)
            . "</div><br>";
          }

          return true;
        }
        if (true === version_compare(WOOCOMMERCE_VERSION, '2.1', '<')) {
          echo '<br><h2>' . __('Information', LUNUPAYMENTWC) . '</h2>' . PHP_EOL;
          echo "<div class='woocommerce-error'>"
           .sprintf(__(
             'Sorry, but there was an error processing your order. Please try a different payment method or contact us if you need assistance (Lunu Cryptocurrencies Gateway Plugin v0.0.0+ not configured / %s not activated).',
             LUNUPAYMENTWC), $this->title)
            ."</div><br>";
          return true;
        }
        if ($amount <= 0) {
          echo '<br><h2>' . __('Information', LUNUPAYMENTWC) . '</h2>' . PHP_EOL;
          echo "<div class='woocommerce-error'>". sprintf(__("This order's amount is %s - it cannot be paid for. Please contact us if you need assistance.", LUNUPAYMENTWC), $amount ." " . $currency)."</div><br>";
          return true;
        }

        // lunu-awaiting
        if (!($order_status === "pending" || $post_status == "wc-pending")) {
          return true;
        }


        $confirmation_token = get_post_meta($order_id, '_lunupayment_confirmation_token', true);

        if ($confirmation_token) {
          $payment_status = get_post_meta($order_id, '_lunupayment_status', true);
          $payment_id = get_post_meta($order_id, '_lunupayment_id', true);
        } else {

          $description = array();
          foreach($order->get_items() as $item_id => $item) {
            $product = $item->get_product();
            $description[] = $item->get_quantity() . ' × ' . $item->get_name()
              . ' (' . $item->get_product_id() . ' v' . $item->get_variation_id() . ')';
          }

          $params = array(
            'email' => $orderData['billing']['email'],
            'shop_order_id' => $order_id,
            'amount' => $amount,
            'amount_of_shipping' => $shipping_amount,
            'currency' => $currency,
            'description' => 'Order #' . $order_id . ' ' . join(', ', $description)
          );


          $payment_information = $this->lunu_payment_create($params);
          $error_message = '';

          if (!empty($payment_information['error_message'])) {
              $error_message = htmlspecialchars($payment_information['error_message']);
          } elseif (empty($payment_information)) {
              $error_message = 'Lunu Payment service is temporarily unavailable';
          }

          if(!empty($error_message)) {
            echo '<div class="woocommerce-error">' . $error_message . '</div><br/>';
            return true;
          }

          $payment_id = $payment_information['id'];
          $confirmation_token = $payment_information['confirmation_token'];
          $confirmation_url = $payment_information['confirmation_url'];
          $created_at = $payment_information['created_at'];
          $expires = $payment_information['expires'];
          $payment_status = $payment_information['status'];

          update_post_meta($order_id, '_lunupayment_id', $payment_id);
          update_post_meta($order_id, '_lunupayment_confirmation_token', $confirmation_token);
          update_post_meta($order_id, '_lunupayment_confirmation_url', $confirmation_url);
          update_post_meta($order_id, '_lunupayment_created_at', $created_at);
          update_post_meta($order_id, '_lunupayment_expires', $expires);
          update_post_meta($order_id, '_lunupayment_status', $payment_status);
        }


        $widget_version = LUNUPAYMENT_WIDGET_VERSION;

        $payment_status = strtolower($payment_status);

        if ($payment_status === LUNUPAYMENT_STATUS_PENDING) {
          echo "<script>
            window.jQuery && jQuery(document).ready(function() {
              jQuery('.entry-title').text('" . __('Pay Now', LUNUPAYMENTWC) . "');
              jQuery('.woocommerce-thankyou-order-received').remove();
             });
          </script>
          <!--HTML element that will display the payment form-->
          <div id=\"payment-form\"></div><br><br>
          <script>
          (function(d, t) {
            var n = d.getElementsByTagName(t)[0], s = d.createElement(t);
            s.type = 'text/javascript';
            s.charset = 'utf-8';
            s.async = true;
            s.src = 'https://plugins.lunu.io/packages/widget-ui/" . $widget_version . ".js?t=' + 1 * new Date();
            s.onload = function() {
              new window.Lunu.widgets.Payment(
                d.getElementById('payment-form'),
                {
                  confirmation_token: '" . $confirmation_token . "',
                  // Token that must be received from the Processing Service before making a payment
                  // Required parameter

                  enableLunuGift: " . ($lunu_gift_enabled ? 'true' : 'false') . ",
                  overlay: true,

                  callbacks: {
                    init_error: function(error) {
                      // Handling initialization errors
                    },
                    init_success: function(data) {
                      // Handling a Successful Initialization
                    },
                    payment_paid: function(params) {
                      // Handling a successful payment event
                      var handleSuccess = window.LUNU_PAYMENT_SUCCESS_CALLBACK;
                      handleSuccess && handleSuccess(params);
                      " . (empty($success_url) ? "" : "window.location.href = '" . $success_url . "';") . "
                    },
                    payment_cancel: function() {
                      // Handling a payment cancellation event
                      var handleCancel = window.LUNU_PAYMENT_CANCEL_CALLBACK;
                      handleCancel && handleCancel();
                      " . (empty($cancel_url) ? "" : "window.location.href = '" . $cancel_url . "';") . "
                    },
                    payment_close: function() {
                      // Handling the event of closing the widget window
                    }
                  }
                }
              );
            };
            n.parentNode.insertBefore(s, n);
          })(document, 'script');
          </script>";
          return true;
        }

        echo '<br><h2>' . __('Information', LUNUPAYMENTWC) . '</h2>' . PHP_EOL;

        if ($payment_status === LUNUPAYMENT_STATUS_PAID) {
          if (!empty($success_url)) {
            echo "<script>window.location.href = '" . $success_url . "';</script>";
          }
          echo "<div class='woocommerce-success'>" . __("This order's status is 'Paid'", LUNUPAYMENTWC) . "</div><br>";
          return true;
        }

        if ($payment_status === 'canceled') {
          if (!empty($cancel_url)) {
            echo "<script>window.location.href = '" . $cancel_url . "';</script>";
          }
          echo "<div class='woocommerce-error'>" . __("This order's status is 'Cancelled' - it cannot be paid for. Please contact us if you need assistance.", LUNUPAYMENTWC) . "</div><br>";
          return true;
        }

        echo '<div class="woocommerce-error">Lunu Payment service is temporarily unavailable</div><br>';
        return true;
      }


      // Lunu Cryptocurrencies Gateway - Instant Payment Notification
      public function lunupayment_callback($callback_data) {

        $this->lunu_log('Payment callback', array(
          'payment' => $callback_data
        ));

        $payment_id = $callback_data['id'];
        $shop_order_id = $callback_data['shop_order_id'];

        if (empty($payment_id)) return false;

        if (empty($shop_order_id)) {
          $posts = get_posts(array(
            'meta_key' => '_lunupayment_id',
            'meta_value' => $payment_id,
            'post_type' => wc_get_order_types(),
            'post_status' => array_keys(wc_get_order_statuses())
          ));
          $order = isset($posts[0]) ? $posts[0] : null;
          $shop_order_id = empty($order) ? null : $order->ID;
          if (empty($shop_order_id)) return false;
        }

        $order = new WC_Order($shop_order_id);
        if ($order === false) {
          return false;
        }
        $order_status = $order->status;

        if ($order_status !== 'pending' && $order_status !== LUNUPAYMENT_WC_STATUS_AWAITING_CONFIRMATION_WP) {
          return false;
        }

        $payment_status = get_post_meta($shop_order_id, '_lunupayment_status', true);
        if (
          $payment_status === LUNUPAYMENT_STATUS_PAID
          || $payment_status === LUNUPAYMENT_STATUS_FAILED
          || $payment_status === LUNUPAYMENT_STATUS_CANCELED
          || $payment_status === LUNUPAYMENT_STATUS_EXPIRED
        ) {
          return false;
        }

        $callback_payment_status = strtolower($callback_data['status']);

        $payment = $this->lunu_payment_check($payment_id);
        $this->lunu_log('Payment checking', array(
          'payment' => $payment,
        ));

        if (empty($payment)) {
          return false;
        }

        $payment_status = strtolower($payment['status']);
        if (
          $payment_status !== $callback_payment_status
          || $payment_status !== LUNUPAYMENT_STATUS_PAID
          && $payment_status !== LUNUPAYMENT_STATUS_AWAITING_CONFIRMATION
          && $payment_status !== LUNUPAYMENT_STATUS_FAILED
          && $payment_status !== LUNUPAYMENT_STATUS_CANCELED
          && $payment_status !== LUNUPAYMENT_STATUS_EXPIRED
        ) {
          return false;
        }

        $payment_amount = floatval($payment['amount']);
        $subtotal = $payment_amount;

        $order_amount = floatval(
          true === version_compare(WOOCOMMERCE_VERSION, '3.0', '<')
            ? $order->order_total
            : $order->get_total()
        );

        if ($payment_amount !== $order_amount) {
          $this->lunu_log('Payment amount is invalid', array(
            'payment_amount' => $payment_amount,
            'order_amount' => $order_amount
          ));
          return false;
        }

        if (
          $payment_status === LUNUPAYMENT_STATUS_FAILED
          || $payment_status === LUNUPAYMENT_STATUS_CANCELED
          || $payment_status === LUNUPAYMENT_STATUS_EXPIRED
        ) {
          update_post_meta($shop_order_id, '_lunupayment_status', $payment_status);
          $order->set_status(LUNUPAYMENT_WC_STATUS_CANCELED);
          $order->save();
          return true;

        } elseif ($payment_status === LUNUPAYMENT_STATUS_PAID) {
          $order->payment_complete();
          // $order->add_order_note('Payment Received via Lunu service<br/>');
          $order->set_status(LUNUPAYMENT_WC_STATUS_PROCESSING, 'Payment Received via Lunu service<br/>');
          $order->save();
          update_post_meta($shop_order_id, '_lunupayment_status', $payment_status);
          return true;

        } elseif ($payment_status === LUNUPAYMENT_STATUS_AWAITING_CONFIRMATION) {
          update_post_meta($shop_order_id, '_lunupayment_status', $payment_status);
          lunu_payment_awaiting($order);
          return true;
        }

        return true;
      }


      public function lunu_payment_create($params = array()) {
        global $wp_version;

        $shop_order_id = $params['shop_order_id'];

        $time = time();

        $timeout = 3600;

        $woocommerce_hold_stock_minutes = intval(get_option('woocommerce_hold_stock_minutes'));
        if ($woocommerce_hold_stock_minutes > 0) {
          $timeout = $woocommerce_hold_stock_minutes * 60;
        }


        $data = array(
          'shop_order_id' => '' . $shop_order_id,
          'email' => '' . $params['email'],
          'amount' => '' . $params['amount'],
		  'fiat_code' => '' . $params['currency'],
          'amount_of_shipping' => '' . $params['amount_of_shipping'],
          'callback_url' => LUNUPAYMENT_PAYMENT_CALLBACK_ENDPOINT,
          'description' => '' . $params['description'],
          'expires' => date("c", $time + $timeout)
        );

        $url = getUrlEndpoint() . 'create';

        $options = array(
          'method' => 'POST',
          'headers' => array(
            'Authorization' => 'Basic ' . base64_encode($this->app_id . ':' . $this->api_secret),
            'Idempotence-Key' => 'WC_' . $time . '_' . $shop_order_id,
            'Content-Type' => 'application/json',
            'User-Agent' => 'WordPress v' . (isset($wp_version) ? $wp_version : '') .
              ' | WooCommerce v' . WOOCOMMERCE_VERSION . ' | Lunu Extension 2.0.0'
          ),
          'body' => json_encode($data)
        );

        $WP_Http = new WP_Http();
        $response = $WP_Http->request($url, $options);

        $data['email'] = '*******';
        $this->lunu_log('Payment create', array(
          // 'options' => $options,
          'url' => $url,
          'request' => $data,
          'response' => $response,
        ));

        if (!is_wp_error($response) && isset($response['body'])) {
          $body = $response['body'];

          $data = json_decode($body, true);
          if (is_array($data)) {
            if(is_array($data['response'])) {
                return $data['response'];
            } elseif(!empty($data['error']['message'])) {
                return ['error_message' => $data['error']['message']];
            }
          }
        } elseif(is_wp_error($response)) {
          $error_message = $response->get_error_message();
          if(!empty($error_message)) {
            return ['error_message' => $error_message];
          }
        }
        // $this->lunu_log('Payment create', $response);

        return false;
      }


      function lunu_payment_check($payment_id) {
        $url = getUrlEndpoint() . 'get/' . $payment_id;

        $WP_Http = new WP_Http();
        $response = $WP_Http->request($url, array(
          'method' => 'POST',
          'headers' => array(
            'Authorization' => 'Basic ' . base64_encode($this->app_id . ':' . $this->api_secret)
          )
        ));

        if (!is_wp_error($response) && isset($response['body'])) {
          $data = json_decode($response['body'], true);
          if (is_array($data) && is_array($data['response'])) {
            return $data['response'];
          }
        }
        $this->lunu_log('Payment checking error', array(
          'payment_url' => $payment_url,
          'response' => $response,
        ));
        return null;
      }


      // scheduled_subscription_payment function.
      public function unable_to_update_subscription_status($subscr_order, $new_status, $old_status) {
        $method = true === version_compare(WOOCOMMERCE_VERSION, '3.0', '<')
          ? $subscr_order->payment_method
          : $subscr_order->get_payment_method();

        if (
          $method == "lunupaymentpayments"
          && $old_status == "active"
          && $new_status == "on-hold"
        ) {
          $customer_id = true === version_compare(WOOCOMMERCE_VERSION, '3.0', '<')
            ? $subscr_order->customer_id
            : $subscr_order->get_customer_id();
          $userprofile = $customer_id
            ? "<a href='" . admin_url("user-edit.php?user_id=" . $customer_id) . "'>User" . $customer_id . "</a>"
            : __('User', LUNUPAYMENTWC);

          $parentid = true === version_compare(WOOCOMMERCE_VERSION, '3.0', '<')
            ? $subscr_order->parent_id
            : $subscr_order->get_parent_id();
          $orderpage = $parentid
            ? "Original <a href='" . admin_url("post.php?post=" . $parentid . "&action=edit")."'>order #" . $parentid . "</a>, subscription expired. <br/> "
            : '';

          $subscr_order->update_status('expired', sprintf(__('Cryptocurrencies recurring payments not available. %s <br/> %s need to resubscribe.', LUNUPAYMENTWC), $orderpage, $userprofile)) . " <br/><br/> ";
        }

        return false;
      }
    }
  }
}


function lunu_payment_callback_notify(WP_REST_Request $request) {
  global $woocommerce;

  $gateways = $woocommerce->payment_gateways->payment_gateways();

  if (!isset($gateways['lunupayments'])) return;

  $success = $gateways['lunupayments']->lunupayment_callback(
      json_decode($request->get_body(), true)
  );

  return array(
    'status' => $success ? 'accepted' : 'rejected'
  );
}


function lunu_permission_callback() {
  return true;
}
add_action('rest_api_init', function() {
  register_rest_route('lunu/payment/v1', '/notify', array(
    'methods' => 'POST',
    'callback' => 'lunu_payment_callback_notify',
    'permission_callback' => 'lunu_permission_callback'
  ));
});
