<?php

namespace CargoTrackingForWooCommerce\Woocommerce;

use CargoTrackingForWooCommerce\View\View;

class BackEndTable
{
    public function __construct()
    {
        add_action('manage_shop_order_posts_custom_column', [$this, 'column'], 50);
        add_filter('manage_edit-shop_order_columns', [$this, 'columns']);
    }
    public function column($column)
    {
        if ('cargo_tracking_for_woocommerce' !== $column) {
            return;
        }
        global $the_order;

        if (!empty(get_post_meta($the_order->get_id(), 'cargo_tracking_for_woocommerce-data'))) {
            $data = get_post_meta($the_order->get_id(), 'cargo_tracking_for_woocommerce-data')[0];
            View::icon($data);
        }
    }

    public function columns($columns)
    {
        $ek['cargo_tracking_for_woocommerce'] = __('Cargo Tracking', 'cargo_tracking_for_woocommerce');
        $columns = array_slice($columns, 0, 4, true) + $ek + array_slice($columns, 4, NULL, true);
        return $columns;
    }
}
