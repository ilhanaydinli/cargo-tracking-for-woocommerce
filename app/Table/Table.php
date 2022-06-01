<?php

namespace CargoTrackingForWooCommerce\Table;

class Table extends \WP_List_Table
{

    public function __construct()
    {

        parent::__construct([
            'singular' => 'singular_form',
            'plural'   => 'plural_form',
            'ajax'     => false
        ]);
    }

    public function get_bulk_actions()
    {
        return [
            'delete' => __('Delete', 'cargo_tracking_for_woocommerce'),
            'edit'   => __('Edit', 'cargo_tracking_for_woocommerce'),
        ];
    }

    public function process_bulk_action()
    {
        $action = $this->current_action();

        switch ($action) {

            case 'delete':
                $cargoCompanies = get_option('cargo_tracking_for_woocommerce');
                $keys = isset($_REQUEST['key']) ? wp_parse_slug_list($_REQUEST['key']) : [];
                foreach ($keys as $key) {
                    unset($cargoCompanies[sanitize_key($key)]);
                }
                update_option(
                    'cargo_tracking_for_woocommerce',
                    $cargoCompanies
                );
                wp_safe_redirect('admin.php?page=wc-settings&tab=cargo_tracking_for_woocommerce');
                break;
            case 'edit':
                $keys = isset($_REQUEST['key']) ? wp_parse_slug_list($_REQUEST['key']) : [];

                wp_safe_redirect('admin.php?page=wc-settings&tab=cargo_tracking_for_woocommerce&section=new&key=' . sanitize_key($keys[0]));
                break;
            default:
                // do nothing or something else
                return;
                break;
        }

        return;
    }



    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden   = ['key'];
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort($data, [&$this, 'sort_data']);

        $perPage = 10;
        $currentPage = $this->get_pagenum();

        $totalItems = count($data);
        $this->set_pagination_args([
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ]);

        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        $this->_column_headers = [$columns, $hidden, $sortable];
        $this->items = $data;

        $this->process_bulk_action();
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = [
            'cb'            => '<input type="checkbox">',
            'key'    => 'key',
            'company'       => __('Company', 'cargo_tracking_for_woocommerce'),
            'img'       => __('Logo', 'cargo_tracking_for_woocommerce'),
            'description' => __('Description', 'cargo_tracking_for_woocommerce'),
            'url'        => __('Url', 'cargo_tracking_for_woocommerce'),
        ];

        return $columns;
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return ['company' => ['company', false]];
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        $data = get_option('cargo_tracking_for_woocommerce');
        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
                //case 'id':
            case 'company':
                return '<a href="' . admin_url('admin.php?page=wc-settings&tab=cargo_tracking_for_woocommerce&section=new&key=' . $item['key']) . '">' . $item[$column_name] . '</a>';
            case 'img':

                if (!empty($item[$column_name])) {
                    $src = $item[$column_name];
                } else {
                    $src = plugin_dir_url(dirname(__FILE__, 2)) . 'assets/img/no-image.png';
                }

                return '<img src="' . $src . '" class="cargo-tracking-for-woocommerce-cargo-img"/>';
            case 'description':
            case 'url':
                //case 'action':
                return $item[$column_name];

            default:
                return print_r($item, true);
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data($a, $b)
    {
        // Set defaults
        $orderby = 'company';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = sanitize_key($_GET['orderby']);
        }

        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = sanitize_key($_GET['order']);
        }


        $result = strcmp($a[$orderby], $b[$orderby]);

        if ($order === 'asc') {
            return $result;
        }

        return -$result;
    }

    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="key[]" value="%s" />',
            $item['key']
        );
    }
}
