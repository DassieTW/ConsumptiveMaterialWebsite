<template>
    <div class="card w-100" id="consumehead">
        <div class="card-header">
            <h3>{{ $t('monthlyPRpageLang.isnConsumeAdd') }}</h3>
        </div>
        <div class="card-body">
            <div id="consume" class="row justify-content-center align-items-center">
                <div class="col-auto">
                    <label class="col col-auto form-label">{{ $t('monthlyPRpageLang.isn') }}</label>
                    <input class="form-control form-control-lg" :class="{ 'is-invalid': is_isn_input_Invalid }" type="text"
                        v-model="isn_input" id="isn_input" name="isn_input" :placeholder="$t('monthlyPRpageLang.enterisn')">
                    <span v-if="is_isn_input_Invalid" class="invalid-feedback d-block" role="alert">
                        <strong>{{ $t('monthlyPRpageLang.noisn') }}</strong>
                    </span>
                </div>
                <div class="col-auto">
                    <label class="col col-auto form-label">{{ $t('monthlyPRpageLang.90isn') }}</label>
                    <input class="form-control form-control-lg" :class="{ 'is-invalid': is_90isn_input_Invalid }"
                        v-model="isn90_input" type="text" id="90isn_input" name="90isn_input"
                        :placeholder="$t('monthlyPRpageLang.enter90isn')">
                    <span v-if="is_90isn_input_Invalid" class="invalid-feedback d-block" role="alert">
                        <strong>{{ $t('monthlyPRpageLang.noisn') }}</strong>
                    </span>
                </div>
            </div>
            <div class="w-100" style="height: 2ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="row justify-content-center">
                <button type="submit" id="add" name="add" class="col col-auto btn btn-primary" @click="addManually">
                    {{ $t('monthlyPRpageLang.add') }}
                </button>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <button class="col col-auto btn btn-lg btn-warning" @click="addRejectedToTable">
                    {{ $t('monthlyPRpageLang.loadconsume') }}
                </button>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>{{ $t("monthlyPRpageLang.upload") }}</h3>
        </div>

        <div class="row justify-content-center">
            <div class="card-body">
                <div class=" w-100">
                    <div class="row w-100 justify-content-center mb-3">
                        <div class="col col-auto ">
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
                        <div class="row w-100 justify-content-center">
                            <button type="submit" name="upload" class="col col-auto btn btn-lg btn-primary"
                                @click="onUploadClick">
                                {{ $t('monthlyPRpageLang.upload1') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <!-- <div class="card-header">
            <h3>{{ $t('monthlyPRpageLang.stockupload') }}</h3>
        </div> -->
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
                    <input type="submit" id="delete" name="delete" class="col col-auto btn btn-lg btn-danger"
                        :value="$t('basicInfoLang.delete')" @click="deleteRow">
                </div>
            </div>
            <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
            <span v-if="isInvalid_DB" class="invalid-feedback d-block" role="alert">
                <strong>{{ validation_err_msg }}</strong>
            </span>
            <table-lite :is-fixed-first-column="true" :is-static-mode="true" :hasCheckbox="true"
                :isLoading="table.isLoading" :messages="table.messages" :columns="table.columns" :rows="table.rows"
                :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
                @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows"
                @row-input="rowUserInput"></table-lite>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <div class="row w-100 justify-content-between">
                <label class="form-label col col-auto">{{ $t('monthlyPRpageLang.surepeopleemail') }}:</label>
                <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="row col col-auto">
                    <div class="input-group">
                        <select class="form-select form-select-lg col col-auto" v-model="selected_mail">
                            <option style="display: none;" disabled selected value="">
                                {{ $t("monthlyPRpageLang.noemail") }}
                            </option>
                            <option v-for="mail in all_mails" :value="mail.email">{{ mail.姓名 }}</option>
                        </select>
                        <span class="input-group-text input-group-text-lg" id="emailTail">{{ selected_mail }}</span>
                    </div>
                </div>
                <button v-if="uploadToDBReady" type="submit" name="upload" class="col col-auto btn btn-lg btn-primary"
                    @click="onSendToDBClick">
                    {{ $t('monthlyPRpageLang.submit') }}
                </button>
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
import useUnitConsumptionSearch from "../../composables/UnitConsumptionSearch.ts";
import useCommonlyUsedFunctions from "../../composables/CommonlyUsedFunctions.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        let exampleUrl = ref(window.location.origin + '/download/ConsumeExample.xlsx');
        const app = getCurrentInstance(); // get the current instance
        let thisHtmlLang = document
            .getElementsByTagName("HTML")[0]
            .getAttribute("lang");
        // get the current locale from html tag
        app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

        const { mats, mails, getRejected, getCheckersMails, uploadToDB } = useUnitConsumptionSearch();
        const { queryResult, manualResult, validateISN, validateISN_manual } = useCommonlyUsedFunctions();

        onBeforeMount(getCheckersMails);

        let is_90isn_input_Invalid = ref(false); // 90 isn input validation
        let is_isn_input_Invalid = ref(false); // isn input validation
        let isn90_input = ref("");
        let isn_input = ref("");
        let isInvalid = ref(false); // file input validation
        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref("");
        let uploadToDBReady = ref(false); // validation
        const all_mails = reactive([]);
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

        const addManually = async () => {
            is_isn_input_Invalid.value = false;
            is_90isn_input_Invalid.value = false;
            let allRowsObj;

            if (isn_input.value == undefined || isn_input.value == null || isn_input.value == "") {
                is_isn_input_Invalid.value = true;
                return;
            } // if
            else {
                await validateISN_manual([isn_input.value.trim()]);
                // console.log(allRowsObj); // test
                allRowsObj = JSON.parse(manualResult.value);
                if (allRowsObj.data[0].月請購 === "" || allRowsObj.data[0].月請購 === null || allRowsObj.data[0].月請購.toLowerCase() === "null") {
                    is_isn_input_Invalid.value = true;
                    return;
                } // if
            } // else

            if (isn90_input.value == undefined || isn90_input.value == null || isn90_input.value == "") {
                is_90isn_input_Invalid.value = true;
                return;
            } // if
            else {
                allRowsObj.data[0].料號90 = isn90_input.value.trim();
                if (data.length == 0) {
                    allRowsObj.data[0].id = 0;
                } else {
                    allRowsObj.data[0].id = parseInt(data[data.length - 1].id) + 1;
                } // if else
                allRowsObj.data[0].doubleCheck = false;
                allRowsObj.data[0].單耗 = 0;

                // remove duplicate data from other input
                let indexOfObject = data.findIndex(object => {
                    return (object.料號90 === allRowsObj.data[0].料號90 && object.料號 === allRowsObj.data[0].料號);
                });

                if (indexOfObject != -1) { // if an existing record is found in table
                    data.splice(indexOfObject, 1);
                } // if

                data.push(allRowsObj.data[0]);
            } // else
        } // addManually

        const addRejectedToTable = async () => {
            await getRejected();
            if (JSON.parse(mats.value).datas.length < 1) {
                notyf.open({
                    type: "warning",
                    message: app.appContext.config.globalProperties.$t("monthlyPRpageLang.noload"),
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
                // console.log(mats.value); // test

                // remove all the old rejected data in table
                for (let i = 0; i < data.length; i++) {
                    if (data[i].doubleCheck) {
                        data.splice(i, 1);
                    } // if
                } // for

                let allRowsObj = JSON.parse(mats.value);
                for (let i = 0; i < allRowsObj.datas.length; i++) {
                    if (data.length == 0) {
                        allRowsObj.datas[i].id = 0;
                    } else {
                        allRowsObj.datas[i].id = parseInt(data[data.length - 1].id) + 1;
                    } // if else
                    allRowsObj.datas[i].doubleCheck = true;

                    // remove duplicate data from other input
                    let indexOfObject = data.findIndex(object => {
                        return (object.料號90 === allRowsObj.datas[i].料號90 && object.料號 === allRowsObj.datas[i].料號);
                    });

                    if (indexOfObject != -1) { // if an existing record is found in table
                        data.splice(indexOfObject, 1);
                    } // if

                    data.push(allRowsObj.datas[i]);
                } // for

                notyf.open({
                    type: "success",
                    message: app.appContext.config.globalProperties.$t("monthlyPRpageLang.total") + " " + allRowsObj.datas.length + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.record") + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.loadsuccess"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });
            } // else
        } // addRejectedToTable

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
                        } else if (input_data[0][0].trim() !== "90料號" || input_data[0][1].trim() !== "料號" || input_data[0][2].trim() !== "單耗") {
                            isInvalid.value = true;
                            validation_err_msg.value = app.appContext.config.globalProperties.$t("fileUploadErrors.Content_errors");
                        } else {
                            let tempArr = Array();
                            
                            for (let i = 1; i < input_data.length; i++) {
                                if (input_data[i][1] != undefined && input_data[i].length > 2 && input_data[i][0].trim() != "" && input_data[i][0].trim() != null) {
                                    tempArr.push(input_data[i][1].trim());
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
            // data.splice(0); // cleanup data from previous upload
            queryResult.value = "";
            file.value = event.target.files ? event.target.files[0] : null;
        } // onInputChange


        const triggerModal = async () => {
            console.log("Loading Modal Triggered!"); // test
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
                let deleteID = document.getElementsByClassName("vtl-tbody-tr")[checkedRows[i]].children[3].firstChild.firstChild.getAttribute("id");
                deleteID = deleteID.replace('unitConsumption', '');

                let indexOfObject = data.findIndex(object => {
                    return parseInt(object.id) === parseInt(deleteID);
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
                if (data[j].月請購 === "" || data[j].月請購 === null || data[j].月請購.toLowerCase() === "null") {
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
            for (let i = 1; i < data.length; i++) {
                if (data[i].料號 != null && data[i].料號.trim() != "") {
                    duplicatedArray.push(data[i].料號.trim() + "_" + data[i].料號90.trim());
                } // if
            } // for

            let findDuplicatesResult = findDuplicates(duplicatedArray);
            // console.log(findDuplicatesResult); // test
            if (findDuplicatesResult.length > 0) {
                isInvalid_DB.value = true;
                validation_err_msg.value =
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.repeated_isn_90_pair") +
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
            let pn90Array = [];
            let ucArray = [];
            for (let j = 0; j < data.length; j++) {
                pnArray.push(data[j].料號);
                pn90Array.push(data[j].料號90);
                ucArray.push(data[j].單耗);
            } // for
            // console.log(ucArray); //test
            // actually updating database now
            let start = Date.now();
            let result = await uploadToDB(pnArray, pn90Array, ucArray, selected_mail.value);
            let timeTaken = Date.now() - start;
            console.log("Total time taken : " + timeTaken + " milliseconds");
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");

            if (result === "success") {
                uploadToDBReady.value = false;
                notyf.open({
                    type: "success",
                    message: app.appContext.config.globalProperties.$t("monthlyPRpageLang.total") + " " + JSON.parse(mats.value).record + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.record") + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.change") + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.success"),
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
            // console.log(queryResult.value); // test
            await triggerModal();
            if (queryResult.value == "") {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            let allRowsObj = JSON.parse(queryResult.value);
            console.log(allRowsObj); // test
            for (let i = 0; i < allRowsObj.data.length; i++) {
                allRowsObj.data[i].料號90 = input_data[i + 1][0];
                allRowsObj.data[i].單耗 = input_data[i + 1][2];
                if (data.length == 0) {
                    allRowsObj.data[i].id = 0;
                } else {
                    allRowsObj.data[i].id = parseInt(data[data.length - 1].id) + 1;
                } // if else
                allRowsObj.data[i].doubleCheck = false;

                data.push(allRowsObj.data[i]);
            } // for

            // console.log(data); // test
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        }); // watch for data change

        watch(mails, async () => {
            let allRowsObj = JSON.parse(mails.value);
            // console.log(allRowsObj.data.length);
            for (let i = 0; i < allRowsObj.data.length; i++) {
                all_mails.push(allRowsObj.data[i]);
            } // for
        });

        watch(selected_mail, () => {
            if (selected_mail.value != undefined && selected_mail.value != "" && selected_mail.value != null) {
                uploadToDBReady.value = true;
            } // if
        });

        function initialScientificNotaionToFixed(x) {
            // toFixed
            if (Math.abs(x) < 1.0) {
                var e = parseInt(x.toString().split("e-")[1]);
                if (e) {
                    x *= Math.pow(10, e - 1);
                    x = "0." + new Array(e).join("0") + x.toString().substring(2);
                } // if
            } else {
                var e = parseInt(x.toString().split("+")[1]);
                if (e > 20) {
                    e -= 20;
                    x /= Math.pow(10, e);
                    x += new Array(e + 1).join("0");
                } // if
            } // if-else

            return x;
        } // to prevent scientific notaion

        // Table config
        const table = reactive({
            isLoading: false,
            columns: [
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.isn"
                    ),
                    field: "料號",
                    width: "15ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.月請購 === "" || row.月請購 === null || row.月請購.toLowerCase() === "null") { // if isn not exist in consumptive_material table
                            return (
                                '<input type="hidden" id="number' +
                                i +
                                '" name="number' +
                                i +
                                '" value="' +
                                row.料號 +
                                '">' +
                                '<div class="text-nowrap text-danger scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.料號 +
                                "</div>"
                            );
                        } else if (row.doubleCheck) {
                            return (
                                '<input type="hidden" id="number' +
                                i +
                                '" name="number' +
                                i +
                                '" value="' +
                                row.料號 +
                                '">' +
                                '<div class="text-nowrap scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto; width: 100%; color: #e67300;">' +
                                row.料號 +
                                "</div>"
                            );
                        } else { // isn exist in database
                            return (
                                '<input type="hidden" id="number' +
                                i +
                                '" name="number' +
                                i +
                                '" value="' +
                                row.料號 +
                                '">' +
                                '<div class="text-nowrap scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.料號 +
                                "</div>"
                            );
                        } // if else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.90isn"
                    ),
                    field: "90料號",
                    width: "15ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.月請購 === "" || row.月請購 === null || row.月請購.toLowerCase() === "null") { // if isn not exist in consumptive_material table
                            return (
                                '<input type="hidden" id="number90' +
                                i +
                                '" name="number90' +
                                i +
                                '" value="' +
                                row.料號90 +
                                '">' +
                                '<div class="text-nowrap text-danger scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.料號90 +
                                "</div>"
                            );
                        } else if (row.doubleCheck) {
                            return (
                                '<input type="hidden" id="number90' +
                                i +
                                '" name="number90' +
                                i +
                                '" value="' +
                                row.料號90 +
                                '">' +
                                '<div class="text-nowrap scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto; width: 100%; color: #e67300;">' +
                                row.料號90 +
                                "</div>"
                            );
                        } else { // isn exist in database
                            return (
                                '<input type="hidden" id="number90' +
                                i +
                                '" name="number90' +
                                i +
                                '" value="' +
                                row.料號90 +
                                '">' +
                                '<div class="text-nowrap scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.料號90 +
                                "</div>"
                            );
                        } // if else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.consume"
                    ),
                    field: "單耗",
                    width: "13ch",
                    sortable: true,
                    hasInput: function (row, i) {
                        return (
                            '<input style="width:13ch;" type="number" ' +
                            'oninput="ScientificNotaionToFixed(this)" ' +
                            'id="unitConsumption' +
                            row.id +
                            '"' +
                            ' name="unitConsumption' +
                            row.id +
                            '" value="' +
                            initialScientificNotaionToFixed(row.單耗) +
                            '"' +
                            ' class="form-control text-center p-0 m-0" step="0.000001">'
                        );
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
                        if (row.月請購 === "" || row.月請購 === null || row.月請購.toLowerCase() === "null") { // if isn not exist in consumptive_material table
                            return (
                                '<input type="hidden" id="name' +
                                i +
                                '" name="name' +
                                i +
                                '" value="' +
                                row.品名 +
                                '">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                app.appContext.config.globalProperties.$t("monthlyPRpageLang.noisn") +
                                "</div>"
                            );
                        } // if
                        else {
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
                        if (row.月請購 === "" || row.月請購 === null || row.月請購.toLowerCase() === "null") { // if isn not exist in consumptive_material table
                            return (
                                '<input type="hidden" id="format' +
                                i +
                                '" name="format' +
                                i +
                                '" value="' +
                                row.規格 +
                                '">' +
                                '<div class="text-nowrap scrollableWithoutScrollbar text-danger"' +
                                ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                                app.appContext.config.globalProperties.$t("monthlyPRpageLang.noisn") +
                                "</div>"
                            );
                        } // if
                        else {
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
                        } // else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.unit"
                    ),
                    field: "單位",
                    width: "8ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.月請購 === "" || row.月請購 === null || row.月請購.toLowerCase() === "null") { // if isn not exist in consumptive_material table
                            return (
                                '<input type="hidden" id="unit' +
                                i +
                                '" name="unit' +
                                i +
                                '" value="' +
                                row.單位 +
                                '">' +
                                '<div class="text-nowrap text-danger scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                                "?" +
                                "</div>"
                            );
                        } // if
                        else {
                            return (
                                '<input type="hidden" id="unit' +
                                i +
                                '" name="unit' +
                                i +
                                '" value="' +
                                row.單位 +
                                '">' +
                                '<div class="text-nowrap scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                                row.單位 +
                                "</div>"
                            );
                        } // else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.lt"
                    ),
                    field: "LT",
                    width: "8ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="lt' +
                            i +
                            '" name="lt' +
                            i +
                            '" value="' +
                            Math.round(row.LT) +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            Math.round(row.LT) +
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

        const rowUserInput = (row, rowNum) => {
            // console.log(document.getElementById("unitConsumption" + rowNum).value);
            data[row.id].單耗 = document.getElementById("unitConsumption" + row.id).value;
            // console.log(data); // test
        };

        return {
            exampleUrl,
            isn90_input,
            isn_input,
            is_90isn_input_Invalid,
            is_isn_input_Invalid,
            isInvalid,
            isInvalid_DB,
            validation_err_msg,
            uploadToDBReady,
            searchTerm,
            table,
            all_mails,
            selected_mail,
            updateCheckedRows,
            rowUserInput,
            addManually,
            addRejectedToTable,
            onUploadClick,
            onInputChange,
            onSendToDBClick,
            deleteRow,
        };
    }, // setup
});
</script>
