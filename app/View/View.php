<?php

namespace CargoTrackingForWooCommerce\View;

class View
{
    public static function icon($data)
    {

        if (!empty($data['img'])) {
            $src = $data['img'];
        } else {
            $src = plugin_dir_url(dirname(__FILE__, 2)) . 'assets/img/no-image.png';
        }

        echo '<div class="cargo-tracking-for-woocommerce-tooltipdiv">
                <a target="_blank" href="' . esc_url($data['url']) . '" class="cargo-tracking-for-woocommerce-tooltipa"><img src="' . esc_attr($src) . '" class="cargo-tracking-for-woocommerce-cargo-img"/></a>
                <span class="cargo-tracking-for-woocommerce-tooltip">' . esc_html($data['description']) . '</span></div>';
    }

    public static function text($data)
    {
        $emailDescripton = get_option('cargo_tracking_for_woocommerce_emailDescription');

        $emailDescriptonReplace = str_replace(
            ['[shipping_date]', '[tracking_code]', '[img]', '[description]', '[url]'],
            [
                $data['shipping_date'],
                $data['tracking_code'],
                $data['img'],
                $data['description'],
                $data['url']
            ],
            $emailDescripton
        );

        echo wp_kses_post($emailDescriptonReplace);
    }
}
