<template>
    <div class="card">
        <div class="card-header">
            <h3>{{ $t('inboundpageLang.LocationChangeRecord') }}</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="row col col-auto">
                    <div class="col col-auto">
                        <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
                    </div>
                    <div class="col col-6 p-0 m-0">
                        <input id="pnInput" class="text-center form-control form-control-lg"
                            v-bind:placeholder="$t('inboundpageLang.enterisn_or_loc')" v-model="searchTerm" />
                    </div>
                </div>
                <div class="row col col-auto text-center align-items-center justify-content-between">
                    <div class="form-check form-check-inline col col-auto m-0">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1"
                            value="month" v-model="picked" checked>
                        <label class="form-check-label" for="inlineRadio1">{{ $t('inboundpageLang.within_a_month')
                            }}</label>
                    </div>
                    <div class="form-check form-check-inline col col-auto m-0">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2"
                            value="quarter" v-model="picked">
                        <label class="form-check-label" for="inlineRadio2">{{ $t('inboundpageLang.within_three_months')
                            }}</label>
                    </div>
                    <div class="form-check form-check-inline col col-auto m-0">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3"
                            value="year" v-model="picked">
                        <label class="form-check-label" for="inlineRadio3">{{ $t('inboundpageLang.within_a_year')
                            }}</label>
                    </div>
                </div>
            </div>
            <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
            <div class="row">
                <span class="col col-auto text-danger fw-bold">
                    {{ $t('inboundpageLang.stock_within_brackets') }}
                </span>
            </div>
            <table-lite id="searchTable" :is-fixed-first-column="true" :isStaticMode="true" :isSlotMode="true"
                :hasCheckbox="false" :messages="table.messages" :columns="table.columns" :rows="table.rows"
                :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
                :is-loading="table.isLoading" @is-finished="table.isLoading = false">
            </table-lite>
        </div>
    </div>
</template>

<script>
import { defineComponent, reactive, ref, computed } from "vue";
import {
    getCurrentInstance,
    onBeforeMount,
    watch,
} from "@vue/runtime-core";
import TableLite from "./TableLite.vue";
import useInboundStockSearch from "../../composables/InboundStockSearch.ts";
import useCommonlyUsedFunctions from "../../composables/CommonlyUsedFunctions.ts";

export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { mats, errors, getLocTransferRecord } = useInboundStockSearch(); // axios get the mats data
        const { queryResult, locations, validateISN, getLocs } = useCommonlyUsedFunctions();

        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref("");

        onBeforeMount(async () => {
            table.isLoading = true;
            await getLocTransferRecord(JSON.stringify("month"), JSON.stringify("none"), JSON.stringify("none"));
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
        const picked = ref("month");

        const triggerModal = async () => {
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            return new Promise((resolve, reject) => {
                resolve();
            });
        } // triggerModal

        watch(picked, async () => {
            await triggerModal();
            table.isLoading = true;
            await getLocTransferRecord(JSON.stringify(picked.value), JSON.stringify("none"), JSON.stringify("none"));
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
            table.isLoading = false;
        }); // watch for data change

        watch(mats, async () => {
            await triggerModal();
            table.isLoading = true;
            data.splice(0); // clear the data
            console.log(JSON.parse(mats.value)); // test
            let allRowsObj = JSON.parse(mats.value);

            for (let i = 0; i < allRowsObj.data.length; i++) {
                allRowsObj.data[i].id = i;
                data.push(allRowsObj.data[i]);
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
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.品名 +
                            "</div>"
                        );
                    },
                },
                // {
                //     label: app.appContext.config.globalProperties.$t(
                //         "basicInfoLang.format"
                //     ),
                //     field: "規格",
                //     width: "13ch",
                //     sortable: true,
                //     display: function (row, i) {
                //         return (
                //             '<div class="CustomScrollbar text-nowrap"' +
                //             ' style="overflow-x: auto; width: 100%;">' +
                //             row.規格 +
                //             "</div>"
                //         );
                //     },
                // },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.orig_loc"
                    ),
                    field: "調出儲位",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.調出儲位 === null || row.調出儲位 === undefined) row.調出儲位 = "N/A";
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.調出儲位 + ' <small>( ' + row.原調出儲位庫存 + ' ' + row.單位 + ' )</small>' +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.transferamount"
                    ),
                    field: "調動數量",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.調動數量 === null || row.調動數量 === undefined) row.調動數量 = "N/A";
                        return (
                            '<div class="text-nowrap CustomScrollbar row justify-content-between"' +
                            ' style="overflow-x: auto;">' +
                            '<span class="col col-auto m-0 p-0"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l370.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z"/></svg></span>' +
                            '<span class="col col-auto m-0 p-0 fw-bold">' +
                            row.調動數量 + ' <small>' + row.單位 + '</small>' +
                            '</span>' +
                            '<span class="col col-auto m-0 p-0"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l370.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z"/></svg></span>' +
                            '</div>'
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.newloc"
                    ),
                    field: "接收儲位",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.接收儲位 === null || row.接收儲位 === undefined) row.接收儲位 = "N/A";
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.接收儲位 + ' <small>( ' + row.原接收儲位庫存 + ' ' + row.單位 + ' )</small>' +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.inpeople"
                    ),
                    field: "操作人",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.操作人 === null || row.操作人 === undefined) row.操作人 = "N/A";
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.姓名 + ' ( ' + row.操作人 + ' )' +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.operateTime"
                    ),
                    field: "操作時間",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.操作時間 === null || row.操作時間 === undefined) row.操作時間 = "N/A";
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.操作時間 +
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
                    x.調出儲位
                        .includes(searchTerm.value) ||
                    x.接收儲位
                        .includes(searchTerm.value)
                );
            }),
            totalRecordCount: computed(() => {
                return table.rows.length;
            }),
            sortable: {
                order: "操作時間",
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
            // console.log(rowsKey); // test
            checkedRows = rowsKey;
        };

        return {
            searchTerm,
            table,
            picked,
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
