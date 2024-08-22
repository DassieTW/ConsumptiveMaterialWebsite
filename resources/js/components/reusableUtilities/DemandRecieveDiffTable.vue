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
            @return-checked-rows="updateCheckedRows">
            <template v-slot:料號="{ row, key }">
                <div class="CustomScrollbar text-nowrap" style="overflow-x: auto; width: 100%;">
                    {{ row.料號 }}
                </div>
            </template>
            <template v-slot:請購數量="{ row, key }">
                <div class="col col-auto align-items-center m-0 p-0">
                    <span class="m-0 p-0" style="width: 12ch;">
                        {{ parseInt(row.請購數量).toLocaleString('en', { useGrouping: true }) }}
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
                <div class="CustomScrollbar text-nowrap" style="overflow-x: auto; width: 100%;">
                    {{ row.需求與領用差異 }}%
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
import { yearTag, monthTag, checkedRows, searchTerm, data, table, datasetBuyUSD, datasetRealUSD } from '../../composables/DiffTableStore.js';
import * as XLSX from 'xlsx';
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
        const { Currency, getCurrency } = useMonthlyPRSearch(); // axios get the currency


        onBeforeMount(async () => {
            await getCurrency();
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

            // get today's date for filename
            let today = new Date();
            let dd = String(today.getDate()).padStart(2, '0');
            let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0
            let yyyy = today.getFullYear();
            today = yyyy + "_" + mm + '_' + dd;

            let rows = Array();
            for (let i = 0; i < data.length; i++) {
                let tempObj = new Object;
                tempObj.料號 = data[i].料號;
                tempObj.品名 = data[i].品名;
                tempObj.請購數量 = data[i].請購數量;
                tempObj.實際領用數量 = data[i].實際領用數量;
                tempObj.單位 = data[i].單位;
                tempObj.需求與領用差異量 = data[i].需求與領用差異量;
                tempObj.需求與領用差異 = data[i].需求與領用差異 + "%";

                rows.push(tempObj);
            } // for

            const worksheet = XLSX.utils.json_to_sheet(rows);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, "PR vs Acq " + yearTag.value + "_" + (monthTag.value + 1));
            XLSX.writeFile(workbook,
                app.appContext.config.globalProperties.$t(
                    "callpageLang.req_vs_real_percent"
                ) + ".xlsx", { compression: true });

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // OutputExcelClick

        const SortCurrentMonthTable = async (inputMonth, resultArray) => {
            // 無法確定此廠這個月的入庫是來自固定前一個月的請購or固定提前(早兩個月)的請購
            // 回頭一個月一個月找時 若有料號match 表示整批的請購月份也是現在的月份
            // 將找到matching請購月份紀錄下來 loop完inbound list後
            // 會在loop buylist時只取用此請購月份的資料 (作為有請購但沒有(尚未?)入庫的料)
            let matchingBuylistMonth = -99;
            // console.log(all_data_sorted); // test
            // find the matching buylist month
            for (let i = 0; i < all_data_sorted.inbound[inputMonth].length; i++) {
                let tempArry = all_data_sorted.inbound[inputMonth];
                for (let j = 1; j < 3; j++) {
                    let prevBuylistMonth = inputMonth - j;
                    if (prevBuylistMonth == -1) { // 去年12月請購
                        let obj = all_data_sorted.buylist_lastyear[11].find(o => o.料號 === tempArry[i].料號);
                        // console.log(obj); // test
                        if (obj) {
                            if (prevBuylistMonth > matchingBuylistMonth) {
                                matchingBuylistMonth = prevBuylistMonth;
                            } // if
                            break;
                        } // if
                    } else if (prevBuylistMonth == -2) { // 去年11月請購
                        let obj = all_data_sorted.buylist_lastyear[10].find(o => o.料號 === tempArry[i].料號);
                        // console.log(obj); // test
                        if (obj) {
                            if (prevBuylistMonth > matchingBuylistMonth) {
                                matchingBuylistMonth = prevBuylistMonth;
                            } // if
                            break;
                        } // if
                    } else { // 今年內請購
                        let obj = all_data_sorted.buylist[prevBuylistMonth].find(o => o.料號 === tempArry[i].料號);
                        if (obj) {
                            if (prevBuylistMonth > matchingBuylistMonth) {
                                matchingBuylistMonth = prevBuylistMonth;
                            } // if
                            break;
                        } // if
                    } // if else
                } // for
            } // for

            // loop thru inbound list and push to table
            for (let i = 0; i < all_data_sorted.inbound[inputMonth].length; i++) {
                let singleEntry = {};
                let tempArry = all_data_sorted.inbound[inputMonth];
                singleEntry.料號 = tempArry[i].料號;
                singleEntry.品名 = tempArry[i].品名;

                if (matchingBuylistMonth == -99) { // if the whole batch is newly inbound without any buy records
                    singleEntry.請購數量 = 0;
                } else if (matchingBuylistMonth == -1) { // if the batch's buy record is from last year Dec
                    let obj = all_data_sorted.buylist_lastyear[11].find(o => o.料號 === singleEntry.料號);
                    // console.log(obj); // test
                    if (obj) {
                        singleEntry.請購數量 = parseFloat(obj.本次請購數量);
                        if (Number.isNaN(parseFloat(obj.本次請購數量))) {
                            singleEntry.請購數量 = 0;
                        } // if
                    } // if
                    else { // 新入料
                        singleEntry.請購數量 = 0;
                    } // else
                } else if (matchingBuylistMonth == -2) { // if the batch's buy record is from last year Nov
                    let obj = all_data_sorted.buylist_lastyear[10].find(o => o.料號 === singleEntry.料號);
                    if (obj) {
                        singleEntry.請購數量 = parseFloat(obj.本次請購數量);
                        if (Number.isNaN(parseFloat(obj.本次請購數量))) {
                            singleEntry.請購數量 = 0;
                        } // if
                    } // if
                    else { // 新入料
                        singleEntry.請購數量 = 0;
                    } // else
                } else { // if the batch's buy record is within this year
                    let obj = all_data_sorted.buylist[matchingBuylistMonth].find(o => o.料號 === singleEntry.料號);
                    if (obj) {
                        singleEntry.請購數量 = parseFloat(obj.本次請購數量);
                        if (Number.isNaN(parseFloat(obj.本次請購數量))) {
                            singleEntry.請購數量 = 0;
                        } // if
                    } // if
                    else { // 新入料
                        singleEntry.請購數量 = 0;
                    } // else
                } // if else

                singleEntry.單位 = tempArry[i].單位;
                singleEntry.實際領用數量 = parseInt(tempArry[i].入庫數量);
                singleEntry.需求與領用差異量 = singleEntry.請購數量 - singleEntry.實際領用數量;
                singleEntry.需求與領用差異 = 100 * (singleEntry.請購數量 - singleEntry.實際領用數量) / ((singleEntry.請購數量 + singleEntry.實際領用數量) / 2);
                if (Number.isNaN(singleEntry.需求與領用差異)) {
                    singleEntry.需求與領用差異 = 0;
                } // if
                singleEntry.單價 = parseFloat(tempArry[i].單價);
                singleEntry.幣別 = tempArry[i].幣別;

                resultArray.push(singleEntry);
            } // for

            // fish out entries that are within buylist but not within inbound
            if (matchingBuylistMonth == -99) {
                // if the whole batch are all newly inbound without any corresponding buy records 
                // OR if there's no inbound records
                // 目前作法：直接抓上個月的請購資料
                let prevBuylistMonth = inputMonth - 1;
                if (prevBuylistMonth == -1) { // 去年12月請購
                    for (let i = 0; i < all_data_sorted.buylist_lastyear[11].length; i++) {
                        let tempMonthRecord = all_data_sorted.buylist_lastyear[11];
                        let singleEntry = {};
                        singleEntry.料號 = tempMonthRecord[i].料號;
                        singleEntry.品名 = tempMonthRecord[i].品名;
                        singleEntry.請購數量 = parseFloat(tempMonthRecord[i].本次請購數量);
                        if (Number.isNaN(parseFloat(tempMonthRecord[i].本次請購數量))) {
                            singleEntry.請購數量 = 0;
                        } // if
                        singleEntry.單位 = tempMonthRecord[i].單位;
                        singleEntry.實際領用數量 = 0;
                        singleEntry.需求與領用差異量 = singleEntry.請購數量 - singleEntry.實際領用數量;
                        singleEntry.需求與領用差異 = 100 * (singleEntry.請購數量 - singleEntry.實際領用數量) / ((singleEntry.請購數量 + singleEntry.實際領用數量) / 2);
                        if (Number.isNaN(singleEntry.需求與領用差異)) {
                            singleEntry.需求與領用差異 = 0;
                        } // if
                        singleEntry.單價 = parseFloat(tempMonthRecord[i].單價);
                        singleEntry.幣別 = tempMonthRecord[i].幣別;

                        resultArray.push(singleEntry);
                    } // for
                } else if (prevBuylistMonth == -2) { // 去年11月請購
                    for (let i = 0; i < all_data_sorted.buylist_lastyear[10].length; i++) {
                        let tempMonthRecord = all_data_sorted.buylist_lastyear[10];
                        let singleEntry = {};
                        singleEntry.料號 = tempMonthRecord[i].料號;
                        singleEntry.品名 = tempMonthRecord[i].品名;
                        singleEntry.請購數量 = parseFloat(tempMonthRecord[i].本次請購數量);
                        if (Number.isNaN(parseFloat(tempMonthRecord[i].本次請購數量))) {
                            singleEntry.請購數量 = 0;
                        } // if
                        singleEntry.單位 = tempMonthRecord[i].單位;
                        singleEntry.實際領用數量 = 0;
                        singleEntry.需求與領用差異量 = singleEntry.請購數量 - singleEntry.實際領用數量;
                        singleEntry.需求與領用差異 = 100 * (singleEntry.請購數量 - singleEntry.實際領用數量) / ((singleEntry.請購數量 + singleEntry.實際領用數量) / 2);
                        if (Number.isNaN(singleEntry.需求與領用差異)) {
                            singleEntry.需求與領用差異 = 0;
                        } // if
                        singleEntry.單價 = parseFloat(tempMonthRecord[i].單價);
                        singleEntry.幣別 = tempMonthRecord[i].幣別;

                        resultArray.push(singleEntry);
                    } // for
                } else { // 今年內請購
                    for (let i = 0; i < all_data_sorted.buylist[prevBuylistMonth].length; i++) {
                        let tempMonthRecord = all_data_sorted.buylist[prevBuylistMonth];
                        let singleEntry = {};
                        singleEntry.料號 = tempMonthRecord[i].料號;
                        singleEntry.品名 = tempMonthRecord[i].品名;
                        singleEntry.請購數量 = parseFloat(tempMonthRecord[i].本次請購數量);
                        if (Number.isNaN(parseFloat(tempMonthRecord[i].本次請購數量))) {
                            singleEntry.請購數量 = 0;
                        } // if
                        singleEntry.單位 = tempMonthRecord[i].單位;
                        singleEntry.實際領用數量 = 0;
                        singleEntry.需求與領用差異量 = singleEntry.請購數量 - singleEntry.實際領用數量;
                        singleEntry.需求與領用差異 = 100 * (singleEntry.請購數量 - singleEntry.實際領用數量) / ((singleEntry.請購數量 + singleEntry.實際領用數量) / 2);
                        if (Number.isNaN(singleEntry.需求與領用差異)) {
                            singleEntry.需求與領用差異 = 0;
                        } // if
                        singleEntry.單價 = parseFloat(tempMonthRecord[i].單價);
                        singleEntry.幣別 = tempMonthRecord[i].幣別;

                        resultArray.push(singleEntry);
                    } // for
                } // if else
            } else if (matchingBuylistMonth == -1) { // if the batch's buy record is from last year Dec
                for (let i = 0; i < all_data_sorted.buylist_lastyear[11].length; i++) {
                    let tempMonthRecord = all_data_sorted.buylist_lastyear[11];
                    let obj = resultArray.find(o => o.料號 === tempMonthRecord[i].料號);
                    if (obj === undefined) {
                        let singleEntry = {};
                        singleEntry.料號 = tempMonthRecord[i].料號;
                        singleEntry.品名 = tempMonthRecord[i].品名;
                        singleEntry.請購數量 = parseFloat(tempMonthRecord[i].本次請購數量);
                        if (Number.isNaN(parseFloat(tempMonthRecord[i].本次請購數量))) {
                            singleEntry.請購數量 = 0;
                        } // if
                        singleEntry.單位 = tempMonthRecord[i].單位;
                        singleEntry.實際領用數量 = 0;
                        singleEntry.需求與領用差異量 = singleEntry.請購數量 - singleEntry.實際領用數量;
                        singleEntry.需求與領用差異 = 100 * (singleEntry.請購數量 - singleEntry.實際領用數量) / ((singleEntry.請購數量 + singleEntry.實際領用數量) / 2);
                        if (Number.isNaN(singleEntry.需求與領用差異)) {
                            singleEntry.需求與領用差異 = 0;
                        } // if
                        singleEntry.單價 = parseFloat(tempMonthRecord[i].單價);
                        singleEntry.幣別 = tempMonthRecord[i].幣別;

                        resultArray.push(singleEntry);
                    } // if
                } // for
            } else if (matchingBuylistMonth == -2) { // if the batch's buy record is from last year Nov
                for (let i = 0; i < all_data_sorted.buylist_lastyear[10].length; i++) {
                    let tempMonthRecord = all_data_sorted.buylist_lastyear[10];
                    let obj = resultArray.find(o => o.料號 === tempMonthRecord[i].料號);
                    if (obj === undefined) {
                        let singleEntry = {};
                        singleEntry.料號 = tempMonthRecord[i].料號;
                        singleEntry.品名 = tempMonthRecord[i].品名;
                        singleEntry.請購數量 = parseFloat(tempMonthRecord[i].本次請購數量);
                        if (Number.isNaN(parseFloat(tempMonthRecord[i].本次請購數量))) {
                            singleEntry.請購數量 = 0;
                        } // if
                        singleEntry.單位 = tempMonthRecord[i].單位;
                        singleEntry.實際領用數量 = 0;
                        singleEntry.需求與領用差異量 = singleEntry.請購數量 - singleEntry.實際領用數量;
                        singleEntry.需求與領用差異 = 100 * (singleEntry.請購數量 - singleEntry.實際領用數量) / ((singleEntry.請購數量 + singleEntry.實際領用數量) / 2);
                        if (Number.isNaN(singleEntry.需求與領用差異)) {
                            singleEntry.需求與領用差異 = 0;
                        } // if
                        singleEntry.單價 = parseFloat(tempMonthRecord[i].單價);
                        singleEntry.幣別 = tempMonthRecord[i].幣別;

                        resultArray.push(singleEntry);
                    } // if
                } // for
            } else { // if the batch's buy record is within this year
                for (let i = 0; i < all_data_sorted.buylist[matchingBuylistMonth].length; i++) {
                    let tempMonthRecord = all_data_sorted.buylist[matchingBuylistMonth];
                    let obj = resultArray.find(o => o.料號 === tempMonthRecord[i].料號);
                    if (obj === undefined) {
                        let singleEntry = {};
                        singleEntry.料號 = tempMonthRecord[i].料號;
                        singleEntry.品名 = tempMonthRecord[i].品名;
                        singleEntry.請購數量 = parseFloat(tempMonthRecord[i].本次請購數量);
                        if (Number.isNaN(parseFloat(tempMonthRecord[i].本次請購數量))) {
                            singleEntry.請購數量 = 0;
                        } // if
                        singleEntry.單位 = tempMonthRecord[i].單位;
                        singleEntry.實際領用數量 = 0;
                        singleEntry.需求與領用差異量 = singleEntry.請購數量 - singleEntry.實際領用數量;
                        singleEntry.需求與領用差異 = 100 * (singleEntry.請購數量 - singleEntry.實際領用數量) / ((singleEntry.請購數量 + singleEntry.實際領用數量) / 2);
                        if (Number.isNaN(singleEntry.需求與領用差異)) {
                            singleEntry.需求與領用差異 = 0;
                        } // if
                        singleEntry.單價 = parseFloat(tempMonthRecord[i].單價);
                        singleEntry.幣別 = tempMonthRecord[i].幣別;

                        resultArray.push(singleEntry);
                    } // if
                } // for
            } // if else
        }; // SortCurrentMonthTable

        const CalChartDatasets = async () => {
            let tempBuyUSD = [];
            let tempRealUSD = [];

            let monthlyTemp = [];
            let exchange_table = Currency.value.rates;
            for (let i = 0; i < 12; i++) { // loop thru whole year
                monthlyTemp.splice(0); // clean up old records
                await SortCurrentMonthTable(i, monthlyTemp); // get the sorted data by month
                let currentMonthBuyTotalPrice = 0;
                let currentMonthRealTotalPrice = 0;
                // console.log(monthlyTemp); // test
                for (let j = 0; j < monthlyTemp.length; j++) {
                    if (monthlyTemp[j].料號) { // for safety measure
                        if (checkedRows.length > 0) {
                            if (checkedRows.find(o => o.料號 == monthlyTemp[j].料號) != undefined) {
                                if (monthlyTemp[j].幣別 == "RMB") {
                                    if (!Number.isNaN(parseFloat((parseFloat(monthlyTemp[j].請購數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table['CNY'])).toFixed(5)))) {
                                        currentMonthBuyTotalPrice += parseFloat((parseFloat(monthlyTemp[j].請購數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table['CNY'])).toFixed(5));
                                    } // if

                                    if (!Number.isNaN(parseFloat((parseFloat(monthlyTemp[j].實際領用數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table['CNY'])).toFixed(5)))) {
                                        currentMonthRealTotalPrice += parseFloat((parseFloat(monthlyTemp[j].實際領用數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table['CNY'])).toFixed(5));
                                    } // if
                                } // if
                                else {
                                    if (!Number.isNaN(parseFloat((parseFloat(monthlyTemp[j].請購數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table[monthlyTemp[j].幣別])).toFixed(5)))) {
                                        currentMonthBuyTotalPrice += parseFloat((parseFloat(monthlyTemp[j].請購數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table[monthlyTemp[j].幣別])).toFixed(5));
                                    } // if
                                    if (!Number.isNaN(parseFloat((parseFloat(monthlyTemp[j].實際領用數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table[monthlyTemp[j].幣別])).toFixed(5)))) {
                                        currentMonthRealTotalPrice += parseFloat((parseFloat(monthlyTemp[j].實際領用數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table[monthlyTemp[j].幣別])).toFixed(5));
                                    } // if
                                } // else
                            } // if
                        } // if
                        else {
                            if (monthlyTemp[j].幣別 == "RMB") {
                                if (!Number.isNaN(parseFloat((parseFloat(monthlyTemp[j].請購數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table['CNY'])).toFixed(5)))) {
                                    currentMonthBuyTotalPrice += parseFloat((parseFloat(monthlyTemp[j].請購數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table['CNY'])).toFixed(5));
                                } // if

                                if (!Number.isNaN(parseFloat((parseFloat(monthlyTemp[j].實際領用數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table['CNY'])).toFixed(5)))) {
                                    currentMonthRealTotalPrice += parseFloat((parseFloat(monthlyTemp[j].實際領用數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table['CNY'])).toFixed(5));
                                } // if
                            } // if
                            else {
                                if (!Number.isNaN(parseFloat((parseFloat(monthlyTemp[j].請購數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table[monthlyTemp[j].幣別])).toFixed(5)))) {
                                    currentMonthBuyTotalPrice += parseFloat((parseFloat(monthlyTemp[j].請購數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table[monthlyTemp[j].幣別])).toFixed(5));
                                } // if
                                if (!Number.isNaN(parseFloat((parseFloat(monthlyTemp[j].實際領用數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table[monthlyTemp[j].幣別])).toFixed(5)))) {
                                    currentMonthRealTotalPrice += parseFloat((parseFloat(monthlyTemp[j].實際領用數量) * parseFloat(monthlyTemp[j].單價) * (exchange_table['USD'] / exchange_table[monthlyTemp[j].幣別])).toFixed(5));
                                } // if
                            } // else
                        } // else

                    } // if
                } // for

                tempBuyUSD.push(currentMonthBuyTotalPrice);
                tempRealUSD.push(currentMonthRealTotalPrice);
            } // for

            // console.log(tempRealUSD); // test
            // console.log(tempBuyUSD); // test
            datasetBuyUSD.value = tempBuyUSD;
            datasetRealUSD.value = tempRealUSD;
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
            buylist_lastyear: [],
            buylist: [],
            inbound: []
        };
        watch(mats, async () => {
            await triggerModal();
            all_data_sorted = {
                buylist_lastyear: [],
                buylist: [],
                inbound: []
            }; // clean up possible old records
            data.splice(0); // clean up possible old records
            if (mats.value == "") {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            let allRowsObj = JSON.parse(mats.value);

            // sort the last year's last few months' buylist
            for (let i = 0; i < 12; i++) {
                let newArray = allRowsObj.buylist_lastyear.filter(function (el) {
                    let sql_Date_To_JS_Date = new Date(el.請購時間.replace(' ', 'T'))
                    return sql_Date_To_JS_Date.getMonth() == i;
                });

                // sum the entries with the same 料號                
                let sum_result = Object.values(newArray.reduce((acc, curr) => {
                    let item = acc[curr.料號];
                    if (item) { // 匯率 其實是美金金額 不是真的匯率
                        item.本次請購數量 += parseFloat(curr.本次請購數量);
                        item.匯率 += parseFloat(curr.匯率);
                    } else {
                        curr.匯率 = parseFloat(curr.匯率);
                        curr.本次請購數量 = parseFloat(curr.本次請購數量);
                        acc[curr.料號] = curr;
                    } // if else

                    return acc;
                }, {}));

                all_data_sorted.buylist_lastyear[i] = sum_result;
            } // for

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
                        item.匯率 = parseFloat(item.匯率) + parseFloat(curr.匯率);
                        item.本次請購數量 = parseFloat(item.本次請購數量) + parseFloat(curr.本次請購數量);
                    } else {
                        curr.匯率 = parseFloat(curr.匯率);
                        curr.本次請購數量 = parseFloat(curr.本次請購數量);
                        acc[curr.料號] = curr;
                    } // if else

                    return acc;
                }, {}));

                all_data_sorted.buylist[i] = sum_result;
            } // for

            // sort the yearly inbound
            for (let i = 0; i < 12; i++) {
                let newArray = allRowsObj.inbound.filter(function (el) {
                    let sql_Date_To_JS_Date = new Date(el.入庫時間.replace(' ', 'T'))
                    return sql_Date_To_JS_Date.getMonth() == i;
                });

                // sum the entries with the same 料號
                let sum_result = Object.values(newArray.reduce((acc, curr) => {
                    let item = acc[curr.料號];

                    if (item) {
                        item.入庫數量 = parseInt(item.入庫數量) + parseInt(curr.入庫數量);
                    } else {
                        curr.入庫數量 = parseInt(curr.入庫數量);
                        acc[curr.料號] = curr;
                    } // if else

                    return acc;
                }, {}));

                all_data_sorted.inbound[i] = sum_result;
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