<template>
    <div class="card w-100">
        <button class="go-corner" type="button" data-bs-toggle="collapse" data-bs-target=".multi-collapse"
            aria-expanded="false" aria-controls="multiCollapse1 multiCollapse2">
            <div class="go-arrow">
                {{ $t("monthlyPRpageLang.search") }}
            </div>
        </button>
        <div class="card-header">
            <h3>{{ $t("monthlyPRpageLang.importMonthlyData") }}</h3>
        </div>

        <div class="row justify-content-center">
            <div class="card-body">
                <div class="w-100">
                    <div class="row w-100 justify-content-center mb-3">
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

    <button id="togglebtn" class="btn btn-primary col col-auto" type="button" data-bs-toggle="collapse"
        data-bs-target="#importedtable" aria-expanded="false" aria-controls="importedtable" style="display: none;">
    </button>

    <div class="card collapse" id="importedtable">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="row col col-auto">
                    <div class="col col-auto">
                        <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }}
                            :</label>
                    </div>
                    <div class="col col-auto p-0 m-0">
                        <input id="pnInput" class="text-center form-control form-control-lg"
                            v-bind:placeholder="$t('monthlyPRpageLang.enter90isn')" v-model="searchTerm" />
                    </div>
                </div>
                <div class="col col-auto">
                    <button v-if="uploadToDBReady" id="delete" name="delete" class="col col-auto btn btn-lg btn-danger"
                        :value="$t('basicInfoLang.delete')" @click="deleteRow">{{ $t('basicInfoLang.delete')
                        }}</button>
                </div>
            </div>
            <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
            <span v-if="isInvalid_DB" class="invalid-feedback d-block" role="alert">
                <strong>{{ validation_err_msg }}</strong>
            </span>
            <table-lite :is-fixed-first-column="true" :is-static-mode="true" :hasCheckbox="true"
                :isLoading="table.isLoading" :messages="table.messages" :columns="table.columns" :rows="table.rows"
                :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
                @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows"></table-lite>

            <div class="row w-100 justify-content-center">
                <button v-if="uploadToDBReady" type="submit" name="upload" class="col col-auto btn btn-lg btn-primary"
                    @click="onSendToDBClick">
                    {{ $t('monthlyPRpageLang.addtodatabase') }}
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

        const { mats, uploadMonthlyToDB } = useMonthlyPRSearch();
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
                let deleteID = document.getElementsByClassName("vtl-tbody-tr")[checkedRows[i]].children[1].firstChild.firstChild.getAttribute("id");
                deleteID = deleteID.replace('ninetyisn', '');

                let indexOfObject = data.findIndex(object => {
                    return parseInt(object.excel_row_num) === parseInt(deleteID);
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
                        } else if (input_data[0][0].trim() !== "90料號" || input_data[0][1].trim() !== "料號" || !input_data[0][2].trim().toLowerCase().includes("mps")) {
                            isInvalid.value = true;
                            validation_err_msg.value = app.appContext.config.globalProperties.$t("fileUploadErrors.Content_errors");
                        } else {
                            let tempArr = Array();
                            for (let i = 1; i < input_data.length; i++) {
                                if (input_data[i][0] != undefined && input_data[i].length > 2 && input_data[i][0].trim() != "" && input_data[i][0].trim() != null) {
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
            console.log("The modal should be triggered by now."); // test
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
            if (queryResult.value == "") {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            let allRowsObj = JSON.parse(queryResult.value);
            // console.log(allRowsObj.data); // test
            let singleEntry = {};

            for (let i = 1; i < input_data.length; i++) {
                singleEntry.料號90 = input_data[i][0].toString().trim();
                singleEntry.料號 = input_data[i][1].toString().trim();
                singleEntry.本月MPS = input_data[i][2];
                singleEntry.本月生產天數 = input_data[i][3];
                singleEntry.下月MPS = input_data[i][4];
                singleEntry.下月生產天數 = input_data[i][5];
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

                data.push(singleEntry);
                singleEntry = {};
            } // for

            if (!$("#importedtable").hasClass("show")) {
                $("#togglebtn").click();
            } // if

            // console.log(data); // test
            uploadToDBReady.value = true;
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        }); // watch for data change

        // Table config
        const table = reactive({
            isLoading: false,
            columns: [
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.90isn"
                    ),
                    field: "料號90",
                    width: "15ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="ninetyisn' +
                            row.excel_row_num +
                            '" name="ninetyisn' +
                            i +
                            '" value="' +
                            row.料號90 +
                            '">' +
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.料號90 +
                            "</div>"
                        );
                    },
                },
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
                                '<input type="hidden" id="isn' +
                                row.excel_row_num +
                                '" name="isn' +
                                i +
                                '" value="' +
                                row.料號 +
                                '">' +
                                '<div class="scrollableWithoutScrollbar text-nowrap text-danger"' +
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
                                '<div class="scrollableWithoutScrollbar text-nowrap"' +
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
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseInt(row.本月MPS).toLocaleString('en', { useGrouping: true }) +
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
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseInt(row.本月生產天數) +
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
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseInt(row.下月MPS).toLocaleString('en', { useGrouping: true }) +
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
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseInt(row.下月生產天數) +
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
            data[row.excel_row_num].單耗 = document.getElementById("unitConsumption" + row.excel_row_num).value;
            // console.log(data); // test
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
            rowUserInput,
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

.go-corner {
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    width: 55px;
    height: 55px;
    overflow: hidden;
    top: 0;
    right: 0;
    background-color: #00838d;
    border: none;
    border-radius: 0 4px 0 55px;
    transition-duration: .2s;
}

.go-corner:hover {
    width: 82px;
    height: 82px;
    border-radius: 0 4px 0 82px;

    .go-arrow {
        margin-top: -30px;
        margin-right: -30px;
        transform-origin: top right;
        transform: scale(1.5);
    }
}

.go-arrow {
    margin-top: -10px;
    margin-right: -10px;
    color: white;
    transition-duration: .2s;
}
</style>