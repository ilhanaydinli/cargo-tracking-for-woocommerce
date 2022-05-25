<?php

if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

if (get_option('cargo_tracking_for_woocommerce_deletePluginData') == 'yes') {
    $orders = wc_get_orders([
        'limit' => -1,
        'return' => 'ids'
    ]);
    foreach ($orders as $id) {
        delete_post_meta($id, 'cargo_tracking_for_woocommerce-data');
    }
    delete_option('cargo_tracking_for_woocommerce');
    delete_option('cargo_tracking_for_woocommerce_emailCheck');
    delete_option('cargo_tracking_for_woocommerce_emailHeading');
    delete_option('cargo_tracking_for_woocommerce_emailSubject');
    delete_option('cargo_tracking_for_woocommerce_emailDescription');
    delete_option('cargo_tracking_for_woocommerce_emailTemplates');
    delete_option('cargo_tracking_for_woocommerce_disablePluginStatus');
    delete_option('cargo_tracking_for_woocommerce_deletePluginData');
}
