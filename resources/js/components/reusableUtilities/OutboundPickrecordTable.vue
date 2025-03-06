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
import useOutboundPickrecord from "../../composables/OutboundPickRecordSearch.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { mats, getMats } = useOutboundPickrecord(); // axios get the mats data

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

            let workbook = new ExcelJS.Workbook();
            let worksheet = workbook.addWorksheet(app.appContext.config.globalProperties.$t("outboundpageLang.pickrecord"));

            worksheet.columns = [
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.isn"), key: '料號', width: 20 },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.pName"), key: '品名', width: 20 },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.format"), key: '規格', width: 20 },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.usereason"), key: '領用原因', width: 20 },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.line"), key: '線別', width: 20 },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.pickamount"), key: '預領數量', width: 20 },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.realpickamount"), key: '實際領用數量', width: 20 },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.diffreason"), key: '實領差異原因', width: 20 },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.loc"), key: '儲位', width: 20 },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.pickpeople"), key: '領料人員', width: 20 },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.sendpeople"), key: '發料人員', width: 20 },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.picklistnum"), key: '領料單號', width: 20 },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.opentime"), key: '開單時間', width: 20 },
                { header: app.appContext.config.globalProperties.$t("monthlyPRpageLang.pr_sender"), key: '開單人員', width: 20 },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.outboundtime"), key: '出庫時間', width: 20 },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.mark"), key: '備註', width: 20 }
            ];

            data.forEach(item => {
                worksheet.addRow({
                    料號: item.料號,
                    品名: item.品名,
                    規格: item.規格,
                    領用原因: item.領用原因,
                    線別: item.線別,
                    預領數量: item.預領數量 + " " + item.單位,
                    實際領用數量: item.實際領用數量 + " " + item.單位,
                    實領差異原因: item.實領差異原因,
                    儲位: item.儲位,
                    領料人員: item.領料人員,
                    發料人員: item.發料人員,
                    領料單號: item.領料單號,
                    開單時間: item.開單時間,
                    開單人員: item.開單人員,
                    出庫時間: item.出庫時間,
                    備註: item.備註
                });
            });

            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            FileSaver.saveAs(blob, app.appContext.config.globalProperties.$t("outboundpageLang.pickrecord") + "_" + today + ".xlsx");

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // OutputExcelClick

        watch(mats, () => {
            // console.log(JSON.parse(mats.value)); // test
            let allRowsObj = JSON.parse(mats.value);
            //console.log(allRowsObj.datas.length);
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
                        "outboundpageLang.usereason"
                    ),
                    field: "領用原因",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.領用原因 +
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
                        "outboundpageLang.pickamount"
                    ),
                    field: "預領數量",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.預領數量 + '&nbsp;<small>' + row.單位 + '</small>' +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.realpickamount"
                    ),
                    field: "實際領用數量",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.實際領用數量 + '&nbsp;<small>' + row.單位 + '</small>' +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.diffreason"
                    ),
                    field: "實領差異原因",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.實領差異原因 === null) row.實領差異原因 = "";
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.實領差異原因 +
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
                        "outboundpageLang.pickpeople"
                    ),
                    field: "領料人員",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.領料人員 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.sendpeople"
                    ),
                    field: "發料人員",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.發料人員 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.picklistnum"
                    ),
                    field: "領料單號",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.領料單號 +
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
                        "outboundpageLang.outboundtime"
                    ),
                    field: "出庫時間",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.出庫時間 +
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