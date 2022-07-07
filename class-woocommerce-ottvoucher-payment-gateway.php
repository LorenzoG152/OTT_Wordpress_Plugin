<?php 

class WC_Ottvoucher_Payment_Gateway extends WC_Payment_Gateway{

    private $order_status;

	public function __construct(){
		$this->id = 'ottvoucher_payment';
		$this->method_title = __('OTT Voucher Payment','woocommerce-ottvoucher-payment-gateway');
		$this->method_description = __('OTT Voucher Payment redirects customers to OTT Voucher to enter their payment information.	','woocommerce-ottvoucher-payment-gateway');
		$this->title = __('OTT Voucher Payment','woocommerce-ottvoucher-payment-gateway');
		$this->has_fields = true;
		$this->init_form_fields();
		$this->init_settings();
		$this->enabled = $this->get_option('enabled');
		$this->title = $this->get_option('title');
		$this->description = $this->get_option('description');
		$this->username = $this->get_option('username');
		// $this->username = "OTTECOM001";
		// $this->password = 'JE_dm?2$wN';
		$this->password = $this->get_option('password');
		$this->mode = $this->get_option('mode');
		$this->apikey = $this->get_option('apikey');
		// $this->apikey = '87a02653-3ceb-44bc-bc4c-6d3c618b54e8';
		$this->text_box_required = $this->get_option('text_box_required');
		$this->order_status = $this->get_option('order_status');
		$this->url = 'https://api.ott-mobile.com/';

		$authkey_var = $this->username.":".$this->password;

		$this->authkey = base64_encode($authkey_var);

	add_action('woocommerce_update_options_payment_gateways_'.$this->id, array($this, 'OTT_process_admin_options'));

	}
	public function OTT_process_admin_options() {

		$this->init_settings();
	
		$post_data = $this->get_post_data();
	
		foreach ( $this->get_form_fields() as $key => $field ) {
			if ( 'title' !== $this->get_field_type( $field ) ) {
				try {
					$this->settings[ $key ] = $this->get_field_value( $key, $field, $post_data );
				} catch ( Exception $e ) {
					$this->add_error( $e->getMessage() );
				}
			}
		}
		
		// $soapUrl = $this->url."api/v1/GetAPIKey"; 
		// $soapUser = $_POST['woocommerce_ottvoucher_payment_username']; 
		// $soapPassword = $_POST['woocommerce_ottvoucher_payment_password']; 
		// $headers = array(
		// 	"Content-type: text/html;charset=\"utf-8\"",
		// 	"Accept: text/html",
		// 	"Cache-Control: no-cache",
		// 	"Pragma: no-cache",
		// 	"Content-length: 0",
		// );
		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		// curl_setopt($ch, CURLOPT_URL, $soapUrl);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword);
		// curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		// curl_setopt($ch, CURLOPT_POST, true);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// $response = curl_exec($ch); 
		// $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		// curl_close($ch);
	
		// $res = json_decode($response,true);
	
		// if($httpcode == '200'){
		// 	$this->settings[ 'apikey' ] = $res['apiKey'];
		// 		echo '<p style="position: absolute; top: 50px; left: 180px;border: 1px solid lightgray;padding: 8px 20px;background: #fff;border-left: 4px solid #46b450;font-weight: 500;">Api Key Generate Successfully</p>';
		// }elseif($httpcode == '201'){

		// 	// $this->settinngs[ 'apikey' ] = $res['apiKey'];
		// 	echo '<p style="position: absolute; top: 50px; left: 180px;border: 1px solid lightgray;padding: 8px 20px;background: #fff;border-left: 4px solid #46b450;font-weight: 500;">Use Your Existing Api Key</p>';
		// }elseif($httpcode == '401'){
			
		// 			echo '<p style="position: absolute; top: 50px; left: 180px;border: 1px solid lightgray;padding: 8px 20px;background: #fff;border-left: 4px solid #46b450;font-weight: 500;">Invalid Username or Password</p>';
			
		// }else{
			
		// 	echo '<p style="position: absolute; top: 50px; left: 180px;border: 1px solid lightgray;padding: 8px 20px;background: #fff;border-left: 4px solid #46b450;font-weight: 500;">There are some technical Issue</p>';
			
		// }
	
		return update_option( $this->get_option_key(), apply_filters( 'woocommerce_settings_api_sanitized_fields_' . $this->id, $this->settings ), 'yes' );
	  }
	public function init_form_fields(){
				$this->form_fields = array(
					'enabled' => array(
					'title' 		=> __( 'Enable/Disable', 'woocommerce-ottvoucher-payment-gateway' ),
					'type' 			=> 'checkbox',
					'label' 		=> __( 'Enable OTT Voucher Payment', 'woocommerce-ottvoucher-payment-gateway' ),
					'default' 		=> 'yes'
					),

		            'title' => array(
						'title' 		=> __( 'Method Title', 'woocommerce-ottvoucher-payment-gateway' ),
						'type' 			=> 'text',
						'description' 	=> __( 'Set Your Method Title', 'woocommerce-ottvoucher-payment-gateway' ),
						'default'		=> __( 'OTT Voucher Payment', 'woocommerce-ottvoucher-payment-gateway' ),
						'desc_tip'		=> true,
					),
					'description' => array(
						'title' => __( 'Description', 'woocommerce-ottvoucher-payment-gateway' ),
						'type' => 'textarea',
						'css' => 'width:500px;',
						'default' => 'Pay via OTT Voucher Payment',
						'description' 	=> __( 'The message which you want it to appear to the customer in the checkout page.', 'woocommerce-ottvoucher-payment-gateway' ),
					),
		
					'username' => array(
						'class'         => 'user_name1',
						'title' 		=> __( 'User Name', 'woocommerce-ottvoucher-payment-gateway' ),
						'type' 			=> 'text',
						'description' 	=> __( 'Please Enter Test Username', 'woocommerce-ottvoucher-payment-gateway' ),
						'default'		=> __( '', 'woocommerce-ottvoucher-payment-gateway' ),
						// 'desc_tip'		=> true,
					),
					'password' => array(
						'title' 		=> __( 'Password', 'woocommerce-ottvoucher-payment-gateway' ),
						'type' 			=> 'password',
						'description' 	=> __( 'Please Enter Test password', 'woocommerce-ottvoucher-payment-gateway' ),
						'default'		=> __('', 'woocommerce-ottvoucher-payment-gateway' ),
						// 'desc_tip'		=> true,
					),
					'apikey' => array(
						'title' 		=> __( 'apikey', 'woocommerce-ottvoucher-payment-gateway' ),
						'type' 			=> 'text',
						'description' 	=> __( 'Please Enter the API Key', 'woocommerce-ottvoucher-payment-gateway' ),
						'default'		=> __('', 'woocommerce-ottvoucher-payment-gateway' ),
						// 'desc_tip'		=> true,
					),
					'order_status' => array(
						'title' => __( 'Order Status After The Checkout', 'woocommerce-ottvoucher-payment-gateway' ),
						'type' => 'select',
						'options' => wc_get_order_statuses(),
						'default' => 'wc-on-hold',
						'description' 	=> __( 'The default order status if this gateway used in payment.', 'woocommerce-ottvoucher-payment-gateway' ),
					),
			 );
	}
	
