<template>
    <div class="card-header">
        <h3>{{ $t("callpageLang.diffalert") }}</h3>
    </div>
    <div class="card-body">
        <Chart ref="chartRef" type="line" :data="chartData" :options="options" @click="onClick" />
    </div>
</template>

<script>
import { yearTag, monthTag, checkedRows, data, table } from '../../composables/DiffTableStore.js';
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
    getDatasetAtEvent,
    getElementAtEvent,
    getElementsAtEvent
} from 'vue-chartjs';

ChartJS.register(CategoryScale, LinearScale, BarElement, LineElement, PointElement, Filler, Title, Tooltip, Legend);

export default {
    name: 'App',
    components: { Chart },
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

            console.log('dataset', chartData.datasets[datasetIndex].label)
        } // datasetAtEvent

        const elementAtEvent = (element) => {
            if (!element.length) return

            const { datasetIndex, index } = element[0]

            console.log(
                'element',
                chartData.labels[index],
                chartData.datasets[datasetIndex].data[index]
            )
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

        const chartData = {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [
                {
                    label: app.appContext.config.globalProperties.$t("monthlyPRpageLang.buyamount1") + "(USD)",
                    borderColor: 'rgb(9, 116, 230)',
                    backgroundColor: 'rgba(9, 116, 230, 0.5)',
                    pointStyle: 'rect',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
                },
                {
                    label: app.appContext.config.globalProperties.$t("outboundpageLang.realpickamount") + "(USD)",
                    borderColor: 'rgb(245, 44, 44)',
                    backgroundColor: 'rgba(245, 44, 44, 0.5)',
                    pointStyle: 'rect',
                    fill: '-1',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,],
                },
                // {
                //     type: 'bar',
                //     label: 'Bar',
                //     backgroundColor: '#ff9991',
                //     data: [10, 20, 30, 40, 90, 100, 50, 1, 40, 90, 100, 5]
                // },
            ],
        };
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
        }
    }
}
</script>