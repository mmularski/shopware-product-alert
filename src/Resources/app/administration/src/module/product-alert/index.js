import './page/product-alert-list';
import './page/product-alert-details';
import enGB from './snippet/en-GB';

Shopware.Module.register('product-alert', {
    color: '#ff3d58',
    icon: 'default-chart-bar',
    title: 'Product Alert',
    description: 'List of products and subscribers count for out of stock notification.',

    snippets: {
        'en-GB': enGB
    },

    routes: {
        list: {
            component: 'product-alert-list',
            path: 'list'
        },
        details: {
            component: 'product-alert-details',
            path: 'details/:id',
            meta: {
                parentPath: 'product.alert.list'
            }
        }
    },

    navigation: [{
        label: 'Product Alert',
        color: '#ff3d58',
        path: 'product.alert.list',
        icon: 'default-chart-bar',
        parent: 'sw-catalogue',
        position: 100
    }]
});