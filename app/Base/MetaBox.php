<?php

namespace CargoTrackingForWooCommerce\Base;

use CargoTrackingForWooCommerce\View\View;

class MetaBox
{
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_order_tracking_metabox'], 10);
        add_action('woocommerce_process_shop_order_meta', [$this, 'save_order_tracking_metabox'], 999);
    }


    public function add_order_tracking_metabox()
    {
        add_meta_box('cargo_tracking_for_woocommerce', __('Tracking Code', 'cargo_tracking_for_woocommerce'), function ($post) {

            $cargoCompanies = get_option('cargo_tracking_for_woocommerce');

            $dataPost = get_post_custom($post->ID);

            $data = isset($dataPost['cargo_tracking_for_woocommerce-data'][0]) ? unserialize($dataPost['cargo_tracking_for_woocommerce-data'][0]) : null;

            $trackingCode = isset($data['tracking_code']) ? $data['tracking_code'] : '';
            $shippingDate = isset($data['shipping_date']) ? $data['shipping_date'] : '';
            $send_email_status = isset($data) ? (isset($data['send_email']) ? $data['send_email'] : null) : 'yes';
            $change_order_type_status = isset($data) ? (isset($data['change_order_type']) ? $data['change_order_type'] : null) : 'yes';

            $cargoCompaniesKeyAndValue[''] = __('Select Cargo Company', 'cargo_tracking_for_woocommerce');
            foreach ($cargoCompanies as $key => $value) {
                $cargoCompaniesKeyAndValue[$key] = $value['company'];
            }

            woocommerce_wp_select([
                'id'      => 'cargo_tracking_for_woocommerce-cargo_company_key',
                'label'   => __('Select Cargo Company', 'cargo_tracking_for_woocommerce'),
                'options' => $cargoCompaniesKeyAndValue,
                'value' => isset($data['key']) ? $data['key'] : '',
                'custom_attributes' =>  isset($data['key']) ? ['disabled' => 'disabled'] : null
            ]);

            woocommerce_wp_text_input([
                'id'      => 'cargo_tracking_for_woocommerce-tracking_code',
                'label'   => __('Tracking Code', 'cargo_tracking_for_woocommerce'),
                'placeholder' => __('Tracking Code', 'cargo_tracking_for_woocommerce'),
                'value' => isset($trackingCode) ? $trackingCode : '',
                'custom_attributes' => isset($data['key']) ? ['disabled' => 'disabled'] : ['autoComplete' => 'none']
            ]);

            woocommerce_wp_text_input([
                'id'      => 'cargo_tracking_for_woocommerce-shipping_date',
                'class' => 'date-picker-field',
                'label'   => __('Shipping Date', 'cargo_tracking_for_woocommerce'),
                'placeholder' => __('Shipping Date', 'cargo_tracking_for_woocommerce'),
                'value' => isset($shippingDate) ? $shippingDate : '',
                'custom_attributes' => isset($data['key']) ? ['disabled' => 'disabled'] : ['autoComplete' => 'none']
            ]);

            woocommerce_wp_checkbox(array(
                'id'      => 'cargo_tracking_for_woocommerce-send_email',
                'label'   => __('Send E-Mail', 'cargo_tracking_for_woocommerce'),
                'value' => isset($send_email_status) ? $send_email_status : '',
                'custom_attributes' => isset($data['key']) ? ['disabled' => 'disabled'] : null,
                'desc_tip' => true,
                'description' =>  __('If you mark it, an email containing the cargo tracking text will be sent.', 'cargo_tracking_for_woocommerce'),
            ));

            woocommerce_wp_checkbox(array(
                'id'      => 'cargo_tracking_for_woocommerce-change_order_type',
                'label'   => __('Change Order Status', 'cargo_tracking_for_woocommerce'),
                'value' => isset($change_order_type_status) ? $change_order_type_status : '',
                'custom_attributes' => isset($data['key']) ? ['disabled' => 'disabled'] : null,
                'desc_tip' => true,
                'description' =>  __('If you mark it, the order status will be updated as shipped.', 'cargo_tracking_for_woocommerce'),
            ));

            if (!empty($data['tracking_code'])) {
                echo '<hr>';
                View::text($data);
            }
?>

            <div class="cargo-tracking-for-woocommerce-meta-box-buttons">
                <button type="submit" id="cargo-tracking-for-woocommerce-link-delete" class="button button-link-delete" name="cargo_tracking_for_woocommerce-delete" value="delete">
                    <?php _e('Delete', 'cargo_tracking_for_woocommerce') ?>
                </button>
                <button type="submit" class="button button-primary" name="cargo_tracking_for_woocommerce-save" value="save">
                    <?php _e('Save', 'cargo_tracking_for_woocommerce') ?>
                </button>
            </div>
<?php

        }, 'shop_order', 'side', 'high');
    }

    public function save_order_tracking_metabox($order_id)
    {
        $cargo_company_key = sanitize_text_field($_POST['cargo_tracking_for_woocommerce-cargo_company_key']);
        $tracking_code = sanitize_text_field($_POST['cargo_tracking_for_woocommerce-tracking_code']);
        $shipping_date = sanitize_text_field($_POST['cargo_tracking_for_woocommerce-shipping_date']);

        $order_status = sanitize_key($_POST['order_status']);
        $send_email = sanitize_key($_POST['cargo_tracking_for_woocommerce-send_email']);
        $change_order_type = sanitize_key($_POST['cargo_tracking_for_woocommerce-change_order_type']);
        $delete = sanitize_key($_POST['cargo_tracking_for_woocommerce-delete']);

        $order = wc_get_order($order_id);

        if ($delete == 'delete') {
            $order->update_status('pending');
            delete_post_meta($order_id, 'cargo_tracking_for_woocommerce-data');
            return;
        }

        if (
            $cargo_company_key != ''
            && isset($tracking_code)
            && isset($shipping_date)
        ) {
            $cargoCompanies = get_option('cargo_tracking_for_woocommerce');

            $img = $cargoCompanies[$cargo_company_key]['img'];


            $description = str_replace(
                ['[shipping_date]', '[tracking_code]', '[img]', '[company]'],
                [
                    $shipping_date,
                    $tracking_code,
                    $cargoCompanies[$cargo_company_key]['img'],
                    $cargoCompanies[$cargo_company_key]['company']
                ],
                $cargoCompanies[$cargo_company_key]['description']
            );

            $url = str_replace(
                ['[shipping_date]', '[tracking_code]', '[img]', '[company]'],
                [
                    $shipping_date,
                    $tracking_code,
                    $cargoCompanies[$cargo_company_key]['img'],
                    $cargoCompanies[$cargo_company_key]['company']
                ],
                $cargoCompanies[$cargo_company_key]['url']
            );

            $data = [
                'key' => $cargo_company_key,
                'img' => $img,
                'tracking_code' => $tracking_code,
                'shipping_date' => $shipping_date,
                'description' => $description,
                'url' => $url,
                'send_email' => $send_email,
                'change_order_type' => $change_order_type,
                'status' => ($order_status == 'wc-cargo_shipping') ? 1 : 0
            ];

            if (metadata_exists('post', $order_id, 'cargo_tracking_for_woocommerce-data')) {
                update_post_meta($order_id, 'cargo_tracking_for_woocommerce-data', $data);
            } else {
                add_post_meta($order_id, 'cargo_tracking_for_woocommerce-data', $data);
            }

            if ($data['change_order_type'] == 'yes') {
                $order->update_status('cargo_shipping');
            }
        }
    }
}
