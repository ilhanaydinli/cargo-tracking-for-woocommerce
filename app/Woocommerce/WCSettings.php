<?php

namespace CargoTrackingForWooCommerce\Woocommerce;

use CargoTrackingForWooCommerce\Table\TableFunctions;

class WCSettings extends \WC_Settings_Page
{

    public function __construct()
    {
        $this->id    = 'cargo_tracking_for_woocommerce';

        add_filter('woocommerce_settings_tabs_array', [$this, 'add_settings_tab'], 50);
        add_action('woocommerce_sections_' . $this->id, [$this, 'output_sections']);
        add_action('woocommerce_settings_' . $this->id, [$this, 'output']);
        add_action('woocommerce_settings_save_' . $this->id, [$this, 'save']);
    }

    public function add_settings_tab($settings_tabs)
    {
        $settings_tabs[$this->id] = __('Cargo Tracking', 'cargo_tracking_for_woocommerce');
        return $settings_tabs;
    }

    public function get_sections()
    {

        $sections = [
            ''              => __('Cargo Tracking', 'cargo_tracking_for_woocommerce'),
            'new'     => __('New Add', 'cargo_tracking_for_woocommerce'),
            'settings'       => __('Settings', 'cargo_tracking_for_woocommerce'),
        ];

        return apply_filters('woocommerce_get_sections_' . $this->id, $sections);
    }

    public function get_settings($section = null, $data = null)
    {
        if (!isset($data)) {
            $data = [
                'img' => '',
                'company' => '',
                'description' => __('Your order was sent via [company] on [shipping_date]. Cargo Tracking Code: [tracking_code]', 'cargo_tracking_for_woocommerce'),
                'url' => 'https:// [tracking_code]',
            ];
        }

        switch ($section) {
            case 'new':
                $settings = [
                    [
                        'name'     => __('Cargo Company Add', 'cargo_tracking_for_woocommerce'),
                        'type'     => 'title',
                        'desc'  => __('You can add a new cargo company from this section.', 'cargo_tracking_for_woocommerce'),
                    ],
                    [
                        'name' => __('Logo', 'cargo_tracking_for_woocommerce'),
                        'type' => 'img',
                        'desc_tip' => __('Select the picture of the cargo company.', 'cargo_tracking_for_woocommerce'),
                        'id'   => 'img',
                        'default' => $data['img']
                    ],
                    [
                        'name' => __('Cargo Company Name', 'cargo_tracking_for_woocommerce'),
                        'type' => 'text',
                        'desc_tip' => __('Enter the name of the cargo company.', 'cargo_tracking_for_woocommerce'),
                        'id'   => 'company',
                        'default' => $data['company']
                    ],
                    [
                        'name' => __('Description', 'cargo_tracking_for_woocommerce'),
                        'type' => 'textarea',
                        'desc_tip' => __('Text for users cargo tracking section.', 'cargo_tracking_for_woocommerce'), 'desc'  => __('Tags available: [shipping_date], [tracking_code], [img], [company]', 'cargo_tracking_for_woocommerce'),
                        'id'   => 'description',
                        'css'      => 'height: 100px;',
                        'default' => $data['description']
                    ],
                    [
                        'name' => __('Url', 'cargo_tracking_for_woocommerce'),
                        'type' => 'text',
                        'desc_tip' => __('The url where users will follow the cargo.', 'cargo_tracking_for_woocommerce'),
                        'desc'  => __('Tags available: [shipping_date], [tracking_code], [img], [company]', 'cargo_tracking_for_woocommerce'),
                        'id'   => 'url',
                        'default' => $data['url']
                    ],
                    [
                        'type' => 'sectionend',
                    ]
                ];
                break;
            case 'settings':
                $settings = [
                    [
                        'name'     => __('Settings', 'cargo_tracking_for_woocommerce'),
                        'type'     => 'title',
                        'desc'  => __('In this section, you can adjust the cargo tracking plugin.', 'cargo_tracking_for_woocommerce'),
                    ],
                    [
                        'title'    => __('E-Mail Sending', 'cargo_tracking_for_woocommerce'),
                        'desc'     => __('Send email when order status changes to "Order Shipped".', 'cargo_tracking_for_woocommerce'),
                        'desc_tip'  => __('Check this field to send email to users that the order status has been updated to "Order Shipped".', 'cargo_tracking_for_woocommerce'),
                        'id'       => 'cargo_tracking_for_woocommerce_emailCheck',
                        'type'     => 'checkbox',
                    ],
                    [
                        'name' => __('E-Mail Heading', 'cargo_tracking_for_woocommerce'),
                        'type' => 'text',
                        'desc_tip' => __('You can change the e-mail header.', 'cargo_tracking_for_woocommerce'),
                        'desc'     => __('You can use the tags you use in the header section of Woocommerce email templates.', 'cargo_tracking_for_woocommerce'),
                        'id'   => 'cargo_tracking_for_woocommerce_emailHeading',
                    ],
                    [
                        'name' => __('E-Mail Subject', 'cargo_tracking_for_woocommerce'),
                        'type' => 'text',
                        'desc_tip' => __('You can change the e-mail subject.', 'cargo_tracking_for_woocommerce'),
                        'desc'     => __('You can use the tags you use in the subject section of Woocommerce email templates.', 'cargo_tracking_for_woocommerce'),
                        'id'   => 'cargo_tracking_for_woocommerce_emailSubject',
                    ],
                    [
                        'title'    => __('E-Mail and Detail Content', 'cargo_tracking_for_woocommerce'),
                        'desc'     => __('Tags available: [shipping_date.], [tracking_code], [img], [description], [url]', 'cargo_tracking_for_woocommerce'),
                        'type' => 'textarea',
                        'desc_tip'  => __('In this section you can edit the detail text in the email description and order details.', 'cargo_tracking_for_woocommerce'),
                        'id'   => 'cargo_tracking_for_woocommerce_emailDescription',
                        'css'      => 'height: 100px;',
                    ],
                    [
                        'title'    => __('E-Mail Template', 'cargo_tracking_for_woocommerce'),
                        'desc'     => __('You can choose which template to send the sent e-mail to. Recommended template: Processing', 'cargo_tracking_for_woocommerce'),
                        'id'       => 'cargo_tracking_for_woocommerce_emailTemplates',
                        'type'     => 'select',
                        'class'    => 'wc-enhanced-select',
                        'desc_tip' => true,
                        'options'  => $this->get_email_templates(),
                    ],
                    [
                        'title'    => __('Orders Status', 'cargo_tracking_for_woocommerce'),
                        'desc'     => __('When you disable or remove the plugin, the status of orders that have "Order Shipment" status is given.', 'cargo_tracking_for_woocommerce'),
                        'id'       => 'cargo_tracking_for_woocommerce_disablePluginStatus',
                        'type'     => 'select',
                        'class'    => 'wc-enhanced-select',
                        'desc_tip' => true,
                        'options'  => $this->get_order_statuses(),
                    ],
                    [
                        'title'    => __('Delete All Data', 'cargo_tracking_for_woocommerce'),
                        'desc'     => __('Delete all plug-in data when I delete the plug-in.', 'cargo_tracking_for_woocommerce'),
                        'id'       => 'cargo_tracking_for_woocommerce_deletePluginData',
                        'type'     => 'checkbox',
                        'desc_tip' => __('Caution: If you select this option, all cargo data you have entered into orders and all data of the add-on (cargo data, add-on settings, etc.) will be deleted.', 'cargo_tracking_for_woocommerce'),
                    ],
                    [
                        'type' => 'sectionend',
                    ],
                ];
                break;
        }
        return apply_filters('woocommerce_get_settings_' . $this->id, $settings);
    }

