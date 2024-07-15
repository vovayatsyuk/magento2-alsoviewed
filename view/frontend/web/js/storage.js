define([
    'underscore'
], function (_) {
    'use strict';

    return {
        component: 'Vovayatsyuk_Alsoviewed/js/storage',
        getIds: function (namespace, limit) {
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
    };
});
