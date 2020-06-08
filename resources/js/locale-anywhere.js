Nova.booting((Vue, router, store) => {
    Vue.component('locale-anywhere-dropdown', require('./components/Dropdown'));
    Vue.component('detail-locale-anywhere', require('./components/DetailField'));
    Vue.component('form-locale-anywhere', require('./components/FormField'));

    if (Nova.config.localeAnywhere.customDetailToolbar) {
        Vue.component('custom-detail-toolbar', require('./components/CustomDetailToolbar'));
    }
});
