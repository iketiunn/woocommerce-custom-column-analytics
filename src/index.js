// Import SCSS entry file so that webpack picks up changes
import './index.scss';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';

const addTableColumn = (reportTableData) => {
    if ('orders' !== reportTableData.endpoint) {
        return reportTableData;
    }

    const newHeaders = [
        ...reportTableData.headers,
        // {
        //     label: __('Payment Method', 'woocommerce-custom-column-analytics'),
        //     key: 'payment_method',
        //     required: false,
        // },
        {
            label: __('Shipping Method', 'woocommerce-custom-column-analytics'),
            key: 'shipping_method',
            required: false,
        },
        {
            label: __('Shipping Name', 'woocommerce-custom-column-analytics'),
            key: 'shipping_name',
            required: false,
        },
        {
            label: __('Shipping Phone', 'woocommerce-custom-column-analytics'),
            key: 'shipping_phone',
            required: false,
        },
        {
            label: __('Shipping Address', 'woocommerce-custom-column-analytics'),
            key: 'shipping_address',
            required: false,
        },
    ];

    const newRows = reportTableData.rows.map((row, index) => {
        const item = reportTableData.items.data[index];
        const newRow = [
            ...row,
            {
                display: item.payment_method || __('N/A', 'woocommerce-custom-column-analytics'),
                value: item.payment_method || '',
            },
            {
                display: item.shipping_method || __('N/A', 'woocommerce-custom-column-analytics'),
                value: item.shipping_method || '',
            },
            {
                display: item.shipping_name || __('N/A', 'woocommerce-custom-column-analytics'),
                value: item.shipping_name || '',
            },
            {
                display: item.shipping_phone || __('N/A', 'woocommerce-custom-column-analytics'),
                value: item.shipping_phone || '',
            },
            {
                display: item.shipping_address || __('N/A', 'woocommerce-custom-column-analytics'),
                value: item.shipping_address || '',
            },
        ];
        return newRow;
    });

    reportTableData.headers = newHeaders;
    reportTableData.rows = newRows;

    return reportTableData;
};

addFilter('woocommerce_admin_report_table', 'woocommerce-custom-column-analytics', addTableColumn);
