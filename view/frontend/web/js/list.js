define([
    'jquery'
], function ($) {
    'use strict';

    function loadProducts(config, el) {
        $(el).attr('aria-busy', true);

        $.ajax({
            url: config.url,
            method: 'post',
            data: {
                block: config.blockData
            }
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
        var tab = $(el).closest('[role="tabpanel"]').prev();

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
