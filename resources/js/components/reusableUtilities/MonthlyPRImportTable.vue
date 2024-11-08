<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <h3 class="col col-auto m-0">{{ $t("monthlyPRpageLang.importMonthlyData") }}</h3>
                <button class="col col-auto btn btn-light m-0 p-0 flip-btn" @click="(flip = !flip)">
                    <i class="bi bi-arrow-left-right"> </i>
                    {{ $t("monthlyPRpageLang.search") }}
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row justify-content-center mb-3">
                <div class="col col-auto">
                    <a :href="exampleUrl" download>
                        {{ $t('monthlyPRpageLang.exampleExcel') }}
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
                        <label for="pnInput1" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }}
                            :</label>
                    </div>
                    <div class="col col-auto p-0 m-0">
                        <input id="pnInput1" class="text-center form-control form-control-lg"
                            v-bind:placeholder="$t('monthlyPRpageLang.enter90isn')" v-model="searchTerm" />
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
                <strong>{{ validation_err_msg }}</strong>
            </span>
            <table-lite :is-fixed-first-column="true" :is-static-mode="true" :hasCheckbox="true"
                :isLoading="table.isLoading" :messages="table.messages" :columns="table.columns" :rows="table.rows"
                :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
                @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows">
            </table-lite>

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
import TableLite from "./TableLite.vue";
import useMonthlyPRSearch from "../../composables/MonthlyPRSearch.ts";
import useCommonlyUsedFunctions from "../../composables/CommonlyUsedFunctions.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        let exampleUrl = ref(window.location.origin + '/download/ImportMonthExample.xlsx');
        const app = getCurrentInstance(); // get the current instance
        let thisHtmlLang = document
            .getElementsByTagName("HTML")[0]
            .getAttribute("lang");
        // get the current locale from html tag
        app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

        const { mats, uc, uploadMonthlyToDB, validate_UnitConsume } = useMonthlyPRSearch();
        const { queryResult, validateISN } = useCommonlyUsedFunctions();

        let isInvalid = ref(false); // validation
        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref("");
        let uploadToDBReady = ref(false); // validation
        const file = ref();
        let input_data;
        let checkedRows = [];

        const deleteRow = () => {
            // console.log(checkedRows); // test
            for (let i = 0; i < checkedRows.length; i++) {
                let indexOfObject = data.findIndex(object => {
                    return parseInt(object.excel_row_num) === parseInt(checkedRows[i].excel_row_num);
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
            // console.log(data); // test
        } // deleteRow

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

        const onUploadClick = async () => {
            isInvalid_DB.value = false;
            mats.value = "";
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
                        if (input_data === undefined || input_data[0] === undefined || input_data[0][0] === undefined || input_data[0][1] === undefined || input_data[0][2] === undefined) {
                            isInvalid.value = true;
                            validation_err_msg.value = app.appContext.config.globalProperties.$t("fileUploadErrors.Content_errors");
                        } else if (input_data[0][0].toString().trim() !== "90料號" || input_data[0][1].toString().trim() !== "料號" || !input_data[0][2].toString().trim().toLowerCase().includes("mps")) {
                            isInvalid.value = true;
                            validation_err_msg.value = app.appContext.config.globalProperties.$t("fileUploadErrors.Content_errors");
                        } else {
                            let tempArr = Array();
                            let tempArr90 = Array();
                            for (let i = 1; i < input_data.length; i++) {
                                if (input_data[i][0] != undefined && input_data[i].length > 2 && input_data[i][0].toString().trim() != "" && input_data[i][0].toString().trim() != null) {
                                    tempArr.push(input_data[i][1].toString().trim());
                                    tempArr90.push(input_data[i][0].toString().trim());
                                } // if
                                else {
                                    input_data.splice(i, 1); // remove the empty row
                                    i = i - 1;
                                } // else
                            } // for

                            // console.log(tempArr90); // test
                            await triggerModal();
                            await validateISN(tempArr);
                            await validate_UnitConsume(tempArr, tempArr90);
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

        const onSendToDBClick = async () => {
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
                if (data[j].月請購 === "" || data[j].月請購 === null || data[j].月請購.toLowerCase() === "null") {
                    hasError = true;
                    rowsCount = j;
                } // if
            } // for

            if (hasError) {
                isInvalid_DB.value = true;
                validation_err_msg.value =
                    "Excel " +
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.row") +
                    " " + data[rowsCount].excel_row_num + " " +
                    "(" + data[rowsCount].料號 + ") " +
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
            // validate if all the 單耗 exist
            for (let j = 0; j < data.length && hasError == false; j++) {
                if (data[j].單耗 === "" || data[j].單耗 === null || data[j].單耗.toLowerCase() === "null" ||
                    data[j].狀態 !== "已完成") {
                    hasError = true;
                    rowsCount = j;
                } // if
            } // for

            if (hasError) {
                isInvalid_DB.value = true;
                validation_err_msg.value =
                    "Excel " +
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.row") +
                    " " + data[rowsCount].excel_row_num + ": " + data[rowsCount].料號90 +
                    "(" + data[rowsCount].料號 + ") " +
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.nounitconsumption");

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
            // validate if there's duplicate values in excel
            let duplicatedArray = Array();
            for (let i = 0; i < data.length; i++) {
                // console.log(data[i].料號.trim() + "_" + data[i].料號90.toString().trim()); // test
                if (data[i].料號 != null && data[i].料號.trim() != "") {
                    duplicatedArray.push(data[i].料號.toString().trim() + "_" + data[i].料號90.toString().trim());
                } // if
            } // for

            let findDuplicatesResult = findDuplicates(duplicatedArray);
            // console.log(findDuplicatesResult); // test
            if (findDuplicatesResult.length > 0) {
                isInvalid_DB.value = true;
                validation_err_msg.value =
                    app.appContext.config.globalProperties.$t("inboundpageLang.repeated_isn_90_pair") +
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
            // actually updating database now
            let number = [];
            let number90 = [];
            let nextmps = [];
            let nextday = [];
            let nowmps = [];
            let nowday = [];
            for (let i = 0; i < data.length; i++) {
                number.push(data[i].料號);
                number90.push(data[i].料號90);
                nextmps.push(data[i].下月MPS);
                nextday.push(data[i].下月生產天數);
                nowmps.push(data[i].本月MPS);
                nowday.push(data[i].本月生產天數);
            } // for

            // console.log(number); // test
            let start = Date.now();
            let result = await uploadMonthlyToDB(number, number90, nextmps, nextday, nowmps, nowday);
            let timeTaken = Date.now() - start;
            console.log("Total time taken : " + timeTaken + " milliseconds");
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");

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
                if (result.response.status == 420) {
                    isInvalid_DB.value = true;
                    let PR_ALREADY = JSON.parse(result.response.data.PR_ALREADY);
                    // console.log(PR_ALREADY); // test
                    let row_sep = 1; // just here to make the output prettier
                    validation_err_msg.value = app.appContext.config.globalProperties.$t("monthlyPRpageLang.nonmonthly_pr_already_sxb") + "\n";
                    for (let i = 0; i < PR_ALREADY.length; i++) {
                        if (row_sep === 2) {
                            validation_err_msg.value = validation_err_msg.value + PR_ALREADY[i].料號90 + '(' + PR_ALREADY[i].料號 + ')' + "、\n";
                            row_sep = 0;
                        } else {
                            validation_err_msg.value = validation_err_msg.value + PR_ALREADY[i].料號90 + '(' + PR_ALREADY[i].料號 + ')' + "、";
                        } // if else

                        row_sep++;
                    } // for
                    validation_err_msg.value = validation_err_msg.value.slice(0, -1); // Remove the last character "、"
                } // if

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

        watch(uc, async () => {
            await triggerModal();
            if (queryResult.value == "") {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            let allRowsObj = JSON.parse(queryResult.value);
            let allRowsObj_UC = JSON.parse(uc.value);

            // console.log(allRowsObj_UC); // test
            let singleEntry = {};

            for (let i = 1; i < input_data.length; i++) {
                singleEntry.料號90 = input_data[i][0].toString().trim();
                singleEntry.料號 = input_data[i][1].toString().trim();
                if (input_data[i][2] == undefined || input_data[i][2].toString().trim() == "") {
                    isInvalid.value = true;
                    validation_err_msg.value =
                        singleEntry.料號90 + "->" + singleEntry.料號 + " " +
                        app.appContext.config.globalProperties.$t("monthlyPRpageLang.nowmps") + " " +
                        app.appContext.config.globalProperties.$t("validation.required");

                    $("body").loadingModal("hide");
                    $("body").loadingModal("destroy");
                    return;
                } // if
                singleEntry.本月MPS = parseFloat(input_data[i][2].toString().replace(/,/g, ''));
                // -------------------------------------------------------------------------------
                if (input_data[i][3] == undefined || input_data[i][3].toString().trim() == "") {
                    isInvalid.value = true;
                    validation_err_msg.value =
                        singleEntry.料號90 + "->" + singleEntry.料號 + " " +
                        app.appContext.config.globalProperties.$t("monthlyPRpageLang.nowday") + " " +
                        app.appContext.config.globalProperties.$t("validation.required");

                    $("body").loadingModal("hide");
                    $("body").loadingModal("destroy");
                    return;
                } // if
                singleEntry.本月生產天數 = parseFloat(input_data[i][3].toString().replace(/,/g, ''));
                // -------------------------------------------------------------------------------
                if (input_data[i][4] == undefined || input_data[i][4].toString().trim() == "") {
                    isInvalid.value = true;
                    validation_err_msg.value =
                        singleEntry.料號90 + "->" + singleEntry.料號 + " " +
                        app.appContext.config.globalProperties.$t("monthlyPRpageLang.nextmps") + " " +
                        app.appContext.config.globalProperties.$t("validation.required");

                    $("body").loadingModal("hide");
                    $("body").loadingModal("destroy");
                    return;
                } // if
                singleEntry.下月MPS = parseFloat(input_data[i][4].toString().replace(/,/g, ''));
                // -------------------------------------------------------------------------------
                if (input_data[i][5] == undefined || input_data[i][5].toString().trim() == "") {
                    isInvalid.value = true;
                    validation_err_msg.value =
                        singleEntry.料號90 + "->" + singleEntry.料號 + " " +
                        app.appContext.config.globalProperties.$t("monthlyPRpageLang.nextday") + " " +
                        app.appContext.config.globalProperties.$t("validation.required");

                    $("body").loadingModal("hide");
                    $("body").loadingModal("destroy");
                    return;
                } // if
                singleEntry.下月生產天數 = parseFloat(input_data[i][5].toString().replace(/,/g, ''));
                // -------------------------------------------------------------------------------
                singleEntry.excel_row_num = i + 1;

                if (data.length == 0) {
                    singleEntry.id = 0;
                } else {
                    singleEntry.id = parseInt(data[data.length - 1].id) + 1;
                } // if else

                let indexOfObject = allRowsObj.data.findIndex(object => {
                    return (object.料號 === singleEntry.料號);
                });

                if (indexOfObject != -1) { // if an existing record is found
                    singleEntry = Object.assign(singleEntry, allRowsObj.data[indexOfObject]);
                    // console.log(singleEntry); // test
                } // if
                else {
                    singleEntry.月請購 = "";
                } // else

                // check if Unit Consumption Exist and Approved
                indexOfObject = allRowsObj_UC.data.findIndex(object => {
                    return (object.料號 === singleEntry.料號 && object.料號90 === singleEntry.料號90);
                });

                if (indexOfObject != -1) { // if an existing record is found
                    singleEntry.單耗 = allRowsObj_UC.data[indexOfObject].單耗;
                    singleEntry.狀態 = allRowsObj_UC.data[indexOfObject].狀態;
                } // if
                else {
                    singleEntry.單耗 = null;
                    singleEntry.狀態 = null;
                } // else

                data.push(singleEntry);
                singleEntry = {};
            } // for

            if (!$("#importedtable").hasClass("show")) {
                $("#togglebtn").click();
            } // if

            console.log(data); // test
            uploadToDBReady.value = true;
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
                        "monthlyPRpageLang.90isn"
                    ),
                    field: "料號90",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.單耗 === null) { // if isn not exist in consumptive_material table
                            return (
                                '<input type="hidden" id="ninetyisn' +
                                row.excel_row_num +
                                '" name="ninetyisn' +
                                i +
                                '" value="' +
                                row.料號90 +
                                '">' +
                                '<div class="CustomScrollbar text-nowrap text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.料號90 +
                                "</div>"
                            );
                        } // if
                        else {
                            return (
                                '<input type="hidden" id="ninetyisn' +
                                row.excel_row_num +
                                '" name="ninetyisn' +
                                i +
                                '" value="' +
                                row.料號90 +
                                '">' +
                                '<div class="CustomScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.料號90 +
                                "</div>"
                            );
                        } // else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.isn"
                    ),
                    field: "料號",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.月請購 === "" || row.月請購 === null || row.月請購.toLowerCase() === "null" || row.單耗 === null) { // if isn not exist in consumptive_material table
                            return (
                                '<input type="hidden" id="isn' +
                                row.excel_row_num +
                                '" name="isn' +
                                i +
                                '" value="' +
                                row.料號 +
                                '">' +
                                '<div class="CustomScrollbar text-nowrap text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.料號 +
                                "</div>"
                            );
                        } // if
                        else {
                            return (
                                '<input type="hidden" id="isn' +
                                row.excel_row_num +
                                '" name="isn' +
                                i +
                                '" value="' +
                                row.料號 +
                                '">' +
                                '<div class="CustomScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.料號 +
                                "</div>"
                            );
                        } // else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.nowmps"
                    ),
                    field: "本月MPS",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="nowmps' +
                            i +
                            '" name="nowmps' +
                            i +
                            '" value="' +
                            row.本月MPS +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            parseFloat(row.本月MPS).toLocaleString('en', { useGrouping: true }) +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.nowday"
                    ),
                    field: "本月生產天數",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="nowday' +
                            i +
                            '" name="nowday' +
                            i +
                            '" value="' +
                            row.本月生產天數 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            parseFloat(row.本月生產天數).toLocaleString('en', { useGrouping: true }) +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.nextmps"
                    ),
                    field: "下月MPS",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="nextmps' +
                            i +
                            '" name="nextmps' +
                            i +
                            '" value="' +
                            row.下月MPS +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            parseFloat(row.下月MPS).toLocaleString('en', { useGrouping: true }) +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.nextday"
                    ),
                    field: "下月生產天數",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="nextday' +
                            i +
                            '" name="nextday' +
                            i +
                            '" value="' +
                            row.下月生產天數 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            parseFloat(row.下月生產天數).toLocaleString('en', { useGrouping: true }) +
                            "</div>"
                        );
                    },
                }
            ],
            rows: computed(() => {
                return data.filter((x) =>
                    x.料號90
                        .toLowerCase()
                        .includes(searchTerm.value.toLowerCase())
                );
            }),
            totalRecordCount: computed(() => {
                return table.rows.length;
            }),
            // sortable: {
            //     order: "id",
            //     sort: "asc",
            // },
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
            flip: ref(false),
            exampleUrl,
            isInvalid,
            isInvalid_DB,
            validation_err_msg,
            uploadToDBReady,
            searchTerm,
            table,
            updateCheckedRows,
            onUploadClick,
            onInputChange,
            onSendToDBClick,
            deleteRow
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