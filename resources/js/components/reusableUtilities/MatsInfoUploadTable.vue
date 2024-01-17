<template>
    <div class="card">
        <div class="card-header">
            <h3>{{ $t("basicInfoLang.upload") }}</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-center mb-3">
                <div class="col col-auto ">
                    <a :href="exampleUrl" download>
                        {{ $t('inboundpageLang.exampleExcel') }}
                    </a>
                </div>

                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <label class="col col-auto form-label">{{ $t('inboundpageLang.plz_upload') }}</label>
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
                    <button type="submit" name="upload" class="col col-auto btn btn-lg btn-primary" @click="onUploadClick">
                        {{ $t('basicInfoLang.upload1') }}
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
                        <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
                    </div>
                    <div class="col col-6 p-0 m-0">
                        <input id="pnInput" class="text-center form-control form-control-lg"
                            v-bind:placeholder="$t('basicInfoLang.enterisn')" v-model="searchTerm" />
                    </div>
                </div>
            </div>
            <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
            <span v-if="isInvalid_DB" class="invalid-feedback d-block" role="alert">
                <strong>{{ validation_err_msg }}</strong>
            </span>
            <table-lite :is-fixed-first-column="true" :is-static-mode="true" :hasCheckbox="false"
                :isLoading="table.isLoading" :messages="table.messages" :columns="table.columns" :rows="table.rows"
                :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
                @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows">
            </table-lite>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="row justify-content-center">
                <div class="col col-auto">
                    <button v-if="uploadToDBReady" type="submit" name="upload"
                        class="col col-auto fs-3 text-center btn btn-lg btn-info" @click="onSendToDBClick">
                        <i class="bi bi-cloud-upload-fill"></i>
                        {{ $t('inboundpageLang.upload1') }}
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
import useConsumptiveMaterials from "../../composables/ConsumptiveMaterials.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        let exampleUrl = ref(window.location.origin + '/download/MaterialExample.xlsx');
        const app = getCurrentInstance(); // get the current instance
        let thisHtmlLang = document
            .getElementsByTagName("HTML")[0]
            .getAttribute("lang");
        // get the current locale from html tag
        app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

        const { mats, queryResult, uploadToDB, getDispatchers } = useConsumptiveMaterials(); // axios get the mats data

        // onBeforeMount();

        let isInvalid = ref(false); // validation
        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref("");
        let uploadToDBReady = ref(false); // validation
        let dispatchers = [];
        const file = ref();
        let input_data;

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
                        } else if (input_data[0][0].trim() !== "料號" || input_data[0][1].trim() !== "品名" || input_data[0][2].trim() !== "規格") {
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
                            await getDispatchers();
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

            // validate if there's duplicate values in excel
            let duplicatedArray = Array();
            for (let i = 0; i < data.length; i++) {
                // console.log(data[i].料號.trim() + "_" + data[i].儲位.toString().trim()); // test
                if (data[i].料號 != null && data[i].料號.trim() != "") {
                    duplicatedArray.push(data[i].料號.toString().trim());
                } // if
            } // for

            let findDuplicatesResult = findDuplicates(duplicatedArray);
            // console.log(findDuplicatesResult); // test
            if (findDuplicatesResult.length > 0) {
                isInvalid_DB.value = true;
                validation_err_msg.value =
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.repeated_isn") +
                    " : " + findDuplicatesResult[0];

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
            // validate if theres any 'text-danger' in class names

            if (document.getElementsByClassName("text-danger").length > 0) {
                let idStr = document.getElementsByClassName("text-danger")[0].parentNode.childNodes[0].getAttribute("id");
                rowsCount = idStr.replace(/[^0-9]/g, "");
                hasError = true;
                console.log(idStr, rowsCount); // test
            } // if

            if (hasError) {
                isInvalid_DB.value = true;
                validation_err_msg.value =
                    "Excel " +
                    app.appContext.config.globalProperties.$t("barcodeGenerator.temp_save_error") + " " +
                    app.appContext.config.globalProperties.$t("inboundpageLang.row") +
                    " " + rowsCount + " " +
                    "(" + document.getElementById("number" + rowsCount).value + ") ";

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
            let nameArray = [];
            let specArray = [];
            let priceArray = [];
            let currencyArray = [];
            let unitArray = [];
            let mpqArray = [];
            let moqArray = [];
            let ltArray = [];
            let gradeaArray = [];
            let monthlyArray = [];
            let dispatcherArray = [];
            let safestockArray = [];
            for (let j = 0; j < data.length; j++) {
                pnArray.push(data[j].料號);
                nameArray.push(data[j].品名);
                specArray.push(data[j].規格);
                priceArray.push(data[j].單價);
                currencyArray.push(data[j].幣別);
                unitArray.push(data[j].單位);
                mpqArray.push(data[j].MPQ);
                moqArray.push(data[j].MOQ);
                ltArray.push(data[j].LT);
                if (data[j].A級資材.trim() === "是" || data[j].A級資材.trim().toLowerCase() === "yes") {
                    gradeaArray.push("是");
                } else {
                    gradeaArray.push("否");
                } // if else

                if (data[j].月請購.trim() === "是" || data[j].月請購.trim().toLowerCase() === "yes") {
                    monthlyArray.push("是");
                } else {
                    monthlyArray.push("否");
                } // if else

                dispatcherArray.push(data[j].發料部門);

                if (data[j].月請購.trim() === "是" || data[j].月請購.trim().toLowerCase() === "yes") {
                    safestockArray.push("null");
                } else {
                    safestockArray.push(data[j].安全庫存);
                } // if else
            } // for

            // console.log(pnArray, nameArray, specArray, priceArray, currencyArray, unitArray, mpqArray, moqArray, ltArray, gradeaArray, monthlyArray, dispatcherArray, safestockArray); // test
            // actually updating database now
            let start = Date.now();
            let result = await uploadToDB(pnArray, nameArray, specArray, priceArray, unitArray, currencyArray, mpqArray, moqArray, ltArray, gradeaArray, monthlyArray, dispatcherArray, safestockArray);
            let timeTaken = Date.now() - start;
            console.log("Total time taken : " + timeTaken + " milliseconds");
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");

            if (result === "success") {
                uploadToDBReady.value = false;
                notyf.open({
                    type: "success",
                    message: app.appContext.config.globalProperties.$t("inboundpageLang.change") + " " + app.appContext.config.globalProperties.$t("inboundpageLang.success"),
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
            if (queryResult.value === "" || queryResult.value == null || JSON.parse(queryResult.value).data === undefined) {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            for (let i = 0; i < JSON.parse(queryResult.value).data.length; i++) {
                dispatchers.push(JSON.parse(queryResult.value).data[i].發料部門);
            } // for

            let singleEntry = {};

            for (let i = 1; i < input_data.length; i++) {
                if (input_data[i][0] !== undefined && input_data[i][1] !== undefined &&
                    input_data[i][2] !== undefined && input_data[i][3] !== undefined &&
                    input_data[i][4] !== undefined && input_data[i][5] !== undefined &&
                    input_data[i][6] !== undefined && input_data[i][7] !== undefined &&
                    input_data[i][8] !== undefined && input_data[i][9] !== undefined &&
                    input_data[i][10] !== undefined && input_data[i][11] !== undefined) {

                    singleEntry.料號 = input_data[i][0].toString().trim();
                    singleEntry.品名 = input_data[i][1].toString().trim();
                    singleEntry.規格 = input_data[i][2].toString().trim();
                    singleEntry.單價 = parseFloat(input_data[i][3]);
                    singleEntry.幣別 = input_data[i][4].toString().trim();
                    singleEntry.單位 = input_data[i][5].toString().trim();
                    singleEntry.MPQ = parseInt(input_data[i][6]);
                    singleEntry.MOQ = parseInt(input_data[i][7]);
                    singleEntry.LT = parseFloat(input_data[i][8]);
                    singleEntry.月請購 = input_data[i][9].toString().trim();
                    singleEntry.A級資材 = input_data[i][10].toString().trim();
                    singleEntry.發料部門 = input_data[i][11].toString().trim();
                    singleEntry.安全庫存 = parseInt(input_data[i][12]);

                    if (dispatchers.includes(singleEntry.發料部門)) {
                        singleEntry.dispatcherError = false;
                    } else {
                        singleEntry.dispatcherError = true;
                    } // if else

                    singleEntry.id = i + 1;

                    data.push(singleEntry);
                    singleEntry = {};
                } // if
            } // for

            // console.log(data); // test
            if (!$("#importedtable").hasClass("show")) {
                $("#togglebtn").click();
            } // if

            uploadToDBReady.value = true;
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        }); // watch for data change

        watch(data, () => {
            document.getElementsByClassName("vtl-table")[0].scrollIntoView({ behavior: "smooth" });
        });

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
                        if (row.料號 === "" || row.料號 === null) {
                            return (
                                '<input type="hidden" id="number' +
                                row.id +
                                '" name="number' +
                                i +
                                '" value="">' +
                                '<div class="text-nowrap text-danger scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                'Missing' +
                                "</div>"
                            );
                        } else {
                            return (
                                '<input type="hidden" id="number' +
                                row.id +
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
                        "basicInfoLang.pName"
                    ),
                    field: "品名",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.品名 === "" || row.品名 === null) {
                            return (
                                '<input type="hidden" id="name' +
                                row.id +
                                '" name="name' +
                                i +
                                '" value="' +
                                row.品名 +
                                '">' +
                                '<div class="text-nowrap text-danger scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                "Missing" +
                                "</div>"
                            );
                        } else {
                            return (
                                '<input type="hidden" id="name' +
                                row.id +
                                '" name="name' +
                                i +
                                '" value="' +
                                row.品名 +
                                '">' +
                                '<div class="text-nowrap scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.品名 +
                                "</div>"
                            );
                        } // if else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.format"
                    ),
                    field: "規格",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.規格 === "" || row.規格 === null) {
                            return (
                                '<input type="hidden" id="format' +
                                row.id +
                                '" name="format' +
                                i +
                                '" value="' +
                                row.規格 +
                                '">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                "Missing" +
                                "</div>"
                            );
                        } else {
                            return (
                                '<input type="hidden" id="format' +
                                row.id +
                                '" name="format' +
                                i +
                                '" value="' +
                                row.規格 +
                                '">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.規格 +
                                "</div>"
                            );
                        } // if else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.price"
                    ),
                    field: "單價",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        if (Number.isNaN(row.單價)) {
                            return (
                                '<input type="hidden" id="price' +
                                row.id +
                                '" name="price' +
                                i +
                                '" value="">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                parseFloat(row.單價) +
                                "</div>"
                            );
                        } else {
                            return (
                                '<input type="hidden" id="price' +
                                row.id +
                                '" name="price' +
                                i +
                                '" value="' +
                                parseFloat(row.單價) +
                                '">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                parseFloat(row.單價) +
                                "</div>"
                            );
                        } // if else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.money"
                    ),
                    field: "幣別",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        let currencyExist = false;
                        let currencyDict = [
                            "RMB",
                            "USD",
                            "JPY",
                            "TWD",
                            "VND",
                            "IDR",
                        ];

                        let returnStr = "";
                        returnStr +=
                            '<input type="hidden" id="money' +
                            row.id +
                            '" name="money' +
                            i;

                        currencyDict.forEach((element) => {
                            if (row.幣別.toString().trim().toLowerCase() === element.toLowerCase()) {
                                returnStr +=
                                    '" value="' +
                                    element +
                                    '">' +
                                    '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                    ' style="overflow-x: auto; width: 100%;">' +
                                    element +
                                    "</div>";
                                currencyExist = true;
                            } // if
                        }); // for each in sender array

                        if (!currencyExist) {
                            returnStr +=
                                '" value="">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.幣別 +
                                "</div>";
                        } // if

                        return returnStr;
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.unit"
                    ),
                    field: "單位",
                    width: "8ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.單位 === "" || row.單位 === null) {
                            return (
                                '<input type="hidden" id="unit' +
                                row.id +
                                '" name="unit' +
                                i +
                                '" value="">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                "Missing" +
                                "</div>"
                            );
                        } else {
                            return (
                                '<input type="hidden" id="unit' +
                                row.id +
                                '" name="unit' +
                                i +
                                '" value="' +
                                row.單位 +
                                '">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.單位 +
                                "</div>"
                            );
                        }
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.mpq"
                    ),
                    field: "MPQ",
                    width: "8ch",
                    sortable: true,
                    display: function (row, i) {
                        if (Number.isNaN(row.MPQ)) {
                            return (
                                '<input type="hidden" id="mpq' +
                                row.id +
                                '" name="mpq' +
                                i +
                                '" value="">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.MPQ +
                                "</div>"
                            );
                        } else {
                            return (
                                '<input type="hidden" id="mpq' +
                                row.id +
                                '" name="mpq' +
                                i +
                                '" value="' +
                                row.MPQ +
                                '">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.MPQ +
                                "</div>"
                            );
                        } // if else
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
                        if (Number.isNaN(row.MOQ)) {
                            return (
                                '<input type="hidden" id="moq' +
                                row.id +
                                '" name="moq' +
                                i +
                                '" value="">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.MOQ +
                                "</div>"
                            );
                        } else {
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
                        } // if else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.lt"
                    ),
                    field: "LT",
                    width: "8ch",
                    sortable: true,
                    display: function (row, i) {
                        if (Number.isNaN(row.LT)) {
                            return (
                                '<input type="hidden" id="lt' +
                                row.id +
                                '" name="lt' +
                                i +
                                '" value="">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.LT +
                                "</div>"
                            );
                        } else {
                            return (
                                '<input type="hidden" id="lt' +
                                row.id +
                                '" name="lt' +
                                i +
                                '" value="' +
                                row.LT +
                                '">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.LT +
                                "</div>"
                            );
                        } // if else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.month"
                    ),
                    field: "月請購",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.月請購.trim() === "是" || row.月請購.trim().toLowerCase() === "yes") {
                            return (
                                '<input type="hidden" id="monthly' +
                                row.id +
                                '" name="monthly' +
                                i +
                                '" value="是">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                app.appContext.config.globalProperties.$t("basicInfoLang.yes") +
                                "</div>"
                            );
                        } // if
                        else {
                            return (
                                '<input type="hidden" id="monthly' +
                                row.id +
                                '" name="monthly' +
                                i +
                                '" value="否">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                app.appContext.config.globalProperties.$t("basicInfoLang.no") +
                                "</div>"
                            );
                        } // else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.gradea"
                    ),
                    field: "A級資材",
                    width: "7ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.A級資材.trim() === "是" || row.A級資材.trim().toLowerCase() === "yes") {
                            return (
                                '<input type="hidden" id="gradea' +
                                row.id +
                                '" name="gradea' +
                                i +
                                '" value="是">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                app.appContext.config.globalProperties.$t("basicInfoLang.yes") +
                                "</div>"
                            );
                        } // if
                        else {
                            return (
                                '<input type="hidden" id="gradea' +
                                row.id +
                                '" name="gradea' +
                                i +
                                '" value="否">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                app.appContext.config.globalProperties.$t("basicInfoLang.no") +
                                "</div>"
                            );
                        } // else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.senddep"
                    ),
                    field: "發料部門",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.dispatcherError) { // if 發料部門 not exist in 發料部門 table
                            if (row.發料部門 === "" || row.發料部門 === null) {
                                return (
                                    '<input type="hidden" id="send' +
                                    row.id +
                                    '" name="send' +
                                    i +
                                    '" value="">' +
                                    '<div class="scrollableWithoutScrollbar text-nowrap text-danger"' +
                                    ' style="overflow-x: auto; width: 100%;">' +
                                    "Missing" +
                                    "</div>"
                                );
                            } else {
                                return (
                                    '<input type="hidden" id="send' +
                                    row.id +
                                    '" name="send' +
                                    i +
                                    '" value="">' +
                                    '<div class="scrollableWithoutScrollbar text-nowrap text-danger"' +
                                    ' style="overflow-x: auto; width: 100%;">' +
                                    row.發料部門 +
                                    "</div>"
                                );
                            } // if else
                        } else {
                            return (
                                '<input type="hidden" id="send' +
                                row.id +
                                '" name="send' +
                                i +
                                '" value="' +
                                row.發料部門 +
                                '">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.發料部門 +
                                "</div>"
                            );
                        } // if else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.safe"
                    ),
                    field: "安全庫存",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.月請購.trim() === "是" || row.月請購.trim().toLowerCase() === "yes") {
                            return (
                                '<input type="hidden" id="safe' +
                                row.id +
                                '" name="safe' +
                                i +
                                '" value="">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                app.appContext.config.globalProperties.$t("basicInfoLang.differ_by_client") +
                                "</div>"
                            );
                        } else {
                            if (Number.isNaN(row.安全庫存)) {
                                return (
                                    '<input type="hidden" id="safe' +
                                    row.id +
                                    '" name="safe' +
                                    i +
                                    '" value="">' +
                                    '<div class="scrollableWithoutScrollbar text-nowrap text-danger"' +
                                    ' style="overflow-x: auto; width: 100%;">' +
                                    row.安全庫存 +
                                    "</div>"
                                );
                            } else {
                                return (
                                    '<input type="hidden" id="safe' +
                                    row.id +
                                    '" name="safe' +
                                    i +
                                    '" value="' +
                                    row.安全庫存 +
                                    '">' +
                                    '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                    ' style="overflow-x: auto; width: 100%;">' +
                                    row.安全庫存 +
                                    "</div>"
                                );
                            } // if else
                        } // if else
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
            // console.log(rowsKey);
        };

        return {
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