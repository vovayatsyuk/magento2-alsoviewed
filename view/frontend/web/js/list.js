define([
    'jquery',
    'Vovayatsyuk_Alsoviewed/js/storage'
], function ($, storage) {
    'use strict';

    var result;

    /**
     * @return {Array}
     */
    function getRecentlyComparedProductIds() {
        return storage.getIds('recently_compared_product', 5);
    }

    /**
     * @return {Array}
     */
    function getRecentlyViewedProductIds() {
        return storage.getIds('recently_viewed_product', 5);
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
            data: data,
            success: function (json) {
                var content = $(el).children('.block-content').first();

                if ($(json.html).find('.product-item').length) {
                    $(el).show();
                }

                content.html(json.html).trigger('contentUpdated');
                content.children().applyBindings();
            },
            complete: function () {
                $(el).attr('aria-busy', false);
            }
        });
    }

    result = function (config, el) {
        var container = $(el).closest('.product.data.items'),
            index = container.find('.data.item.content').index(el),
            tab = container.find('.data.item.title').eq(index);

        if (tab.length) {
            $(el).show();

            if (!tab.hasClass('active')) {
                tab.one('beforeOpen collapsible:beforeOpen', function () {
                    loadProducts(config, el);
                });
            }

            return;
        }

        loadProducts(config, el);
    };

    result.component = 'Vovayatsyuk_Alsoviewed/js/list';

    return result;
});
