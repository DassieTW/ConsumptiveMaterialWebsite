<template>
    <div class="card w-100">
        <ChartJSLine></ChartJSLine>
    </div>
    <div class="card w-100">
        <div class="card-header">
            <h3>{{ $t("callpageLang.diffalert") }}</h3>
        </div>
        <div class="card-body">
            <DemandRecieveDiffTable></DemandRecieveDiffTable>
        </div>
    </div>
</template>

<script>
import { defineComponent, nextTick, reactive, ref, computed } from "vue";
import {
    getCurrentInstance,
    onBeforeMount,
    onBeforeUnmount,
    onMounted,
    watch,
} from "@vue/runtime-core";
// import TableLite from "./TableLite.vue";
import DemandRecieveDiffTable from "./DemandRecieveDiffTable.vue";
import ChartJSLine from "./ChartJSLine.vue";
export default defineComponent({
    name: "App",
    components: { DemandRecieveDiffTable, ChartJSLine },
    setup() {
        const app = getCurrentInstance(); // get the current instance
        let thisHtmlLang = document
            .getElementsByTagName("HTML")[0]
            .getAttribute("lang");
        // get the current locale from html tag
        app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

        const checkedRows = ref(""); // connect all children's "checkedRows"
        // watch(checkedRows, async () => {
        //     console.log(checkedRows.value); // test
        // });
        return {
            
        };
    }, // setup
});
</script>