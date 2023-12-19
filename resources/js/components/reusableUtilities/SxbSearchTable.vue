<template>
    <div class="row" style="text-align: left">
        <div class="col col-auto">
            <label for="sxbInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
        </div>
        <div class="col col-3 p-0 m-0">
            <input id="sxbInput" class="text-center form-control form-control-lg"
                v-bind:placeholder="$t('monthlyPRpageLang.entersxb')" v-model="searchTerm" />
        </div>
    </div>
    <div class="w-100" style="height: 1ch"></div>
    <!-- </div>breaks cols to a new line-->
    <table-lite :is-static-mode="true" :isSlotMode="true" :hasCheckbox="false" :messages="table.messages"
        :columns="table.columns" :rows="table.rows" :total="table.totalRecordCount" :page-options="table.pageOptions"
        :sortable="table.sortable" :is-fixed-first-column="false">
        <template v-slot:SXB單號="{ row, key }">
            <div class="col col-auto align-items-center m-0 p-0">
                <span class="m-0 p-0" style="width: 14ch;">{{ row.SXB單號 }}</span>
                <button @click="openSXBDetails(row.SXB單號)" type="button" data-bs-toggle="modal"
                    data-bs-target="#detailTable" class="btn btn-outline-info btn-sm ms-1 my-0 px-1 py-0"
                    style="border-radius: 20px;" :id="'sxb' + row.id" :name="'sxb' + key">More</button>
            </div>
        </template>
    </table-lite>

    <!-- Modal -->
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
                    <div class="row" style="text-align: left">
                        <div class="col col-auto">
                            <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
                        </div>
                        <div class="col col-3 p-0 m-0">
                            <input id="pnInput" class="text-center form-control form-control-lg"
                                v-bind:placeholder="$t('basicInfoLang.enterisn')" v-model="searchTerm2" />
                        </div>
                    </div>
                    <div class="w-100" style="height: 1ch"></div>
                    <!-- </div>breaks cols to a new line-->
                    <table-lite :is-static-mode="true" :isSlotMode="true" :hasCheckbox="false" :messages="table2.messages"
                        :columns="table2.columns" :rows="table2.rows" :total="table2.totalRecordCount"
                        :page-options="table2.pageOptions" :sortable="table2.sortable" :is-fixed-first-column="false" @row-clicked="rowClicked">
                    </table-lite>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-lg btn-danger" style="border-radius: 5px;">
                        {{ $t('monthlyPRpageLang.review_cancel') }}
                    </button>
                    <button type="button" class="btn btn-lg btn-success" style="border-radius: 5px;">
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
import TableLite from "./TableLite.vue";
import useSxbSearch from "../../composables/SxbSearch.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { mats, getMats } = useSxbSearch(); // axios get the mats data

        onBeforeMount(getMats);

        const searchTerm = ref(""); // Search text
        const searchTerm2 = ref(""); // Search text for modal table
        const modalTitle = ref("");
        const app = getCurrentInstance(); // get the current instance
        let thisHtmlLang = document
            .getElementsByTagName("HTML")[0]
            .getAttribute("lang");
        // get the current locale from html tag
        app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

        // pour the data in
        const data = reactive([]);
        const data2 = reactive([]);

        const triggerModal = async () => {
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            return new Promise((resolve, reject) => {
                resolve();
            });
        } // triggerModal

        const openSXBDetails = (SXB) => {
            // console.log("clicked!"); // test
            modalTitle.value = SXB;
            data2.splice(0);
            for (let i = 0; i < AllRecords.length; i++) {
                if (AllRecords[i].SXB單號 === SXB) {
                    data2.push(AllRecords[i]);
                } // if
            } // for

            // console.log(data2); // test
        } // openSXBDetails

        let AllRecords = [];
        watch(mats, async () => {
            await triggerModal();

            // console.log(JSON.parse(mats.value)); // test
            let allRowsObj = JSON.parse(mats.value);
            data.splice(0);
            AllRecords = [];
            // console.log(allRowsObj.datas); // test
            for (let i = 0; i < allRowsObj.datas.length; i++) {
                allRowsObj.datas[i].本次請購數量 = parseInt(
                    allRowsObj.datas[i].本次請購數量
                );

                allRowsObj.datas[i].id = i + 1;
                AllRecords.push(allRowsObj.datas[i]);
                let indexOfObject = data.findIndex(object => {
                    return (object.SXB單號 === allRowsObj.datas[i].SXB單號);
                });

                if (indexOfObject === -1) {
                    data.push(allRowsObj.datas[i]);
                } // if
            } // for

            // console.log(allRowsObj.datas); // test
            // console.log(AllRecords); // test
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        }); // watch for data change

        // Table config
        const table = reactive({
            isLoading: false,
            columns: [
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.sxb"
                    ),
                    field: "SXB單號",
                    width: "14ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.buytime"
                    ),
                    field: "請購時間",
                    width: "15ch",
                    sortable: true,
                    display: function (row, i) {
                        let returnStr = "";
                        // console.log(row); // test
                        returnStr =
                            '<input type="hidden" id="srm' +
                            i +
                            '" name="srm' +
                            i +
                            '" value="' +
                            row.請購時間 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.請購時間 +
                            "</div>";

                        return returnStr;
                    },
                },
            ],
            rows: computed(() => {
                return data.filter((x) =>
                    x.SXB單號
                        .toLowerCase()
                        .includes(searchTerm.value.toLowerCase())
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

        const table2 = reactive({
            isLoading: false,
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
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
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
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.品名 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.moq"
                    ),
                    field: "MOQ",
                    width: "8ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="moq' +
                            row.id +
                            '" name="moq' +
                            i +
                            '" value="' +
                            row.MOQ +
                            '">' +
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.MOQ +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.buyamount"
                    ),
                    field: "本次請購數量",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="buyamount' +
                            row.id +
                            '" name="buyamount' +
                            i +
                            '" value="' +
                            row.本次請購數量 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseInt(row.本次請購數量).toLocaleString("en-US") +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.buyprice"
                    ),
                    field: "請購金額",
                    width: "11ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="buyprice' +
                            row.id +
                            '" name="buyprice' +
                            i +
                            '" value="' +
                            row.請購金額 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseFloat(row.請購金額).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") +
                            " <small>" + row.幣別 + "</small>" +
                            "</div>"
                        );
                    },
                },
            ],
            rows: computed(() => {
                return data2.filter((x) =>
                    x.料號
                        .toLowerCase()
                        .includes(searchTerm2.value.toLowerCase())
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

        /**
        * Row clicked event
        */
        const rowClicked = (row) => {
            console.log("Row clicked!", row);
        };

        return {
            searchTerm,
            searchTerm2,
            table,
            table2,
            modalTitle,
            rowClicked,
            openSXBDetails,
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