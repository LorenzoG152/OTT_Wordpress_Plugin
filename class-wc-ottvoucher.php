<?php
/*
    Plugin Name: OTT Voucher for WooCommerce
    Description: Enable your WooCommerce store to accept OTT Voucher.
    Author:      Elula Online		
	Author URI: https://www.elula.online
    Version: 1.0
 */

// Exit if accessed directly
if (false === defined('ABSPATH')) {
    exit;
}

define('OTT_VOUCHER_PLUGIN', plugin_dir_url(__FILE__));

$active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
if(wpruby_ottvoucher_payment_is_woocommerce_active()){
	add_filter('woocommerce_payment_gateways', 'add_ottvoucher_payment_gateway');
	function add_ottvoucher_payment_gateway( $gateways ){
		$gateways[] = 'WC_ottvoucher_Payment_Gateway';
		return $gateways; 
	}

	add_action('plugins_loaded', 'init_ottvoucher_payment_gateway');
	function init_ottvoucher_payment_gateway(){
		require 'class-woocommerce-ottvoucher-payment-gateway.php';
	}

	add_action( 'plugins_loaded', 'ottvoucher_payment_load_plugin_textdomain' );
	function ottvoucher_payment_load_plugin_textdomain() {
	  load_plugin_textdomain( 'woocommerce-ottvoucher-payment-gateway', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
}else{
	echo '<div class="notice notice-error is-dismissible"> 
			<p><strong>WooCommerce required for OTT Voucher Payment Gateway</strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text">Dismiss this notice.</span>
			</button>
		</div>';	 
}

function wpruby_ottvoucher_payment_is_woocommerce_active()
{
	$active_plugins = (array) get_option('active_plugins', array());

	if (is_multisite()) {
		$active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
	}
	return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
}

add_action( 'woocommerce_thankyou', 'response_check', 4 );
 
function response_check( $order_id ) {
	$order = new WC_Order( $order_id );
	if($order->get_payment_method() == 'ottvoucher_payment'){
		$wc_gateways      = new WC_Ottvoucher_Payment_Gateway();
		$order_status = $wc_gateways->settings['order_status'];
		$order->update_status( $order_status , __( 'Awaiting payment', 'woocommerce-ottvoucher-payment-gateway' ));
 	}
 
}

add_filter( 'woocommerce_available_payment_gateways', 'ottvoucher_enable_manager' );
  
function ottvoucher_enable_manager( $available_gateways ) {
	
  	if(get_woocommerce_currency() != 'ZAR'){
      unset( $available_gateways['ottvoucher_payment'] );
	wc_add_notice( __('OTT Voucher Payment Gateway only Work with ZAR Currency.','woocommerce-ottvoucher-payment-gateway'), 'error');
   } 
   return $available_gateways;
}