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
import MatsInfoTable from "./components/reusableUtilities/MatsInfoTable.vue";
import MatsInfoUploadTable from "./components/reusableUtilities/MatsInfoUploadTable.vue";
import InboundSearchTable from "./components/reusableUtilities/InboundSearchTable.vue";
import InboundStockTable from "./components/reusableUtilities/InboundStockTable.vue";
import InboundMonthTable from "./components/reusableUtilities/InboundMonthTable.vue";
import InboundStockUploadTable from "./components/reusableUtilities/InboundStockUploadTable.vue";
import OutboundPickrecordTable from "./components/reusableUtilities/OutboundPickrecordTable.vue";
import OutboundBackrecordTable from "./components/reusableUtilities/OutboundBackrecordTable.vue";
import TransitSearchTable from "./components/reusableUtilities/TransitSearchTable.vue";
import SxbSearchTable from "./components/reusableUtilities/SxbSearchTable.vue";
import UnitConsumptionUploadTable from "./components/reusableUtilities/UnitConsumptionUploadTable.vue";
import MonthlyPRTable from "./components/reusableUtilities/MonthlyPRTable.vue";
import NonMonthlyPRTable from "./components/reusableUtilities/NonMonthlyPRTable.vue";
import CombinedMonthlyPRSearchTable from "./components/reusableUtilities/CombinedMonthlyPRSearchTable.vue"
import OboundInboundSearchTable from "./components/reusableUtilities/OboundINSearchTable.vue";
import OboundISNSearchTable from "./components/reusableUtilities/OboundISNSearch.vue";
import OboundPickrecordTable from "./components/reusableUtilities/OboundPickrecordTable.vue";
import OboundBackrecordTable from "./components/reusableUtilities/OboundBackrecordTable.vue";
import OboundStockTable from "./components/reusableUtilities/OboundStockTable.vue";
import NewsTable from "./components/reusableUtilities/NewsTable.vue";
import DemandRecieveDiffPage from './components/reusableUtilities/DemandRecieveDiffPage.vue';
import UserTable from "./components/reusableUtilities/UserTable.vue";

const app = createApp({
  components: {
    "vue-bread-crumb": BreadCrumbNav,
    "mats-info-table": MatsInfoTable,
    "mats-info-upload-table": MatsInfoUploadTable,
    "inbound-search-table": InboundSearchTable,
    "inbound-stock-table": InboundStockTable,
    "inbound-month-table": InboundMonthTable,
    "inbound-stock-upload-table": InboundStockUploadTable,
    "outbound-pickrecord-table": OutboundPickrecordTable,
    "outbound-backrecord-table": OutboundBackrecordTable,
    "transit-search-table": TransitSearchTable,
    "sxb-search-table": SxbSearchTable,
    "unit-consumption-upload-table": UnitConsumptionUploadTable,
    "monthly-pr-table": MonthlyPRTable,
    "non-monthly-pr-table": NonMonthlyPRTable,
    "combined-monthly-search-table": CombinedMonthlyPRSearchTable,
    "obound-insearch-table": OboundInboundSearchTable,
    "obound-isnsearch-table": OboundISNSearchTable,
    "obound-pickrecord-table": OboundPickrecordTable,
    "obound-backrecord-table": OboundBackrecordTable,
    "obound-stock-table": OboundStockTable,
    "news-table": NewsTable,
    "demand-recieve-diff-page": DemandRecieveDiffPage,
    "user-table": UserTable,
  },
});

app.use(Vue3Langjs, {
  messages: vueTranslations,
  // the locale file gen by command "php artisan lang:js resources/js/vue-translations.js --no-lib --quiet"
});

app.use(router);
app.mount("#mountingPoint");
