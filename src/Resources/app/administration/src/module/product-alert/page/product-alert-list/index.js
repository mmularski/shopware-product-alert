import template from './product-alert-list.html.twig';

const {Component, Mixin} = Shopware;
const {Criteria} = Shopware.Data;

Component.register('product-alert-list', {
    template,

    inject: [
        'repositoryFactory',
        'productAlertApiService'
    ],

    mixins: [
        Mixin.getByName('notification')
    ],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            repository: null,
            rows: null,
            processSuccess: false,
            isLoading: false,
            modalVisible: false,
        };
    },

    created() {
        this.fetchData();
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
    },

    methods: {
        fetchData() {
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
        processAlerts() {
            this.closeModal();
            this.createNotificationInfo({
                title: this.$tc('global.default.info'),
                message: this.$tc('product-alert.process.started')
            });

            this.isLoading = true;

            this.productAlertApiService.process().then((response) => {
                this.fetchData();

                this.createNotificationSuccess({
                    title: this.$tc('global.default.success'),
                    message: this.$tc('product-alert.process.success', 0, {count: response.data})
                });
            }).catch(() => {
                this.createNotificationError({
                    title: this.$tc('global.default.error'),
                    message: this.$tc('product-alert.process.error')
                });

                this.processSuccess = false;
            });

            this.processSuccess = true;
            this.isLoading = false;
        },
        processFinish() {
            this.processSuccess = false;
        },
        showModal() {
            this.modalVisible = true;
        },
        closeModal() {
            this.modalVisible = false;
        },
    }
});