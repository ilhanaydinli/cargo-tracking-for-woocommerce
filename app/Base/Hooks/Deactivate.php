<?php

namespace CargoTrackingForWooCommerce\Base\Hooks;

class Deactivate
{
    public function __construct()
    {
        $file = plugin_dir_path(dirname(__FILE__, 3)) . 'CargoTrackingForWooCommerce.php';
        register_deactivation_hook($file, [$this, 'setOrders']);
    }

    public function setOrders()
    {
        $orders = wc_get_orders([
            'limit' => -1,
            'post_status' => 'wc-cargo_shipping',
        ]);
        $status = get_option('cargo_tracking_for_woocommerce_disablePluginStatus');
        foreach ($orders as $order) {
            $order->update_status($status);
        }
    }
}
