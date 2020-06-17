import template from './product-alert-list.html.twig';

const {Criteria} = Shopware.Data;

Shopware.Component.register('product-alert-list', {
    template,

    inject: [
        'repositoryFactory'
    ],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            repository: null,
            rows: null
        };
    },

    created() {
        this.repository = this.repositoryFactory.create('product_alert');

        const criteria = new Criteria();
        criteria.addFilter(Criteria.equals('product_alert.isSent', false));
        criteria.addAssociation('product');

        this.repository
            .search(criteria, Shopware.Context.api)
            .then((result) => {
                this.rows = result;
            });
    },

    computed: {
        columns() {
            return [
                {
                    property: 'product.id',
                    dataIndex: 'product.id',
                    label: this.$tc('product-alert.list.columnId'),
                    allowResize: true,
                    primary: true,
                    routerLink: 'product.alert.details'
                },
                {
                    property: 'product.name',
                    dataIndex: 'product.name',
                    label: this.$tc('product-alert.list.columnName'),
                    allowResize: true,
                    primary: false,
                    routerLink: 'product.alert.details'
                },
                {
                    property: 'email',
                    dataIndex: 'email',
                    label: this.$tc('product-alert.list.columnEmail'),
                    allowResize: true,
                    primary: false
                }
            ];
        }
    }
});