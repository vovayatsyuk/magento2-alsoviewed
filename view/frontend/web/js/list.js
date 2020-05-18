define([
    'jquery',
    'underscore'
], function ($, _) {
    'use strict';

    function getProductIdsFromLocalStorage(namespace, limit) {
        var ids = [],
            data = localStorage.getItem(namespace);

        if (!data) {
            return ids;
        }

        try {
            data = JSON.parse(data);
        } catch (e) {
            data = {};
        }

        data = _.sortBy(data, 'added_at');
        ids = _.pluck(data, 'product_id');

        return ids.slice(Math.max(ids.length - limit, 0));
    }

    /**
     * @return {Array}
     */
    function getRecentlyComparedProductIds() {
        return getProductIdsFromLocalStorage('recently_compared_product', 5);
    }

    /**
     * @return {Array}
     */
    function getRecentlyViewedProductIds() {
        return getProductIdsFromLocalStorage('recently_viewed_product', 5);
    }

    /**
     * @param {Object} config
     * @param {Element} el
     */
    function loadProducts(config, el) {
        var data = {
            block: config.blockData
        };

        $(el).attr('aria-busy', true);

        if (config.blockData.basis) {
            if (config.blockData.basis.indexOf('compared') !== -1) {
                data.recently_compared_ids = getRecentlyComparedProductIds().join(',');
            }
            if (config.blockData.basis.indexOf('viewed') !== -1) {
                data.recently_viewed_ids = getRecentlyViewedProductIds().join(',');
            }
        }

        $.ajax({
            url: config.url,
            method: 'post',
            data: data
        }).done(function (json) {
            var content = $(el).children('.block-content').first();

            if ($(json.html).find('.product-item').length) {
                $(el).show();
            }

            content.html(json.html).trigger('contentUpdated');
            content.children().applyBindings();
        }).complete(function () {
            $(el).attr('aria-busy', false);
        });
    }

    return function (config, el) {
        var tab = $(el).closest('.data.item.content').prev();

        if (tab.length) {
            $(el).show();

            if (!tab.hasClass('active')) {
                tab.one('beforeOpen', function () {
                    loadProducts(config, el);
                });
            }

            return;
        }

        loadProducts(config, el);
    }
});
