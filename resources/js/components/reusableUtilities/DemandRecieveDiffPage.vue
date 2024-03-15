<template>
    <div class="card w-100">
        <ChartJSLine v-model="searchText"></ChartJSLine>
    </div>
    <div class="card w-100">
        <div class="card-header">
            <h3>{{ $t("callpageLang.diffalert") }}</h3>
        </div>
        <div class="card-body">
            <DemandRecieveDiffTable v-model="searchText"></DemandRecieveDiffTable>
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

        const searchText = ref(""); // connect all children's "searchTerm"
        watch(searchText, async () => {
            // console.log(searchText.value); // test
        });
        return {
            searchText
        };
    }, // setup
});
</script>
<style scoped>
.flip-card {
    background-color: transparent;
    perspective: 1000px;
    /* Remove this if you don't want the 3D effect */
    height: 75vh;
    overflow-y: auto;
}

.flip-card::-webkit-scrollbar-track {
    -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
    border-radius: 4px;
    background-color: #F5F5F5;
}

.flip-card::-webkit-scrollbar {
    width: 4px;
    -webkit-appearance: none;
}

.flip-card::-webkit-scrollbar-thumb {
    border-radius: 4px;
    -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
    background-color: rgba(0, 0, 0, 0.3);
}

/* This container is needed to position the front and back side */
.flip-card-inner {
    position: relative;
    transition: transform 0.5s;
    transform-style: preserve-3d;
}

/* Do an horizontal flip when you move the mouse over the flip box container */
.flip-card.transition .flip-card-inner {
    transform: rotateY(180deg);
}

/* Position the front and back side */
.flip-card-front,
.flip-card-back {
    position: absolute;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
}

.flip-card-back {
    transform: rotateY(180deg);
}
</style>