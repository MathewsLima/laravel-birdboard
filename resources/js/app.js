require('./bootstrap');

window.Vue = require('vue');

Vue.component('theme-switcher', require('./components/ThemeSwitcher.vue').default);

new Vue({
    el: '#app'
});
