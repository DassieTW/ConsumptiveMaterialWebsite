<template>
    <div class="row justify-content-between">
        <div class="row col col-auto">
            <div class="col col-auto">
                <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
            </div>
            <div class="col col-6 p-0 m-0">
                <input id="pnInput" class="text-center form-control form-control-lg"
                    v-bind:placeholder="$t('monthlyPRpageLang.enterisn_or_descr')" v-model="searchTerm" />
            </div>
        </div>
        <div class="col col-auto">
            <button type="submit" id="delete" name="delete" class="col col-auto btn btn-lg btn-danger"
                @click="deleteRow">
                <i class="bi bi-trash3-fill fs-4"></i>
            </button>
            &nbsp;
            <button id="download" name="download" class="col col-auto btn btn-lg btn-success" @click="OutputExcelClick">
                <i class="bi bi-file-earmark-arrow-down-fill fs-4"></i>
            </button>
        </div>
    </div>
    <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
    <span v-if="isInvalid_DB" class="invalid-feedback d-block" role="alert">
        <strong>{{ validation_err_msg }}</strong>
    </span>
    <table-lite id="searchTable" :is-fixed-first-column="true" :isStaticMode="true" :isSlotMode="true"
        :hasCheckbox="true" :messages="table.messages" :columns="table.columns" :rows="table.rows"
        :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
        @do-search="doSearch" @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows"
        @row-input="rowUserInput">
        <template v-slot:月請購="{ row, key }">
            <div v-if="row.月請購 === '是'">
                <select @change="(event) => { (row.月請購 = event.target.value); rowUserInput(row, key); }"
                    style="width: 7ch;" class="col col-auto form-select form-select-lg ps-2 p-0 m-0"
                    :id="'month' + row.id" :name="'month' + key">
                    <option value="是" selected>{{ $t("basicInfoLang.yes") }}</option>
                    <option value="否">{{ $t("basicInfoLang.no") }}</option>
                </select>
            </div>
            <div v-else>
                <select @change="(event) => { (row.月請購 = event.target.value); rowUserInput(row, key); }"
                    style="width: 7ch;" class="col col-auto form-select form-select-lg ps-2 p-0 m-0"
                    :id="'month' + row.id" :name="'month' + key">
                    <option value="是">{{ $t("basicInfoLang.yes") }}</option>
                    <option value="否" selected>{{ $t("basicInfoLang.no") }}</option>
                </select>
            </div>
        </template>

        <template v-slot:安全庫存="{ row, key }">
            <div v-if="row.月請購 === '否'">
                <input @change="rowUserInput(row, key)" :class="{ 'is-invalid': (row.安全庫存 === null) }"
                    class="form-control text-center p-0 m-0" style="width: 8ch;" type="number" :id="'safe' + row.id"
                    :name="'safe' + key" :value="row.安全庫存" />
            </div>
            <div v-else>{{ $t("basicInfoLang.differ_by_client") }}</div>
        </template>
    </table-lite>
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <div class="row justify-content-center">
        <div class="col col-auto">
            <button type="submit" id="change" name="change" class="col col-auto fs-3 text-center btn btn-lg btn-info"
                @click="onSendToDBClick">
                <i class="bi bi-cloud-upload-fill"></i>
                {{ $t('basicInfoLang.change') }}
            </button>
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
        const { mats, queryResult, getMats, deletePN, uploadToDB } = useConsumptiveMaterials(); // axios get the mats data

        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref("");

        onBeforeMount(getMats);

        const searchTerm = ref(""); // Search text
        const app = getCurrentInstance(); // get the current instance
        let thisHtmlLang = document
            .getElementsByTagName("HTML")[0]
            .getAttribute("lang");
        // get the current locale from html tag
        app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

        // pour the data in
        const data = reactive([]);
        const senders = reactive([]); // access the value by senders[0], senders[1] ...
        let checkedRows = [];

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

        const deleteRow = async () => {
            let isn = [];

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
                isn.push(checkedRows[i].料號);
            } // for

            // console.log(isn); // test
            await triggerModal();
            let result = await deletePN(isn);
            if (result === "success") {
                for (let i = 0; i < checkedRows.length; i++) {
                    let indexOfObject = data.findIndex(object => {
                        return parseInt(object.id) === parseInt(checkedRows[i].id);
                    });

                    if (indexOfObject != -1) {
                        data.splice(indexOfObject, 1);
                    } // if
                } // for

                notyf.open({
                    type: "success",
                    message: app.appContext.config.globalProperties.$t("monthlyPRpageLang.change") + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.success"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });

                document.querySelectorAll('.vtl-tbody-checkbox').forEach(el => el.checked = false);

                if (document.querySelector(".vtl-thead-checkbox").checked) {
                    document.querySelector(".vtl-thead-checkbox").click();
                } // if
                checkedRows = [];
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

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // deleteRow

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
                tempObj.單價 = parseFloat(data[i].單價.toString());
                tempObj.幣別 = data[i].幣別;
                tempObj.單位 = data[i].單位;
                tempObj.MPQ = parseInt(data[i].MPQ.toString());
                tempObj.MOQ = parseInt(data[i].MOQ.toString());
                tempObj.LT = parseInt(data[i].LT.toString());
                tempObj.月請購 = data[i].月請購;
                tempObj.A級資材 = data[i].A級資材;
                tempObj.發料部門 = data[i].發料部門;
                tempObj.安全庫存 = data[i].安全庫存;
                rows.push(tempObj);
            } // for

            const worksheet = XLSX.utils.json_to_sheet(rows);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, app.appContext.config.globalProperties.$t("basicInfoLang.matssearch"));
            XLSX.writeFile(workbook,
                app.appContext.config.globalProperties.$t(
                    "basicInfoLang.matssearch"
                ) + "_" + today + ".xlsx", { compression: true });

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // OutputExcelClick

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
            // trim the white spaces and validate safestock if non-monthly
            for (let j = 0; j < data.length && hasError === false; j++) {
                data[j].料號 = data[j].料號.toString().trim();
                data[j].品名 = data[j].品名.toString().trim();
                data[j].規格 = data[j].規格.toString().trim();
                data[j].單價 = data[j].單價.toString().trim();
                data[j].幣別 = data[j].幣別.toString().trim();
                data[j].單位 = data[j].單位.toString().trim();
                data[j].MPQ = data[j].MPQ.toString().trim();
                data[j].MOQ = data[j].MOQ.toString().trim();
                data[j].LT = data[j].LT.toString().trim();
                data[j].A級資材 = data[j].A級資材.toString().trim();
                data[j].月請購 = data[j].月請購.toString().trim();
                data[j].發料部門 = data[j].發料部門.toString().trim();
                if (data[j].月請購 === '否') {
                    if (data[j].安全庫存 === null) {
                        hasError = true;
                        validation_err_msg.value =
                            app.appContext.config.globalProperties.$t("basicInfoLang.entersafe") +
                            " ( " + data[j].料號 + " ) ";
                    } else {
                        data[j].安全庫存 = data[j].安全庫存.toString().trim();
                    } // if else
                } else {
                    data[j].安全庫存 = "null";
                } // if else
            } // for

            if (hasError) {
                isInvalid_DB.value = true;
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
                gradeaArray.push(data[j].A級資材);
                monthlyArray.push(data[j].月請購);
                dispatcherArray.push(data[j].發料部門);
                safestockArray.push(data[j].安全庫存);
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
                notyf.open({
                    type: "success",
                    message: app.appContext.config.globalProperties.$t("monthlyPRpageLang.total") + " " + JSON.parse(queryResult.value).record + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.record") + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.change") + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.success"),
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
            // console.log(JSON.parse(mats.value)); // test
            let allRowsObj = JSON.parse(mats.value);
            // console.log(allRowsObj.datas.length);
            for (let i = 0; i < allRowsObj.senders.length; i++) {
                senders.push(allRowsObj.senders[i]);
            } // for

            for (let i = 0; i < allRowsObj.datas.length; i++) {
                allRowsObj.datas[i].id = i;
                data.push(allRowsObj.datas[i]);
            } // for

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
            table.isLoading = false;
        }); // watch for data change

        // Table config
        const table = reactive({
            isLoading: true,
            columns: [
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.isn"
                    ),
                    field: "料號",
                    width: "14ch",
                    sortable: true,
                    isKey: true,
                    display: function (row, i) {
                        // console.log(row);
                        return (
                            '<input type="hidden" id="number' +
                            row.id +
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
                        return (
                            '<input type="hidden" id="name' +
                            i +
                            '" name="name' +
                            i +
                            '" value="' +
                            row.品名 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.品名 +
                            "</div>"
                        );
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
                        return (
                            '<input type="hidden" id="format' +
                            i +
                            '" name="format' +
                            i +
                            '" value="' +
                            row.規格 +
                            '">' +
                            '<div class="CustomScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.規格 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t("basicInfoLang.price") +
                        " & " + app.appContext.config.globalProperties.$t("basicInfoLang.money"),
                    field: "單價_幣別",
                    width: "19ch",
                    sortable: false,
                    hasInput: function (row, i) {
                        let returnStr = "";
                        let currencyDict = [
                            "RMB",
                            "USD",
                            "JPY",
                            "TWD",
                            "VND",
                            "IDR",
                        ];

                        returnStr +=
                            '<div class="row">' +
                            '<input style="width: 7ch;" type="number" step="0.00001" id="price' +
                            row.id +
                            '"' +
                            ' class="col form-control text-center align-self-center p-0 m-0" name="price' +
                            i +
                            '"' +
                            ' value="' +
                            parseFloat(row.單價) +
                            '">';

                        returnStr +=
                            '<select style="width: 8ch;" class="col form-select form-select-lg ps-2 p-0 m-0" id="money' +
                            row.id +
                            '" name="money' +
                            i +
                            '">';

                        currencyDict.forEach((element) => {
                            if (row.幣別 === element) {
                                returnStr +=
                                    '<option ' + 'value="' + element + '" selected>' + element + '</option>';
                            } // if
                            else {
                                returnStr += '<option ' + 'value="' + element + '">' + element + "</option>";
                            } // else
                        }); // for each in sender array
                        returnStr += "</select></div>";
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
                    hasInput: function (row, i) {
                        return (
                            '<input style="width:5ch;" type="text" id="unit' +
                            row.id +
                            '"' +
                            ' name="unit' +
                            i +
                            '" value="' +
                            row.單位 +
                            '"' +
                            ' class="form-control text-center p-0 m-0">'
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.mpq"
                    ),
                    field: "MPQ",
                    width: "9ch",
                    sortable: true,
                    hasInput: function (row, i) {
                        return (
                            '<div class="row">' +
                            '<input style="width:5ch;" type="number" id="mpq' +
                            row.id +
                            '"' +
                            ' name="mpq' +
                            i +
                            '" value="' +
                            row.MPQ +
                            '"' +
                            ' class="form-control text-center col p-0 m-0" min="0">' +
                            '<div class="col input-group-text overflow-scroll py-0 px-1 m-0" style="width: 4ch;">' +
                            "<small>" + row.單位 + "</small>" +
                            '</div></div>'
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.moq"
                    ),
                    field: "MOQ",
                    width: "9ch",
                    sortable: true,
                    hasInput: function (row, i) {
                        return (
                            '<div class="row">' +
                            '<input style="width:5ch;" type="number" id="moq' +
                            row.id +
                            '"' +
                            ' name="moq' +
                            i +
                            '" value="' +
                            row.MOQ +
                            '"' +
                            ' class="form-control text-center col p-0 m-0" min="0">' +
                            '<div class="col input-group-text overflow-scroll py-0 px-1 m-0" style="width: 4ch;">' +
                            "<small>" + row.單位 + "</small>" +
                            '</div></div>'
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.lt"
                    ),
                    field: "LT",
                    width: "8ch",
                    sortable: true,
                    hasInput: function (row, i) {
                        return (
                            '<input style="width:8ch;" type="number" id="lt' +
                            row.id +
                            '"' +
                            ' name="lt' +
                            i +
                            '" value="' +
                            Math.round(row.LT) +
                            '"' +
                            ' class="form-control text-center p-0 m-0" min="0">'
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.month"
                    ),
                    field: "月請購",
                    width: "12ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.gradea"
                    ),
                    field: "A級資材",
                    width: "9ch",
                    sortable: true,
                    hasInput: function (row, i) {
                        let returnStr = "";
                        // console.log(row); // test
                        if (row.A級資材 === "是") {
                            returnStr =
                                '<select style="width: 7ch;" class="col col-auto form-select form-select-lg ps-2 p-0 m-0"' +
                                ' id="gradea' +
                                row.id +
                                '" name="gradea' +
                                i +
                                '">' +
                                '<option value="是" selected>' +
                                app.appContext.config.globalProperties.$t(
                                    "basicInfoLang.yes"
                                ) +
                                "</option>" +
                                '<option value="否">' +
                                app.appContext.config.globalProperties.$t(
                                    "basicInfoLang.no"
                                ) +
                                "</option>" +
                                "</select>";
                        } // if
                        else {
                            returnStr =
                                '<select style="width: 7ch;" class="col col-auto form-select form-select-lg ps-2 p-0 m-0"' +
                                ' id="gradea' +
                                row.id +
                                '" name="gradea' +
                                i +
                                '">' +
                                '<option value="是">' +
                                app.appContext.config.globalProperties.$t(
                                    "basicInfoLang.yes"
                                ) +
                                "</option>" +
                                '<option value="否" selected>' +
                                app.appContext.config.globalProperties.$t(
                                    "basicInfoLang.no"
                                ) +
                                "</option>" +
                                "</select>";
                        } // else

                        return returnStr;
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.senddep"
                    ),
                    field: "發料部門",
                    width: "10ch",
                    sortable: true,
                    hasInput: function (row, i) {
                        let returnStr = "";
                        returnStr +=
                            '<select style="width: 10ch;" class="form-select form-select-lg ps-2 p-0 m-0" id="send' +
                            row.id +
                            '" name="send' +
                            i +
                            '">';
                        senders.forEach((element) => {
                            if (row.發料部門 === element) {
                                returnStr +=
                                    '<option ' + 'value="' + element + '" selected>' + element + '</option>';
                            } // if
                            else {
                                returnStr += '<option ' + 'value="' + element + '">' + element + "</option>";
                            } // else
                        }); // for each in sender array
                        returnStr += "</select>";
                        return returnStr; // return
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.safe"
                    ),
                    field: "安全庫存",
                    width: "13ch",
                    sortable: true,
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

        const updateCheckedRows = (rowsKey) => {
            // console.log(rowsKey); // test
            checkedRows = rowsKey;
        };

        const rowUserInput = (row, rowNum) => {
            // console.log(document.getElementById("unitConsumption" + rowNum).value);
            data[row.id].單價 = document.getElementById("price" + row.id).value;
            data[row.id].幣別 = document.getElementById("money" + row.id).value;
            data[row.id].單位 = document.getElementById("unit" + row.id).value;
            data[row.id].MPQ = document.getElementById("mpq" + row.id).value;
            data[row.id].MOQ = document.getElementById("moq" + row.id).value;
            data[row.id].LT = document.getElementById("lt" + row.id).value;
            data[row.id].月請購 = document.getElementById("month" + row.id).value;
            data[row.id].A級資材 = document.getElementById("gradea" + row.id).value;
            data[row.id].發料部門 = document.getElementById("send" + row.id).value;
            if (document.getElementById("safe" + row.id) == null) {
                data[row.id].安全庫存 = null;
            } else {
                data[row.id].安全庫存 = document.getElementById("safe" + row.id).value;
            } // if else

            // console.log(data); // test
        };

        return {
            searchTerm,
            table,
            isInvalid_DB,
            validation_err_msg,
            updateCheckedRows,
            deleteRow,
            OutputExcelClick,
            rowUserInput,
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
