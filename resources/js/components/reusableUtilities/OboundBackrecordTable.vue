<template>
    <div class="row" style="text-align: left">
        <div class="col col-auto">
            <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
        </div>
        <div class="col col-3 p-0 m-0">
            <input id="pnInput" class="text-center form-control form-control-lg"
                v-bind:placeholder="$t('monthlyPRpageLang.enterisn_or_descr')" v-model="searchTerm" />
        </div>
    </div>
    <div class="w-100" style="height: 1ch"></div>
    <!-- </div>breaks cols to a new line-->
    <table-lite :is-fixed-first-column="true" :is-static-mode="true" :hasCheckbox="false" :isLoading="table.isLoading"
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
import TableLite from "./TableLite.vue";
import useOboundBackrecord from "../../composables/OboundBackRecordSearch.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { mats, getMats } = useOboundBackrecord(); // axios get the mats data

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
        // const senders = reactive([]); // access the value by senders[0], senders[1] ...

        watch(mats, () => {
            console.log(JSON.parse(mats.value)); // test
            let allRowsObj = JSON.parse(mats.value);
            //console.log(allRowsObj.datas.length);
            for (let i = 0; i < allRowsObj.datas.length; i++) {
                data.push(allRowsObj.datas[i]);
            } // for

            document
                .getElementById("QueryFlag")
                .click();
        }); // watch for data change

        // Table config
        const table = reactive({
            isLoading: false,
            columns: [
                {
                    label: app.appContext.config.globalProperties.$t("oboundpageLang.client"),
                    field: "客戶別",
                    width: "12ch",
                    sortable: true,
                    isKey: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="client' +
                            i +
                            '" name="client' +
                            i +
                            '" value="' +
                            row.客戶別 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.客戶別 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.machine"
                    ),
                    field: "機種",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="machine' +
                            i +
                            '" name="machine' +
                            i +
                            '" value="' +
                            row.機種 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.機種 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.process"
                    ),
                    field: "製程",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="production' +
                            i +
                            '" name="production' +
                            i +
                            '" value="' +
                            row.製程 +
                            '">' +
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.製程 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.backreason"
                    ),
                    field: "退回原因",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="backreason' +
                            i +
                            '" name="backreason' +
                            i +
                            '" value="' +
                            row.退回原因 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.退回原因 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.line"
                    ),
                    field: "線別",
                    width: "9ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="line' +
                            i +
                            '" name="line' +
                            i +
                            '" value="' +
                            row.線別 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.線別 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.isn"
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
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.料號 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.pName"
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
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.品名 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.format"
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
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.規格 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.backamount"
                    ),
                    field: "預退數量",
                    width: "15ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="backamount' +
                            i +
                            '" name="backamount' +
                            i +
                            '" value="' +
                            row.預退數量 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.預退數量 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.realbackamount"
                    ),
                    field: "實際退回數量",
                    width: "22ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="realbackamount' +
                            i +
                            '" name="realbackamount' +
                            i +
                            '" value="' +
                            row.實際退回數量 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.實際退回數量 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.mark"
                    ),
                    field: "備註",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.備註 === null) row.備註 = "";
                        return (
                            '<input type="hidden" id="remark' +
                            i +
                            '" name="remark' +
                            i +
                            '" value="' +
                            row.備註 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.備註 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.backdiffreason"
                    ),
                    field: "實退差異原因",
                    width: "25ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.實退差異原因 === null) row.實退差異原因 = "";
                        return (
                            '<input type="hidden" id="backdiffreason' +
                            i +
                            '" name="backdiffreason' +
                            i +
                            '" value="' +
                            row.實退差異原因 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.實退差異原因 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.bound"
                    ),
                    field: "庫別",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="bound' +
                            i +
                            '" name="bound' +
                            i +
                            '" value="' +
                            row.庫別 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.庫別 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.receivepeople"
                    ),
                    field: "收料人員",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="receivepeople' +
                            i +
                            '" name="receivepeople' +
                            i +
                            '" value="' +
                            row.收料人員 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.收料人員 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.receivepeoplenum"
                    ),
                    field: "收料人員工號",
                    width: "17ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="receivepeoplenum' +
                            i +
                            '" name="receivepeoplenum' +
                            i +
                            '" value="' +
                            row.收料人員工號 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.收料人員工號 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.backpeople"
                    ),
                    field: "退料人員",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="backpeople' +
                            i +
                            '" name="backpeople' +
                            i +
                            '" value="' +
                            row.退料人員 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.退料人員 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.backpeoplenum"
                    ),
                    field: "退料人員工號",
                    width: "17ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="backpeoplenum' +
                            i +
                            '" name="backpeoplenum' +
                            i +
                            '" value="' +
                            row.退料人員工號 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.退料人員工號 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.backlistnum"
                    ),
                    field: "退料單號",
                    width: "17ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="backlist' +
                            i +
                            '" name="backlist' +
                            i +
                            '" value="' +
                            row.退料單號 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.退料單號 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.opentime"
                    ),
                    field: "開單時間",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="opentime' +
                            i +
                            '" name="opentime' +
                            i +
                            '" value="' +
                            row.開單時間 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.開單時間 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.inboundtime"
                    ),
                    field: "入庫時間",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="inboundtime' +
                            i +
                            '" name="inboundtime' +
                            i +
                            '" value="' +
                            row.入庫時間 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.入庫時間 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.status"
                    ),
                    field: "功能狀況",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="status' +
                            i +
                            '" name="status' +
                            i +
                            '" value="' +
                            row.功能狀況 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.功能狀況 +
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
                    app.appContext.config.globalProperties.$t("basicInfoLang.record") +
                    ", " +
                    app.appContext.config.globalProperties.$t("basicInfoLang.total") +
                    " {2} " +
                    app.appContext.config.globalProperties.$t("basicInfoLang.record"),
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
            console.log(rowsKey)
        }
        return {
            searchTerm,
            table,
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