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
                            v-bind:placeholder="$t('monthlyPRpageLang.enterisn_or_descr')" v-model="searchTerm" />
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
            <span v-if="isDuplicated" class="invalid-feedback d-block" role="alert">
                <strong style="white-space: pre-wrap;">{{ validation_err_msg_duplicated }}</strong>
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
import useInboundStockSearch from "../../composables/InboundStockSearch.ts";
import useOutboundPickRecord from "../../composables/OutboundPickRecordSearch.ts";
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
        const { reasons, getPickReason, lines, getLines, uploadNewPickList } = useOutboundPickRecord();

        onBeforeMount(async () => {
            table.isLoading = false;
            await getLines();
            await getPickReason();
            await getExistingStock(new Array(0), new Array(0));
        });

        let isInvalid = ref(false); // file input validation
        let isInvalid_DB = ref(false); // add to DB validation
        let isDuplicated = ref(false); // duplicated isn validation
        let validation_err_msg = ref("");
        let validation_err_msg_duplicated = app.appContext.config.globalProperties.$t("outboundpageLang.repeated_isn_will_not_display");
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
            let allData = JSON.parse(mats.value).data;
            let allLines = JSON.parse(lines.value).datas;
            let allLines_arr = allLines.map((item) => {
                return item.線別;
            });
            let allReasons = JSON.parse(reasons.value).datas;
            let allReasons_arr = allReasons.map((item) => {
                return item.領用原因;
            });
            // console.log(allReasons_arr); // test

            // get today's date for filename
            let today = new Date();
            let dd = String(today.getDate()).padStart(2, '0');
            let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            let yyyy = today.getFullYear();
            today = yyyy + "_" + mm + '_' + dd;

            let rows = Array();
            let tempObj = new Object;
            tempObj.料號 = allData[0].料號;
            tempObj.品名 = allData[0].品名;
            tempObj.規格 = allData[0].規格;
            tempObj.發料部門 = allData[0].發料部門;
            tempObj.預領數量 = "";
            tempObj.單位 = allData[0].單位;
            tempObj.線別 = "";
            tempObj.領用原因 = "";
            tempObj.備註 = "";
            rows.push(tempObj);

            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet(app.appContext.config.globalProperties.$t("outboundpageLang.picklist"));

            worksheet.columns = [
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.isn"), key: '料號' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.pName"), key: '品名' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.format"), key: '規格' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.senddep"), key: '發料部門' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.pickamount"), key: '預領數量' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.unit"), key: '單位' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.line"), key: '線別' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.usereason"), key: '領用原因' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.mark"), key: '備註' }
            ];

            worksheet.addRows(rows);

            const ValidationRange_Line = worksheet.getColumn('G');
            ValidationRange_Line.eachCell((cell) => {
                if (cell.row > 1) {
                    cell.dataValidation = {
                        type: 'list',
                        allowBlank: false,
                        errorStyle: 'stop',
                        errorTitle: 'Invalid Data',
                        error: 'Please enter a valid value',
                        formulae: ['"' + allLines_arr.join(',') + '"']
                    };
                } // if
            });

            const ValidationRange_Reason = worksheet.getColumn('H');
            ValidationRange_Reason.eachCell((cell) => {
                if (cell.row > 1) {
                    cell.dataValidation = {
                        type: 'list',
                        allowBlank: false,
                        errorStyle: 'stop',
                        errorTitle: 'Invalid Data',
                        error: 'Please enter a valid value',
                        formulae: ['"' + allReasons_arr.join(',') + '"']
                    };
                } // if
            });

            // Add border to title row and set font to bold on col A to I
            worksheet.getRow(1).eachCell((cell) => {
                cell.font = { bold: true };
                cell.border = {
                    top: { style: 'thin' },
                    left: { style: 'thin' },
                    bottom: { style: 'thin' },
                    right: { style: 'thin' }
                };
            });

            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            FileSaver.saveAs(blob, app.appContext.config.globalProperties.$t("outboundpageLang.download_example") + ".xlsx");

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
                            input_data[0][0] === undefined || input_data[0][4] === undefined ||
                            input_data[0][6] === undefined || input_data[0][7] === undefined) {
                            isInvalid.value = true;
                            validation_err_msg.value = app.appContext.config.globalProperties.$t("fileUploadErrors.Content_errors");
                        } else {
                            let tempArr = Array();

                            for (let i = 1; i < input_data.length; i++) {
                                if (input_data[i][0] != undefined && input_data[i].length > 0 && input_data[i][0].trim() != "" && input_data[i][0].trim() != null) {
                                    tempArr.push(input_data[i][0].trim().replaceAll('\n', ''));
                                } // if
                                else {
                                    input_data.splice(i, 1); // remove the empty row
                                    i = i - 1;
                                } // else
                            } // for

                            // console.log(tempArr); // test
                            await triggerModal();
                            await getExistingStock(tempArr, new Array(0));
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
            mats.value = "";
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
            // prepare the data array to be sent
            let picklistArry = new Array(8);
            let isnArry = new Array();
            let lineArry = new Array();
            let reasonArry = new Array();
            let pNameArry = new Array();
            let formatArry = new Array();
            let unitArry = new Array();
            let qtyArry = new Array();
            let noteArry = new Array();
            for (let j = 0; j < data.length; j++) {
                isnArry.push(data[j].料號);
                lineArry.push(data[j].線別);
                reasonArry.push(data[j].領用原因);
                pNameArry.push(data[j].品名);
                formatArry.push(data[j].規格);
                unitArry.push(data[j].單位);
                qtyArry.push(data[j].預領數量);
                noteArry.push(data[j].備註);
            } // for

            picklistArry[0] = isnArry;
            picklistArry[1] = lineArry;
            picklistArry[2] = reasonArry;
            picklistArry[3] = pNameArry;
            picklistArry[4] = formatArry;
            picklistArry[5] = unitArry;
            picklistArry[6] = qtyArry;
            picklistArry[7] = noteArry;

            // actually updating database now
            let start = Date.now();
            let result = await uploadNewPickList(picklistArry);
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

        watch(mats, async () => {
            await triggerModal();
            if (input_data == undefined || input_data == null || input_data.length == 0) {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                table.isLoading = false;
                return;
            } // if
            validation_err_msg.value =
                app.appContext.config.globalProperties.$t("barcodeGenerator.temp_save_error") +
                " Excel " +
                app.appContext.config.globalProperties.$t("monthlyPRpageLang.row");
            // console.log(input_data); // test
            if (mats.value == "") {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                table.isLoading = false;
                return;
            } // if

            let allRowsObj = JSON.parse(mats.value);
            let allLines = JSON.parse(lines.value);
            let allReasons = JSON.parse(reasons.value);
            // console.log(allRowsObj); // test
            let singleEntry = {};

            for (let i = 1; i < input_data.length; i++) {
                singleEntry.id = i;
                singleEntry.料號 = input_data[i][0].toString().trim();
                if (input_data[i][1] === "" || input_data[i][1] === null || input_data[i][1] === undefined) {
                    singleEntry.品名 = allRowsObj.data.find(object => {
                        return (object.料號 === singleEntry.料號);
                    }).品名;
                } // if
                else {
                    singleEntry.品名 = input_data[i][1].toString().trim();
                } // else

                if (input_data[i][2] === "" || input_data[i][2] === null || input_data[i][2] === undefined) {
                    singleEntry.規格 = allRowsObj.data.find(object => {
                        return (object.料號 === singleEntry.料號);
                    }).規格;
                } // if
                else {
                    singleEntry.規格 = input_data[i][2].toString().trim();
                } // else

                if (input_data[i][3] === "" || input_data[i][3] === null || input_data[i][3] === undefined) {
                    singleEntry.發料部門 = allRowsObj.data.find(object => {
                        return (object.料號 === singleEntry.料號);
                    }).發料部門;
                } // if
                else {
                    singleEntry.發料部門 = input_data[i][3].toString().trim();
                } // else
                singleEntry.預領數量 = parseInt(input_data[i][4]);
                if (input_data[i][5] === "" || input_data[i][5] === null || input_data[i][5] === undefined) {
                    singleEntry.單位 = allRowsObj.data.find(object => {
                        return (object.料號 === singleEntry.料號);
                    }).單位;
                } // if
                else {
                    singleEntry.單位 = input_data[i][5].toString().trim();
                } // else

                if (input_data[i][6] === "" || input_data[i][6] === null || input_data[i][6] === undefined) {
                    singleEntry.線別 = "";
                } // if
                else {
                    singleEntry.線別 = input_data[i][6].toString().trim();
                } // else

                if (input_data[i][7] === "" || input_data[i][7] === null || input_data[i][7] === undefined) {
                    singleEntry.領用原因 = "";
                } // if
                else {
                    singleEntry.領用原因 = input_data[i][7].toString().trim();
                } // else

                if (input_data[i][8] === "" || input_data[i][8] === null || input_data[i][8] === undefined) {
                    singleEntry.備註 = "";
                } // if
                else {
                    singleEntry.備註 = input_data[i][8].toString().trim();
                } // else

                // check if already exist in the table
                let isExist = data.findIndex(object => {
                    return (object.料號 === singleEntry.料號 && object.線別 === singleEntry.線別 && object.領用原因 === singleEntry.領用原因);
                });

                if (isExist != -1) {
                    isDuplicated.value = true;
                    continue;
                } // if

                let indexOfObject = allRowsObj.data.findIndex(object => {
                    return (object.料號 === singleEntry.料號);
                });

                let indexOfObject2 = allLines.datas.findIndex(object => {
                    return (object.線別 === singleEntry.線別);
                });

                let indexOfObject3 = allReasons.datas.findIndex(object => {
                    return (object.領用原因 === singleEntry.領用原因);
                });

                // get the sum of the stock for the same pn
                const sumStock = allRowsObj.data.reduce((acc, cur) => {
                    const found = acc.find(val => val.料號 === cur.料號)
                    if (found) {
                        found.現有庫存 += Number(cur.現有庫存)
                    } // if
                    else {
                        acc.push({ 料號: cur.料號, 現有庫存: Number(cur.現有庫存) })
                    } // else
                    return acc
                }, []);
                // console.log(sumStock); // test

                // Check if 預領數量 exceeds the stock
                let stock = sumStock.find(object => {
                    return (object.料號 === singleEntry.料號);
                });

                // console.log(stock, singleEntry.預領數量); // test
                if (parseInt(stock.現有庫存) < parseInt(singleEntry.預領數量)) {
                    singleEntry.not_enough_stock = true;
                } // if
                else {
                    singleEntry.not_enough_stock = false;
                } // else

                if (indexOfObject != -1) { // if isn exist in consumptive_material table
                    singleEntry.valid_isn = true;
                } // if
                else {
                    singleEntry.valid_isn = false;
                } // else

                if (indexOfObject2 != -1) {
                    singleEntry.valid_line = true;
                } // if
                else {
                    singleEntry.valid_line = false;
                } // else

                if (indexOfObject3 != -1) {
                    singleEntry.valid_reason = true;
                } // if
                else {
                    singleEntry.valid_reason = false;
                } // else

                if (isNaN(singleEntry.預領數量) || singleEntry.valid_isn === false || singleEntry.valid_line === false || singleEntry.valid_reason === false || singleEntry.not_enough_stock === true) {
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
                        "outboundpageLang.isn"
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
                        "outboundpageLang.pName"
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
                        "outboundpageLang.format"
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
                        "outboundpageLang.senddep"
                    ),
                    field: "發料部門",
                    width: "10ch",
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
                                row.發料部門 +
                                "</div>"
                            );
                        } // else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.pickamount"
                    ),
                    field: "預領數量",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        if (!Number.isInteger(row.預領數量)) {
                            if (row.單位 === "N/A") {
                                return (
                                    '<div class="text-nowrap CustomScrollbar"' +
                                    ' style="overflow-x: auto; width: 100%;">' +
                                    '<i class="bi bi-x-lg text-danger fw-bold"></i>' + '&nbsp;<small class="text-danger fw-bold">' + row.單位 + '</small>' +
                                    "</div>"
                                );
                            } // if
                            else {
                                return (
                                    '<div class="text-nowrap CustomScrollbar"' +
                                    ' style="overflow-x: auto; width: 100%;">' +
                                    '<i class="bi bi-x-lg text-danger fw-bold"></i>' + '&nbsp;<small>' + row.單位 + '</small>' +
                                    "</div>"
                                );
                            } // else
                        } // if
                        else if (row.not_enough_stock === true) {
                            return (
                                '<div class="text-nowrap CustomScrollbar text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                app.appContext.config.globalProperties.$t("outboundpageLang.stockless") +
                                "</div>"
                            );
                        } // else if
                        else {
                            return (
                                '<div class="text-nowrap CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.預領數量 + '&nbsp;<small>' + row.單位 + '</small>' +
                                "</div>"
                            );
                        } // else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.line"
                    ),
                    field: "線別",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.valid_line === false) { // if location not exist in location table
                            return (
                                '<div class="text-nowrap CustomScrollbar text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                app.appContext.config.globalProperties.$t("outboundpageLang.no_line") +
                                "</div>"
                            );
                        } // if
                        else {
                            return (
                                '<div class="text-nowrap CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.線別 +
                                "</div>"
                            );
                        } // else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.usereason"
                    ),
                    field: "領用原因",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.valid_reason === false) { // if location not exist in location table
                            return (
                                '<div class="text-nowrap CustomScrollbar text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                app.appContext.config.globalProperties.$t("outboundpageLang.no_reason") +
                                "</div>"
                            );
                        } // if
                        else {
                            return (
                                '<div class="text-nowrap CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.領用原因 +
                                "</div>"
                            );
                        } // else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.mark"
                    ),
                    field: "備註",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.備註 +
                            "</div>"
                        );
                    },
                },
            ],
            rows: computed(() => {
                return data.filter((x) =>
                    x.料號
                        .toLowerCase()
                        .includes(searchTerm.value.toLowerCase()) ||
                    x.品名
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
            isDuplicated,
            validation_err_msg,
            validation_err_msg_duplicated,
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