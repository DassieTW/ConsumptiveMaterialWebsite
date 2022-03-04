/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
global.$ = global.jQuery = require('jquery');
window.Vue = require('vue').default;

import { Notyf } from 'notyf';
window.Notyf = Notyf;
import 'notyf/notyf.min.css'; // for React, Vue and Svelte
import "../sass/app.scss";

// AdminKit (required)
import "./modules/bootstrap";
import "./modules/sidebar";
import "./modules/theme";
import "./modules/feather";

// Charts
import "./modules/chartjs";

// Forms
import "./modules/flatpickr";

// Maps
// import "./modules/vector-maps"; // not used for now
// end of AdminKit imports

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// vue3
import { createApp } from 'vue';
import Vue3Langjs from 'vue3-langjs';
import vueTranslations from './vue-translations';
import router from './vue-router/route';
import BreadCrumbNav from './components/breadcrumb/BreadCrumbNav.vue';

const app = createApp({
    components: {
        'vue-bread-crumb': BreadCrumbNav,
    }
})
app.use(Vue3Langjs, {
    messages: vueTranslations, 
    // the locale file gen by command "php artisan lang:js resources/js/vue-translations.js --no-lib --quiet"
});
app.use(router);
app.mount('#breadcrumbnav');
