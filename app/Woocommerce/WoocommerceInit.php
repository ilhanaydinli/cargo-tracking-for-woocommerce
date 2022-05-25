<?php

namespace App\Woocommerce;

use App\Woocommerce\Email;
use App\Woocommerce\Status;
use App\Woocommerce\SettingsField;
use App\Woocommerce\WCSettings;
use App\Woocommerce\FrontEndTable;
use App\Woocommerce\BackEndTable;

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
