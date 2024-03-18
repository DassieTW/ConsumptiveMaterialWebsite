<template>
    <div class="card-body">
        <Chart ref="chartRef" type="line" :data="data" :options="options" @click="onClick" />
    </div>
</template>

<script>
import { searchTerm, data, table } from '../../composables/DiffTableStore.js'
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

            console.log('dataset', data.datasets[datasetIndex].label)
        } // datasetAtEvent

        const elementAtEvent = (element) => {
            if (!element.length) return

            const { datasetIndex, index } = element[0]

            console.log(
                'element',
                data.labels[index],
                data.datasets[datasetIndex].data[index]
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
            }

            console.log(chart); // test
            console.log(JSON.stringify(getElementAtEvent(chart, event))); // test 
            datasetAtEvent(getDatasetAtEvent(chart, event));
            elementAtEvent(getElementAtEvent(chart, event));
            elementsAtEvent(getElementsAtEvent(chart, event));
        } // onClick

        return {
            chartRef,
            onClick,
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [
                    {
                        label: app.appContext.config.globalProperties.$t("monthlyPRpageLang.buyamount1") + "(USD)",
                        borderColor: 'rgb(9, 116, 230)',
                        backgroundColor: 'rgba(9, 116, 230, 0.5)',
                        pointStyle: 'rect',
                        // fill: '1',
                        data: [19, 67, 88, 21, 90, 90, 78, 5, 50, 60, 10, 90],
                    },
                    {
                        label: app.appContext.config.globalProperties.$t("outboundpageLang.realpickamount") + "(USD)",
                        borderColor: 'rgb(245, 44, 44)',
                        backgroundColor: 'rgba(245, 44, 44, 0.5)',
                        pointStyle: 'rect',
                        fill: '-1',
                        data: [50, 60, 10, 40, 90, 100, 78, 5, 50, 60, 10, 90],
                    },
                    // {
                    //     type: 'bar',
                    //     label: 'Bar',
                    //     backgroundColor: '#ff9991',
                    //     data: [10, 20, 30, 40, 90, 100, 50, 1, 40, 90, 100, 5]
                    // },
                ],
            },
            options: {
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                responsive: true,
                maintainAspectRatio: false,
            },
            searchTerm, // test
        }
    }
}
</script>