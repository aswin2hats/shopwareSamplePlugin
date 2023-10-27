import template from  './swag-package-detail.html.twig';

const { Component, Mixin } = Shopware;

Component.register('swag-package-detail', {
    template,

    inject: [
        'repositoryFactory'
    ],

    mixins: [
        Mixin.getByName('notification')
    ],

    data() {
        return {
            repository: null,
            isLoading: false,
            processSuccess: false,
            package: null
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        columns() {
            return this.getColumn()
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.repository = this.repositoryFactory.create('swag_package');
            this.getPackage();
        },

        getPackage() {
            this.repository.get(this.$route.params.id, Shopware.Context.api).then((entity) => {
                this.package = entity;
            });
        },

        onClickSave() {
            this.isLoading = true;

            this.repository.save(this.package, Shopware.Context.api).then(() => {
                this.getPackage();
                this.isLoading = false;
                this.processSuccess = true;
                this.$router.push({ name: 'swag.package.detail', params: { id: this.package.id }});
            }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('swag-package.detail.errorTitle'),
                    message: exception
                });
            })
        },

        saveFinish() {
            this.processSuccess = false;
        }
    }
});