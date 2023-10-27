import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'collection',
    category: 'form',
    label: 'Collection Form',
    component: 'sw-cms-block-collection',
    previewComponent: 'sw-cms-preview-collection',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed'
    },
    slots: {
        collectionForm: 'collection-form',
    },
});