	public function admin_options() {
		echo $this->result;
		?>
		<h3><?php _e( 'OTT Voucher Payment Settings', 'woocommerce-ottvoucher-payment-gateway' ); ?></h3>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<table class="form-table">
						<?php $this->generate_settings_html();?>
					</table>
				</div>
            </div>
		</div>
		<div class="clear"></div>
		<script>
			jQuery( document ).ready(function(){
				var mode = jQuery('#woocommerce_ottvoucher_payment_mode').children("option:selected").val();
			  jQuery( "#woocommerce_ottvoucher_payment_username" ).parent().children('p').text( "Please enter "+ mode +" username" );
			  jQuery( "#woocommerce_ottvoucher_payment_password" ).parent().children('p').text( "Please enter "+ mode +" password" );
			});
			jQuery("#woocommerce_ottvoucher_payment_mode").change(function(){
				var mode = jQuery(this).children("option:selected").val();
			  jQuery( "#woocommerce_ottvoucher_payment_username" ).parent().children('p').text( "Please enter "+ mode +" username" );
			  jQuery( "#woocommerce_ottvoucher_payment_password" ).parent().children('p').text( "Please enter "+ mode +" password" );
			});
		</script>
		<?php
	}

	public function process_payment( $order_id ) {
		global $woocommerce;
		$order = new WC_Order( $order_id );



		if(empty($this->username) || empty($this->apikey) || empty($this->password) ){
			$order->update_status( 'cancelled' );
			wc_add_notice( __('Add credentials from setting','woocommerce-ottvoucher-payment-gateway '), 'error');
			return;
		}

		$amt = $_POST['ottvoucher_payment-amount'];

		// InitiatePayment
		$user = wp_get_current_user();
		$checkout_page_url = function_exists( 'wc_get_cart_url' ) ? wc_get_checkout_url() : $woocommerce->cart->get_checkout_url();
		$rand = rand();
		$account = '';

		$string = $this->apikey;
		$string .= $account;
		$string .= $amt;
		$string .= $user->ID;
		$string .= $order->get_cancel_order_url_raw();
		$string .= $order->get_billing_phone();
		$string .= $order->get_billing_first_name();
		$string .= $this->get_return_url( $order );
		$string .= $order_id;

		$string = str_replace("true&order=wc_order","true&…der",$string);

		// print_r($string);

		$faledUrl = $order->get_cancel_order_url_raw();

		$faledUrl = str_replace("true&order=wc_order","true&…der",$faledUrl);



		$hash =  hash('sha256', $string );

		$redeemVoucher = $this->url."api/v1/InitiateTransaction"; 
		$voucherdata = array(
			'account' => '',
			'amount' => $amt,
			'clientId' => $user->ID,
			'failedUrl' => $faledUrl,
			'mobile' => $order->get_billing_phone(),
			'name' => $order->get_billing_first_name(),
			'successUrl' => $this->get_return_url( $order ),
			'uniqueReference' => $order_id,
			'hash' => $hash
		);

		// print_r($voucherdata);

		$data2 = $this->authkey;

		$data = array(
			'Authorization: Basic '.$data2.' '
		);

		$redeemVoucher = $this->url."api/v1/InitiateTransaction";

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $redeemVoucher,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $voucherdata,
		  CURLOPT_HTTPHEADER => $data,
		));

		$response = curl_exec($curl);

		// print_r($response);

		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$res = json_decode($response,true);

		// print_r($res);

		curl_close($curl);


		if($httpcode == '200'){
			$order->update_status( 'cancelled' );
			return array(
				'result' => 'success',
				'redirect' => $res['url']
			);
	
		}else{
			$order->update_status( 'cancelled' );
			wc_add_notice( __($res['message'],'woocommerce-ottvoucher-payment-gateway'), 'error');
			return;
		}			
	
	}

	public function payment_fields(){
			global $woocommerce;
			$amount = $woocommerce->cart->total;
			
		 ?>
		<fieldset>
			<p class="form-row form-row-wide">
				<label for="<?php echo $this->id; ?>-voucher-code"><?php echo ($this->description); ?>&nbsp;<span><?php echo get_woocommerce_currency_symbol().$amount;?></span></label>
				    <input id="<?php echo $this->id; ?>-amount" class="input-text" type="hidden" value="<?php echo $amount;?>" name="<?php echo $this->id; ?>-amount">
			</p>						
			<div class="clear"></div>
		</fieldset>
		<?php

	}
	
	
}
