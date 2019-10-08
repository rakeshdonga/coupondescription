define([
    'Magento_SalesRule/js/view/summary/discount'
], function (Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Vendor_Quote/cart/totals/discount'
        },

        getDescription: function() {
            if (!this.totals()) {
                return null;
            }
            if (typeof this.totals()['extension_attributes'] === 'undefined') return null;
            return this.totals()['extension_attributes']['coupon_description'];
        },

        /**
         * @override
         *
         * @returns {Boolean}
         */
        isDisplayed: function () {
            return this.getPureValue() != 0; //eslint-disable-line eqeqeq
        }
    });
});