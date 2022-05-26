<?php

namespace CargoTrackingForWooCommerce\Woocommerce;

class Status
{
    public function __construct()
    {
        add_action('init', [$this, 'init']);
        add_filter('wc_order_statuses', [$this, 'wc_order_statuses']);
    }

    public function init()
    {
        register_post_status('wc-cargo_shipping', [
            'label'                     => __('Order Shipping', 'cargo_tracking_for_woocommerce'),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop('Order Shipping (%s)', 'Order Shipping (%s)', 'cargo_tracking_for_woocommerce')
        ]);
    }

    public function wc_order_statuses($order_statuses)
    {
        $new_order_statuses = [];
        foreach ($order_statuses as $key => $status) {
            $new_order_statuses[$key] = $status;
            if ('wc-processing' === $key) {
                $new_order_statuses['wc-cargo_shipping'] = __('Order Shipping', 'cargo_tracking_for_woocommerce');
            }
        }
        return $new_order_statuses;
    }
}
