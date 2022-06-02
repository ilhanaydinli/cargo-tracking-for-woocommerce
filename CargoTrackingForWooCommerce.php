<?php

/**
 * Plugin Name:       Cargo Tracking for WooCommerce
 * Plugin URI:        https://ilhanaydinli.com/projeler/cargo-tracking-for-woocommerce
 * Description:       With the WooCommerce cargo tracking plugin, you can add as many cargo companies as you want, show cargo tracking links on the front and admin side, and send cargo tracking emails to users.
 * Version:           1.0.4
 * Requires at least: 5.1
 * Requires PHP:      7.0
 * Author:            İlhan Aydınlı
 * Author URI:        https://ilhanaydinli.com
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       cargo_tracking_for_woocommerce
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once dirname(__FILE__) . '/vendor/autoload.php';

use CargoTrackingForWooCommerce\Base\RegisterServices;
use CargoTrackingForWooCommerce\Woocommerce\WoocommerceInit;

class CargoTrackingForWoocommerce
{
    public function __construct()
    {
        load_plugin_textdomain(
            'cargo_tracking_for_woocommerce',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages'
        );

        if (
            in_array(
                'woocommerce/woocommerce.php',
                apply_filters(
                    'active_plugins',
                    get_option('active_plugins')
                )
            )
        ) {
            new RegisterServices();
            new WoocommerceInit();
        }
    }
}
new CargoTrackingForWoocommerce();
