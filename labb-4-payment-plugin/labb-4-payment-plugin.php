<?php
/**
  * Plugin Name: Labb 4 payment plugin
  * Plugin URI: https://iths.se
  * Description: This is a payment plugin.
  * Version 1.0.0
  * Author: Robin Lundström
  * License: GPL2
*/

function init_your_gateway() {
  class WC_Gateway_Your_Gateway extends WC_Payment_Gateway
  {

    function __construct() {
      $this->title = 'Faktura';
      $this->id​ = 'my_payment_method';
      $this->Icon​ = 'hello';
      $this->has_fields = 1;
      $this->method_title​ = 'My payment method';
      $this->method_description = 'This is a payment method';
    }

    public function payment_fields() {
      // description for the user
	    if ( $this->description ) {
	      if ( $this->testmode ) {
    			$this->description .= ' TEST MODE ENABLED. In test mode, you can use the card numbers listed in <a href="#" target="_blank">documentation</a>.';
    			$this->description  = trim( $this->description );
    		}
    		// display the description with <p> tags etc.
    		echo wpautop( wp_kses_post( $this->description ) );
    	}

    	// Form start
    	echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';

    	echo '<div class="form-row form-row-wide">
              <label>Personnummer - 10 siffror<span class="required">*</span></label>
          		<input name="personnummer" id="personnummer" type="text" autocomplete="off">
        		</div>';

    	echo '</fieldset>';
    }

    public function validate_fields(){

    	if( empty( $_POST[ 'personnummer' ]) ) {
    		wc_add_notice(  'Personnummer måste fyllas i.', 'error' );
    		return false;
    	}
      else {
        $pnr = $_POST['personnummer'];

    		if ( !preg_match("/^\d{10}$/", $pnr) ) {
          wc_add_notice(  'Felaktigt personnumer', 'error' );
    			return false;
    		}

    		$pnr = str_replace("-", "", $pnr);
    		$n = 2;

    		for ($i=0; $i<9; $i++) {
    			$tmp = $pnr[$i] * $n;
    			($tmp > 9) ? $sum += 1 + ($tmp % 10) : $sum += $tmp; ($n == 2) ? $n = 1 : $n = 2;
    		}
        return true;
    	}
    }

    function process_payment($order_id) {
      global $woocommerce;
      $order = new WC_Order( $order_id );

      $order->payment_complete();

      return array(
        'result' => 'success',
        'redirect' => $this->get_return_url( $order )
      );

      wc_add_notice( __('Payment error:', 'woothemes') . $error_message, 'error' );
      return;
    }
  }
}

add_action( 'plugins_loaded', 'init_your_gateway' );

function add_your_payment_method( $methods ) {
  $methods['your_shipping_method'] = 'WC_Gateway_Your_Gateway';
  return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_your_payment_method');​

?>
