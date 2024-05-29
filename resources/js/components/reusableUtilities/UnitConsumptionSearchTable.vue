<template>
    <div class="card">
        <div class="card-header">
            <h3>{{ $t('monthlyPRpageLang.isnConsumeUpdate') }}</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="row col col-auto">
                    <div class="col col-auto">
                        <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
                    </div>
                    <div class="col col-6 p-0 m-0">
                        <input id="pnInput" class="text-center form-control form-control-lg"
                            v-bind:placeholder="$t('monthlyPRpageLang.enterisn_90pn_or_descr')" v-model="searchTerm" />
                    </div>
                </div>
                <div class="col col-auto">
                    <button type="submit" id="delete" name="delete" class="col col-auto btn btn-lg btn-danger"
                        @click="deleteRow">
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
                @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows"
                @row-input="rowUserInput"></table-lite>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <div class="row justify-content-between align-items-center">
                <label class="form-label col col-auto">{{ $t('monthlyPRpageLang.surepeopleemail') }}:</label>
                <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="col col-auto">
                    <div class="input-group">
                        <select class="form-select form-select-lg col col-auto text-center" v-model="selected_mail">
                            <option style="display: none;" disabled selected value="">
                                {{ $t("monthlyPRpageLang.noemail") }}
                            </option>
                            <option v-for="mail in all_mails" :value="mail.email">{{ mail.姓名 }}</option>
                        </select>
                        <span class="input-group-text input-group-text-lg" id="emailTail">{{ selected_mail }}</span>
                    </div>
                </div>
                <div class="col col-auto">
                    <button v-if="uploadToDBReady" name="upload"
                        class="col col-auto fs-3 text-center btn btn-lg btn-info" @click="onSendToDBClick">
                        <i class="bi bi-envelope-check-fill"></i>
                        {{ $t('monthlyPRpageLang.submit') }}
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

        const { mats, mails, getAll, getCheckersMails, uploadToDB } = useUnitConsumptionSearch();
        const { queryResult, manualResult, validateISN, validateISN_manual } = useCommonlyUsedFunctions();

        onBeforeMount(getCheckersMails);
        onBeforeMount(async () => {
            await getCheckersMails();
            await getAll();
        });

        let isInvalid = ref(false); // file input validation
        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref("");
        let uploadToDBReady = ref(false); // validation
        const all_mails = reactive([]);
        const file = ref();
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

        const triggerModal = async () => {
            // console.log("Loading Modal Triggered!"); // test
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
            for (let i = 0; i < data.length; i++) {
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

        watch(mats, async () => {
            await triggerModal();
            data.splice(0);
            if (mats.value == "") {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            let allRowsObj = JSON.parse(mats.value);
            // console.log(allRowsObj.data); // test
            let singleEntry = {};
            for (let i = 0; i < allRowsObj.data.length; i++) {
                // console.log(allRowsObj.data[i].料號90); // test
                singleEntry = allRowsObj.data[i];
                singleEntry.id = i;

                data.push(singleEntry);
                singleEntry = {};
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

        watch(data, () => {
            document.getElementsByClassName("vtl-table")[0].scrollIntoView({ behavior: "smooth" });
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
                    width: "14ch",
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
                    field: "料號90",
                    width: "14ch",
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
                        .includes(searchTerm.value.toLowerCase()) ||
                    x.品名
                        .includes(searchTerm.value)
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
            onSendToDBClick,
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