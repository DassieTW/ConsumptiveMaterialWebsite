<template>
    <div class="card-header">
        <div class="row w-100 justify-content-center">
            <div class="col col-auto">
                <input class="text-center form-control form-control-lg"
                    v-bind:placeholder="$t('monthlyPRpageLang.enterisn_or_descr')" v-model="searchTerm" />
            </div>
        </div>
    </div>
    <div class="card-body">
        <Line :data="data" :options="options" />
    </div>
</template>

<script>
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    PointElement,
    BarElement,
    LineElement,
    CategoryScale,
    LinearScale,
    Colors,
    Filler
} from 'chart.js';
import { Line } from 'vue-chartjs';
import * as XLSX from 'xlsx';
import { defineComponent, reactive, ref, computed } from "vue";
import {
    getCurrentInstance,
    onBeforeMount,
    onMounted,
    watch,
} from "@vue/runtime-core";

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, Title, Tooltip, Legend, Colors, Filler)

export default {
    name: 'App',
    components: { Line },
    props: ['modelValue'],
    emits: ['update:modelValue'],
    computed: {
        searchTerm: {
            get() {
                return this.modelValue
            },
            set(value) {
                this.$emit('update:modelValue', value)
            }
        }
    },
    // setup(props) {
    //     return {
    //         props,
    //     }
    // },
    data() {
        const app = getCurrentInstance(); // get the current instance
        let thisHtmlLang = document
            .getElementsByTagName("HTML")[0]
            .getAttribute("lang");
        // get the current locale from html tag
        app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

        return {
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
                maintainAspectRatio: false
            }
        }
    }
}
</script>