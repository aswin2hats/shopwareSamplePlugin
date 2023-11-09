import './page/swag-package-list';
import './page/swag-package-create';
import './page/swag-package-detail';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

const { Module } = Shopware;

Module.register('swag-package', {
    type: 'plugin',
    name: 'package',
    title: 'swag-package.general.mainMenuItemGeneral',
    description: 'swag-package.general.descriptionTextModule',
    color: '#ff3d58',
    icon: 'default-shopping-paper-bag-product',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        index: {
            component: 'swag-package-list',
            path: 'index'
        },
        create: {
            component: 'swag-package-create',
            path: 'create',
            meta: {
                patentPath: 'swag.package.index'
            }
        },
        detail: {
            component: 'swag-package-detail',
            path: 'detail/:id',
            meta: {
                patentPath: 'swag.package.index'
            }
        }
    },


    settingsItem: [{
        to: 'swag.package.index',
        group: 'plugins',
        icon: 'default-shopping-paper-bag-product',
        name: 'swag-package.general.mainMenuItemGeneral',
        icon: 'default-shopping-paper-bag-product',
    }]
    // navigation: [{
    //     parent: 'sw-extension',
    //     label: 'swag-package.general.mainMenuItemGeneral',
    //     color: '#ff3d58',
    //     path: 'swag.package.index',
    //     icon: 'default-shopping-paper-bag-product',
    //     position: 100,
    // }]
});