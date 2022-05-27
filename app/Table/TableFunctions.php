<?php

namespace CargoTrackingForWooCommerce\Table;

use CargoTrackingForWooCommerce\Table\Table;

class TableFunctions
{
    public static function get_table()
    {
        $exampleListTable = new Table();
        $exampleListTable->prepare_items();

        if (isset($_GET['msg'])) {
            echo '<div id="message" class="updated inline"><p><strong>' . esc_html(base64_decode($_GET['msg'])) . '</strong></p></div>';
        } elseif (isset($_GET['err'])) {
            echo '<div id="message" class="error inline"><p><strong>' . esc_html(base64_decode($_GET['err'])) . '</strong></p></div>';
        }
?>
        <div class="wrap" id="wpse-list-table">
            <form method="post">
                <div class="wrap">
                    <h1 class="wp-heading-inline show" style="display:inline-block;"><?php _e('Cargo Tracking', 'cargo_tracking_for_woocommerce') ?> </h1>
                    <a href="<?php echo admin_url('admin.php?page=wc-settings&tab=cargo_tracking_for_woocommerce&section=new'); ?>" class=" page-title-action show"><?php _e('New Cargo Company', 'cargo_tracking_for_woocommerce') ?></a>
                    <?php $exampleListTable->display(); ?>

                </div>
            </form>
        </div>
<?php


    }

    public static function post_add()
    {
        $cargoCompanies = get_option('cargo_tracking_for_woocommerce');

        $key = sanitize_key($_REQUEST['key']);
        if (isset($key)) {
            unset($cargoCompanies[$key]);
        }

        if (!empty($_POST['company']) && !empty($_POST['description'])  && !empty($_POST['url'])) {

            $cargoCompaniesNew[sanitize_title($_POST['company'])] = [
                'key' => sanitize_title($_POST['company']),
                'img' => sanitize_url($_POST['img']),
                'company' => sanitize_text_field($_POST['company']),
                'description' => sanitize_text_field($_POST['description']),
                'url' => sanitize_text_field($_POST['url']),
            ];

            $merged_options = wp_parse_args($cargoCompanies, $cargoCompaniesNew);

            update_option(
                'cargo_tracking_for_woocommerce',
                $merged_options
            );
            return true;
        } else {
            return false;
        }
    }
}
