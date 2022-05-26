<?php

namespace CargoTrackingForWooCommerce\Woocommerce;

use CargoTrackingForWooCommerce\View\View;

class Email
{
    public function __construct()
    {
        if (get_option('cargo_tracking_for_woocommerce_emailCheck') == 'yes') {
            add_action('woocommerce_email_before_order_table', [$this, 'text'], 20, 4);
            add_filter('woocommerce_email_actions', [$this, 'actions']);
            add_action('woocommerce_order_status_cargo_shipping', [$this, 'email'], 20, 2);
        }
    }

    public function text($order, $sent_to_admin, $plain_text, $email)
    {
        if (
            $email->id == 'customer_processing_order'
            && $order->has_status('cargo_shipping')
            && !empty(get_post_meta($order->get_id(), 'cargo_tracking_for_woocommerce-data'))
        ) {
            $data = get_post_meta($order->get_id(), 'cargo_tracking_for_woocommerce-data')[0];
            View::text($data);
        }
    }

    public function actions($actions)
    {
        $actions[] = 'woocommerce_order_status_wc-cargo_shipping';
        return $actions;
    }

    public function email($order_id, $order)
    {
        $dataPost = get_post_custom($order_id);

        $data = isset($dataPost['cargo_tracking_for_woocommerce-data'][0]) ? unserialize($dataPost['cargo_tracking_for_woocommerce-data'][0]) : null;

        if ($data['send_email'] == 'yes') {
            $template  = get_option('cargo_tracking_for_woocommerce_emailTemplates');

            $heading = get_option('cargo_tracking_for_woocommerce_emailHeading');
            $subject = get_option('cargo_tracking_for_woocommerce_emailSubject');

            $mailer = WC()->mailer()->get_emails();

            $mailer[$template]->heading = $heading;
            $mailer[$template]->settings['heading'] = $heading;
            $mailer[$template]->subject = $subject;
            $mailer[$template]->settings['subject'] = $subject;

            $mailer[$template]->trigger($order_id);
        }
    }
}
