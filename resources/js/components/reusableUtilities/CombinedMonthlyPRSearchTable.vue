<template>
    <div class="card">
        <div class="card-header ">
            <h3>{{ $t("monthlyPRpageLang.PR") }}</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="row col col-auto">
                    <div class="col col-auto">
                        <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
                    </div>
                    <div class="col col-6 p-0 m-0">
                        <input id="pnInput" class="text-center form-control form-control-lg"
                            v-bind:placeholder="$t('basicInfoLang.enterisn')" v-model="searchTerm" />
                    </div>
                </div>
                <div class="col col-auto">
                    <button id="delete" name="delete" class="col col-auto btn btn-lg btn-danger" @click="deleteRow">
                        <i class="bi bi-trash3-fill fs-4"></i>
                    </button>
                    &nbsp;
                    <button id="download" name="download" class="col col-auto btn btn-lg btn-success"
                        :value="$t('monthlyPRpageLang.download')" @click="OutputExcelClick">
                        <i class="bi bi-file-earmark-arrow-down-fill fs-4"></i>
                    </button>
                </div>
            </div>
            <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
            <div class="row justify-content-between">
                <span v-if="isInvalid_DB" class="col col-auto text-danger" role="alert">
                    <strong>{{ validation_err_msg }}</strong>
                </span>
                <span v-else class="col col-auto text-danger" role="alert">
                    <strong></strong>
                </span>
                <span class="col col-auto text-danger fs-5">{{ $t('monthlyPRpageLang.shallow_delete') }}</span>
            </div>
            <table-lite id="searchTable" :is-fixed-first-column="true" :is-static-mode="true" :hasCheckbox="true"
                :isLoading="table.isLoading" :messages="table.messages" :columns="table.columns" :rows="table.rows"
                :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
                @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows"></table-lite>

            <div class="row justify-content-center">
                <div class="col col-auto">
                    <button v-if="uploadToDBReady" type="submit" name="upload"
                        class="col col-auto fs-3 text-center btn btn-lg btn-info" @click="onSendClick">
                        <i class="bi bi-envelope-check-fill"></i>
                        {{ $t('monthlyPRpageLang.SendPRReview') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, reactive, ref, computed } from "vue";
import {
    getCurrentInstance,
    onBeforeMount,
    onMounted,
    watch,
} from "@vue/runtime-core";
import * as XLSX from 'xlsx';
import TableLite from "./TableLite.vue";
import useMonthlyPRSearch from "../../composables/MonthlyPRSearch.ts";
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

        const { mats, Currency, getMats_Buylist, getMats_nonMonthly, sendBuylistMail, submitBuylist, getCurrency } = useMonthlyPRSearch(); // axios get the mats data
        onBeforeMount(getCurrency);

        const selectedValue2 = ref('USD');
        let isInvalid = ref(true); // validation

        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref(app.appContext.config.globalProperties.$t("validation.required"));
        let checkedRows = [];
        let uploadToDBReady = ref(false); // validation

        const MPSData = ref(null);
        const nonMPSData = ref(null);
        const combinedData = ref(null);
        let nonMPS_PN_Array = [];
        let MPS_PN_Array = [];
        let MPS_90PN_Array = [];

        const searchTerm = ref(""); // Search text

        // pour the data in
        const data = reactive([]);

        const triggerModal = async () => {
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            return new Promise((resolve, reject) => {
                resolve();
            });
        } // triggerModal

        const deleteRow = () => {
            if (checkedRows.length == 0) {
                notyf.open({
                    type: "warning",
                    message: app.appContext.config.globalProperties.$t("basicInfoLang.nodata"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });

                return;
            } // if

            for (let i = 0; i < checkedRows.length; i++) {
                let indexOfObject = data.findIndex(object => {
                    return parseInt(object.id) === parseInt(checkedRows[i].id);
                });

                if (indexOfObject != -1) {
                    data.splice(indexOfObject, 1);
                } // if
            } // for

            document.querySelectorAll('.vtl-tbody-checkbox').forEach(el => el.checked = false);
            if (document.querySelector(".vtl-thead-checkbox").checked) {
                document.querySelector(".vtl-thead-checkbox").click();
            } // if
            checkedRows = [];

        } // deleteRowv

        const OutputExcelClick = async () => {
            await triggerModal();

            // get today's date for filename
            let today = new Date();
            let dd = String(today.getDate()).padStart(2, '0');
            let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            let yyyy = today.getFullYear();
            today = yyyy + "_" + mm + '_' + dd;

            let rows = Array();

            for (let i = 0; i < data.length; i++) {
                let tempObj = new Object;
                tempObj.料號 = data[i].料號;
                tempObj.品名 = data[i].品名;
                tempObj.規格 = data[i].規格;
                tempObj.單價 = data[i].單價;
                tempObj.幣別1 = data[i].幣別;
                tempObj.當月需求 = parseFloat(data[i].當月需求).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                tempObj.下月需求 = parseFloat(data[i].下月需求).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                tempObj.現有庫存 = parseInt(data[i].現有庫存).toLocaleString("en-US");
                tempObj.在途量 = parseInt(data[i].在途量).toLocaleString("en-US");
                tempObj.本次請購數量 = parseInt(data[i].本次請購數量).toLocaleString("en-US");
                tempObj.請購金額 = parseFloat(data[i].請購金額).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                tempObj.幣別2 = data[i].幣別;
                tempObj.匯率 = parseFloat(data[i].匯率).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                tempObj.MOQ = data[i].MOQ;
                rows.push(tempObj);
            } // for

            const worksheet = XLSX.utils.json_to_sheet(rows);

            // change header name
            XLSX.utils.sheet_add_aoa(worksheet,
                [[
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.isn"),
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.pName"),
                    app.appContext.config.globalProperties.$t("inboundpageLang.format"),
                    app.appContext.config.globalProperties.$t("basicInfoLang.price"),
                    app.appContext.config.globalProperties.$t("basicInfoLang.money"),
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.nowneed"),
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.nextneed"),
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.nowstock"),
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.transit"),
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.buyamount"),
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.buyprice"),
                    app.appContext.config.globalProperties.$t("basicInfoLang.money"),
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.buyprice") + "(USD)",
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.moq"),
                ]],
                { origin: "A1" });

            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, app.appContext.config.globalProperties.$t("monthlyPRpageLang.stock"));
            XLSX.writeFile(workbook,
                app.appContext.config.globalProperties.$t(
                    "monthlyPRpageLang.PR"
                ) + "_" + today + ".xlsx", { compression: true });

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // OutputExcelClick

        const onSendClick = async () => {
            await triggerModal();

            isInvalid_DB.value = false;
            let rowsCount = 0;
            let hasError = false;

            if (data.length <= 0) {
                notyf.open({
                    type: "warning",
                    message: app.appContext.config.globalProperties.$t("basicInfoLang.nodata"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });
                return;
            } // if

            // ----------------------------------------------
            // actually updating database now
            let number = [];
            let pName = [];
            let spec = [];
            let unit_price = [];
            let nowNeed = [];
            let nextNeed = [];
            let stock = [];
            let in_transit = [];
            let req_amount = [];
            let total_price_default_currency = [];
            let total_price_default_currency_name = [];
            let total_price_other_currency = [];
            let moq = [];

            for (let i = 0; i < data.length; i++) {
                number.push(data[i].料號);
                pName.push(data[i].品名);
                spec.push(data[i].規格);
                unit_price.push(data[i].單價);
                nowNeed.push(parseFloat(data[i].當月需求).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                nextNeed.push(parseFloat(data[i].下月需求).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                stock.push(parseInt(data[i].現有庫存).toLocaleString("en-US"));
                in_transit.push(parseInt(data[i].在途量).toLocaleString("en-US"));
                req_amount.push(parseInt(data[i].本次請購數量).toLocaleString("en-US"));
                total_price_default_currency.push(parseFloat(data[i].請購金額).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                total_price_default_currency_name.push(data[i].幣別);
                total_price_other_currency.push(parseFloat(data[i].匯率).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                moq.push(data[i].MOQ);
            } // for

            let start = Date.now();
            let result = await sendBuylistMail(
                number, pName, spec, unit_price,
                nowNeed, nextNeed, stock, in_transit, req_amount,
                total_price_default_currency, total_price_default_currency_name, total_price_other_currency, moq
            );

            let timeTaken = Date.now() - start;
            console.log("Total time taken : " + timeTaken + " milliseconds");
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");

            // ------- test --------
            // result = await submitBuylist(
            //     number, pName, spec, unit_price,
            //     nowNeed, nextNeed, stock, in_transit, req_amount,
            //     total_price_default_currency, total_price_default_currency_name,
            //     total_price_other_currency, moq, nonMPS_PN_Array, MPS_90PN_Array, MPS_PN_Array
            // );
            // ------- ---- --------

            if (result === "success") {
                result = await submitBuylist(
                    number, pName, spec, unit_price,
                    nowNeed, nextNeed, stock, in_transit, req_amount,
                    total_price_default_currency, total_price_default_currency_name,
                    total_price_other_currency, moq, nonMPS_PN_Array, MPS_90PN_Array, MPS_PN_Array
                );

                if (result === "success") {
                    uploadToDBReady.value = false;
                    notyf.open({
                        type: "success",
                        message: app.appContext.config.globalProperties.$t("monthlyPRpageLang.total") + " " + data.length + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.record") + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.change") + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.success"),
                        duration: 3000, //miliseconds, use 0 for infinite duration
                        ripple: true,
                        dismissible: true,
                        position: {
                            x: "right",
                            y: "bottom",
                        },
                    });
                } // if
                else {
                    notyf.open({
                        type: "error",
                        message: app.appContext.config.globalProperties.$t("checkInvLang.update_failed"),
                        duration: 3000, //miliseconds, use 0 for infinite duration
                        ripple: true,
                        dismissible: true,
                        position: {
                            x: "right",
                            y: "bottom",
                        },
                    });
                } // else
            } // if
            else {
                notyf.open({
                    type: "error",
                    message: app.appContext.config.globalProperties.$t("checkInvLang.update_failed"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });
            } // else

        } // onSendClick

        watch(Currency, async () => {
            await triggerModal();
            data.splice(0);
            // console.log(Currency.value); // test
            nonMPS_PN_Array = [];
            MPS_PN_Array = [];
            MPS_90PN_Array = [];
            isInvalid.value = false;
            let exchange_table = Currency.value.rates;

            // if all is good, proceed on generating the CombinedPRTable
            if (isInvalid.value === false) {
                await getMats_Buylist();
                MPSData.value = JSON.parse(mats.value).data;
                await getMats_nonMonthly();
                nonMPSData.value = JSON.parse(mats.value).data;

                // console.log(MPSData.value); // test
                // console.log(nonMPSData.value); // test

                let singleEntry = {};
                let tempAll = [];
                // push temp
                for (let i = 0; i < MPSData.value.length; i++) {
                    singleEntry.料號 = MPSData.value[i].料號.toString().trim();
                    singleEntry.品名 = MPSData.value[i].品名.toString().trim();
                    singleEntry.規格 = MPSData.value[i].規格.toString().trim();
                    singleEntry.單價 = parseFloat(MPSData.value[i].單價);
                    singleEntry.當月需求 = parseInt(MPSData.value[i].本月MPS) * parseFloat(MPSData.value[i].單耗) / 1000;
                    singleEntry.下月需求 = parseInt(MPSData.value[i].下月MPS) * parseFloat(MPSData.value[i].單耗) / 1000;
                    if (MPSData.value[i].total_stock === null) {
                        singleEntry.現有庫存 = 0;
                    } else {
                        singleEntry.現有庫存 = parseInt(MPSData.value[i].total_stock);
                    } // if else

                    if (MPSData.value[i].in_transit === null) {
                        singleEntry.在途量 = 0;
                    } else {
                        singleEntry.在途量 = Math.round(parseFloat(MPSData.value[i].in_transit));
                    } // if else

                    // Don't care, will be calculated below
                    singleEntry.本次請購數量 = Math.ceil(singleEntry.當月需求 + singleEntry.下月需求);

                    singleEntry.請購金額 = parseFloat((singleEntry.本次請購數量 * singleEntry.單價).toFixed(5));
                    singleEntry.幣別 = MPSData.value[i].幣別.toString().trim().toUpperCase();
                    if (singleEntry.幣別 == "RMB") {
                        singleEntry.匯率 = parseFloat((singleEntry.請購金額 * (exchange_table['USD'] / exchange_table['CNY'])).toFixed(5));
                    } // if
                    else {
                        singleEntry.匯率 = parseFloat((singleEntry.請購金額 * (exchange_table['USD'] / exchange_table[singleEntry.幣別])).toFixed(5));
                    } // else
                    singleEntry.MOQ = parseInt(MPSData.value[i].MOQ.toString().trim());
                    singleEntry.id = i;
                    tempAll.push(singleEntry);
                    MPS_PN_Array.push(singleEntry.料號);
                    MPS_90PN_Array.push(MPSData.value[i].料號90);
                    // data.push(singleEntry); // test
                    // console.log(singleEntry); // test
                    singleEntry = {};
                } // for

                for (let i = 0; i < nonMPSData.value.length; i++) {
                    singleEntry.料號 = nonMPSData.value[i].料號.toString().trim();
                    singleEntry.品名 = nonMPSData.value[i].品名.toString().trim();
                    singleEntry.規格 = nonMPSData.value[i].規格.toString().trim();
                    singleEntry.單價 = parseFloat(nonMPSData.value[i].單價);
                    singleEntry.當月需求 = parseFloat(nonMPSData.value[i].當月需求);
                    singleEntry.下月需求 = parseFloat(nonMPSData.value[i].下月需求);
                    if (nonMPSData.value[i].total_stock === null) {
                        singleEntry.現有庫存 = 0;
                    } else {
                        singleEntry.現有庫存 = parseInt(nonMPSData.value[i].total_stock);
                    } // if else

                    if (nonMPSData.value[i].in_transit === null) {
                        singleEntry.在途量 = 0;
                    } else {
                        singleEntry.在途量 = Math.round(parseFloat(nonMPSData.value[i].in_transit));
                    } // if else

                    // Don't care, will be calculated below
                    singleEntry.本次請購數量 = Math.ceil(singleEntry.當月需求 + singleEntry.下月需求);

                    singleEntry.請購金額 = parseFloat((singleEntry.本次請購數量 * singleEntry.單價).toFixed(5));
                    singleEntry.幣別 = nonMPSData.value[i].幣別.toString().trim().toUpperCase();
                    if (singleEntry.幣別 == "RMB") {
                        singleEntry.匯率 = parseFloat((singleEntry.請購金額 * (exchange_table['USD'] / exchange_table['CNY'])).toFixed(5));
                    } // if
                    else {
                        singleEntry.匯率 = parseFloat((singleEntry.請購金額 * (exchange_table['USD'] / exchange_table[singleEntry.幣別])).toFixed(5));
                    } // else
                    singleEntry.MOQ = parseInt(nonMPSData.value[i].MOQ.toString().trim());

                    singleEntry.id = i + MPSData.value.length;

                    tempAll.push(singleEntry);
                    nonMPS_PN_Array.push(singleEntry.料號);
                    singleEntry = {};
                } // for

                // console.log(tempAll); // test

                // sum by 料號
                let sum_result = Object.values(tempAll.reduce((accumulator, currentValue) => {
                    if (!accumulator[currentValue.料號]) { // if the current 料號 is fisrt met, then we create a index for it
                        accumulator[currentValue.料號] =
                        {
                            id: currentValue.id,
                            料號: currentValue.料號,
                            品名: currentValue.品名,
                            規格: currentValue.規格,
                            單價: currentValue.單價,
                            當月需求: 0,
                            下月需求: 0,
                            現有庫存: currentValue.現有庫存,
                            在途量: currentValue.在途量,
                            本次請購數量: 0,
                            請購金額: 0,
                            幣別: currentValue.幣別,
                            匯率: 0,
                            MOQ: currentValue.MOQ
                        };
                    } // if

                    // sum the value by index
                    accumulator[currentValue.料號].當月需求 += currentValue.當月需求;
                    accumulator[currentValue.料號].下月需求 += currentValue.下月需求;
                    return accumulator;
                }, {}));

                // console.log(sum_result); // test

                // Calculate result rows
                let final_result = Object.values(sum_result.reduce((accumulator, currentValue) => {
                    if (!accumulator[currentValue.料號]) { // if the current 料號 is fisrt met, then we create a index for it
                        accumulator[currentValue.料號] =
                        {
                            id: currentValue.id,
                            料號: currentValue.料號,
                            品名: currentValue.品名,
                            規格: currentValue.規格,
                            單價: currentValue.單價,
                            當月需求: currentValue.當月需求,
                            下月需求: currentValue.下月需求,
                            現有庫存: currentValue.現有庫存,
                            在途量: currentValue.在途量,
                            本次請購數量: 0,
                            請購金額: 0,
                            幣別: currentValue.幣別,
                            匯率: 0,
                            MOQ: currentValue.MOQ
                        };
                    } // if

                    // calculate the value by index
                    accumulator[currentValue.料號].本次請購數量 = Math.ceil(currentValue.當月需求 + currentValue.下月需求 - currentValue.現有庫存 - currentValue.在途量);
                    if (accumulator[currentValue.料號].本次請購數量 < 0) {
                        accumulator[currentValue.料號].本次請購數量 = 0;
                    } // if
                    accumulator[currentValue.料號].請購金額 = parseFloat((accumulator[currentValue.料號].本次請購數量 * currentValue.單價).toFixed(5));
                    if (currentValue.幣別 == "RMB") {
                        accumulator[currentValue.料號].匯率 = parseFloat((accumulator[currentValue.料號].請購金額 * (exchange_table['USD'] / exchange_table['CNY'])).toFixed(5));
                    } // if
                    else {
                        accumulator[currentValue.料號].匯率 = parseFloat((accumulator[currentValue.料號].請購金額 * (exchange_table['USD'] / exchange_table[currentValue.幣別])).toFixed(5));
                    } // else
                    // console.log(accumulator); // test
                    return accumulator;
                }, {}));

                // console.log(final_result); // test

                // push to table
                for (let j = 0; j < final_result.length; j++) {
                    data.push(final_result[j]);
                } // for
            } // if

            if (data.length > 0) {
                uploadToDBReady.value = true;
            } // if
            else {
                uploadToDBReady.value = false;
            } // else

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        }); // watch for data change

        // Table config
        const table = reactive({
            isLoading: false,
            columns: [
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.isn"
                    ),
                    field: "料號",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="isn' +
                            row.id +
                            '" name="isn' +
                            i +
                            '" value="' +
                            row.料號 +
                            '">' +
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.料號 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.pName"
                    ),
                    field: "品名",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="name' +
                            i +
                            '" name="name' +
                            i +
                            '" value="' +
                            row.品名 +
                            '">' +
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.品名 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.format"
                    ),
                    field: "規格",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="format' +
                            i +
                            '" name="format' +
                            i +
                            '" value="' +
                            row.規格 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.規格 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.price"
                    ),
                    field: "單價",
                    width: "9ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="price' +
                            i +
                            '" name="price' +
                            i +
                            '" value="' +
                            row.單價 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.單價 + " <small>" + row.幣別 + "</small>" +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.nowneed"
                    ),
                    field: "當月需求",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="nowneed' +
                            i +
                            '" name="nowneed' +
                            i +
                            '" value="' +
                            row.當月需求 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseFloat(row.當月需求).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.nextneed"
                    ),
                    field: "下月需求",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="nextneed' +
                            row.id +
                            '" name="nextneed' +
                            i +
                            '" value="' +
                            row.下月需求 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseFloat(row.下月需求).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.nowstock"
                    ),
                    field: "現有庫存",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="nowstock' +
                            row.id +
                            '" name="nowstock' +
                            i +
                            '" value="' +
                            row.現有庫存 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseInt(row.現有庫存).toLocaleString("en-US") +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.transit"
                    ),
                    field: "在途量",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="transit' +
                            i +
                            '" name="transit' +
                            i +
                            '" value="' +
                            row.在途量 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseInt(row.在途量).toLocaleString("en-US") +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.buyamount"
                    ),
                    field: "本次請購數量",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="buyamount' +
                            row.id +
                            '" name="buyamount' +
                            i +
                            '" value="' +
                            row.本次請購數量 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseInt(row.本次請購數量).toLocaleString("en-US") +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.buyprice"
                    ),
                    field: "請購金額",
                    width: "11ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="buyprice' +
                            row.id +
                            '" name="buyprice' +
                            i +
                            '" value="' +
                            row.請購金額 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseFloat(row.請購金額).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") +
                            " <small>" + row.幣別 + "</small>" +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.buyprice"
                    ) + "(USD)",
                    field: "匯率",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="rate' +
                            row.id +
                            '" name="rate' +
                            i +
                            '" value="' +
                            row.匯率 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseFloat(row.匯率).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") +
                            " " + "<small>USD</small>" +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.moq"
                    ),
                    field: "MOQ",
                    width: "8ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="moq' +
                            row.id +
                            '" name="moq' +
                            i +
                            '" value="' +
                            row.MOQ +
                            '">' +
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.MOQ +
                            "</div>"
                        );
                    },
                },
            ],
            rows: computed(() => {
                return data.filter((x) =>
                    x.料號
                        .toLowerCase()
                        .includes(searchTerm.value.toLowerCase())
                );
            }),
            totalRecordCount: computed(() => {
                return table.rows.length;
            }),
            sortable: {
                order: "id",
                sort: "asc",
            },
            messages: {
                pagingInfo:
                    app.appContext.config.globalProperties.$t(
                        "basicInfoLang.now_showing"
                    ) +
                    " {0} ~ {1} " +
                    app.appContext.config.globalProperties.$t(
                        "basicInfoLang.record"
                    ) +
                    ", " +
                    app.appContext.config.globalProperties.$t(
                        "basicInfoLang.total"
                    ) +
                    " {2} " +
                    app.appContext.config.globalProperties.$t(
                        "basicInfoLang.record"
                    ),
                pageSizeChangeLabel: app.appContext.config.globalProperties.$t(
                    "basicInfoLang.records_per_page"
                ),
                gotoPageLabel: app.appContext.config.globalProperties.$t(
                    "basicInfoLang.go_to_page"
                ),
                noDateAvailable: app.appContext.config.globalProperties.$t(
                    "basicInfoLang.search_with_no_data_returned"
                ),
            },
            pageOptions: [
                {
                    value: 10,
                    text: 10,
                },
                {
                    value: 20,
                    text: 20,
                },
                {
                    value: 40,
                    text: 40,
                },
                {
                    value: 60,
                    text: 60,
                },
            ],
        });

        const updateCheckedRows = (rowsKey) => {
            // console.log(rowsKey); // test
            checkedRows = rowsKey;
        };

        return {
            selectedValue2,
            isInvalid,
            isInvalid_DB,
            validation_err_msg,
            uploadToDBReady,
            searchTerm,
            table,
            updateCheckedRows,
            deleteRow,
            OutputExcelClick,
            onSendClick,
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
