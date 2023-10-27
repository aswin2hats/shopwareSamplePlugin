import template from './sw-cms-el-collection-form.html.twig';
import './sw-cms-el-collection-form.scss';

const { Component, Mixin } = Shopware;

Component.register('sw-cms-el-collection-form', {
    template,

    mixins: [
        Mixin.getByName('cms-element'),
    ],

    created() {
        console.log("dfsfsf");
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.initElementConfig('collection-form');
        },
    },
});