<template>
    <div class="row justify-content-between">
        <div class="row col col-auto">
            <div class="col col-auto">
                <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
            </div>
            <div class="col col-6 p-0 m-0">
                <input id="pnInput" class="text-center form-control form-control-lg"
                    v-bind:placeholder="$t('inboundpageLang.enterisn_or_spec')" v-model="searchTerm" />
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
        :is-loading="table.isLoading" @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows">
        <template v-slot:品名="{ row, key }">
            <input v-model="row.品名" @input="CheckCurrentRow($event);"
                class="form-control text-center align-self-center p-0 m-0" style="width: 13ch;" :id="'pname' + row.id"
                :name="'pname' + key" :value="row.品名" />
        </template>
        <template v-slot:單價_幣別="{ row, key }">
            <!-- DON'T Use input-group here. It messes with the z-index -->
            <div class="row">
                <input v-model="row.單價" @input="CheckCurrentRow($event);"
                    class="form-control text-center align-self-center p-0 m-0 col col-auto"
                    style="width: 8ch; border-bottom-right-radius: 0px !important; border-top-right-radius: 0px !important;"
                    type="number" step="0.001" min="0" :id="'price' + row.id" :name="'price' + key" />
                <select v-model="row.幣別" @input="CheckCurrentRow($event);"
                    style="width: 8ch; border-bottom-left-radius: 0px !important; border-top-left-radius: 0px !important;"
                    class="form-select align-self-center ps-2 p-0 m-0 col col-auto" :id="'money' + row.id"
                    :name="'money' + key">
                    <template v-for="item in currencyDict">
                        <option :selected="row.幣別 === item" :value="item">
                            {{ item }}
                        </option>
                    </template>
                </select>
            </div>
        </template>
        <template v-slot:單位="{ row, key }">
            <input v-model="row.單位" @input="CheckCurrentRow($event);"
                class="form-control text-center align-self-center p-0 m-0" style="width: 5ch;" :id="'unit' + row.id"
                :name="'unit' + key" :value="row.單位" />
        </template>
        <template v-slot:MPQ="{ row, key }">
            <!-- DON'T Use input-group here. It messes with the z-index -->
            <div class="row">
                <input v-model="row.MPQ" @input="CheckCurrentRow($event);"
                    class="form-control text-center p-0 m-0 col col-auto"
                    style="width: 5ch; border-bottom-right-radius: 0px !important; border-top-right-radius: 0px !important;"
                    type="number" min="0" :id="'mpq' + row.id" :name="'mpq' + key" :value="row.MPQ" />
                <small class="input-group-text text-center align-self-center p-0 m-0 col col-auto"
                    style="border-bottom-left-radius: 0px !important; border-top-left-radius: 0px !important;">
                    {{ row.單位 }}
                </small>
            </div>
        </template>
        <template v-slot:MOQ="{ row, key }">
            <!-- DON'T Use input-group here. It messes with the z-index -->
            <div class="row">
                <input v-model="row.MOQ" @input="CheckCurrentRow($event);"
                    class="form-control text-center p-0 m-0 col col-auto"
                    style="width: 5ch; border-bottom-right-radius: 0px !important; border-top-right-radius: 0px !important;"
                    type="number" min="0" :id="'moq' + row.id" :name="'moq' + key" :value="row.MOQ" />
                <small class="input-group-text text-center align-self-center p-0 m-0 col col-auto"
                    style="border-bottom-left-radius: 0px !important; border-top-left-radius: 0px !important;">
                    {{ row.單位 }}
                </small>
            </div>
        </template>
        <template v-slot:LT="{ row, key }">
            <input v-model="row.LT" @input="CheckCurrentRow($event);" class="form-control text-center p-0 m-0"
                style="width: 5ch;" type="number" min="0" :id="'lt' + row.id" :name="'lt' + key"
                :value="Math.round(row.LT)" />
        </template>
        <template v-slot:月請購="{ row, key }">
            <select v-model="row.月請購" @input="CheckCurrentRow($event);" style="width: 7ch;"
                class="col col-auto form-select form-select-lg ps-2 p-0 m-0" :id="'month' + row.id"
                :name="'month' + key">
                <option value="是" :selected="row.月請購 === '是'">{{ $t("basicInfoLang.yes") }}</option>
                <option value="否" :selected="row.月請購 === '否'">{{ $t("basicInfoLang.no") }}</option>
            </select>
        </template>
        <template v-slot:A級資材="{ row, key }">
            <select v-model="row.A級資材" @input="CheckCurrentRow($event);" style="width: 7ch;"
                class="col col-auto form-select form-select-lg ps-2 p-0 m-0" :id="'gradea' + row.id"
                :name="'gradea' + key">
                <option value="是" :selected="row.A級資材 === '是'">{{ $t("basicInfoLang.yes") }}</option>
                <option value="否" :selected="row.A級資材 === '否'">{{ $t("basicInfoLang.no") }}</option>
            </select>
        </template>
        <template v-slot:發料部門="{ row, key }">
            <select v-model="row.發料部門" @input="CheckCurrentRow($event);" style="width: 10ch;"
                class="form-select form-select-lg ps-2 p-0 m-0" :id="'send' + row.id" :name="'send' + key">
                <template v-for="item in senders">
                    <option :selected="row.發料部門 === item" :value="item">
                        {{ item }}
                    </option>
                </template>
            </select>
        </template>
        <template v-slot:安全庫存="{ row, key }">
            <div v-if="row.月請購 === '否'">
                <input v-model="row.安全庫存" :class="{ 'is-invalid': (row.安全庫存 === null) }"
                    class="form-control text-center p-0 m-0" style="width: 8ch;" type="number" min="0"
                    :id="'safe' + row.id" :name="'safe' + key" :value="row.安全庫存" />
            </div>
            <div v-else>
                <input v-model="row.安全庫存" class="form-control text-center p-0 m-0" style="width: 0ch;"
                    :id="'safe' + row.id" :name="'safe' + key" :value="null" hidden />
                {{ $t("basicInfoLang.differ_by_client") }}
            </div>
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
import ExcelJS from 'exceljs';
import FileSaver from "file-saver";
import TableLite from "./TableLite.vue";
import useConsumptiveMaterials from "../../composables/ConsumptiveMaterials.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { mats, queryResult, getMats, deletePN, uploadToDB } = useConsumptiveMaterials(); // axios get the mats data

        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref("");

        onBeforeMount(async () => {
            table.isLoading = true;
            await getMats();
        });

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
        let currencyDict = ref([
            "RMB",
            "USD",
            "JPY",
            "TWD",
            "VND",
            "IDR",
        ]);

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

        function CheckCurrentRow(e) {
            // console.log(e.target.closest('tr').firstChild.firstChild); // test
            if (!e.target.closest('tr').firstChild.firstChild.checked) {
                e.target.closest('tr').firstChild.firstChild.click();
            } // if
        } // CheckCurrentRow

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

            let rows = [];

            for (let i = 0; i < data.length; i++) {
                let tempObj = {};
                tempObj.料號 = data[i].料號;
                tempObj.品名 = data[i].品名;
                tempObj.規格 = data[i].規格;
                tempObj.單價 = parseFloat(data[i].單價.toString());
                tempObj.幣別 = data[i].幣別;
                tempObj.單位 = data[i].單位;
                tempObj.MPQ = parseInt(data[i].MPQ.toString());
                tempObj.MOQ = parseInt(data[i].MOQ.toString());
                tempObj.LT = parseInt(data[i].LT.toString());
                if (data[i].月請購 === '是') {
                    tempObj.月請購 = app.appContext.config.globalProperties.$t("basicInfoLang.yes");
                } else {
                    tempObj.月請購 = app.appContext.config.globalProperties.$t("basicInfoLang.no");
                } // if else

                if (data[i].A級資材 === '是') {
                    tempObj.A級資材 = app.appContext.config.globalProperties.$t("basicInfoLang.yes");
                } else {
                    tempObj.A級資材 = app.appContext.config.globalProperties.$t("basicInfoLang.no");
                } // if else
                tempObj.發料部門 = data[i].發料部門;
                tempObj.安全庫存 = data[i].安全庫存;
                rows.push(tempObj);
            } // for

            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet(app.appContext.config.globalProperties.$t("basicInfoLang.matssearch"));

            worksheet.columns = [
                { header: app.appContext.config.globalProperties.$t("basicInfoLang.isn"), key: '料號', width: 20 },
                { header: app.appContext.config.globalProperties.$t("basicInfoLang.pName"), key: '品名', width: 20 },
                { header: app.appContext.config.globalProperties.$t("basicInfoLang.format"), key: '規格', width: 20 },
                { header: app.appContext.config.globalProperties.$t("basicInfoLang.price"), key: '單價', width: 20 },
                { header: app.appContext.config.globalProperties.$t("basicInfoLang.money"), key: '幣別', width: 20 },
                { header: app.appContext.config.globalProperties.$t("basicInfoLang.unit"), key: '單位', width: 20 },
                { header: app.appContext.config.globalProperties.$t("basicInfoLang.mpq"), key: 'MPQ', width: 20 },
                { header: app.appContext.config.globalProperties.$t("basicInfoLang.moq"), key: 'MOQ', width: 20 },
                { header: app.appContext.config.globalProperties.$t("basicInfoLang.lt"), key: 'LT', width: 20 },
                { header: app.appContext.config.globalProperties.$t("basicInfoLang.month"), key: '月請購', width: 20 },
                { header: app.appContext.config.globalProperties.$t("basicInfoLang.gradea"), key: 'A級資材', width: 20 },
                { header: app.appContext.config.globalProperties.$t("basicInfoLang.senddep"), key: '發料部門', width: 20 },
                { header: app.appContext.config.globalProperties.$t("basicInfoLang.safe"), key: '安全庫存', width: 20 },
            ];

            rows.forEach((row) => {
                worksheet.addRow(row);
            });

            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            FileSaver.saveAs(blob, app.appContext.config.globalProperties.$t("basicInfoLang.matssearch") + "_" + today + ".xlsx");

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        }; // OutputExcelClick

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
            // console.log(checkedRows.length); //test
            if (checkedRows.length <= 0) {
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
            for (let j = 0; j < checkedRows.length && hasError === false; j++) {
                checkedRows[j].料號 = checkedRows[j].料號.toString().trim();
                checkedRows[j].品名 = checkedRows[j].品名.toString().trim();
                checkedRows[j].規格 = checkedRows[j].規格.toString().trim();
                checkedRows[j].單價 = checkedRows[j].單價.toString().trim();
                checkedRows[j].幣別 = checkedRows[j].幣別.toString().trim();
                checkedRows[j].單位 = checkedRows[j].單位.toString().trim();
                checkedRows[j].MPQ = checkedRows[j].MPQ.toString().trim();
                checkedRows[j].MOQ = checkedRows[j].MOQ.toString().trim();
                checkedRows[j].LT = checkedRows[j].LT.toString().trim();
                checkedRows[j].A級資材 = checkedRows[j].A級資材.toString().trim();
                checkedRows[j].月請購 = checkedRows[j].月請購.toString().trim();
                checkedRows[j].發料部門 = checkedRows[j].發料部門.toString().trim();
                if (checkedRows[j].月請購 === '否') {
                    if (checkedRows[j].安全庫存 === null) {
                        hasError = true;
                        validation_err_msg.value =
                            app.appContext.config.globalProperties.$t("basicInfoLang.entersafe") +
                            " ( " + checkedRows[j].料號 + " ) ";
                    } else {
                        checkedRows[j].安全庫存 = checkedRows[j].安全庫存.toString().trim();
                    } // if else
                } else {
                    checkedRows[j].安全庫存 = "null";
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
            for (let j = 0; j < checkedRows.length; j++) {
                pnArray.push(checkedRows[j].料號);
                nameArray.push(checkedRows[j].品名);
                specArray.push(checkedRows[j].規格);
                priceArray.push(checkedRows[j].單價);
                currencyArray.push(checkedRows[j].幣別);
                unitArray.push(checkedRows[j].單位);
                mpqArray.push(checkedRows[j].MPQ);
                moqArray.push(checkedRows[j].MOQ);
                ltArray.push(checkedRows[j].LT);
                gradeaArray.push(checkedRows[j].A級資材);
                monthlyArray.push(checkedRows[j].月請購);
                dispatcherArray.push(checkedRows[j].發料部門);
                safestockArray.push(checkedRows[j].安全庫存);
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
                allRowsObj.datas[i].單價 = parseFloat(allRowsObj.datas[i].單價);
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
                        "basicInfoLang.pName"
                    ),
                    field: "品名",
                    width: "13ch",
                    sortable: true,
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
                            '<div class="CustomScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.規格 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t("basicInfoLang.price"),
                    field: "單價_幣別",
                    width: "16ch",
                    sortable: false,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.unit"
                    ),
                    field: "單位",
                    width: "6ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.mpq"
                    ),
                    field: "MPQ",
                    width: "8ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.moq"
                    ),
                    field: "MOQ",
                    width: "8ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.lt"
                    ),
                    field: "LT",
                    width: "7ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.month"
                    ),
                    field: "月請購",
                    width: "9ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.gradea"
                    ),
                    field: "A級資材",
                    width: "9ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.senddep"
                    ),
                    field: "發料部門",
                    width: "12ch",
                    sortable: true,
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
                    x.規格
                        .includes(searchTerm.value)
                );
            }),
            totalRecordCount: computed(() => {
                return table.rows.length;
            }),
            sortable: {
                order: "料號",
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
            checkedRows = rowsKey;
            // console.log(rowsKey); // test
        };

        return {
            searchTerm,
            table,
            isInvalid_DB,
            validation_err_msg,
            updateCheckedRows,
            CheckCurrentRow,
            deleteRow,
            OutputExcelClick,
            onSendToDBClick,
            currencyDict,
            senders,
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
