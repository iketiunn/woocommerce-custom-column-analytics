(()=>{"use strict";const e=window.wp.hooks,o=window.wp.i18n;(0,e.addFilter)("woocommerce_admin_report_table","woocommerce-custom-column-analytics",(e=>{if("orders"!==e.endpoint)return e;const i=[...e.headers,{label:(0,o.__)("Shipping Method","woocommerce-custom-column-analytics"),key:"shipping_method",required:!1},{label:(0,o.__)("Shipping Name","woocommerce-custom-column-analytics"),key:"shipping_name",required:!1},{label:(0,o.__)("Shipping Phone","woocommerce-custom-column-analytics"),key:"shipping_phone",required:!1},{label:(0,o.__)("Shipping Address","woocommerce-custom-column-analytics"),key:"shipping_address",required:!1}],n=e.rows.map(((i,n)=>{const s=e.items.data[n];return[...i,{display:s.payment_method||(0,o.__)("N/A","woocommerce-custom-column-analytics"),value:s.payment_method||""},{display:s.shipping_method||(0,o.__)("N/A","woocommerce-custom-column-analytics"),value:s.shipping_method||""},{display:s.shipping_name||(0,o.__)("N/A","woocommerce-custom-column-analytics"),value:s.shipping_name||""},{display:s.shipping_phone||(0,o.__)("N/A","woocommerce-custom-column-analytics"),value:s.shipping_phone||""},{display:s.shipping_address||(0,o.__)("N/A","woocommerce-custom-column-analytics"),value:s.shipping_address||""}]}));return e.headers=i,e.rows=n,e}))})();
//# sourceMappingURL=index.js.map