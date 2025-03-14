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
            <button id="download" name="download" class="col col-auto btn btn-lg btn-success"
                :value="$t('inboundpageLang.download')" @click="OutputExcelClick">
                <i class="bi bi-file-earmark-arrow-down-fill"></i>
            </button>
        </div>
    </div>
    <div class="w-100" style="height: 1ch"></div>
    <!-- </div>breaks cols to a new line-->
    <table-lite :is-fixed-first-column="true" :is-static-mode="true" :isSlotMode="true" :hasCheckbox="false"
        :is-loading="table.isLoading" :messages="table.messages" :columns="table.columns" :rows="table.rows"
        :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
        @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows">
        <template v-slot:月請購="{ row, key }">
            <span v-if="row.月請購 == '是'">{{ $t("basicInfoLang.yes") }}</span>
            <span v-else>{{ $t("basicInfoLang.no") }}</span>
        </template>
    </table-lite>
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
import useInboundStockSearch from "../../composables/InboundStockSearch.ts";

export default defineComponent({
    name: "App",
    components: { TableLite },

    setup() {
        const { mats, getMats } = useInboundStockSearch(); // axios get the mats data
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

        const OutputExcelClick = async () => {
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet(app.appContext.config.globalProperties.$t("inboundpageLang.stockmonth"));

            let today = new Date().toISOString().slice(0, 10);
            // Add header row
            worksheet.addRow([
                app.appContext.config.globalProperties.$t("inboundpageLang.isn"),
                app.appContext.config.globalProperties.$t("inboundpageLang.pName"),
                app.appContext.config.globalProperties.$t("inboundpageLang.format"),
                app.appContext.config.globalProperties.$t("inboundpageLang.nowstock"),
                app.appContext.config.globalProperties.$t("inboundpageLang.monthuse"),
                app.appContext.config.globalProperties.$t("inboundpageLang.stockmonth"),
                app.appContext.config.globalProperties.$t("basicInfoLang.price"),
                app.appContext.config.globalProperties.$t("basicInfoLang.money"),
                app.appContext.config.globalProperties.$t("basicInfoLang.month"),
            ]);

            // Add data rows
            data.forEach(item => {
                worksheet.addRow([
                    item.料號,
                    item.品名,
                    item.規格,
                    `${item.現有庫存} ${item.單位}`,
                    item.月使用量,
                    item.庫存使用月數,
                    item.單價,
                    item.幣別,
                    item.月請購 === '是' ? app.appContext.config.globalProperties.$t("basicInfoLang.yes") : app.appContext.config.globalProperties.$t("basicInfoLang.no")
                ]);
            });

            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: "application/octet-stream" });
            FileSaver.saveAs(blob, `${app.appContext.config.globalProperties.$t("inboundpageLang.stockmonth")}_${today}.xlsx`);

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // OutputExcelClick

        // pour the data in
        const data = reactive([]);
        // const senders = reactive([]); // access the value by senders[0], senders[1] ...
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
                        "inboundpageLang.isn"
                    ),
                    field: "料號",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="isn' +
                            i +
                            '" name="isn' +
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
                        "inboundpageLang.pName"
                    ),
                    field: "品名",
                    width: "14ch",
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
                        "inboundpageLang.format"
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
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.規格 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.nowstock"
                    ),
                    field: "現有庫存",
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
                        "inboundpageLang.monthuse"
                    ),
                    field: "月使用量",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="monthuse' +
                            i +
                            '" name="monthuse' +
                            i +
                            '" value="' +
                            row.月使用量 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.月使用量 + '&nbsp;<small>' + row.單位 + '</small>' +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.stockmonth"
                    ),
                    field: "庫存使用月數",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="monthstock' +
                            i +
                            '" name="monthstock' +
                            i +
                            '" value="' +
                            row.庫存使用月數 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.庫存使用月數 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.price"
                    ),
                    field: "單價",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="price' +
                            i +
                            '" name="price' +
                            i +
                            '" value="' +
                            row.單價 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.單價 +
                            "</div>"
                        );
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
                        return (
                            '<input type="hidden" id="money' +
                            i +
                            '" name="money' +
                            i +
                            '" value="' +
                            row.幣別 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.幣別 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.month"
                    ),
                    field: "月請購",
                    width: "10ch",
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
                order: "月使用量",
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
            updateCheckedRows,
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
