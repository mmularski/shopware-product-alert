import template from './product-alert-details.html.twig';

const {Criteria} = Shopware.Data;

Shopware.Component.register('product-alert-details', {
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
            alertData: {id: null, name: null, count: null},
            entity: null
        };
    },

    created() {
        this.repository = this.repositoryFactory.create('product_alert');

        const criteria = this.getCriteria();
        criteria.addFilter(Criteria.equals('product_alert.id', this.$route.params.id));

        this.repository
            .search(criteria, Shopware.Context.api)
            .then((result) => {
                this.entity = result[0];

                this.alertData.id = this.entity.product.id;
                this.alertData.name = this.entity.product.name;

                this.getCount();
            });
    },

    methods: {
        getCriteria() {
            const criteria = new Criteria();
            criteria.addFilter(Criteria.equals('product_alert.isSent', false));
            criteria.addAssociation('product');

            return criteria;
        },
        getCount() {
            const criteria = this.getCriteria();
            criteria.addFilter(Criteria.equals('product_alert.productId', this.entity.product.id));
            criteria.addAggregation(Criteria.terms('count', 'product.id'));

            this.repository
                .search(criteria, Shopware.Context.api)
                .then((result) => {
                    this.alertData.count = result.aggregations.count.buckets[0].count;
                });
        }
    }
});