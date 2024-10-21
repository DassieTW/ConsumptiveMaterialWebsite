<template>
    <div class="row" style="text-align: left">
        <div class="col col-auto">
            <label for="pnInput" class="col-form-label"
                >{{ $t("basicInfoLang.quicksearch") }} :</label
            >
        </div>
        <div class="col col-3 p-0 m-0">
            <input
                id="pnInput"
                class="text-center form-control form-control-lg"
                v-bind:placeholder="$t('monthlyPRpageLang.enterisn_or_descr')"
                v-model="searchTerm"
            />
        </div>
    </div>
    <div class="w-100" style="height: 1ch"></div>
    <!-- </div>breaks cols to a new line-->
    <table-lite
        :is-fixed-first-column="true"
        :is-static-mode="true"
        :hasCheckbox="true"
        :isLoading="table.isLoading"
        :messages="table.messages"
        :columns="table.columns"
        :rows="table.rows"
        :total="table.totalRecordCount"
        :page-options="table.pageOptions"
        :sortable="table.sortable"
        @is-finished="table.isLoading = false"
        @return-checked-rows="updateCheckedRows"
    ></table-lite>
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
import useOboundInboundSearch from "../../composables/OboundINSearch.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { mats, getMats } = useOboundInboundSearch(); // axios get the mats data

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

            document.getElementById("QueryFlag").click();
            table.isLoading = false;
        }); // watch for data change

        // Table config
        const table = reactive({
            isLoading: true,
            columns: [
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.inlist"
                    ),
                    field: "入庫單號",
                    width: "14ch",
                    sortable: true,
                    isKey: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="inboundlist' +
                            i +
                            '" name="inboundlist' +
                            i +
                            '" value="' +
                            row.入庫單號 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.入庫單號 +
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
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
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
                    width: "15ch",
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
                        "oboundpageLang.format"
                    ),
                    field: "規格",
                    width: "15ch",
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
                        "oboundpageLang.client"
                    ),
                    field: "客戶別",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="client' +
                            i +
                            '" name="client' +
                            i +
                            '" value="' +
                            row.客戶別 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.客戶別 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.bound"
                    ),
                    field: "庫別",
                    width: "12ch",
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
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.庫別 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.inboundnum"
                    ),
                    field: "入庫數量",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="inboundnum' +
                            i +
                            '" name="inboundnum' +
                            i +
                            '" value="' +
                            row.數量 +
                            '">' +
                            '<div class="CustomScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.數量 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.inpeople"
                    ),
                    field: "入庫人員",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="inboundpeople' +
                            i +
                            '" name="inboundpeople' +
                            i +
                            '" value="' +
                            row.入庫人員 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.入庫人員 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.inboundtime"
                    ),
                    field: "入庫時間",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="inboundtime' +
                            i +
                            '" name="inboundtime' +
                            i +
                            '" value="' +
                            row.時間 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.時間 +
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
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.備註 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "oboundpageLang.inreason"
                    ),
                    field: "入庫原因",
                    width: "15ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="inboundreason' +
                            i +
                            '" name="inboundreason' +
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
            ],
            rows: computed(() => {
                return data.filter(
                    (x) =>
                        x.料號
                            .toLowerCase()
                            .includes(searchTerm.value.toLowerCase()) ||
                        x.品名.includes(searchTerm.value)
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
            console.log(rowsKey);
            // only check one
            let count = $(".vtl-paging-count-dropdown").val();
            rowsKey = parseInt(rowsKey);
            for (let i = 0; i < count; i++) {
                if (i !== rowsKey) {
                    $("#checkbox" + i).prop("disabled", true);
                } else {
                    $("#checkbox" + i).prop("disabled", false);
                }
            }
            if (isNaN(rowsKey)) {
                console.log(1);
                $(".vtl-tbody-checkbox").prop("disabled", false);
            }
        };
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
