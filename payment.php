<?php

/**
 * Plugin Name: Woocommerce Public bank-Cybersource
 * Plugin URI: https://wordpress.org/plugins/woocommerce-payment-gateway-public-bank
 * Description: Malaysia Public Bank Standard Payment Gateway for Woocommerce
 * Version: 9.9.9
 * Author: 
 * Author URI: 
 * Text Domain: woocommerce-public-bank-cybersource
 *
 * Copyright: © 2009-2015 WooCommerce.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}
add_action('plugins_loaded', 'init_wc_public_bank_cybersouce');

function init_wc_public_bank_cybersouce()
{
    if (!class_exists('WC_Payment_Gateway')) return;

    class WC_Gateway_Public_Bank_CyberSource extends WC_Payment_Gateway
    {


        public static $log_enabled = true;
        public static $log = false;

        public function __construct()
        {
            $this->id                 = 'public_bank_cybersource';
            $this->has_fields         = true; //if need some option in checkout page
            $this->order_button_text  = __('Proceed to Public bank Cybersource', 'woocommerce');
            $this->method_title       = __('Public bank Cybersource', 'woocommerce');
            $this->method_description = 'Setting for Public bank Cybersource';
            $this->supports           = array(
                'products'
            );

            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables.
            $this->title          = $this->get_option('title');
            $this->description    = $this->get_option('description');

            $this->testmode       = 'yes' === $this->get_option('testmode', 'no');

            $this->debug          = 'yes' === $this->get_option('debug', 'no');
            $this->return_url = WC()->api_request_url('wc_public_bank_cybersource_return');
            $this->notify_url = WC()->api_request_url('wc_public_bank_cybersource');
            $this->thankyou_url = $this->get_option('thankyou_page');
            $this->profile_id = $this->testmode ? $this->get_option('profile_id_test') : $this->get_option('profile_id');
            $this->access_key = $this->testmode ? $this->get_option('access_key_test') : $this->get_option('access_key');
            $this->secret_key = $this->testmode ? $this->get_option('secret_key_test') : $this->get_option('secret_key');
            if ($this->testmode) {
                $this->endpoint_checkout = 'https://testsecureacceptance.cybersource.com/pay';
            } else {
                $this->endpoint_checkout = 'https://secureacceptance.cybersource.com/pay';
            }
            self::$log_enabled    = $this->debug;
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_api_wc_public_bank_cybersource', array($this, 'capture_payment'));
            add_action('woocommerce_api_wc_public_bank_cybersource_return', array($this, 'receipt_payment'));
            add_action('woocommerce_receipt_public_bank_cybersource', array($this, 'checkout_form'));
        }



        public static function log($error, $level = 'info')
        {
            if (self::$log_enabled) {
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $date = date('d/m/Y H:i:s');
                $error = $date . ": " . $error . "\n";

                $log_file = __DIR__ . "/log.txt";
                if (!file_exists($log_file) || filesize($log_file) > 1048576) {
                    $fh = fopen($log_file, 'w');
                } else {
                    //echo "Append log to log file ".$log_file;
                    $fh = fopen($log_file, 'a');
                }

                fwrite($fh, $error);
                fclose($fh);
            }
        }

        public function get_icon()
        {
            $icon_html = '';
            return apply_filters('woocommerce_gateway_icon', $icon_html, $this->id);
        }


        public function is_valid_for_use()
        {
            return true;
        }

        public function admin_options()
        {
            if ($this->is_valid_for_use()) {
                parent::admin_options();
            } else {
?>
                <div class="inline error">
                    <p><strong><?php _e('Gateway Disabled', 'woocommerce'); ?></strong>: <?php _e('Public Bank does not support your store currency.', 'woocommerce'); ?></p>
                </div>
<?php
            }
        }

        //form in checkout page
        public function payment_fields($order_id='')
        {		
            echo $this->description;
        }

        //check form is valid
        public function validate_fields()
        {

            return true;
            $method = sanitize_text_field($_POST['wc_pb_merchant']);
            $ziip = (int) ($_POST['ziip']);
            $ziip_plan = sanitize_text_field($_POST['ziip_plan']);
            $ziip_card = $_POST['ziip_card'];
			if (!$this->validate_merchant($method)) {
                wc_add_notice(__('Please choose a payment method', 'woocommerce'), 'error');
                return false;
            }
            
            return true;
        }

        private function validate_merchant($method)
        {
            return true;
            if (empty($method)) {
                return false;
            }
            if ($method != 'visa' && $method != 'master') {
                return false;
            }
            return true;
        }
		
		private function check_cc($cc, $extra_check = false){
			$cards = array(
				"visa" => "(4\d{12}(?:\d{3})?)",
				"amex" => "(3[47]\d{13})",
				"jcb" => "(35[2-8][89]\d\d\d{10})",
				"maestro" => "((?:5020|5038|6304|6579|6761)\d{12}(?:\d\d)?)",
				"solo" => "((?:6334|6767)\d{12}(?:\d\d)?\d?)",
				"mastercard" => "((5|2)[1-5]\d{14})",
				"switch" => "(?:(?:(?:4903|4905|4911|4936|6333|6759)\d{12})|(?:(?:564182|633110)\d{10})(\d\d)?\d?)",
			);
			$names = array("Visa", "American Express", "JCB", "Maestro", "Solo", "Mastercard", "Switch");
			$matches = array();
			$pattern = "#^(?:".implode("|", $cards).")$#";
			$result = preg_match($pattern, str_replace(" ", "", $cc), $matches);
			if($extra_check && $result > 0){
				$result = (validatecard($cc))?1:0;
			}
			return ($result>0)?$names[sizeof($matches)-2]:false;
		}
		
        /**
         * Initialise Gateway Settings Form Fields.
         */
        public function init_form_fields()
        {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Enable Malaysia Public Bank Payment', 'woocommerce'),
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => __('Title', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
                    'default' => 'Credit Card (Processed by PBE)',
                    'desc_tip'      => true,
                ),
                'description' => array(
                    'title' => __('Message show when choose the payment method', 'woocommerce'),
                    'type' => 'textarea',
                    'default' => ''
                ),
                'notify_url' => array(
                    'title' => __('Notify URL to setup Profile'),
                    'type' => 'checkbox',
                    'description' => WC()->api_request_url('wc_public_bank_cybersource')
                ),
                'return_url' => array(
                    'title' => __('Return URL to setup Profile'),
                    'type' => 'checkbox',
                    'description' => WC()->api_request_url('wc_public_bank_cybersource_return')
                ),
                'thankyou_page' => array(
                    'title' => __('Thank you page'),
                    'type' => 'select',
                    'options' => $this->get_pages('Choose...'),
                    'description' => "Chooseing page/url to redirect after checkout to Public Bank Success."
                ),
                'access_key' => array(
                    'title' => __('Access key'),
                    'type' => 'text',
                    'description' => ""
                ),
                'profile_id' => array(
                    'title' => __('Profile id'),
                    'type' => 'text',
                    'description' => ""
                ),
                'secret_key' => array(
                    'title' => __('Secret key'),
                    'type' => 'textarea',
                    'description' => ""
                ),
                'testmode' => array(
                    'title' => __('Testmode', 'woocommerce'),
                    'type' => 'checkbox',
                    'description' => __('Enable test mode.', 'woocommerce'),
                    'default' => __('no', 'woocommerce'),
                ),
                'access_key_test' => array(
                    'title' => __('Access key for test'),
                    'type' => 'text',
                    'description' => ""
                ),
                'profile_id_test' => array(
                    'title' => __('Profile id for test'),
                    'type' => 'text',
                    'description' => ""
                ),
                'secret_key_test' => array(
                    'title' => __('Secret key for test'),
                    'type' => 'textarea',
                    'description' => ""
                ),
                'debug' => array(
                    'title' => __('Debug', 'woocommerce'),
                    'type' => 'checkbox',
                    'description' => sprintf(__('Log Public Bank events, inside %s', 'woocommerce'), '<code>' . __DIR__ . '/log.txt</code>'),
                    'default' => 'yes',
                )
            );
        }


        /**
         * Process the payment and return the result.
         * @param  int $order_id
         * @return array
         */
        public function process_payment($order_id)
        {

            global $woocommerce;
            $order = new WC_Order($order_id);
            $method = sanitize_text_field($_POST['wc_pb_merchant']);
            $ziip_card = $_POST['ziip_card'];
            if ($this->validate_merchant($method)) {
                return array(
                    'result'  => 'success',
                    'redirect'  => add_query_arg(array('order' => $order->id, 'wc_pb_merchant' => $method, 'ziip' => $ziip, 'ziip_plan' => $ziip_plan, 'ziip_card' => $ziip_card), add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay'))))
                );
            } else {
                return array(
                    'result'  => 'failed'
                );
            }
        }

        function checkout_form($order_id)
        {
            global $woocommerce;
            $order = new WC_Order($order_id); 
            $tx_id = $order->get_id();//sprintf('%020d', $order->get_id());           
            //$has_key = 'PUBLICBANKBERHAD0001000000888800PBBSECRET3300000888';
            $securityKeyReq = base64_encode(sha1($has_key, true));
            $params = array(
                'access_key' => $this->access_key,
                'profile_id' => $this->profile_id,
                'transaction_uuid' => uniqid(),
                'signed_field_names' => 'access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency',
                // 'unsigned_field_names' => '',
                'unsigned_field_names' => 'bill_to_address_city,bill_to_address_country,bill_to_address_line1,bill_to_address_postal_code,bill_to_address_state,bill_to_email,bill_to_forename,bill_to_phone,bill_to_surname',
                'signed_date_time' => gmdate("Y-m-d\TH:i:s\Z"),
                'locale' => 'en',
                'transaction_type' => 'authorization',
                'reference_number' => $tx_id,
                'amount' => $this->number_format($order->get_total(), $order),
                'currency' => get_woocommerce_currency(),
                'bill_to_address_city' => $order->get_billing_city(),
                'bill_to_address_country' => $order->get_billing_country(),
                'bill_to_address_line1' => $order->get_billing_address_1(),
                'bill_to_address_postal_code' => $order->get_billing_postcode(),
                'bill_to_address_state' => $order->get_billing_state(),
                'bill_to_email' => $order->get_billing_email(),
                'bill_to_forename' => $order->get_billing_first_name(),
                'bill_to_phone' => $order->get_billing_phone(),
                'bill_to_surname' => $order->get_billing_last_name(),
            );
            $params['signature'] = $this->sign($params);
            //$this->debug($has_key);
             //			$this->debug($params);die;
            
            $link = $this->endpoint_checkout;				
            echo '<form action="' . $link . '" method="POST" name="jb_payment_form" id="jb_payment_form">';
            foreach ($params as $key => $val) {
                echo '<input name="' . $key . '" value="' . $val . '" type="hidden" />';
            }
            echo '<center style="font-size:18px;font-weight:bold;">Please wait while we redirect you to our payment processors website</center>';
            //echo '<center><button type="submit">'.__('continue', 'woocommerce').'</button></center>';
            echo '</form>';

            echo '<script>document.jb_payment_form.submit();</script>';
            return;
        }


        function sign ($params) {
            return $this->signData($this->buildDataToSign($params), $this->secret_key);
        }
        
        function signData($data, $secretKey) {
            return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
        }
        
        function buildDataToSign($params) {
                $signedFieldNames = explode(",",$params["signed_field_names"]);
                foreach ($signedFieldNames as $field) {
                    $dataToSign[] = $field . "=" . $params[$field];
                }
                return $this->commaSeparate($dataToSign);
        }
        
        function commaSeparate ($dataToSign) {
            return implode(",",$dataToSign);
        }
  

        //process in return
        public function capture_payment()
        {
            $this->log('Notify: ' . json_encode($_REQUEST));
            $order_id = $_REQUEST['reference_number'];
            if(!$order_id){
                die;
            }
            $tx_id = $_REQUEST['transaction_uuid'];
            $order = wc_get_order((int) $order_id);
            //$this->debug($_POST);
            //$this->debug($status);
            $card_number = $_REQUEST['cardnumber'];
            $params = $_POST;
            unset($params['woocommerce-login-nonce']);
            unset($params['_wpnonce']);
            unset($params['woocommerce-reset-password-nonce']);
			if (strcmp($params["signature"], $this->sign($params))==0) {
                $order->add_order_note(sprintf(__('Payment of %1$s was captured - Credit card: %2$s, Transaction ID: %3$s', 'woocommerce'), $this->id, $card_number, $tx_id));
                $order->payment_complete($tx_id);
                $order->reduce_order_stock();
                update_post_meta($order->get_id(), '_transaction_id', $tx_id);
            } else {
                $order->add_order_note("Payment failed. {$result}");
            }
            exit;
        }
        public function receipt_payment()
        {
            $order_id = $_REQUEST['reference_number'];
			if(!$order_id){
				$thankyou_page = $this->thankyou_page ? get_permalink($this->thankyou_page) : wc_get_page_permalink('cart');
                wp_redirect($thankyou_page);
				exit;
			}
            $order = wc_get_order((int) $order_id);
            //$this->debug($_POST);
            //$this->debug($status);
            $this->log('return: ' . json_encode($_REQUEST).' status '.$order->get_status());
			
            if ($order->get_status() == 'success') {
                global $woocommerce;
                $woocommerce->cart->empty_cart();
                $thankyou_page = $this->thankyou_page ? get_permalink($this->thankyou_page) : esc_url_raw(add_query_arg('utm_nooverride', '1', $this->get_return_url($order)));
                wp_redirect($thankyou_page);
            } else {
                wp_redirect(wc_get_page_permalink('cart'));
            }
            exit;
        }

        /**
         * Can the order be refunded via Public Bank?
         * @param  WC_Order $order
         * @return bool
         */
        public function can_refund_order($order)
        {
            return false;
        }

        /**
         * Process a refund if supported.
         * @param  int    $order_id
         * @param  float  $amount
         * @param  string $reason
         * @return bool True or false based on success, or a WP_Error object
         */
        public function process_refund($order_id, $amount = null, $reason = '')
        {
            $order = wc_get_order($order_id);
            //waitting for development
            return new WP_Error('error', __('None support refund', 'woocommerce'));
            /*
			if ( ! $this->can_refund_order( $order ) ) {
				$this->log( 'Refund Failed: No transaction ID' );
				return new WP_Error( 'error', __( 'Refund Failed: No transaction ID', 'woocommerce' ) );
			}
			$order->add_order_note( sprintf( __( 'Refunded %s - Refund ID: %s', 'woocommerce' ), $result['GROSSREFUNDAMT'], $result['REFUNDTRANSACTIONID'] ) );
			*/
        }

        protected function number_format($price, $order)
        {
            $decimals = 2;
            return sprintf('%012d', (number_format($price, $decimals, '.', '') ));
        }

        function get_pages($title = false, $indent = true)
        {
            $wp_pages = get_pages('sort_column=menu_order');
            $page_list = array();
            if ($title) $page_list[] = $title;
            foreach ($wp_pages as $page) {
                $prefix = '';
                // show indented child pages?
                if ($indent) {
                    $has_parent = $page->post_parent;
                    while ($has_parent) {
                        $prefix .=  ' - ';
                        $next_page = get_page($has_parent);
                        $has_parent = $next_page->post_parent;
                    }
                }
                // add to page list array array
                $page_list[$page->ID] = $prefix . $page->post_title;
            }
            return $page_list;
        }

        function debug($value)
        {
            echo '<pre>';
            print_r($value);
            echo '</pre>';
        }
    }

    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_$plugin", 'plugin_public_bank_cybersource_action_links');
}
function woocommerce_add_malaysia_public_bank_cybersource($methods)
{
    $methods[] = 'WC_Gateway_Public_Bank_CyberSource';
    return $methods;
}

add_filter('woocommerce_payment_gateways', 'woocommerce_add_malaysia_public_bank_cybersource');


function plugin_public_bank_cybersource_action_links($links)
{
    $plugin_links = array();

    if (version_compare(WC()->version, '2.6', '>=')) {
        $section_slug = 'public_bank_cybersource';
    } else {
        $section_slug = strtolower('WC_Gateway_Public_Bank_CyberSource');
    }
    $setting_url = admin_url('admin.php?page=wc-settings&tab=checkout&section=' . $section_slug);
    $plugin_links[] = '<a href="' . esc_url($setting_url) . '">' . esc_html__('Settings', 'woocommerce-payment-public-bank') . '</a>';

    return array_merge($plugin_links, $links);
}


