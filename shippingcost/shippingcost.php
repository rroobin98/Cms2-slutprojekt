<?php

/**
 * Plugin Name: shippingcost
 * Description: En fraktmodul som beräknar fraktkostnaden baserat på vikt utifrån en prislista
 * Version: 1.0.0
 * Author: Robin Lundström
 */

if ( ! defined( 'WPINC' ) ) {

    die;

}

/*
 * Kontrollera om WooCommerce är aktivt
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    function shippcost_shipping_method() {
        if ( ! class_exists( 'ShippCost_Shipping_Method' ) ) {
            class ShippCost_Shipping_Method extends WC_Shipping_Method {
                /**
                 * Konstruktör för fraktklass
                 *
                 * @access public
                 * @return void
                 */
                public function __construct() {
                    $this->id                 = 'shippcost';
                    $this->method_title       = __( 'ShippCost Shipping', 'shippcost' );
                    $this->method_description = __( 'Custom Shipping Method for ShippCost', 'shippcost' );

                    // Tillgänglighet och länder
                    $this->availability = 'including';
                    $this->countries = array(
                        'SE',  // Sweden
                        );

                    $this->init();

                    $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'ShippCost Shipping', 'shippcost' );
                }

                /**
                 * Ibiterar inställingar
                 *
                 * @access public
                 * @return void
                 */
                function init() {
                    // Ladda inställnings-API: et
                    $this->init_form_fields();
                    $this->init_settings();

                    // Spara inställningar i admin om du har definierat något
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }

                /**
                 * Definiera inställningsfältet för den här leveransen
                 * @return void
                 */
                function init_form_fields() {

                    $this->form_fields = array(

                     'enabled' => array(
                          'title' => __( 'Enable', 'shippcost' ),
                          'type' => 'checkbox',
                          'description' => __( 'Enable this shipping.', 'shippcost' ),
                          'default' => 'yes'
                          ),

                     'title' => array(
                        'title' => __( 'Title', 'shippcost' ),
                          'type' => 'text',
                          'description' => __( 'Title to be display on site', 'shippcost' ),
                          'default' => __( 'ShippCost Shipping', 'shippcost' )
                          ),

                     'weight' => array(
                        'title' => __( 'Weight (kg)', 'shippcost' ),
                          'type' => 'number',
                          'description' => __( 'Maximum allowed weight', 'shippcost' ),
                          'default' => 100
                          ),

                     );

                }

                /**
                 * Denna funktion används för att beräkna fraktkostnaden. Inom denna funktion kan man kontrollera för vikter, dimensioner och andra parametrar.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping( $package ) {

                    $weight = 0;
                    $cost = 0;
                    $country = $package["destination"]["country"];

                    foreach ( $package['contents'] as $item_id => $values )
                    {
                        $_product = $values['data'];
                        $weight = $weight + $_product->get_weight() * $values['quantity'];
                    }

                    $weight = wc_get_weight( $weight, 'kg' );

                    if( $weight <= 1 ) {

                        $cost = 30;

                    } elseif( $weight <= 3 ) {

                        $cost = 60;

                    } elseif( $weight <= 5 ) {

                        $cost = 100;

                      } elseif( $weight <= 10 ) {

                          $cost = 200;

                    } else {

                        $cost = 10;

                    }

                    $countryZones = array(
                        'SE' => 0,
                        );

                    $zonePrices = array(
                        0 => 0,
                        1 => 30,
                        2 => 50,
                        3 => 70
                        );

                    $zoneFromCountry = $countryZones[ $country ];
                    $priceFromZone = $zonePrices[ $zoneFromCountry ];

                    $cost += $priceFromZone;

                    $rate = array(
                        'id' => $this->id,
                        'label' => $this->title,
                        'cost' => $cost
                    );

                    $this->add_rate( $rate );

                }
            }
        }
    }

    add_action( 'woocommerce_shipping_init', 'shippcost_shipping_method' );

    function add_shippcost_shipping_method( $methods ) {
        $methods[] = 'ShippCost_Shipping_Method';
        return $methods;
    }

    add_filter( 'woocommerce_shipping_methods', 'add_shippcost_shipping_method' );

    function shippcost_validate_order( $posted )   {

        $packages = WC()->shipping->get_packages();

        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

        if( is_array( $chosen_methods ) && in_array( 'shippcost', $chosen_methods ) ) {

            foreach ( $packages as $i => $package ) {

                if ( $chosen_methods[ $i ] != "shippcost" ) {

                    continue;

                }

                $ShippCost_Shipping_Method = new ShippCost_Shipping_Method();
                $weightLimit = (int) $ShippCost_Shipping_Method->settings['weight'];
                $weight = 0;

                foreach ( $package['contents'] as $item_id => $values )
                {
                    $_product = $values['data'];
                    $weight = $weight + $_product->get_weight() * $values['quantity'];
                }

                $weight = wc_get_weight( $weight, 'kg' );

                if( $weight > $weightLimit ) {

                        $message = sprintf( __( 'Sorry, %d kg exceeds the maximum weight of %d kg for %s', 'shippcost' ), $weight, $weightLimit, $ShippCost_Shipping_Method->title );

                        $messageType = "error";

                        if( ! wc_has_notice( $message, $messageType ) ) {

                            wc_add_notice( $message, $messageType );

                        }
                }
            }
        }
    }

    add_action( 'woocommerce_review_order_before_cart_contents', 'shippcost_validate_order' , 10 );
    add_action( 'woocommerce_after_checkout_validation', 'shippcost_validate_order' , 10 );
}
