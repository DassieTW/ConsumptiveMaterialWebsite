<template>
    <div class="card-header row align-items-center">
        <div class="col col-2">
            <input class="text-center m-0 p-0 form-control form-control-lg" type="number" min="1996"
                v-model="yearTag" />
        </div>
        <h3 class="col col-auto align-middle m-0 p-0">
            {{ $t("callpageLang.diffalert") }}
        </h3>
    </div>
    <div class="card-body">
        <Line ref="chartRef" :data="chartData" :options="options" @click="onClick" />
    </div>
</template>

<script>
import { yearTag, monthTag, checkedRows, data, table, datasetBuyUSD, datasetRealUSD } from '../../composables/DiffTableStore.js';
import * as XLSX from 'xlsx';
import { defineComponent, reactive, ref, computed } from "vue";
import {
    getCurrentInstance,
    onBeforeMount,
    onMounted,
    watch,
} from "@vue/runtime-core";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    InteractionItem,
    LineElement,
    PointElement,
    Filler
} from 'chart.js';
import {
    Chart,
    Line,
    getDatasetAtEvent,
    getElementAtEvent,
    getElementsAtEvent
} from 'vue-chartjs';

ChartJS.register(CategoryScale, LinearScale, BarElement, LineElement, PointElement, Filler, Title, Tooltip, Legend);

export default {
    name: 'App',
    components: { Line },
    setup() {
        const app = getCurrentInstance(); // get the current instance
        let thisHtmlLang = document
            .getElementsByTagName("HTML")[0]
            .getAttribute("lang");
        // get the current locale from html tag
        app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

        const datasetAtEvent = (dataset) => {
            if (!dataset.length) return

            const datasetIndex = dataset[0].datasetIndex

            console.log('dataset', chartData.value.datasets[datasetIndex].label)
        } // datasetAtEvent

        const elementAtEvent = (element) => {
            if (!element.length) return

            const { datasetIndex, index } = element[0]

            // console.log(
            //     'element',
            //     datasetIndex,
            //     index,
            //     chartData.value.labels[index],
            //     chartData.value.datasets[datasetIndex].data[index]
            // );

            monthTag.value = index;
        } // elementAtEvent

        const elementsAtEvent = (elements) => {
            if (!elements.length) return

            console.log('elements', elements)
        } // elementsAtEvent

        const chartRef = ref(null);

        const onClick = (event) => {
            const {
                value: { chart }
            } = chartRef

            if (!chart) {
                return
            } // if

            // datasetAtEvent(getDatasetAtEvent(chart, event));
            elementAtEvent(getElementAtEvent(chart, event));
            // elementsAtEvent(getElementsAtEvent(chart, event));
        } // onClick

        const date = new Date();
        const currentMonth = date.getMonth();
        const currentYear = date.getFullYear();
        const monthList = ref(app.appContext.config.globalProperties.$t("monthlyPRpageLang.months").split('_'));
        monthList.value.splice(currentMonth + 1, 12);
        watch(yearTag, async () => {
            if (parseInt(yearTag.value) == currentYear) {
                let tempRef = app.appContext.config.globalProperties.$t("monthlyPRpageLang.months").split('_');
                tempRef.splice(currentMonth + 1, 12);
                monthList.value = tempRef;
            } else {
                monthList.value = app.appContext.config.globalProperties.$t("monthlyPRpageLang.months").split('_');
            } // if else
        }); // watch for data change

        const chartData = computed(() => ({
            labels: monthList.value,
            datasets: [
                {
                    label: app.appContext.config.globalProperties.$t("monthlyPRpageLang.buyamount1") + "(USD)",
                    borderColor: 'rgb(9, 116, 230)',
                    backgroundColor: 'rgba(9, 116, 230, 0.5)',
                    pointStyle: 'rect',
                    data: datasetBuyUSD.value,
                },
                {
                    label: app.appContext.config.globalProperties.$t("outboundpageLang.realpickamount") + "(USD)",
                    borderColor: 'rgb(245, 44, 44)',
                    backgroundColor: 'rgba(245, 44, 44, 0.5)',
                    pointStyle: 'rect',
                    fill: '-1',
                    data: datasetRealUSD.value,
                },
                // {
                //     type: 'bar',
                //     label: 'Bar',
                //     backgroundColor: '#ff9991',
                //     data: [10, 20, 30, 40, 90, 100, 50, 1, 40, 90, 100, 5]
                // },
            ],
        }));
        const options = {
            interaction: {
                mode: 'index',
                intersect: false,
            },
            responsive: true,
            maintainAspectRatio: false,
            // radius: 10,
            hoverRadius: 10,
        };

        return {
            chartRef,
            onClick,
            chartData,
            options,
            yearTag
        }
    }
}
</script>
<style scoped>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}
</style>