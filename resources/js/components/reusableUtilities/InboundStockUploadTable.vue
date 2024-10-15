<template>
    <div class="card">
        <div class="card-header">
            <h3>{{ $t("inboundpageLang.upload") }}</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-center mb-3">
                <div class="col col-auto">
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
                    <button type="submit" name="upload" class="col col-auto btn btn-lg btn-primary"
                        @click="onUploadClick">
                        {{ $t('inboundpageLang.upload1') }}
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
                        <label for="pnInput1" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
                    </div>
                    <div class="col col-6 p-0 m-0">
                        <input id="pnInput1" class="text-center form-control form-control-lg"
                            v-bind:placeholder="$t('monthlyPRpageLang.enterisn_or_descr')" v-model="searchTerm" />
                    </div>
                </div>
                <div class="col col-auto">
                    <button id="delete" name="delete" class="col col-auto btn btn-lg btn-danger" @click="deleteRow">
                        <i class="bi bi-trash3-fill fs-4"></i>
                    </button>
                </div>
            </div>
            <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
            <span v-if="isInvalid_DB" class="invalid-feedback d-block" role="alert">
                <strong>{{ validation_err_msg }}</strong>
            </span>
            <table-lite :is-fixed-first-column="true" :is-static-mode="true" :isSlotMode="true" :hasCheckbox="true"
                :isLoading="table.isLoading" :messages="table.messages" :columns="table.columns" :rows="table.rows"
                :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
                @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows">
                <template v-slot:原儲位="{ row, key }">
                    <div class="col col-auto align-items-center m-0 p-0">
                        <button @click="openLocDetails(row.料號)" type="button" data-bs-toggle="modal"
                            data-bs-target="#detailTable" class="btn btn-outline-info my-0 px-1 py-0"
                            style="border-radius: 20px;" :id="'oldloc' + row.id" :name="'oldloc' + key">Info</button>
                    </div>
                </template>
            </table-lite>
            <div class="w-100" style="height: 1ch;"></div> <!-- breaks cols to a new line-->
            <div class="row justify-content-center">
                <div class="col col-auto">
                    <button v-if="uploadToDBReady" type="submit" name="upload"
                        class="col col-auto fs-3 text-center btn btn-lg btn-info" @click="onSendToDBClick">
                        <i class="bi bi-inboxes-fill"></i>
                        {{ $t('templateWords.inbound') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailTable" tabindex="-1" aria-labelledby="detailTable" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <h1 class="col col-auto modal-title m-0 p-0 fs-4">
                        {{ modalTitle }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-between">
                        <div class="row col col-auto">
                            <div class="col col-auto">
                                <label for="pnInput2" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }}
                                    :</label>
                            </div>
                            <div class="col col-auto p-0 m-0">
                                <input id="pnInput2" class="text-center form-control form-control-lg"
                                    v-bind:placeholder="$t('monthlyPRpageLang.enterisn_or_descr')"
                                    v-model="searchTerm2" />
                            </div>
                        </div>
                        <div class="col col-auto">
                            <button id="download" name="download" class="col col-auto btn btn-lg btn-success"
                                :value="$t('monthlyPRpageLang.download')" @click="OutputExcelClick()">
                                <i class="bi bi-file-earmark-arrow-down-fill fs-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="w-100" style="height: 1ch"></div>
                    <!-- </div>breaks cols to a new line-->
                    <table-lite :is-static-mode="true" :isSlotMode="true" :hasCheckbox="false"
                        :messages="table2.messages" :columns="table2.columns" :rows="table2.rows"
                        :total="table2.totalRecordCount" :page-options="table2.pageOptions" :sortable="table2.sortable"
                        :is-fixed-first-column="false">
                    </table-lite>
                </div>
                <div v-if="showFooter" class="modal-footer justify-content-between">
                    <button @click="sxb_reject" type="button" class="btn btn-lg btn-danger" style="border-radius: 5px;">
                        {{ $t('monthlyPRpageLang.review_cancel') }}
                    </button>
                    <button @click="sxb_approve" type="button" class="btn btn-lg btn-success"
                        style="border-radius: 5px;">
                        {{ $t('monthlyPRpageLang.review_complete') }}
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
import useInboundStockSearch from "../../composables/InboundStockSearch.ts";
import useTransitSearch from "../../composables/TransitSearch.ts";
import useCommonlyUsedFunctions from "../../composables/CommonlyUsedFunctions.ts";
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

        const { mats, uploadToDB, getExistingStock } = useInboundStockSearch();
        const { mats_inTransit, getTransit } = useTransitSearch(); // axios get the mats data
        const { queryResult, locations, validateISN, getLocs } = useCommonlyUsedFunctions();

        onBeforeMount(async () => {
            await getTransit();
            await getLocs();
        });

        let isInvalid = ref(false); // validation
        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref("");
        let uploadToDBReady = ref(false); // validation
        let checkedRows = [];
        const file = ref();
        let input_data;
        let sumInboundQuantity = {};

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

        // Helper function to deal with Objects
        const deepCopyObject = (obj) => {
            let tempObj = {};
            for (let [key, value] of Object.entries(obj)) {
                if (Array.isArray(value)) {
                    tempObj[key] = deepCopy(value);
                } // if
                else {
                    if (typeof value === 'object') {
                        tempObj[key] = deepCopyObject(value);
                    } // if
                    else {
                        tempObj[key] = value
                    } // else
                } // else
            } // for
            return tempObj;
        } // deepCopyObject

        const deepCopy = (arr) => {
            let copy = [];
            arr.forEach(elem => {
                if (Array.isArray(elem)) {
                    copy.push(deepCopy(elem))
                } else {
                    if (typeof elem === 'object') {
                        copy.push(deepCopyObject(elem))
                    } else {
                        copy.push(elem)
                    }
                }
            })
            return copy;
        } // deepCopy

        const onUploadClick = async () => {
            isInvalid_DB.value = false;
            queryResult.value = "";
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
                        if (input_data === undefined || input_data[0] === undefined || input_data[0][0] === undefined || input_data[0][1] === undefined || input_data[0][2] === undefined || input_data[0][3] === undefined) {
                            isInvalid.value = true;
                            validation_err_msg.value = app.appContext.config.globalProperties.$t("fileUploadErrors.Content_errors");
                        } // if
                        else {
                            let tempArr = Array();
                            for (let i = 1; i < input_data.length; i++) {
                                if (input_data[i][0] != undefined && input_data[i].length > 2 && input_data[i][0].toString().trim() != "" && input_data[i][0].toString().trim() != null) {
                                    tempArr.push(input_data[i][0].toString().trim());
                                } // if
                                else {
                                    input_data.splice(i, 1); // remove the empty row
                                    i = i - 1;
                                } // else
                            } // for

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
        const searchTerm2 = ref(""); // Search text for modal table
        const modalTitle = ref("");
        let showFooter = ref(false);

        // pour the data in
        const data = reactive([]);
        const data2 = reactive([]);
        // const senders = reactive([]); // access the value by senders[0], senders[1] ...

        const triggerModal = async () => {
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            return new Promise((resolve, reject) => {
                resolve();
            });
        } // triggerModal

        const openLocDetails = (PN) => {
            // console.log("clicked!"); // test
            modalTitle.value = PN;
            data2.splice(0);
            // console.log(JSON.parse(mats.value).data); // test
            Object.assign(data2, JSON.parse(mats.value).data.flatMap((obj) => {
                if (obj.料號 === PN) {
                    return [obj];
                } // if
                else {
                    return [];
                } // else
            }));
        } // openLocDetails

        const OutputExcelClick = async () => {
            await triggerModal();

            // get today's date for filename
            let today = new Date();
            let dd = String(today.getDate()).padStart(2, '0');
            let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            let yyyy = today.getFullYear();
            today = yyyy + "_" + mm + '_' + dd;

            let rows = data2.map((obj) => {
                return {
                    "料號": obj.料號,
                    "品名": obj.品名,
                    "規格": obj.規格,
                    "現有庫存": obj.現有庫存,
                    "儲位": obj.儲位,
                };
            });

            const worksheet = XLSX.utils.json_to_sheet(rows);

            // change header name
            XLSX.utils.sheet_add_aoa(worksheet,
                [[
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.isn"),
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.pName"),
                    app.appContext.config.globalProperties.$t("inboundpageLang.format"),
                    app.appContext.config.globalProperties.$t("monthlyPRpageLang.nowstock"),
                    app.appContext.config.globalProperties.$t("inboundpageLang.loc"),
                ]],
                { origin: "A1" });

            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, app.appContext.config.globalProperties.$t("monthlyPRpageLang.PR"));
            XLSX.writeFile(workbook,
                modalTitle.value + "_" + app.appContext.config.globalProperties.$t(
                    "inboundpageLang.nowstock"
                ) + "_" + today + ".xlsx", { compression: true });

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // OutputExcelClick
        
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

        } // deleteRow

        const onSendToDBClick = async () => {
            await triggerModal();
            isInvalid_DB.value = false;
            let rowsCount = [];
            let hasError = false;
            let inbound_data = data.map((obj) => {
                return [obj.料號, obj.入庫量, obj.新儲位, obj.入庫原因];
            });
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
            for (let j = 0; j < data.length; j++) {
                if (data[j].月請購 === "" || data[j].月請購 === null || data[j].月請購.toLowerCase() === "null") {
                    hasError = true;
                    rowsCount.push(j);
                } // if
            } // for

            if (hasError) {
                isInvalid_DB.value = true;
                validation_err_msg.value =
                    "Excel " +
                    app.appContext.config.globalProperties.$t("inboundpageLang.row") +
                    " " + rowsCount.map(row => row + 1).join(", ") + " " +
                    "(" + rowsCount.map(row => data[row].料號).join(", ") + ") " +
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

            // ----------------------------------------------
            // validate if all the loc exist
            rowsCount = [];
            for (let j = 0; j < data.length; j++) {
                if (!locsArray.includes(data[j].新儲位)) {
                    hasError = true;
                    rowsCount.push(j);
                } // if
            } // for

            if (hasError) {
                isInvalid_DB.value = true;
                validation_err_msg.value =
                    "Excel " +
                    app.appContext.config.globalProperties.$t("inboundpageLang.row") +
                    " " + rowsCount.map(row => row + 1).join(", ") + " " +
                    "(" + rowsCount.map(row => data[row].新儲位).join(", ") + ") " +
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

            // ----------------------------------------------
            // validate if there's duplicate values in excel
            let duplicatedArray = Array();
            for (let i = 0; i < data.length; i++) {
                if (data[i].料號 != null && data[i].料號.trim() != "") {
                    duplicatedArray.push(data[i].料號.toString().trim() + "_" + data[i].新儲位.toString().trim());
                } // if
            } // for

            let findDuplicatesResult = findDuplicates(duplicatedArray);
            // console.log(findDuplicatesResult); // test
            if (findDuplicatesResult.length > 0) {
                isInvalid_DB.value = true;
                validation_err_msg.value =
                    app.appContext.config.globalProperties.$t("inboundpageLang.repeated_isn_loc_pair") +
                    " : " + findDuplicatesResult.map(entry => entry.split("_")[0] +
                        " (" + entry.split("_")[1] + ")").join(", ");

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
            // check if inbound quantity greater than the intransit quantity
            rowsCount = [];
            for (let i = 0; i < data.length; i++) {
                const 料號 = data[i].料號;
                const 在途量 = parseInt(data[i].在途量);
                if (sumInboundQuantity[料號] > 在途量) {
                    hasError = true;
                    rowsCount.push(i);
                } // if
            } // for

            if (hasError) {
                isInvalid_DB.value = true;
                validation_err_msg.value =
                    "Excel " +
                    app.appContext.config.globalProperties.$t("inboundpageLang.row") +
                    " " + rowsCount.map(row => row + 1).join(", ") + " " +
                    "(" + rowsCount.map(row => data[row].料號).join(", ") + ") " +
                    app.appContext.config.globalProperties.$t("inboundpageLang.inboundnum") + " > " + app.appContext.config.globalProperties.$t("inboundpageLang.transit");

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
            // get existing stock for sum up
            let tempArr_isn = data.map((obj) => {
                return obj.料號;
            });

            let tempArr_loc = data.map((obj) => {
                return obj.新儲位;
            });

            // console.log(tempArr_loc); // test

            let start = Date.now();
            let newStock = data.map((obj) => {
                return [obj.料號, obj.入庫量, obj.新儲位, obj.入庫原因];
            });
            // console.log(newStock); // test
            let newInTransit;
            let result = await getExistingStock(tempArr_isn, tempArr_loc); // get the existing stock
            if (result === "success") {
                for (let i = 0; i < newStock.length; i++) {
                    let foundObj = JSON.parse(mats.value).data.find(
                        (o) => {
                            return (o.料號 === newStock[i][0].trim() && o.儲位 === newStock[i][2].trim());
                        });

                    if (foundObj !== undefined) {
                        newStock[i][1] = parseInt(newStock[i][1]) + parseInt(foundObj.現有庫存);
                    } // if
                } // for

                // convert InTransit to a two-dimensional array and deduct the sumInboundQuantity from it
                newInTransit = JSON.parse(mats_inTransit.value).data.flatMap((obj) => {
                    let value = parseInt(obj.請購數量) - sumInboundQuantity[obj.料號];
                    value = value < 0 ? 0 : value;
                    if (tempArr_isn.includes(obj.料號)) {
                        return [[obj.料號, value]];
                    } else {
                        return [];
                    } // if else
                });
            } // if
            else {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                console.log("Failed to get existing stock");
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

                return;
            } // else
            let timeTaken = Date.now() - start;
            console.log("Total time taken : " + timeTaken + " milliseconds");
            // console.log(newStock); // test
            // console.log(newInTransit); // test

            // actually updating database now
            start = Date.now();
            result = await uploadToDB(newStock, inbound_data, newInTransit);
            timeTaken = Date.now() - start;
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

                data.splice(0); // clean up the data
                queryResult.value = "";
                await getTransit();
                await getLocs();
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

        let locsArray = Array();
        watch(queryResult, async () => {
            await triggerModal();
            if (queryResult.value == "") {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            let allRowsObj = JSON.parse(queryResult.value);
            // console.log(allRowsObj.data.length);
            let allRowsObj2 = JSON.parse(mats_inTransit.value);
            // console.log(allRowsObj2.data); // test
            let sumOfExistingStock;
            let singleEntry = {};

            // get existing stock for sum up
            let tempArr_isn = Array();
            for (let i = 1; i < input_data.length; i++) {
                if (input_data[i][0].trim() != "" && input_data[i][0].trim() != null) {
                    tempArr_isn.push(input_data[i][0].trim());
                } // if
            } // for
            let result = await getExistingStock(tempArr_isn, []); // get the existing stock
            if (result === "success") {
                let tempArr = JSON.parse(mats.value).data;
                // sum the entries with the same 料號                
                sumOfExistingStock = Object.values(tempArr.reduce((acc, curr) => {
                    let item = acc[curr.料號];
                    if (item) {
                        item.現有庫存 += parseInt(curr.現有庫存);
                    } else {
                        curr.現有庫存 = parseInt(curr.現有庫存);
                        acc[curr.料號] = curr;
                    } // if else

                    return acc;
                }, {}));
            } // if
            else {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                console.log("Failed to get existing stock");
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

                return;
            } // else

            for (let i = 1; i < input_data.length; i++) {
                singleEntry.料號 = input_data[i][0].toString().trim();
                tempArr_isn.push(singleEntry.料號);
                singleEntry.入庫量 = Math.abs(parseInt(
                    input_data[i][1]
                ));

                // Check if singleEntry.料號 is found in mats_inTransit
                let foundInAllRowsObj2 = allRowsObj2.data.find(obj => obj.料號 === singleEntry.料號);
                if (foundInAllRowsObj2) {
                    singleEntry.在途量 = parseFloat(foundInAllRowsObj2.請購數量);
                } else {
                    singleEntry.在途量 = 0;
                } // if else

                // Check existing stock
                foundInAllRowsObj2 = sumOfExistingStock.find(obj => obj.料號 === singleEntry.料號);
                if (foundInAllRowsObj2) {
                    singleEntry.現有庫存 = parseFloat(foundInAllRowsObj2.現有庫存);
                } else {
                    singleEntry.原儲位 = "N/A";
                    singleEntry.現有庫存 = 0;
                } // if else

                singleEntry.新儲位 = input_data[i][2].toString().trim();
                singleEntry.入庫原因 = input_data[i][3].toString().trim();
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
                } // if
                else {
                    singleEntry.月請購 = "";
                } // else

                data.push(singleEntry);
                singleEntry = {};
            } // for

            sumInboundQuantity = {};
            sumInboundQuantity = data.reduce((acc, item) => {
                const 料號 = item.料號;
                const 入庫量 = parseInt(item.入庫量);
                if (acc[料號]) {
                    acc[料號] += 入庫量;
                } else {
                    acc[料號] = 入庫量;
                } // if else
                return acc;
            }, {});

            JSON.parse(locations.value).data.forEach(element => {
                locsArray.push(element.儲存位置);
            });

            if (!$("#importedtable").hasClass("show")) {
                $("#togglebtn").click();
            } // if

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
                                '<div class="text-nowrap text-danger CustomScrollbar"' +
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
                        if (row.月請購 === "" || row.月請購 === null || row.月請購.toLowerCase() === "null") { // if isn not exist in consumptive_material table
                            return (
                                '<input type="hidden" id="name' +
                                i +
                                '" name="name' +
                                i +
                                '" value="' +
                                row.品名 +
                                '">' +
                                '<div class="CustomScrollbar text-nowrap text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                app.appContext.config.globalProperties.$t("inboundpageLang.noisn") +
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
                                '<div class="CustomScrollbar text-nowrap"' +
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
                                '<div class="text-nowrap CustomScrollbar text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
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
                        "inboundpageLang.stock"
                    ),
                    field: "庫存",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="stock' +
                            i +
                            '" name="stock' +
                            i +
                            '" value="' +
                            row.現有庫存 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.現有庫存 + '&nbsp;<small>' + row.單位 + '</small>' +
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
                            '<input type="hidden" id="intransit' +
                            i +
                            '" name="intransit' +
                            i +
                            '" value="' +
                            row.在途量 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.在途量 + '&nbsp;<small>' + row.單位 + '</small>' +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.inboundnum"
                    ),
                    field: "入庫量",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        if (sumInboundQuantity[row.料號] > row.在途量) {
                            return (
                                '<input type="hidden" id="inbound' +
                                i +
                                '" name="inbound' +
                                i +
                                '" value="' +
                                row.入庫量 +
                                '">' +
                                '<div class="text-nowrap CustomScrollbar text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.入庫量 + '&nbsp;<small>' + row.單位 + '</small>' +
                                "</div>"
                            );
                        } else {
                            return (
                                '<input type="hidden" id="inbound' +
                                i +
                                '" name="inbound' +
                                i +
                                '" value="' +
                                row.入庫量 +
                                '">' +
                                '<div class="text-nowrap CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.入庫量 + '&nbsp;<small>' + row.單位 + '</small>' +
                                "</div>"
                            );
                        } // if else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.inreason"
                    ),
                    field: "入庫原因",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="oldloc' +
                            i +
                            '" name="oldloc' +
                            i +
                            '" value="' +
                            row.入庫原因 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.入庫原因 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.oldloc"
                    ),
                    field: "原儲位",
                    width: "10ch",
                    sortable: false,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.newloc"
                    ),
                    field: "新儲位",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        if (locsArray.includes(row.新儲位)) {
                            return (
                                '<input type="hidden" id="newloc' +
                                i +
                                '" name="newloc' +
                                i +
                                '" value="' +
                                row.新儲位 +
                                '">' +
                                '<div class="text-nowrap CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.新儲位 +
                                "</div>"
                            );
                        } // if
                        else {
                            return (
                                '<input type="hidden" id="newloc' +
                                i +
                                '" name="newloc' +
                                i +
                                '" value="' +
                                row.新儲位 +
                                '">' +
                                '<div class="text-nowrap CustomScrollbar text-danger"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.新儲位 + " (" + app.appContext.config.globalProperties.$t("inboundpageLang.noloc") +
                                ")</div>"
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

        const table2 = reactive({
            isLoading: true,
            columns: [
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.isn"
                    ),
                    field: "料號",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.料號 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.pName"
                    ),
                    field: "品名",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="CustomScrollbar text-nowrap"' +
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
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.規格 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.stock"
                    ),
                    field: "庫存",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.現有庫存 + '&nbsp;<small>' + row.單位 + '</small>' +
                            "</div>"
                        );
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
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.儲位 +
                            "</div>"
                        );
                    },
                },
            ],
            rows: computed(() => {
                return data2.filter((x) =>
                    x.料號
                        .toLowerCase()
                        .includes(searchTerm2.value.toLowerCase()) ||
                    x.品名
                        .includes(searchTerm2.value)
                );
            }),
            totalRecordCount: computed(() => {
                return table2.rows.length;
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
            exampleUrl,
            isInvalid,
            isInvalid_DB,
            validation_err_msg,
            uploadToDBReady,
            searchTerm,
            searchTerm2,
            table,
            table2,
            modalTitle,
            openLocDetails,
            showFooter,
            onUploadClick,
            onInputChange,
            onSendToDBClick,
            updateCheckedRows,
            deleteRow,
            OutputExcelClick,
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