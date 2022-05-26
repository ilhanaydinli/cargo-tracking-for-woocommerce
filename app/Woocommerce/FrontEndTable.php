<?php

namespace CargoTrackingForWooCommerce\Woocommerce;

use CargoTrackingForWooCommerce\View\View;

class FrontEndTable
{
    public function __construct()
    {
        add_action('woocommerce_order_details_after_order_table_items', [$this, 'text']);
        add_action('woocommerce_my_account_my_orders_columns', [$this, 'columns']);
        add_action('woocommerce_my_account_my_orders_column_cargo_tracking_for_woocommerce', [$this, 'icon']);
    }

    public function text($order)
    {
        if (
            $order->has_status('cargo_shipping')
            && !empty(get_post_meta($order->get_id(), 'cargo_tracking_for_woocommerce-data'))
        ) {
            $data = get_post_meta($order->get_id(), 'cargo_tracking_for_woocommerce-data')[0];
            View::text($data);
        }
    }

    public function columns($columns)
    {
        $ek['cargo_tracking_for_woocommerce'] = __('Cargo Tracking', 'cargo_tracking_for_woocommerce');
        $columns = array_slice($columns, 0, 4, true) + $ek + array_slice($columns, 4, NULL, true);
        return $columns;
    }

    public function icon($order)
    {
        if (
            $order->has_status('cargo_shipping')
            && !empty(get_post_meta($order->get_id(), 'cargo_tracking_for_woocommerce-data'))
        ) {
            $data = get_post_meta($order->get_id(), 'cargo_tracking_for_woocommerce-data')[0];
            View::icon($data);
        }
    }
}
