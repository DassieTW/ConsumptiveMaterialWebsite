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
                    &nbsp;
                    <button id="download" name="download" class="col col-auto btn btn-lg btn-success"
                        :value="$t('monthlyPRpageLang.download')" @click="OutputExcelClick">
                        <i class="bi bi-file-earmark-arrow-down-fill fs-4"></i>
                    </button>
                </div>
            </div>
            <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
            <span v-if="isInvalid_DB" class="invalid-feedback d-block" role="alert">
                <strong>{{ validation_err_msg }}</strong>
            </span>
            <table-lite :is-fixed-first-column="true" :is-static-mode="true" :isSlotMode="true" :hasCheckbox="true"
                :isLoading="table.isLoading" :messages="table.messages" :columns="table.columns" :rows="table.rows"
                :rowClasses="table.rowClasses" :total="table.totalRecordCount" :page-options="table.pageOptions"
                :sortable="table.sortable" @is-finished="table.isLoading = false"
                @return-checked-rows="updateCheckedRows">
                <template v-slot:單耗="{ row, key }">
                    <div class="input-group m-0 p-0">
                        <input style="width:11ch;" type="number" :id="'unitConsumption' + row.id"
                        :name="'unitConsumption' + row.id" :value="ScientificNotaionToFixed(parseFloat(row.單耗))"
                        v-model="row.單耗" @input="CheckCurrentRow($event);" class="form-control text-center p-0 m-0"
                        step="0.000001" min="0">
                        <small class="input-group-text text-center p-0 m-0">{{ row.單位 }}</small>
                    </div>
                </template>
            </table-lite>

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

        const { mats, mails, recordCount, getAll, getCheckersMails, uploadToDB, deleteUC } = useUnitConsumptionSearch();

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

        const OutputExcelClick = async () => {
            await triggerModal();
            await getAll();
            // get today's date for filename
            let today = new Date();
            let dd = String(today.getDate()).padStart(2, '0');
            let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            let yyyy = today.getFullYear();
            today = yyyy + "_" + mm + '_' + dd;

            let rows = Array();
            for (let i = 0; i < data.length; i++) {
                let tempObj = new Object;
                tempObj[app.appContext.config.globalProperties.$t("monthlyPRpageLang.isn")] = data[i].料號;
                tempObj[app.appContext.config.globalProperties.$t("monthlyPRpageLang.90isn")] = data[i].料號90;
                tempObj[app.appContext.config.globalProperties.$t("monthlyPRpageLang.pName")] = data[i].品名;
                tempObj[app.appContext.config.globalProperties.$t("monthlyPRpageLang.format")] = data[i].規格;
                tempObj[app.appContext.config.globalProperties.$t("monthlyPRpageLang.consume")] = data[i].單耗;
                tempObj[app.appContext.config.globalProperties.$t("monthlyPRpageLang.email")] = data[i].畫押信箱;
                if (data[i].狀態 === "已完成") {
                    tempObj[app.appContext.config.globalProperties.$t("monthlyPRpageLang.status")] = app.appContext.config.globalProperties.$t("monthlyPRpageLang.review_complete");
                } // if
                else { // 待畫押, 待重畫
                    tempObj[app.appContext.config.globalProperties.$t("monthlyPRpageLang.status")] = app.appContext.config.globalProperties.$t("monthlyPRpageLang.review_pending");
                } // else

                rows.push(tempObj);
            } // for

            const worksheet = XLSX.utils.json_to_sheet(rows);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, app.appContext.config.globalProperties.$t("monthlyPRpageLang.consume"));
            XLSX.writeFile(workbook,
                app.appContext.config.globalProperties.$t(
                    "monthlyPRpageLang.consume"
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

        const deleteRow = async () => {
            await triggerModal();
            let pnArray = [];
            let pn90Array = [];
            for (let i = 0; i < checkedRows.length; i++) {
                pnArray.push(checkedRows[i].料號);
                pn90Array.push(checkedRows[i].料號90);
            } // for

            let result = await deleteUC(pnArray, pn90Array);
            if (result === "success") {
                notyf.open({
                    type: "success",
                    message: app.appContext.config.globalProperties.$t("monthlyPRpageLang.total") + " " + JSON.parse(recordCount.value).record + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.record") + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.delete") + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.success"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });

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

        const onSendToDBClick = async () => {
            // console.log(selected_mail.value); // test
            await triggerModal();
            // console.log("The modal should be triggered by now."); // test
            isInvalid_DB.value = false;
            let rowsCount = 0;
            let hasError = false;
            // console.log(data.length); //test
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

            // prepare the data arrays to be sent
            let pnArray = [];
            let pn90Array = [];
            let ucArray = [];
            for (let j = 0; j < checkedRows.length; j++) {
                pnArray.push(checkedRows[j].料號);
                pn90Array.push(checkedRows[j].料號90);
                ucArray.push(checkedRows[j].單耗.toString());
            } // for

            // console.log(ucArray); // test
            // actually updating database now
            let start = Date.now();
            let result = await uploadToDB(pnArray, pn90Array, ucArray, selected_mail.value);
            let timeTaken = Date.now() - start;
            console.log("Total time taken : " + timeTaken + " milliseconds");
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");

            await getAll();

            if (result === "success") {
                notyf.open({
                    type: "success",
                    message: app.appContext.config.globalProperties.$t("monthlyPRpageLang.total") + " " + JSON.parse(recordCount.value).record + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.record") + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.change") + " " + app.appContext.config.globalProperties.$t("monthlyPRpageLang.success"),
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
            uploadToDBReady.value = false;
            selected_mail.value = "";
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
            table.isLoading = false;
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

        function ScientificNotaionToFixed(x) {
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

        function CheckCurrentRow(e) {
            // console.log(e.target.closest('tr').firstChild.firstChild); // test
            if (!e.target.closest('tr').firstChild.firstChild.checked) {
                e.target.closest('tr').firstChild.firstChild.click();
            } // if
        } // CheckCurrentRow

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
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.90isn"
                    ),
                    field: "料號90",
                    width: "15ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="number90' +
                            i +
                            '" name="number90' +
                            i +
                            '" value="' +
                            row.料號90 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.料號90 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.consume"
                    ),
                    field: "單耗",
                    width: "12ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.pName"
                    ),
                    field: "品名",
                    width: "12ch",
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
                            '<div class="CustomScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.品名 +
                            "</div>"
                        );
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
                        return (
                            '<input type="hidden" id="format' +
                            i +
                            '" name="format' +
                            i +
                            '" value="' +
                            row.規格 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important;">' +
                            row.規格 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.surepeopleemail"
                    ),
                    field: "畫押信箱",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important;">' +
                            row.畫押信箱 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.remark"
                    ),
                    field: "狀態",
                    width: "8ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.狀態 === "已完成") {
                            return (
                                '<div class="text-nowrap CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                app.appContext.config.globalProperties.$t("monthlyPRpageLang.review_complete") +
                                "</div>"
                            );
                        } // if
                        else { // 待畫押, 待重畫
                            return (
                                '<div class="text-nowrap CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                app.appContext.config.globalProperties.$t("monthlyPRpageLang.review_pending") +
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
                        .includes(searchTerm.value.toLowerCase()) ||
                    x.料號90
                        .toLowerCase()
                        .includes(searchTerm.value.toLowerCase()) ||
                    x.品名
                        .includes(searchTerm.value)
                );
            }),
            rowClasses: function (x) {
                if (x.狀態 === "已完成") {
                    return ["table-success"];
                } // if
                else {
                    return ["table-danger"];
                } // else
            },
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
            onSendToDBClick,
            deleteRow,
            ScientificNotaionToFixed,
            CheckCurrentRow,
            OutputExcelClick
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