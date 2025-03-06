<template>
    <div class="row justify-content-between">
        <div class="row col col-auto">
            <div class="col col-auto">
                <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
            </div>
            <div class="col col-auto p-0 m-0">
                <input id="pnInput" class="text-center form-control form-control-lg"
                    v-bind:placeholder="$t('monthlyPRpageLang.enterisn_or_descr')" v-model="searchTerm" />
            </div>
        </div>
        <div class="col col-auto">
            <button id="download" name="download" class="col col-auto btn btn-lg btn-success"
                :value="$t('monthlyPRpageLang.download')" @click="OutputExcelClick">
                <i class="bi bi-file-earmark-arrow-down-fill fs-4"></i>
            </button>
        </div>
    </div>
    <div class="w-100" style="height: 1ch"></div>
    <!-- </div>breaks cols to a new line-->
    <table-lite :is-fixed-first-column="true" :is-static-mode="true" :hasCheckbox="false" :is-loading="table.isLoading"
        :messages="table.messages" :columns="table.columns" :rows="table.rows" :total="table.totalRecordCount"
        :page-options="table.pageOptions" :sortable="table.sortable" @is-finished="table.isLoading = false"
        @return-checked-rows="updateCheckedRows"></table-lite>
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
import useOutboundBackrecord from "../../composables/OutboundBackRecordSearch.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { mats, getMats } = useOutboundBackrecord(); // axios get the mats data

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
        // const senders = reactive([]); // access the value by senders[0], senders[1] ...

        const OutputExcelClick = async () => {
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            // get today's date for filename
            let today = new Date();
            let dd = String(today.getDate()).padStart(2, '0');
            let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            let yyyy = today.getFullYear();
            today = yyyy + "_" + mm + '_' + dd;

            let rows = Array();
            for (let i = 0; i < data.length; i++) {
                let tempObj = new Object;
                tempObj.isn = data[i].料號;
                tempObj.pName = data[i].品名;
                tempObj.format = data[i].規格;
                tempObj.backReason = data[i].退回原因;
                tempObj.status = data[i].功能狀況;
                tempObj.line = data[i].線別;
                tempObj.backAmount = data[i].預退數量 + " " + data[i].單位;
                tempObj.realBackAmount = data[i].實際退回數量 + " " + data[i].單位;
                tempObj.backDiffReason = data[i].實退差異原因;
                tempObj.loc = data[i].儲位;
                tempObj.receivePeople = data[i].收料人員;
                tempObj.backPeople = data[i].退料人員;
                tempObj.backListNum = data[i].退料單號;
                tempObj.openTime = data[i].開單時間;
                tempObj.sender = data[i].開單人員;
                tempObj.inboundTime = data[i].入庫時間;
                tempObj.mark = data[i].備註;
                rows.push(tempObj);
            }

            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet(app.appContext.config.globalProperties.$t("outboundpageLang.backrecord"));

            worksheet.columns = [
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.isn"), key: 'isn' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.pName"), key: 'pName' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.format"), key: 'format' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.backreason"), key: 'backReason' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.status"), key: 'status' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.line"), key: 'line' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.backamount"), key: 'backAmount' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.realbackamount"), key: 'realBackAmount' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.backdiffreason"), key: 'backDiffReason' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.loc"), key: 'loc' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.receivepeople"), key: 'receivePeople' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.backpeople"), key: 'backPeople' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.backlistnum"), key: 'backListNum' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.opentime"), key: 'openTime' },
                { header: app.appContext.config.globalProperties.$t("monthlyPRpageLang.pr_sender"), key: 'sender' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.inboundtime"), key: 'inboundTime' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.mark"), key: 'mark' }
            ];

            worksheet.addRows(rows);

            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            FileSaver.saveAs(blob, app.appContext.config.globalProperties.$t("outboundpageLang.backrecord") + "_" + today + ".xlsx");

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        }

        watch(mats, () => {
            let allRowsObj = JSON.parse(mats.value);
            // console.log(allRowsObj.datas); // test
            for (let i = 0; i < allRowsObj.datas.length; i++) {
                data.push(allRowsObj.datas[i]);
            } // for

            table.isLoading = false;
        }); // watch for data change

        // Table config
        const table = reactive({
            isLoading: true,
            columns: [
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.isn"
                    ),
                    field: "料號",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%; user-select: text; z-index: 1; position: relative;">' +
                            row.料號 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.pName"
                    ),
                    field: "品名",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.品名 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.format"
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
                        "outboundpageLang.backreason"
                    ),
                    field: "退回原因",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.退回原因 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.status"
                    ),
                    field: "功能狀況",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.功能狀況 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.line"
                    ),
                    field: "線別",
                    width: "8ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.線別 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.backamount"
                    ),
                    field: "預退數量",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.預退數量 + '&nbsp;<small>' + row.單位 + '</small>' +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.realbackamount"
                    ),
                    field: "實際退回數量",
                    width: "15ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.實際退回數量 + '&nbsp;<small>' + row.單位 + '</small>' +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.backdiffreason"
                    ),
                    field: "實退差異原因",
                    width: "23ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.實退差異原因 === null) row.實退差異原因 = "";
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.實退差異原因 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.loc"
                    ),
                    field: "儲位",
                    width: "10ch",
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
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.receivepeople"
                    ),
                    field: "收料人員",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.收料人員 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.backpeople"
                    ),
                    field: "退料人員",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.退料人員 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.backlistnum"
                    ),
                    field: "退料單號",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.退料單號 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.opentime"
                    ),
                    field: "開單時間",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.開單時間 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.pr_sender"
                    ),
                    field: "開單人員",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.開單人員 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.inboundtime"
                    ),
                    field: "入庫時間",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.入庫時間 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.mark"
                    ),
                    field: "備註",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.備註 === null) row.備註 = "";
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.備註 +
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
                order: "開單時間",
                sort: "desc",
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
            console.log(rowsKey);
        };
        return {
            searchTerm,
            table,
            OutputExcelClick,
            updateCheckedRows,
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