define([
    'jquery',
    'Vovayatsyuk_Alsoviewed/js/storage'
], function ($, storage) {
    'use strict';

    return function (config) {
        var latestProducts = storage.getIds('recently_viewed_product', config.limit);

        if (latestProducts.indexOf(config.id) > -1) {
            return;
        }

        $.ajax({
            method: 'post',
            global: false,
            url: config.url,
            data: {
                id: config.id
            }
        });
    };
});