    public function get_order_statuses()
    {
        $new_order_statuses = wc_get_order_statuses();
        unset($new_order_statuses['wc-cargo_shipping']);
        return $new_order_statuses;
    }

    public function get_email_templates()
    {
        $mailer          = WC()->mailer()->get_emails();
        $email_templates = [];
        foreach ($mailer as $key => $value) {
            $email_templates[$key] = $value->title;
        }
        return $email_templates;
    }

    public function output()
    {
        global $current_section, $hide_save_button;

        if ('' === $current_section) {
            $hide_save_button = true;
            TableFunctions::get_table();
        } elseif ('new' === $current_section) {
            if (isset($_REQUEST['key'])) {
                $key = sanitize_key($_REQUEST['key']);
                $data = get_option('cargo_tracking_for_woocommerce');
                $settings = $this->get_settings($current_section, $data[$key]);
            } else {
                $settings = $this->get_settings($current_section);
            }
            \WC_Admin_Settings::output_fields($settings);
        } elseif ('settings' === $current_section) {
            $settings = $this->get_settings($current_section);
            \WC_Admin_Settings::output_fields($settings);
        } else {
        }
    }

    public function save()
    {
        global $current_section;

        switch ($current_section) {
            case 'new':
                $sonuc = TableFunctions::post_add($this->get_settings($current_section));
                $this->returnResult($sonuc);
                break;
            case 'settings':
                $settings = $this->get_settings($current_section);
                \WC_Admin_Settings::save_fields($settings);
                break;
        }
    }

    public function returnResult($sonuc)
    {
        if ($sonuc) {
            $messages = __('New cargo company added.', 'cargo_tracking_for_woocommerce');
            wp_safe_redirect('admin.php?page=wc-settings&tab=cargo_tracking_for_woocommerce&msg=' . base64_encode($messages));
        } else {
            \WC_Admin_Settings::add_error(__('Please fill in all fields.', 'cargo_tracking_for_woocommerce'));
        }
    }
}
