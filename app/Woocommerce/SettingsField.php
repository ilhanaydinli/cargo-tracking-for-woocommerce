<?php

namespace CargoTrackingForWooCommerce\Woocommerce;

class SettingsField
{
    public function __construct()
    {
        add_action('woocommerce_admin_field_img', [$this, 'field_img']);
    }

    public function field_img($value)
    {
        $option_value = (array) \WC_Admin_Settings::get_option($value['id'],  $value['default']);

        $default_image = plugin_dir_url(dirname(__FILE__, 2)) . 'assets/img/no-image.png';

        if (!empty($option_value[0])) {
            $src = $option_value[0];
            $value2 = $option_value[0];
        } else {
            $src = $default_image;
            $value2 = '';
        }

?>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo wc_help_tip($value['desc_tip']); ?></label>
            </th>
            <td class="forminp forminp-text">

                <div class="upload">
                    <img data-src="<?php echo esc_attr($default_image) ?>" src="<?php echo esc_attr($src) ?>" class="cargo-tracking-for-woocommerce-cargo-img" />
                    <div>
                        <input type="hidden" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" value="<?php echo esc_attr($value2) ?>" />
                        <button type="submit" class="upload_image_button button"><?php _e('İnsert', 'cargo_tracking_for_woocommerce') ?></button>
                        <button type="submit" class="remove_image_button button"><?php _e('Remove', 'cargo_tracking_for_woocommerce') ?></button>
                    </div>
                </div>
            </td>
        </tr>
<?php
    }
}
