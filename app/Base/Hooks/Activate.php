<?php

namespace CargoTrackingForWooCommerce\Base\Hooks;

class Activate
{
    public function __construct()
    {
        $file = plugin_dir_path(dirname(__FILE__, 3)) . 'CargoTrackingForWooCommerce.php';
        register_activation_hook($file,  [$this, 'activate']);

        $this->assetDir = plugin_dir_url(dirname(__FILE__, 3)) . 'assets/img/';
    }

    public function activate()
    {
        $cargoCompanies = get_option('cargo_tracking_for_woocommerce');

        $cargoCompaniesYeni['aras-kargo'] = [
            'key' => 'aras-kargo',
            'img' => $this->assetDir . 'aras.png',
            'company' => 'Aras Kargo',
            'description' => __('Your order was sent via [company] on [shipping_date]. Cargo Tracking Code: [tracking_code]', 'cargo_tracking_for_woocommerce'),
            'url' => 'https://kargotakip.araskargo.com.tr/mainpage.aspx?code=[tracking_code]',
        ];

        $cargoCompaniesYeni['yurt-ici-kargo'] = [
            'key' => 'yurt-ici-kargo',
            'img' => $this->assetDir . 'yurtici.png',
            'company' => 'Yurt İçi Kargo',
            'description' => __('Your order was sent via [company] on [shipping_date]. Cargo Tracking Code: [tracking_code]', 'cargo_tracking_for_woocommerce'),
            'url' => 'https://selfservis.yurticikargo.com/reports/SSWDocumentDetail.aspx?DocId=[tracking_code]',
        ];

        $cargoCompaniesYeni['surat-kargo'] = [
            'key' => 'surat-kargo',
            'img' => $this->assetDir . 'surat.png',
            'company' => 'Sürat Kargo',
            'description' => __('Your order was sent via [company] on [shipping_date]. Cargo Tracking Code: [tracking_code]', 'cargo_tracking_for_woocommerce'),
            'url' => 'http://suratkargo.com.tr/KargoTakip/?kargotakipno=[tracking_code]',
        ];

        $cargoCompaniesYeni['mng-kargo'] = [
            'key' => 'mng-kargo',
            'img' => $this->assetDir . 'mng.png',
            'company' => 'MNG Kargo',
            'description' => __('Your order was sent via [company] on [shipping_date]. Cargo Tracking Code: [tracking_code]', 'cargo_tracking_for_woocommerce'),
            'url' => 'http://service.mngkargo.com.tr/iactive/popup/kargotakip.asp?k=[tracking_code]',
        ];

        $cargoCompaniesYeni['ptt-kargo'] = [
            'key' => 'ptt-kargo',
            'img' => $this->assetDir . 'ptt.png',
            'company' => 'PTT Kargo',
            'description' => __('Your order was sent via [company] on [shipping_date]. Cargo Tracking Code: [tracking_code]', 'cargo_tracking_for_woocommerce'),
            'url' => 'http://gonderitakip.ptt.gov.tr/?barkod=[tracking_code]',
        ];

        $cargoCompaniesYeni['ups-kargo'] = [
            'key' => 'ups-kargo',
            'img' => $this->assetDir . 'ups.png',
            'company' => 'UPS Kargo',
            'description' => __('Your order was sent via [company] on [shipping_date]. Cargo Tracking Code: [tracking_code]', 'cargo_tracking_for_woocommerce'),
            'url' => 'https://www.ups.com/track?loc=tr_TR&tracknum=[tracking_code]',
        ];

        $merged_options = wp_parse_args($cargoCompanies, $cargoCompaniesYeni);

        if ($cargoCompanies) {
            if (count($cargoCompanies) <= 0) {
                update_option(
                    'cargo_tracking_for_woocommerce',
                    $merged_options
                );
            }
        } else {
            update_option(
                'cargo_tracking_for_woocommerce',
                $merged_options
            );
        }
        if (!get_option('cargo_tracking_for_woocommerce_emailCheck')) {
            update_option('cargo_tracking_for_woocommerce_emailCheck', 'yes');
        }
        if (!get_option('cargo_tracking_for_woocommerce_emailHeading')) {
            update_option(
                'cargo_tracking_for_woocommerce_emailHeading',
                __('Order Shipped', 'cargo_tracking_for_woocommerce')
            );
        }
        if (!get_option('cargo_tracking_for_woocommerce_emailSubject')) {
            update_option(
                'cargo_tracking_for_woocommerce_emailSubject',
                __('{site_title} Order Shipping', 'cargo_tracking_for_woocommerce')
            );
        }
        if (!get_option('cargo_tracking_for_woocommerce_emailDescription')) {
            update_option(
                'cargo_tracking_for_woocommerce_emailDescription',
                __('<p>[description]</p><p>Click <a href="[url]" target="_blank">here</a> to follow your cargo.</p>', 'cargo_tracking_for_woocommerce')
            );
        }
        if (!get_option('cargo_tracking_for_woocommerce_emailTemplates')) {
            update_option(
                'cargo_tracking_for_woocommerce_emailTemplates',
                'WC_Email_Customer_Processing_Order'
            );
        }
        if (!get_option('cargo_tracking_for_woocommerce_disablePluginStatus')) {
            update_option(
                'cargo_tracking_for_woocommerce_disablePluginStatus',
                'wc-processing'
            );
        }
        if (!get_option('cargo_tracking_for_woocommerce_deletePluginData')) {
            update_option(
                'cargo_tracking_for_woocommerce_deletePluginData',
                'no'
            );
        }

        $orders = wc_get_orders([
            'limit' => -1,
        ]);
        foreach ($orders as $order) {
            if ($data = $order->get_meta('cargo_tracking_for_woocommerce-data')) {
                if ($data['status'] == 1) {
                    wp_update_post([
                        'ID' => $order->get_id(),
                        'post_status' => 'wc-cargo_shipping'
                    ]);
                }
            }
        }
    }
}
