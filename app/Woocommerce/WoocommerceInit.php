<?php

namespace CargoTrackingForWooCommerce\Woocommerce;

use CargoTrackingForWooCommerce\Woocommerce\Email;
use CargoTrackingForWooCommerce\Woocommerce\Status;
use CargoTrackingForWooCommerce\Woocommerce\SettingsField;
use CargoTrackingForWooCommerce\Woocommerce\WCSettings;
use CargoTrackingForWooCommerce\Woocommerce\FrontEndTable;
use CargoTrackingForWooCommerce\Woocommerce\BackEndTable;

class WoocommerceInit
{
    public function __construct()
    {
        new SettingsField();
        new Email();
        new Status();
        new FrontEndTable();
        new BackEndTable();
        add_filter('woocommerce_get_settings_pages', [$this, 'settings_page']);
    }

    public function settings_page($settings)
    {
        $settings[] = new WCSettings();
        return $settings;
    }
}
