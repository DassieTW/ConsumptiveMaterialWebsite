/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

global.$ = global.jQuery = require("jquery");
require("./bootstrap");
window.Vue = require("vue").default;

import { Notyf } from "notyf";
window.Notyf = Notyf;
import "notyf/notyf.min.css"; // for React, Vue and Svelte
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
import { createApp } from "vue";
import Vue3Langjs from "vue3-langjs";
import vueTranslations from "./vue-translations";
import router from "./vue-router/route";
import BreadCrumbNav from "./components/breadcrumb/BreadCrumbNav.vue";
import BasicInfoTable from "./components/reusableUtilities/BasicInfoTable.vue";
import InboundSearchTable from "./components/reusableUtilities/InboundSearchTable.vue";
import InboundStockTable from "./components/reusableUtilities/InboundStockTable.vue";
import InboundMonthTable from "./components/reusableUtilities/InboundMonthTable.vue";
import OutboundPickrecordTable from "./components/reusableUtilities/OutboundPickrecordTable.vue";
import OutboundBackrecordTable from "./components/reusableUtilities/OutboundBackrecordTable.vue";
import TransitSearchTable from "./components/reusableUtilities/TransitSearchTable.vue";
import SxbSearchTable from "./components/reusableUtilities/SxbSearchTable.vue";
import NotmonthSearchTable from "./components/reusableUtilities/NotmonthSearchTable.vue";
import OboundInboundSearchTable from "./components/reusableUtilities/OboundINSearchTable.vue";
import OboundISNSearchTable from "./components/reusableUtilities/OboundISNSearch.vue";
import OboundPickrecordTable from "./components/reusableUtilities/OboundPickrecordTable.vue";
import OboundBackrecordTable from "./components/reusableUtilities/OboundBackrecordTable.vue";
import OboundStockTable from "./components/reusableUtilities/OboundStockTable.vue";
import NewsTable from "./components/reusableUtilities/NewsTable.vue";

const app = createApp({
  components: {
    "vue-bread-crumb": BreadCrumbNav,
    "basic-info-table": BasicInfoTable,
    "inbound-search-table": InboundSearchTable,
    "inbound-stock-table": InboundStockTable,
    "inbound-month-table": InboundMonthTable,
    "outbound-pickrecord-table": OutboundPickrecordTable,
    "outbound-backrecord-table": OutboundBackrecordTable,
    "transit-search-table": TransitSearchTable,
    "sxb-search-table": SxbSearchTable,
    "notmonth-search-table": NotmonthSearchTable,
    "obound-insearch-table": OboundInboundSearchTable,
    "obound-isnsearch-table": OboundISNSearchTable,
    "obound-pickrecord-table": OboundPickrecordTable,
    "obound-backrecord-table": OboundBackrecordTable,
    "obound-stock-table": OboundStockTable,
    "news-table": NewsTable,
  },
});

app.use(Vue3Langjs, {
  messages: vueTranslations,
  // the locale file gen by command "php artisan lang:js resources/js/vue-translations.js --no-lib --quiet"
});

app.use(router);
app.mount("#mountingPoint");
