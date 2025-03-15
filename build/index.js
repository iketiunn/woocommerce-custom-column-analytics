(()=>{"use strict";const e=window.wp.hooks,o=window.wp.i18n;(0,e.addFilter)("woocommerce_admin_report_table","woocommerce-custom-column-analytics",(e=>{if("orders"!==e.endpoint)return e;const i=[...e.headers,{label:(0,o.__)("Shipping Method","woocommerce-custom-column-analytics"),key:"shipping_method",required:!1},{label:(0,o.__)("Shipping Name","woocommerce-custom-column-analytics"),key:"shipping_name",required:!1},{label:(0,o.__)("Shipping Phone","woocommerce-custom-column-analytics"),key:"shipping_phone",required:!1},{label:(0,o.__)("Shipping Address","woocommerce-custom-column-analytics"),key:"shipping_address",required:!1}],s=e.rows.map(((i,s)=>{const n=e.items.data[s];return[...i,{display:n.shipping_method||(0,o.__)("N/A","woocommerce-custom-column-analytics"),value:n.shipping_method||""},{display:n.shipping_name||(0,o.__)("N/A","woocommerce-custom-column-analytics"),value:n.shipping_name||""},{display:n.shipping_phone||(0,o.__)("N/A","woocommerce-custom-column-analytics"),value:n.shipping_phone||""},{display:n.shipping_address||(0,o.__)("N/A","woocommerce-custom-column-analytics"),value:n.shipping_address||""}]}));return e.headers=i,e.rows=s,e}))})();
//# sourceMappingURL=index.js.map