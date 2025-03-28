<template>
    <div class="card-header row align-items-center">
        <h3 class="col col-auto align-middle m-0 p-0">
            {{ yearTag + "-" + monthStr + " " + $t("callpageLang.diffalert") }}
        </h3>
    </div>
    <div class="card-body">
        <div class="row justify-content-between">
            <div class="row col col-auto">
                <div class="col col-auto">
                    <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
                </div>
                <div class="col col-auto p-0 m-0">
                    <input id="pnInput" class="text-center form-control form-control-lg"
                        v-bind:placeholder="$t('monthlyPRpageLang.enterisn_or_descr')" v-model="searchTerm" />
                </div>
            </div>
            <div class="col col-auto">
                <button id="download" name="download" class="col col-auto btn btn-lg btn-success"
                    :value="$t('monthlyPRpageLang.download')" @click="OutputExcelClick('All')">
                    <i class="bi bi-file-earmark-arrow-down-fill fs-4"></i>
                </button>
            </div>
        </div>
        <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
        <table-lite id="searchTable" :is-fixed-first-column="true" :hasCheckbox="true" :isStaticMode="true"
            :isSlotMode="true" :messages="table.messages" :columns="table.columns" :rows="table.rows"
            :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
            :is-loading="table.isLoading" @is-finished="table.isLoading = false"
            @return-checked-rows="updateCheckedRows">
            <template v-slot:料號="{ row, key }">
                <div class="CustomScrollbar text-nowrap" style="overflow-x: auto; width: 100%;">
                    {{ row.料號 }}
                </div>
            </template>
            <template v-slot:當月需求="{ row, key }">
                <div class="col col-auto align-items-center m-0 p-0">
                    <span class="m-0 p-0" style="width: 12ch;">
                        {{ parseInt(row.當月需求).toLocaleString('en', { useGrouping: true }) }}
                        <small>{{ row.單位 }}</small>
                    </span>
                </div>
            </template>
            <template v-slot:實際領用數量="{ row, key }">
                <div class="col col-auto align-items-center m-0 p-0">
                    <span class="m-0 p-0" style="width: 12ch;">
                        {{ parseInt(row.實際領用數量).toLocaleString('en', { useGrouping: true }) }}
                        <small>{{ row.單位 }}</small>
                    </span>
                </div>
            </template>
            <template v-slot:需求與領用差異量="{ row, key }">
                <div class="col col-auto align-items-center m-0 p-0">
                    <span class="m-0 p-0" style="width: 12ch;">
                        {{ parseInt(row.需求與領用差異量).toLocaleString('en', { useGrouping: true }) }}
                        <small>{{ row.單位 }}</small>
                    </span>
                </div>
            </template>
            <template v-slot:需求與領用差異="{ row, key }">
                <div v-if="isNaN(row.需求與領用差異)" class="CustomScrollbar text-nowrap" style="overflow-x: auto; width: 100%;">
                    0%
                </div>
                <div v-else-if="!isFinite(row.需求與領用差異)" class="CustomScrollbar text-nowrap"
                    style="overflow-x: auto; width: 100%;">
                    <span class="fs-3 m-0 p-0" style="height: 60%;">∞</span>
                </div>
                <div v-else class="CustomScrollbar text-nowrap" style="overflow-x: auto; width: 100%;">
                    {{ row.需求與領用差異.toFixed(2) }}%
                </div>
            </template>
        </table-lite>
    </div>
</template>

