<template>
    <div class="card">
        <div class="card-header">
            <h3>{{ $t("inboundpageLang.upload") }}</h3>
        </div>

        <div class="row justify-content-center">
            <div class="card-body">
                <div class=" w-100">
                    <div class="row w-100 justify-content-center mb-3">
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
                        <div class="row w-100 justify-content-center">
                            <button type="submit" name="upload" class="col col-auto btn btn-lg btn-primary"
                                @click="onUploadClick">
                                {{ $t('inboundpageLang.upload1') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <!-- <div class="card-header">
            <h3>{{ $t('inboundpageLang.stockupload') }}</h3>
        </div> -->
        <div class="card-body">
            <div class="row" style="text-align: left">
                <div class="col col-auto">
                    <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
                </div>
                <div class="col col-3 p-0 m-0">
                    <input id="pnInput" class="text-center form-control form-control-lg"
                        v-bind:placeholder="$t('basicInfoLang.enterisn')" v-model="searchTerm" />
                </div>
            </div>
            <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
            <span v-if="isInvalid_DB" class="invalid-feedback d-block" role="alert">
                <strong>{{ validation_err_msg }}</strong>
            </span>
            <table-lite :is-fixed-first-column="true" :is-static-mode="true" :hasCheckbox="false"
                :isLoading="table.isLoading" :messages="table.messages" :columns="table.columns" :rows="table.rows"
                :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
                @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows"></table-lite>

            <div class="row w-100 justify-content-center">
                <button v-if="uploadToDBReady" type="submit" name="upload" class="col col-auto btn btn-lg btn-primary"
                    @click="onSendToDBClick">
                    {{ $t('inboundpageLang.addtodatabase') }}
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
import useInboundStockSearch from "../../composables/InboundStockSearch.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        let exampleUrl = ref(window.location.origin + '/download/StockExample.xlsx');
        const app = getCurrentInstance(); // get the current instance
        let thisHtmlLang = document
            .getElementsByTagName("HTML")[0]
            .getAttribute("lang");
        // get the current locale from html tag
        app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

        const { mats, queryResult, locations, validateISN, getLocs, getExistingStock, uploadToDB } = useInboundStockSearch(); // axios get the mats data
        onBeforeMount(getLocs);

        let isInvalid = ref(false); // validation
        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref("");
        let uploadToDBReady = ref(false); // validation
        const file = ref();
        let input_data;

        const onUploadClick = () => {
            isInvalid_DB.value = false;

            mats.value = "";
            if (file.value) {
                $("body").loadingModal({
                    text: "Loading...",
                    animation: "circle",
                });

                // console.log(file.value); // test
                if (file.value.type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || file.value.type == "application/vnd.ms-excel" || file.value.type == "application/vnd.ms-excel" || file.value.type == ".csv") {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        /* Parse data */
                        const bstr = e.target.result;
                        const wb = XLSX.read(bstr, { type: 'binary' });
                        /* Get first worksheet */
                        const wsname = wb.SheetNames[0];
                        const ws = wb.Sheets[wsname];
                        /* Convert array of arrays */
                        input_data = XLSX.utils.sheet_to_json(ws, { header: 1 });
                        // console.log(input_data); // data[row#][col#]  test
                        if (input_data[0][0] === undefined || input_data[0][1] === undefined || input_data[0][2] === undefined) {
                            isInvalid.value = true;
                            validation_err_msg.value = app.appContext.config.globalProperties.$t("fileUploadErrors.Content_errors");
                        } else if (input_data[0][0].trim() !== "料號" || input_data[0][1].trim() !== "數量" || input_data[0][2].trim() !== "儲位") {
                            isInvalid.value = true;
                            validation_err_msg.value = app.appContext.config.globalProperties.$t("fileUploadErrors.Content_errors");
                        } else {
                            let tempArr = Array();
                            for (let i = 1; i < input_data.length; i++) {
                                if (input_data[i][0].trim() != "" && input_data[i][0].trim() != null) {
                                    tempArr.push(input_data[i][0].trim());
                                } // if
                            } // for

                            validateISN(tempArr);
                            getExistingStock(input_data);
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
            file.value = event.target.files ? event.target.files[0] : null;
        } // onInputChange

        const searchTerm = ref(""); // Search text

        // pour the data in
        const data = reactive([]);
        // const senders = reactive([]); // access the value by senders[0], senders[1] ...

        const onSendToDBClick = () => {
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });
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
                    app.appContext.config.globalProperties.$t("inboundpageLang.row") +
                    " " + data[rowsCount].excel_row_num + " " +
                    "(" + data[rowsCount].料號 + ") " +
                    app.appContext.config.globalProperties.$t("inboundpageLang.noisn");

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

            for (let j = 0; j < data.length && hasError == false; j++) {
                if (!locsArray.includes(data[j].儲位)) {
                    hasError = true;
                    rowsCount = j;
                } // if
            } // for

            if (hasError) {
                isInvalid_DB.value = true;
                validation_err_msg.value =
                    "Excel " +
                    app.appContext.config.globalProperties.$t("inboundpageLang.row") +
                    " " + data[rowsCount].excel_row_num + " " +
                    "(" + data[rowsCount].儲位 + ") " +
                    app.appContext.config.globalProperties.$t("inboundpageLang.noloc");

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

            for (let i = 0; i < input_data.length; i++) {
                for (let j = 0; j < JSON.parse(mats.value).data.length; j++) {
                    if (input_data[i][0] === JSON.parse(mats.value).data[j].料號 && input_data[i][2] === JSON.parse(mats.value).data[j].儲位) {
                        input_data[i][1] = input_data[i][1] + parseInt(JSON.parse(mats.value).data[j].現有庫存);
                        break;
                    } // if
                } // for
            } // for

            // console.log(input_data); // test

            async () => {
                $("body").loadingModal({
                    text: "Loading...",
                    animation: "circle",
                });
                const result = await uploadToDB(input_data);
                console.log(result); // test
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
            } // wait for upload

        } // onSendToDBClick

        let locsArray = Array();
        watch(queryResult, () => {
            data.splice(0); // cleanup data from previous upload
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            let allRowsObj = JSON.parse(queryResult.value);
            //console.log(allRowsObj.data.length);
            for (let i = 0; i < allRowsObj.data.length; i++) {
                allRowsObj.data[i].數量 = parseInt(
                    input_data[i + 1][1]
                );

                // console.log(input_data[i + 1][2]); // test
                allRowsObj.data[i].儲位 = input_data[i + 1][2].trim();
                allRowsObj.data[i].excel_row_num = i + 1;

                data.push(allRowsObj.data[i]);
            } // for

            JSON.parse(locations.value).data.forEach(element => {
                locsArray.push(element.儲存位置);
            });

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        }); // watch for data change

        watch(mats, () => {
            // console.log(mats.value); // test
            // check if existing stock query is complete or not
            if (mats.value !== "" && JSON.parse(mats.value).data != undefined && JSON.parse(mats.value).data != null && JSON.parse(mats.value).data != "") {
                uploadToDBReady.value = true;
            } // if

            // check if upload successful or not
            if (mats.value !== "" && JSON.parse(mats.value).record > 0) {
                uploadToDBReady.value = false;
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                notyf.open({
                    type: "success",
                    message: app.appContext.config.globalProperties.$t("inboundpageLang.total") + " " + JSON.parse(mats.value).record + " " + app.appContext.config.globalProperties.$t("inboundpageLang.record") + " " + app.appContext.config.globalProperties.$t("inboundpageLang.change") + " " + app.appContext.config.globalProperties.$t("inboundpageLang.success"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });
            } // if
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
                                app.appContext.config.globalProperties.$t("inboundpageLang.row") +
                                " " + row.excel_row_num +
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
                        "inboundpageLang.format"
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
                                app.appContext.config.globalProperties.$t("inboundpageLang.noisn") +
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
                        "inboundpageLang.amount"
                    ),
                    field: "數量",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.月請購 === "" || row.月請購 === null || row.月請購.toLowerCase() === "null") { // if isn not exist in consumptive_material table
                            return (
                                '<input type="hidden" id="amount' +
                                i +
                                '" name="amount' +
                                i +
                                '" value="' +
                                row.數量 +
                                '">' +
                                '<div class="text-nowrap scrollableWithoutScrollbar text-danger"' +
                                ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                                row.數量 +
                                "</div>"
                            );
                        } // if
                        else {
                            return (
                                '<input type="hidden" id="amount' +
                                i +
                                '" name="amount' +
                                i +
                                '" value="' +
                                row.數量 +
                                '">' +
                                '<div class="text-nowrap scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                                row.數量 +
                                "</div>"
                            );
                        } // else
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
                        "inboundpageLang.loc"
                    ),
                    field: "儲位",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        if (locsArray.includes(row.儲位)) {
                            return (
                                '<input type="hidden" id="loc' +
                                i +
                                '" name="loc' +
                                i +
                                '" value="' +
                                row.儲位 +
                                '">' +
                                '<div class="text-nowrap scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                                row.儲位 +
                                "</div>"
                            );
                        } // if
                        else {
                            return (
                                '<input type="hidden" id="loc' +
                                i +
                                '" name="loc' +
                                i +
                                '" value="' +
                                row.儲位 +
                                '">' +
                                '<div class="text-nowrap text-danger scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                                row.儲位 + " " + app.appContext.config.globalProperties.$t("inboundpageLang.noloc") +
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
