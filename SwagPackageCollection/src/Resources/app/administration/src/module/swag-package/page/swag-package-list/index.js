import template from  './swag-package-list.html.twig';

const { Component } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('swag-package-list', {
    template,

    inject: [
        'repositoryFactory'
    ],

    data() {
        return {
            repository: null,
            packages: null
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
        this.createComponent();
    },

    methods: {
        createComponent() {
            this.repository = this.repositoryFactory.create('swag_package');
            this.repository.search(new Criteria(), Shopware.Context.api).then((result) => {
                this.packages = result;
            });
        },

        getColumn() {
            return [{
                property: 'name',
                label: this.$tc('swag-package.list.columnName'),
                routerLink: 'swag.package.detail',
                inlineEdit: 'string',
                allowResize: true,
                primary: true,
            }, {
                property: 'height',
                label: this.$tc('swag-package.list.columnHeight'),
                inlineEdit: 'int',
                allowResize: true,
            }, {
                property: 'width',
                label: this.$tc('swag-package.list.columnWidth'),
                inlineEdit: 'int',
                allowResize: true,
            }, {
                property: 'length',
                label: this.$tc('swag-package.list.columnLength'),
                inlineEdit: 'int',
                allowResize: true,
            }]
        }
    }
});