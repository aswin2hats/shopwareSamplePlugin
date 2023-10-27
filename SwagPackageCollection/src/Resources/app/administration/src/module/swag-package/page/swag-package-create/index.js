const { Component } = Shopware;

Component.extend('swag-package-create', 'swag-package-detail', {
    methods: {
        getPackage() {
            this.package = this.repository.create(Shopware.Context.api);
        },

        onClickSave() {
            this.isLoading = true;

            this.repository.save(this.package, Shopware.Context.api).then(() => {
                this.getPackage();
                this.isLoading = false;
                this.$router.push({ name: 'swag.package.index'});
            }).catch((exception) => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('swag-package.detail.errorTitle'),
                    message: exception
                });
            });
        }
    }
})