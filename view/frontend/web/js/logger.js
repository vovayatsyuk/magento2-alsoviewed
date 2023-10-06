define([
    'jquery'
], function ($) {
    'use strict';

    var key = 'alsoviewed_ids',
        result;

    function isRegistered(id, limit) {
        return (localStorage.getItem(key) || '').split(',').slice(0, limit).includes(id);
    }

    function register(id) {
        var data = (localStorage.getItem(key) || `${id}`).split(',');

        data.unshift(id);
        data = [...new Set(data)]; // unique
        data = data.slice(0, 30);

        localStorage.setItem(key, data.join(','));
    }

    result = function (config) {
        if (isRegistered(config.id, config.limit)) {
            return register(config.id);
        }

        register(config.id);

        $.ajax({
            method: 'post',
            global: false,
            url: config.url,
            data: {
                id: config.id
            }
        });
    };

    result.component = 'Vovayatsyuk_Alsoviewed/js/logger';

    return result;
});
