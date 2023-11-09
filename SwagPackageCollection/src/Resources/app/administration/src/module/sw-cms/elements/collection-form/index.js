import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'collection-form',
    label: 'sw-cms.elements.form.label',
    component: 'sw-cms-el-collection-form',
    configComponent: 'sw-cms-el-config-collection-form',
    previewComponent: 'sw-cms-el-preview-collection-form',
    defaultConfig: {
        type: {
            source: 'static',
            value: 'Package Collection Form',
        },
        title: {
            source: 'static',
            value: '',
        },
        mailReceiver: {
            source: 'static',
            value: [],
        },
        defaultMailReceiver: {
            source: 'static',
            value: true,
        },
        confirmationText: {
            source: 'static',
            value: '',
        },
    },
    defaultData: {
        mailReceiver: []
    },
});
