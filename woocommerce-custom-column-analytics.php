<?php
/**
 * Plugin Name: woocommerce-custom-column-analytics
 *
 * @package WooCommerce\Admin
 */

/**
 * Register the JS and CSS.
 */
function add_extension_register_script() {
    if ( ! class_exists( 'Automattic\WooCommerce\Admin\PageController' ) || ! \Automattic\WooCommerce\Admin\PageController::is_admin_or_embed_page() ) {
        return;
    }

    $script_path = '/build/index.js'; // Points to the compiled JS file
    $script_asset_path = dirname( __FILE__ ) . '/build/index.asset.php';
    $script_asset = file_exists( $script_asset_path ) ? require( $script_asset_path ) : array( 'dependencies' => array(), 'version' => filemtime( $script_path ) );
    $script_url = plugins_url( $script_path, __FILE__ );

    wp_register_script(
        'woocommerce-custom-column-analytics',
        $script_url,
        $script_asset['dependencies'],
        $script_asset['version'],
        true
    );

    wp_register_style(
        'woocommerce-custom-column-analytics',
        plugins_url( '/build/style.css', __FILE__ ), // Points to the compiled CSS file
        array(),
        filemtime( dirname( __FILE__ ) . '/build/style.css' )
    );

    wp_enqueue_script( 'woocommerce-custom-column-analytics' );
    wp_enqueue_style( 'woocommerce-custom-column-analytics' );
}

add_action( 'admin_enqueue_scripts', 'add_extension_register_script' );

/**
 * Add payment method, shipping method, and shipping details to the WooCommerce Analytics Orders data.
 */
add_filter('woocommerce_analytics_orders_select_query', function ($results, $args) {
    if ($results && isset($results->data) && !empty($results->data)) {
        foreach ($results->data as $key => $result) {
            $order = wc_get_order($result['order_id']);
            
            // Retrieve payment method title
            // $payment_method_title = $order ? $order->get_payment_method_title() : '';
            // $results->data[$key]['payment_method'] = $payment_method_title;
            
            // Retrieve shipping method title
            $shipping_method_title = '';
            if ($order) {
                $shipping_methods = $order->get_shipping_methods();
                $method_titles = [];
                foreach ($shipping_methods as $shipping_method) {
                    $method_titles[] = $shipping_method->get_method_title();
                }
                $shipping_method_title = implode(', ', $method_titles);
            }
            $results->data[$key]['shipping_method'] = $shipping_method_title;
            
            // Add shipping details
            if ($order) {
                // Check if we're dealing with a regular order, not a refund
                $is_refund = is_a($order, 'WC_Order_Refund');
                
                // Shipping name
                if (!$is_refund && method_exists($order, 'get_shipping_first_name') && method_exists($order, 'get_shipping_last_name')) {
                    $results->data[$key]['shipping_name'] = trim($order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name());
                } else {
                    $results->data[$key]['shipping_name'] = '';
                }
                
                // Shipping phone - fallback to billing phone if shipping phone doesn't exist
                $shipping_phone = '';
                if (!$is_refund && method_exists($order, 'get_shipping_phone')) {
                    $shipping_phone = $order->get_shipping_phone();
                }
                if (empty($shipping_phone) && method_exists($order, 'get_billing_phone')) {
                    $shipping_phone = $order->get_billing_phone();
                }
                $results->data[$key]['shipping_phone'] = $shipping_phone;
                
                // Shipping address
                if (!$is_refund && 
                    method_exists($order, 'get_shipping_address_1') && 
                    method_exists($order, 'get_shipping_address_2') && 
                    method_exists($order, 'get_shipping_city') && 
                    method_exists($order, 'get_shipping_state') && 
                    method_exists($order, 'get_shipping_postcode') && 
                    method_exists($order, 'get_shipping_country')) {
                    $address_parts = array(
                        $order->get_shipping_address_1(),
                        $order->get_shipping_address_2(),
                        $order->get_shipping_city(),
                        $order->get_shipping_state(),
                        $order->get_shipping_postcode(),
                        $order->get_shipping_country()
                    );
                    $address_parts = array_filter($address_parts);
                    $results->data[$key]['shipping_address'] = implode(', ', $address_parts);
                } else {
                    $results->data[$key]['shipping_address'] = '';
                }
            } else {
                $results->data[$key]['shipping_name'] = '';
                $results->data[$key]['shipping_phone'] = '';
                $results->data[$key]['shipping_address'] = '';
            }
        }
    }

    return $results;
}, 10, 2);

/**
 * Add the payment method, shipping method, and shipping details columns to the exported CSV file.
 */
add_filter('woocommerce_report_orders_export_columns', function ($export_columns){
    // $export_columns['payment_method'] = 'Payment Method';
    $export_columns['shipping_method'] = 'Shipping Method';
    $export_columns['shipping_name'] = 'Shipping Name';
    $export_columns['shipping_phone'] = 'Shipping Phone';
    $export_columns['shipping_address'] = 'Shipping Address';
    return $export_columns;
});

/**
 * Add the payment method, shipping method, and shipping details data to the exported CSV file.
 */
add_filter('woocommerce_report_orders_prepare_export_item', function ($export_item, $item){
    // $export_item['payment_method'] = $item['payment_method'];
    $export_item['shipping_method'] = $item['shipping_method'];
    $export_item['shipping_name'] = $item['shipping_name'];
    $export_item['shipping_phone'] = $item['shipping_phone'];
    $export_item['shipping_address'] = $item['shipping_address'];
    return $export_item;
}, 10, 2);
