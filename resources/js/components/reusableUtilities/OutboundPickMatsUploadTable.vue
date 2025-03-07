<template>
    <div class="card">
        <div class="card-header">
            <h3>{{ $t('outboundpageLang.pick') }}</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-center mb-3">
                <div class="col col-auto">
                    <a @click="OutputExcelClick" href="#">
                        {{ $t('outboundpageLang.download_example') }}
                    </a>
                </div>

                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <label class="col col-auto form-label">{{ $t('monthlyPRpageLang.plz_upload') }}</label>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="col col-auto">
                    <input class="form-control" :class="{ 'is-invalid': isInvalid }" id="excel_input" type="file"
                        name="select_file" @change="onInputChange" />
                    <span v-if="isInvalid" class="invalid-feedback d-block" role="alert">
                        <strong>{{ validation_err_msg }}</strong>
                    </span>
                </div>
                <div class="w-100" style="height: 2ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="row justify-content-center">
                    <button type="submit" name="upload" class="col col-auto btn btn-lg btn-primary"
                        @click="onUploadClick">
                        {{ $t('monthlyPRpageLang.upload1') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <button id="togglebtn" class="btn btn-primary col col-auto" type="button" data-bs-toggle="collapse"
        data-bs-target="#importedtable" aria-expanded="false" aria-controls="importedtable" style="display: none;">
    </button>

    <div class="card collapse" id="importedtable">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="row col col-auto">
                    <div class="col col-auto">
                        <label for="pnInput1" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }}:
                        </label>
                    </div>
                    <div class="col col-auto p-0 m-0">
                        <input id="pnInput1" class="text-center form-control form-control-lg"
                            v-bind:placeholder="$t('inboundpageLang.enterisn_or_loc')" v-model="searchTerm" />
                    </div>
                </div>
                <div class="col col-auto">
                    <button v-if="uploadToDBReady" id="delete" name="delete" class="col col-auto btn btn-lg btn-danger"
                        :value="$t('basicInfoLang.delete')" @click="deleteRow">
                        <i class="bi bi-trash3-fill fs-4"></i>
                    </button>
                </div>
            </div>
            <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
            <span v-if="isInvalid_DB" class="invalid-feedback d-block" role="alert">
                <strong style="white-space: pre-wrap;">{{ validation_err_msg }}</strong>
            </span>
            <table-lite :is-fixed-first-column="true" :is-static-mode="true" :hasCheckbox="true"
                :is-loading="table.isLoading" :messages="table.messages" :columns="table.columns" :rows="table.rows"
                :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
                @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows"></table-lite>

            <div class="w-100" style="height: 2ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="row justify-content-center">
                <div class="col col-auto">
                    <button v-if="uploadToDBReady" type="submit" name="upload"
                        class="col col-auto fs-3 text-center btn btn-lg btn-info" @click="onSendToDBClick">
                        <i class="bi bi-cloud-upload-fill"></i>
                        {{ $t('monthlyPRpageLang.upload1') }}
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
import ExcelJS from 'exceljs';
import FileSaver from "file-saver";
import TableLite from "./TableLite.vue";
import useCheckingInventory from "../../composables/CheckingInventory.ts";
import useInboundStockSearch from "../../composables/InboundStockSearch.ts";
import useCommonlyUsedFunctions from "../../composables/CommonlyUsedFunctions.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        // let exampleUrl = ref(window.location.origin + '/download/CheckingExample.xlsx');
        const app = getCurrentInstance(); // get the current instance
        let thisHtmlLang = document
            .getElementsByTagName("HTML")[0]
            .getAttribute("lang");
        // get the current locale from html tag
        app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

        const { mats, getExistingStock } = useInboundStockSearch(); // axios get the mats data
        const { queryResult, manualResult, locations, validateISN, validateISN_manual, getLocs } = useCommonlyUsedFunctions();
        const { upload_checkig_result } = useCheckingInventory();

        onBeforeMount(async () => {
            table.isLoading = false;
            await getLocs();
        });

        let isInvalid = ref(false); // file input validation
        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref("");
        let uploadToDBReady = ref(false); // validation
        const file = ref();
        let input_data;
        let checkedRows = [];
        const searchTerm = ref(""); // Search text
        const selected_mail = ref(""); // Mail reciever name
        const data = reactive([]); // pour the data into table

        const findDuplicates = (arr) => {
            let sorted_arr = arr.slice().sort(); // You can define the comparing function here. 
            // JS by default uses a crappy string compare.
            // (we use slice to clone the array so the original array won't be modified)
            let results = [];
            for (let i = 0; i < sorted_arr.length - 1; i++) {
                if (sorted_arr[i + 1] == sorted_arr[i]) {
                    results.push(sorted_arr[i]);
                } // if
            } // for
            return results;
        } // findDuplicates

        const OutputExcelClick = async () => {
            await triggerModal();
            await getExistingStock(new Array(0), new Array(0));
            let allData = JSON.parse(mats.value).data;
            // console.log(JSON.parse(mats.value).data); // test

            // get today's date for filename
            let today = new Date();
            let dd = String(today.getDate()).padStart(2, '0');
            let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            let yyyy = today.getFullYear();
            today = yyyy + "_" + mm + '_' + dd;

            let rows = Array();
            for (let i = 0; i < allData.length; i++) {
                if (allData[i].料號 === "" || allData[i].料號 === null || allData[i].料號.toLowerCase() === "null") {
                    continue;
                } // if
                let tempObj = new Object;
                tempObj.料號 = allData[i].料號;
                tempObj.品名 = allData[i].品名;
                tempObj.規格 = allData[i].規格;
                tempObj.現有庫存 = allData[i].現有庫存;
                tempObj.盤點庫存 = "";
                tempObj.單位 = allData[i].單位;
                tempObj.儲位 = allData[i].儲位;
                rows.push(tempObj);
            } // for

            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Sheet1');

            worksheet.columns = [
                { header: app.appContext.config.globalProperties.$t("inboundpageLang.isn"), key: '料號' },
                { header: app.appContext.config.globalProperties.$t("inboundpageLang.pName"), key: '品名' },
                { header: app.appContext.config.globalProperties.$t("inboundpageLang.format"), key: '規格' },
                { header: app.appContext.config.globalProperties.$t("inboundpageLang.nowstock"), key: '現有庫存' },
                { header: app.appContext.config.globalProperties.$t("checkInvLang.checking_result"), key: '盤點庫存' },
                { header: app.appContext.config.globalProperties.$t("inboundpageLang.unit"), key: '單位' },
                { header: app.appContext.config.globalProperties.$t("inboundpageLang.loc"), key: '儲位' }
            ];

            worksheet.addRows(rows);

            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            FileSaver.saveAs(blob, app.appContext.config.globalProperties.$t("checkInvLang.check") + "_" + today + ".xlsx");

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // OutputExcelClick

        const onUploadClick = async () => {
            isInvalid_DB.value = false;
            await triggerModal();
            if (file.value) {
                // console.log(file.value); // test
                if (file.value.type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || file.value.type == "application/vnd.ms-excel" || file.value.type == "application/vnd.ms-excel" || file.value.type == ".csv") {
                    const reader = new FileReader();
                    reader.onload = async (e) => {
                        /* Parse data */
                        const bstr = e.target.result;
                        const wb = XLSX.read(bstr, { type: 'binary' });
                        /* Get first worksheet */
                        const wsname = wb.SheetNames[0];
                        const ws = wb.Sheets[wsname];
                        /* Convert array of arrays */
                        input_data = XLSX.utils.sheet_to_json(ws, { header: 1 });
                        // console.log(input_data); // data[row#][col#]  test
                        if (input_data === undefined || input_data[0] === undefined ||
                            input_data[0][0] === undefined || input_data[0][1] === undefined ||
                            input_data[0][2] === undefined || input_data[0][3] === undefined ||
                            input_data[0][4] === undefined || input_data[0][5] === undefined ||
                            input_data[0][6] === undefined) {
                            isInvalid.value = true;
                            validation_err_msg.value = app.appContext.config.globalProperties.$t("fileUploadErrors.Content_errors");
                        } else {
                            let tempArr = Array();

                            for (let i = 1; i < input_data.length; i++) {
                                if (input_data[i][0] != undefined && input_data[i].length > 2 && input_data[i][0].trim() != "" && input_data[i][0].trim() != null) {
                                    tempArr.push(input_data[i][0].trim());
                                } // if
                                else {
                                    input_data.splice(i, 1); // remove the empty row
                                    i = i - 1;
                                } // else
                            } // for

                            // console.log(tempArr); // test
                            await triggerModal();
                            await validateISN(tempArr);
                        } // else
                    };

                    reader.readAsBinaryString(file.value);
                } // if
                else {
                    isInvalid.value = true;
                    validation_err_msg.value = app.appContext.config.globalProperties.$t("validation.mimetypes");
                } // else
            } // if
            else {
                isInvalid.value = true;
                validation_err_msg.value = app.appContext.config.globalProperties.$t("validation.required");
            } // else

            file.value = null;
            document.getElementById('excel_input').value = "";
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // onUploadClick

        const onInputChange = (event) => {
            isInvalid.value = false;
            data.splice(0); // cleanup data from previous upload
            queryResult.value = "";
            file.value = event.target.files ? event.target.files[0] : null;
            if ($("#importedtable").hasClass("show")) {
                $("#togglebtn").click();
            } // if
        } // onInputChange

        function CheckCurrentRow(e) {
            // console.log(e.target.closest('tr').firstChild.firstChild); // test
            if (!e.target.closest('tr').firstChild.firstChild.checked) {
                e.target.closest('tr').firstChild.firstChild.click();
            } // if
        } // CheckCurrentRow

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
            // console.log(checkedRows); // test
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
        } // deleteRow

        const onSendToDBClick = async () => {
            // console.log(selected_mail.value); // test
            await triggerModal();
            // console.log("The modal should be triggered by now."); // test
            isInvalid_DB.value = false;
            let rowsCount = 0;
            let hasError = false;
            // console.log(data.length); //test
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

                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            // ----------------------------------------------
            // validate if all the isn exist
            for (let j = 0; j < data.length && hasError == false; j++) {
                if (!data[j].valid_isn) {
                    hasError = true;
                    rowsCount = j;
                } // if
            } // for

            if (hasError) {
                isInvalid_DB.value = true;
                validation_err_msg.value =
                    data[rowsCount].料號 + " " +
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.noisn");

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

                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            // ----------------------------------------------
            // validate if there's duplicate values in table
            let duplicatedArray = Array();
            for (let i = 0; i < data.length; i++) {
                if (data[i].料號 != null && data[i].料號.trim() != "") {
                    duplicatedArray.push(data[i].料號.toString().trim() + "_" + data[i].儲位.toString());
                } // if
            } // for

            let findDuplicatesResult = findDuplicates(duplicatedArray);
            // console.log(findDuplicatesResult); // test
            if (findDuplicatesResult.length > 0) {
                isInvalid_DB.value = true;
                validation_err_msg.value =
                    app.appContext.config.globalProperties.$t("inboundpageLang.repeated_isn_loc_pair") +
                    " : " + findDuplicatesResult[0].split("_")[0] +
                    " (" + findDuplicatesResult[0].split("_")[1] + ")";

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

                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if
            // ----------------------------------------------
            // prepare the data arrays to be sent
            let pnArray = [];
            let locArray = [];
            let stockArray = [];
            for (let j = 0; j < data.length; j++) {
                pnArray.push(data[j].料號);
                locArray.push(data[j].儲位);
                stockArray.push(data[j].盤點庫存);
            } // for
            // console.log(stockArray); //test
            // actually updating database now
            let start = Date.now();
            let result = await upload_checkig_result(pnArray, locArray, stockArray);
            let timeTaken = Date.now() - start;
            console.log("Total time taken : " + timeTaken + " milliseconds");
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");

            if (result === "success") {
                uploadToDBReady.value = false;
                notyf.open({
                    type: "success",
                    message: app.appContext.config.globalProperties.$t("checkInvLang.update_success"),
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
        } // onSendToDBClick

        watch(queryResult, async () => {
            await triggerModal();
            validation_err_msg.value =
                app.appContext.config.globalProperties.$t("barcodeGenerator.temp_save_error") +
                " Excel " +
                app.appContext.config.globalProperties.$t("monthlyPRpageLang.row");
            // console.log(input_data); // test
            if (queryResult.value == "") {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                table.isLoading = false;
                return;
            } // if

            let allRowsObj = JSON.parse(queryResult.value);
            let allLocsObj = JSON.parse(locations.value);
            // console.log(allLocsObj); // test
            let singleEntry = {};

            for (let i = 1; i < input_data.length; i++) {
                singleEntry.id = i;
                singleEntry.料號 = input_data[i][0].toString().trim();
                singleEntry.品名 = input_data[i][1].toString().trim();
                singleEntry.規格 = input_data[i][2].toString().trim();
                singleEntry.盤點庫存 = parseInt(input_data[i][4]);
                singleEntry.單位 = input_data[i][5].toString().trim();
                singleEntry.儲位 = input_data[i][6].toString().trim();

                let indexOfObject = allRowsObj.data.findIndex(object => {
                    return (object.料號 === singleEntry.料號);
                });

                let indexOfObject2 = allLocsObj.data.findIndex(object => {
                    return (object.儲存位置 === singleEntry.儲位);
                });

                if (indexOfObject != -1) { // if isn exist in consumptive_material table
                    singleEntry.valid_isn = true;
                } // if
                else {
                    singleEntry.valid_isn = false;
                } // else

                if (indexOfObject2 != -1) { // if location exist in location table
                    singleEntry.valid_loc = true;
                } // if
                else {
                    singleEntry.valid_loc = false;
                } // else

                if (isNaN(singleEntry.盤點庫存) || singleEntry.valid_isn === false || singleEntry.valid_loc === false) {
                    isInvalid_DB.value = true;

                    validation_err_msg.value = validation_err_msg.value + " " + (i + 1) + ",";
                } // if
                data.push(singleEntry);
                singleEntry = {};
            } // for

            validation_err_msg.value = validation_err_msg.value.slice(0, -1); // Remove the last character ","

            // console.log(data); // test
            if (!$("#importedtable").hasClass("show")) {
                $("#togglebtn").click();
            } // if

            uploadToDBReady.value = !isInvalid_DB.value;
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
            table.isLoading = false;
        }); // watch for data change

        watch(data, () => {
            document.getElementsByClassName("vtl-table")[0].scrollIntoView({ behavior: "smooth" });
        });

        // Table config
        const table = reactive({
            isLoading: true,
            columns: [
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.isn"
                    ),
                    field: "料號",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.valid_isn === false) { // if isn not exist in consumptive_material table
                            return (
                                '<div class="text-nowrap text-danger CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.料號 +
                                "</div>"
                            );
                        } // if
                        else { // isn exist in database
                            return (
                                '<div class="text-nowrap CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.料號 +
                                "</div>"
                            );
                        } // if else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.pName"
                    ),
                    field: "品名",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.valid_isn === false) { // if isn not exist in consumptive_material table
                            return (
                                '<div class="CustomScrollbar text-nowrap text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                app.appContext.config.globalProperties.$t("monthlyPRpageLang.noisn") +
                                "</div>"
                            );
                        } // if
                        else {
                            if (row.品名 === "N/A") {
                                return (
                                    '<div class="CustomScrollbar text-nowrap text-danger"' +
                                    ' style="overflow-x: auto; width: 100%;">' +
                                    '<span class="text-danger fw-bold">' + row.品名 + '</span>' +
                                    "</div>"
                                );
                            } // if
                            else {
                                return (
                                    '<div class="CustomScrollbar text-nowrap"' +
                                    ' style="overflow-x: auto; width: 100%;">' +
                                    row.品名 +
                                    "</div>"
                                );
                            } // else
                        } // else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.format"
                    ),
                    field: "規格",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.valid_isn === false) { // if isn not exist in consumptive_material table
                            return (
                                '<div class="text-nowrap CustomScrollbar text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                app.appContext.config.globalProperties.$t("monthlyPRpageLang.noisn") +
                                "</div>"
                            );
                        } // if
                        else {
                            return (
                                '<div class="text-nowrap CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.規格 +
                                "</div>"
                            );
                        } // else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "checkInvLang.checking_result"
                    ),
                    field: "庫存",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        if (!Number.isInteger(row.盤點庫存)) {
                            if (row.單位 === "N/A") {
                                return (
                                    '<div class="text-nowrap CustomScrollbar"' +
                                    ' style="overflow-x: auto; width: 100%;">' +
                                    '<i class="bi bi-x-lg text-danger fw-bold"></i>' + '&nbsp;<small class="text-danger fw-bold">' + row.單位 + '</small>' +
                                    "</div>"
                                );
                            } else {
                                return (
                                    '<div class="text-nowrap CustomScrollbar"' +
                                    ' style="overflow-x: auto; width: 100%;">' +
                                    '<i class="bi bi-x-lg text-danger fw-bold"></i>' + '&nbsp;<small>' + row.單位 + '</small>' +
                                    "</div>"
                                );
                            } // else

                        } // if
                        else {
                            return (
                                '<div class="text-nowrap CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.盤點庫存 + '&nbsp;<small>' + row.單位 + '</small>' +
                                "</div>"
                            );
                        } // else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.loc"
                    ),
                    field: "儲位",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.valid_loc === false) { // if location not exist in location table
                            return (
                                '<div class="text-nowrap CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                '<span class="text-danger fw-bold">' + row.儲位 + '</span>' +
                                "</div>"
                            );
                        } // if
                        else {
                            return (
                                '<div class="text-nowrap CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.儲位 +
                                "</div>"
                            );
                        } // else
                    },
                },
            ],
            rows: computed(() => {
                return data.filter((x) =>
                    x.料號
                        .toLowerCase()
                        .includes(searchTerm.value.toLowerCase()) ||
                    x.儲位
                        .includes(searchTerm.value)
                );
            }),
            totalRecordCount: computed(() => {
                return table.rows.length;
            }),
            sortable: {
                order: "儲位",
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
                noDataAvailable: app.appContext.config.globalProperties.$t(
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
                    value: 100,
                    text: 100,
                },
            ],
        });

        const updateCheckedRows = (rowsKey) => {
            // console.log(rowsKey); // test
            checkedRows = rowsKey;
        };

        return {
            // exampleUrl,
            isInvalid,
            isInvalid_DB,
            validation_err_msg,
            uploadToDBReady,
            searchTerm,
            table,
            OutputExcelClick,
            updateCheckedRows,
            onUploadClick,
            onInputChange,
            onSendToDBClick,
            CheckCurrentRow,
            deleteRow,
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