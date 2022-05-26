<?php

namespace CargoTrackingForWooCommerce\Base;

class Assets
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'register_admin']);
        add_action('wp_enqueue_scripts', [$this, 'register_wp']);
        add_filter(
            'plugin_action_links_cargo-tracking-for-woocommerce/CargoTrackingForWooCommerce.php',
            [$this, 'add_plugin_page_settings_link']
        );
    }

    public function register_admin()
    {
        wp_enqueue_media();
        wp_enqueue_script(
            'js',
            plugin_dir_url(dirname(__FILE__, 2)) . 'assets/js/admin.js'
        );
        wp_localize_script(
            'js',
            'cargo_tracking_for_woocommerce',
            [
                'selectImage'  => __('Select Image', 'cargo_tracking_for_woocommerce'),
                'areYouSure'  => __('Are you sure?', 'cargo_tracking_for_woocommerce'),
            ]
        );
        wp_enqueue_style(
            'style',
            plugin_dir_url(dirname(__FILE__, 2)) . 'assets/css/style.css'
        );
    }

    public function register_wp()
    {
        wp_enqueue_style(
            'style',
            plugin_dir_url(dirname(__FILE__, 2)) . 'assets/css/style.css'
        );
    }

    public function add_plugin_page_settings_link($links)
    {
        $links[] = '<a href="' .
            admin_url('admin.php?page=wc-settings&tab=cargo_tracking_for_woocommerce') .
            '">' . __('Settings', 'cargo_tracking_for_woocommerce') . '</a>';
        return $links;
    }
}