<script>
import { defineComponent, reactive, ref, computed, defineModel } from "vue";
import {
    getCurrentInstance,
    onBeforeMount,
    onMounted,
    watch,
} from "@vue/runtime-core";
import { yearTag, monthTag, checkedRows, searchTerm, data, table, datasetNeed, datasetReal } from '../../composables/DiffTableStore.js';
import ExcelJS from 'exceljs';
import FileSaver from "file-saver";
import TableLite from "./TableLite.vue";
import useDiffSearch from "../../composables/DiffSearch.ts";
import useMonthlyPRSearch from "../../composables/MonthlyPRSearch.ts";
import useCommonlyUsedFunctions from "../../composables/CommonlyUsedFunctions.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const app = getCurrentInstance(); // get the current instance
        let thisHtmlLang = document
            .getElementsByTagName("HTML")[0]
            .getAttribute("lang");
        // get the current locale from html tag
        app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

        const { mats, getMats } = useDiffSearch(); // axios get the mats data

        onBeforeMount(async () => {
            table.isLoading = true;
            await getMats();
        });

        const monthList = ref(app.appContext.config.globalProperties.$t("monthlyPRpageLang.months").split('_'));
        const monthStr = ref(monthList.value[monthTag.value]);
        const triggerModal = async () => {
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            return new Promise((resolve, reject) => {
                resolve();
            });
        } // triggerModal

        const OutputExcelClick = async () => {
            await triggerModal();

            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet(app.appContext.config.globalProperties.$t("callpageLang.req_vs_real_percent"));

            // Add header row
            worksheet.addRow([
                app.appContext.config.globalProperties.$t("callpageLang.req_vs_real_percent"),
                app.appContext.config.globalProperties.$t("callpageLang.req_vs_real_percent"),
                app.appContext.config.globalProperties.$t("callpageLang.req_vs_real_percent"),
                app.appContext.config.globalProperties.$t("callpageLang.req_vs_real_percent"),
                app.appContext.config.globalProperties.$t("callpageLang.req_vs_real_percent"),
                app.appContext.config.globalProperties.$t("callpageLang.req_vs_real_percent"),
                app.appContext.config.globalProperties.$t("callpageLang.req_vs_real_percent"),
            ]);

            // Add data rows
            data.forEach(item => {
                worksheet.addRow([
                    item.料號,
                    item.品名,
                    item.當月需求,
                    item.實際領用數量,
                    item.單位,
                    item.需求與領用差異量,
                    `${item.需求與領用差異}%`
                ]);
            });

            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: "application/octet-stream" });
            FileSaver.saveAs(blob, `${app.appContext.config.globalProperties.$t("callpageLang.req_vs_real_percent")}.xlsx`);

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // OutputExcelClick

        const SortCurrentMonthTable = async (inputMonth, resultArray) => {
            // according to Lyra Mao, Buylist is always sent in the first few days of the month
            // so we can safely assume that the outbound data is always later than the buylist data

            // console.log(inputMonth, new Date().getMonth()); // test 
            let workdaysCount = -99;
            // if the year and month is the current year and month
            if (yearTag.value == new Date().getFullYear() && inputMonth == new Date().getMonth()) {
                workdaysCount =
                    [...new Array(new Date().getDate())]
                        .reduce((acc, _, monthDay) => {
                            const date = new Date()
                            date.setDate(1 + monthDay)
                            // ![0, 6].includes(date.getDay()) && acc++ // exclude weekends
                            ![0].includes(date.getDay()) && acc++ // exclude sundays
                            return acc
                        }, 0)
            } // if
            else {
                workdaysCount =
                    [...new Array(new Date(yearTag.value, inputMonth + 1, 0).getDate())]
                        .reduce((acc, _, monthDay) => {
                            const date = new Date(yearTag.value, inputMonth, 1 + monthDay)
                            // ![0, 6].includes(date.getDay()) && acc++ // exclude weekends
                            ![0].includes(date.getDay()) && acc++ // exclude sundays
                            return acc
                        }, 0)
            } // else

            // console.log("workdays Count:" + workdaysCount); // test

            // loop thru outbound list and push to table
            for (let i = 0; i < all_data_sorted.outbound[inputMonth].length; i++) {
                let singleEntry = {};
                let tempArry = all_data_sorted.outbound[inputMonth];
                singleEntry.料號 = tempArry[i].料號;
                singleEntry.品名 = tempArry[i].品名;

                let obj = all_data_sorted.buylist[inputMonth].find(o => o.料號 === singleEntry.料號);
                if (obj) {
                    if (Number.isNaN(parseFloat(obj.當月需求))) {
                        singleEntry.當月需求 = 0;
                    } // if
                    else {
                        singleEntry.當月需求 = parseFloat(obj.當月需求);
                    } // else
                } // if
                else { // 新入料
                    singleEntry.當月需求 = 0;
                } // else

                singleEntry.單位 = tempArry[i].單位;
                singleEntry.實際領用數量 = parseInt(tempArry[i].實際領用數量);
                singleEntry.需求與領用差異量 = singleEntry.當月需求 - singleEntry.實際領用數量;
                singleEntry.需求與領用差異 = 100 * (singleEntry.實際領用數量) / (singleEntry.當月需求 / 26 * workdaysCount); // 26 is the average workdays in a month, as requested by Lyra Mao
                singleEntry.單價 = parseFloat(tempArry[i].單價);
                singleEntry.幣別 = tempArry[i].幣別;

                resultArray.push(singleEntry);
            } // for

            // loop thru buylist and push to table
            let alreadyPushed = false;
            for (let i = 0; i < all_data_sorted.buylist[inputMonth].length; i++) {
                let tempMonthRecord = all_data_sorted.buylist[inputMonth];
                let singleEntry = {};
                singleEntry.料號 = tempMonthRecord[i].料號;
                singleEntry.品名 = tempMonthRecord[i].品名;
                if (Number.isNaN(parseFloat(tempMonthRecord[i].當月需求))) {
                    singleEntry.當月需求 = 0;
                } // if
                else {
                    singleEntry.當月需求 = parseFloat(tempMonthRecord[i].當月需求);
                } // else
                singleEntry.單位 = tempMonthRecord[i].單位;
                let obj = all_data_sorted.outbound[inputMonth].find(o => o.料號 === singleEntry.料號);
                if (obj) {
                    alreadyPushed = true;
                } // if
                else { // 未領用
                    singleEntry.實際領用數量 = 0;
                } // else
                singleEntry.需求與領用差異量 = singleEntry.當月需求 - singleEntry.實際領用數量;
                singleEntry.需求與領用差異 = 100 * (singleEntry.實際領用數量) / (singleEntry.當月需求 / 26 * workdaysCount); // 26 is the average workdays in a month, as requested by Lyra Mao
                singleEntry.單價 = parseFloat(tempMonthRecord[i].單價);
                singleEntry.幣別 = tempMonthRecord[i].幣別;

                if (!alreadyPushed) {
                    resultArray.push(singleEntry);
                    alreadyPushed = false;
                } // if
            } // for
        }; // SortCurrentMonthTable

        const CalChartDatasets = async () => {
            let tempNeedAmount = [];
            let tempRealAmount = [];

            let monthlyTemp = [];
            for (let i = 0; i < 12; i++) { // loop thru whole year
                monthlyTemp.splice(0); // clean up old records
                await SortCurrentMonthTable(i, monthlyTemp); // get the sorted data by month
                let currentMonthNeedTotalAmount = 0;
                let currentMonthRealTotalAmount = 0;
                // console.log(monthlyTemp); // test
                for (let j = 0; j < monthlyTemp.length; j++) {
                    if (monthlyTemp[j].料號) { // for safety measure
                        if (checkedRows.length > 0) {
                            if (checkedRows.find(o => o.料號 == monthlyTemp[j].料號) != undefined) {
                                currentMonthNeedTotalAmount += parseInt(monthlyTemp[j].當月需求);
                                currentMonthRealTotalAmount += parseInt(monthlyTemp[j].實際領用數量);
                            } // if
                        } // if
                        else {
                            currentMonthNeedTotalAmount += parseInt(monthlyTemp[j].當月需求);
                            currentMonthRealTotalAmount += parseInt(monthlyTemp[j].實際領用數量);
                        } // else
                    } // if
                } // for

                tempNeedAmount.push(currentMonthNeedTotalAmount);
                tempRealAmount.push(currentMonthRealTotalAmount);
            } // for

            datasetNeed.value = tempNeedAmount;
            datasetReal.value = tempRealAmount;
        }; // CalChartDatasets

        watch(checkedRows, async () => {
            await CalChartDatasets();
        }); // watch checked rows

        watch(yearTag, async () => {
            if (yearTag.value.toString().length == 4 && parseInt(yearTag.value) >= 1996) {
                await triggerModal();

                data.splice(0); // clean up old records

                await getMats(yearTag.value);

                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
            } // if
        }); // watch year change

        watch(monthTag, async () => {
            // Jan is 0 in monthTag
            await triggerModal();

            data.splice(0); // clean up possible old records

            // uncheck all checkboxes
            document.querySelectorAll('.vtl-tbody-checkbox').forEach(el => el.checked = false);

            if (document.querySelector(".vtl-thead-checkbox").checked) {
                document.querySelector(".vtl-thead-checkbox").click();
            } // if
            checkedRows.splice(0);

            monthStr.value = monthList.value[monthTag.value];
            await SortCurrentMonthTable(monthTag.value, data);

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        }); // watch month change

        var all_data_sorted = {
            buylist: [],
            outbound: []
        };
        watch(mats, async () => {
            await triggerModal();
            all_data_sorted = {
                buylist: [],
                outbound: []
            }; // clean up possible old records
            data.splice(0); // clean up possible old records
            if (mats.value == "") {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            let allRowsObj = JSON.parse(mats.value);

            // sort the yearly buylist
            for (let i = 0; i < 12; i++) {
                let newArray = allRowsObj.buylist.filter(function (el) {
                    let sql_Date_To_JS_Date = new Date(el.請購時間.replace(' ', 'T'))
                    return sql_Date_To_JS_Date.getMonth() == i;
                });

                // sum the entries with the same 料號
                let sum_result = Object.values(newArray.reduce((acc, curr) => {
                    let item = acc[curr.料號];

                    if (item) {
                        // set the current month demand to the latest entry
                        if (new Date(curr.請購時間) > new Date(acc[curr.料號].請購時間)) {
                            acc[curr.料號].當月需求 = parseFloat(curr.當月需求);
                            acc[curr.料號].請購時間 = curr.請購時間;
                        } // if
                    } else {
                        curr.當月需求 = parseFloat(curr.當月需求);
                        acc[curr.料號] = curr;
                    } // if else


                    return acc;
                }, {}));

                all_data_sorted.buylist[i] = sum_result;
            } // for

            // sort the yearly outbound
            for (let i = 0; i < 12; i++) {
                let newArray = allRowsObj.outbound.filter(function (el) {
                    let sql_Date_To_JS_Date = new Date(el.出庫時間.replace(' ', 'T'))
                    return sql_Date_To_JS_Date.getMonth() == i;
                });

                // sum the entries with the same 料號
                let sum_result = Object.values(newArray.reduce((acc, curr) => {
                    let item = acc[curr.料號];

                    if (item) {
                        item.實際領用數量 = parseInt(item.實際領用數量) + parseInt(curr.實際領用數量);
                    } else {
                        curr.實際領用數量 = parseInt(curr.實際領用數量);
                        acc[curr.料號] = curr;
                    } // if else

                    return acc;
                }, {}));

                all_data_sorted.outbound[i] = sum_result;
            } // for

            console.log(all_data_sorted); // test
            await SortCurrentMonthTable(monthTag.value, data);
            await CalChartDatasets();

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
            table.isLoading = false;
        }); // watch for data change

        const updateCheckedRows = (rowsKey) => {
            checkedRows.length = 0;
            checkedRows.push(...rowsKey);
            // console.log(checkedRows); // test
        };

        return {
            table,
            searchTerm,
            updateCheckedRows,
            OutputExcelClick,
            yearTag,
            monthStr
        };
    }, // setup
});
</script>
<style scoped>
::v-deep(.vtl-table .vtl-thead .vtl-thead-th) {
    color: #fff;
    background-color: #196241;
    border-color: #196241;
}
</style>