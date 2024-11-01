<template>
    <div class="card">
        <div class="card-header">
            <h3>{{ $t('inboundpageLang.new') }}</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="row col col-auto">
                    <div class="col col-auto">
                        <label for="sxbInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
                    </div>
                    <div class="col col-auto p-0 m-0">
                        <input id="sxbInput" class="text-center form-control form-control-lg"
                            v-bind:placeholder="$t('inboundpageLang.enterSSZ')" v-model="searchTerm" />
                    </div>
                </div>
                <div class="col col-auto">
                    <button id="download" name="download" class="col col-auto btn btn-lg btn-success"
                        :value="$t('monthlyPRpageLang.download')" @click="OutputExcelClick('All')">
                        <i class="bi bi-file-earmark-arrow-down-fill fs-4"></i>
                    </button>
                </div>
            </div>
            <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
            <span v-if="isInvalid_DB" class="invalid-feedback d-block" role="alert">
                <strong>{{ validation_err_msg }}</strong>
            </span>
            <table-lite :is-static-mode="true" :isSlotMode="true" :hasCheckbox="false" :messages="table.messages"
                :columns="table.columns" :rows="table.rows" :total="table.totalRecordCount"
                :page-options="table.pageOptions" :sortable="table.sortable" :is-fixed-first-column="false">
                <template v-slot:FlowNumber="{ row, key }">
                    <div class="col col-auto align-items-center m-0 p-0">
                        <div class="text-nowrap CustomScrollbar" style="overflow-x: auto; width: 100%;">
                            {{ row.FlowNumber }}
                        </div>
                    </div>
                </template>
                <template v-slot:SXB單號="{ row, key }">
                    <div class="col col-auto align-items-center m-0 p-0">
                        <button @click="openSSZDetails(row.FlowNumber)" type="button" data-bs-toggle="modal"
                            data-bs-target="#detailTable" class="btn btn-outline-info btn-sm ms-1 my-0 px-1 py-0"
                            style="border-radius: 20px;" :id="'sxb' + row.id" :name="'sxb' + key">More</button>
                    </div>
                </template>
                <template v-slot:status="{ row, key }">
                    <div class="col col-auto align-items-center m-0 p-0">
                        <a v-if="row.status.toLowerCase().includes('reject')" @click="openSSZDetails(row.FlowNumber)"
                            data-bs-toggle="modal" data-bs-target="#detailTable" class="m-0 p-0" style="color: red;">
                            {{ $t("monthlyPRpageLang.review_cancel") }}
                        </a>
                        <a v-else class="m-0 p-0" @click="openSSZDetails(row.FlowNumber)" data-bs-toggle="modal"
                            data-bs-target="#detailTable" style="color: #2bb91b;">
                            {{ $t("monthlyPRpageLang.review_complete") }}
                        </a>
                    </div>
                </template>
            </table-lite>
        </div>
    </div>
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
                    <div class="row justify-content-between">
                        <div class="row col col-auto">
                            <div class="col col-auto">
                                <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }}
                                    :</label>
                            </div>
                            <div class="col col-auto p-0 m-0">
                                <input id="pnInput" class="text-center form-control form-control-lg"
                                    v-bind:placeholder="$t('inboundpageLang.enterisn_or_spec')" v-model="searchTerm2" />
                            </div>
                        </div>
                        <div class="col col-auto">
                            <button id="download" name="download" class="col col-auto btn btn-lg btn-success"
                                :value="$t('monthlyPRpageLang.download')" @click="OutputExcelClick(modalTitle)">
                                <i class="bi bi-file-earmark-arrow-down-fill fs-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
                    <table-lite id="searchTable2" :is-fixed-first-column="true" :isStaticMode="true" :isSlotMode="true"
                        :hasCheckbox="false" :messages="table2.messages" :columns="table2.columns" :rows="table2.rows"
                        :total="table2.totalRecordCount" :page-options="table2.pageOptions" :sortable="table2.sortable"
                        @is-finished="table2.isLoading = false">
                        <template v-slot:relQty="{ row, key }">
                            <div class="col col-auto align-items-center m-0 p-0">
                                <div class="text-nowrap CustomScrollbar" style="overflow-x: auto; width: 100%;">
                                    {{ parseInt(row.relQty).toLocaleString("en-US") }}
                                </div>
                            </div>
                        </template>

                        <template v-slot:新儲位="{ row, key }">
                            <div v-if="!(row.status.toLowerCase().includes('reject'))" class="col col-auto p-0 m-0">
                                <select v-model="row.新儲位" style="width: 11ch;"
                                    class="col col-auto form-select form-select-lg ps-2 p-0 m-0"
                                    :id="'newloc_' + row.MatShort" :name="'newloc_' + row.MatShort">
                                    <option id="noneSelected" value="" disabled selected>
                                        {{ $t('inboundpageLang.choose') }}
                                    </option>
                                    <option v-for="item in locsArray" :id="item" :value="item">
                                        {{ item }}
                                    </option>
                                </select>
                            </div>
                            <div v-else>
                                <span class="m-0 p-0" style="color: red;">
                                    {{ $t("monthlyPRpageLang.review_cancel") }}
                                </span>
                            </div>
                        </template>
                    </table-lite>
                </div>
                <div v-if="showFooter" class="modal-footer justify-content-center">
                    <button @click="ssz_claim" type="button" class="btn btn-lg btn-success"
                        style="border-radius: 5px;">
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
import * as XLSX from 'xlsx';
import TableLite from "./TableLite.vue";
import useSSZSearch from "../../composables/SSZSearch.ts";
import useCommonlyUsedFunctions from "../../composables/CommonlyUsedFunctions.ts";

export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { mats_SSZ, mats_SSZInfo, getSSZ, getSSZ_info } = useSSZSearch(); // axios get the mats_SSZInfo data
        const { queryResult, locations, validateISN, getLocs } = useCommonlyUsedFunctions();

        onBeforeMount(async () => {
            await getLocs();
            await getSSZ_info();
        });

        const searchTerm = ref(""); // Search text
        const searchTerm2 = ref(""); // Search text for modal table
        const modalTitle = ref("");
        let isInvalid_DB = ref(false); // add to DB validation
        let showFooter = ref(false);
        const locsArray = reactive([]);


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

        const OutputExcelClick = async (output_range) => {
            await triggerModal();

            // get today's date for filename
            let today = new Date();
            let dd = String(today.getDate()).padStart(2, '0');
            let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            let yyyy = today.getFullYear();
            today = yyyy + "_" + mm + '_' + dd;

            if (output_range === 'All') {
                let rows = Array();
                for (let i = 0; i < AllRecords.length; i++) {
                    let tempObj = new Object;
                    tempObj.單號 = AllRecords[i].SXB單號;
                    tempObj.料號 = AllRecords[i].料號;
                    tempObj.品名 = AllRecords[i].品名;
                    tempObj.MOQ = AllRecords[i].MOQ;
                    tempObj.本次請購數量 = AllRecords[i].本次請購數量;
                    tempObj.總價 = parseFloat(AllRecords[i].請購金額).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + " " + AllRecords[i].幣別;
                    tempObj.請購時間 = AllRecords[i].請購時間;

                    rows.push(tempObj);
                } // for

                const worksheet = XLSX.utils.json_to_sheet(rows);
                const workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, 'ALL');
                XLSX.writeFile(workbook,
                    app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.SXB_search"
                    ) + "(ALL)_" + today + ".xlsx", { compression: true });
            } else {
                let rows = Array();
                for (let i = 0; i < data2.length; i++) {
                    let tempObj = new Object;
                    tempObj.單號 = data2[i].SXB單號;
                    tempObj.料號 = data2[i].料號;
                    tempObj.品名 = data2[i].品名;
                    tempObj.MOQ = data2[i].MOQ;
                    tempObj.本次請購數量 = data2[i].本次請購數量;
                    tempObj.總價 = parseFloat(data2[i].請購金額).toFixed(2).replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",") + " " + data2[i].幣別;
                    tempObj.請購時間 = data2[i].請購時間;

                    rows.push(tempObj);
                } // for

                const worksheet = XLSX.utils.json_to_sheet(rows);
                const workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, data2[0].SXB單號);
                XLSX.writeFile(workbook,
                    app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.SXB_search"
                    ) + "(" + data2[0].SXB單號 + ")_" + today + ".xlsx", { compression: true });
            } // if else

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // OutputExcelClick

        const openSSZDetails = (SSZ) => {
            modalTitle.value = SSZ;
            searchTerm2.value = "";

            let obj = data2.find(o => o.FlowNumber === SSZ);
            if (obj.status.toLowerCase().includes('reject')) {
                showFooter.value = false;
            } // if
            else {
                showFooter.value = true;
            } // else
        } // openSSZDetails

        const ssz_claim = async () => {
            await triggerModal();

            console.log(data2); // test
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // ssz_claim

        watch(mats_SSZInfo, async () => {
            await triggerModal();
            // console.log(JSON.parse(mats_SSZInfo.value)); // test
            data.splice(0);
            data2.splice(0);
            locsArray.splice(0);
            let allRowsObj = JSON.parse(mats_SSZInfo.value);

            JSON.parse(locations.value).data.forEach(element => {
                locsArray.push(element.儲存位置);
            });

            // get unique SSZ number and its status and push to data
            Object.assign(data, [...new Map(allRowsObj.data.map(item => [item["FlowNumber"], item])).values()]);
            for (let i = 0; i < allRowsObj.data.length; i++) {
                allRowsObj.data[i].新儲位 = "";
                data2.push(allRowsObj.data[i]);
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
                        "inboundpageLang.FlowNumber"
                    ),
                    field: "FlowNumber",
                    width: "8ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.status"
                    ),
                    field: "status",
                    width: "6ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.info"
                    ),
                    field: "SXB單號",
                    width: "4ch",
                    sortable: false,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.pr_sender"
                    ),
                    field: "Applicant",
                    width: "8ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.Applicant === null || row.Applicant === undefined) row.Applicant = "N/A";
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.Applicant +
                            "</div>"
                        );
                    },
                },
            ],
            rows: computed(() => {
                return data.filter((x) =>
                    x.FlowNumber
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

        const table2 = reactive({
            isLoading: true,
            columns: [
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.isn"
                    ),
                    field: "MatShort",
                    width: "13ch",
                    sortable: true,
                    isKey: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.MatShort +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.format"
                    ),
                    field: "Spec",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.Spec +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.amount"
                    ),
                    field: "relQty",
                    width: "12ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.newloc"
                    ),
                    field: "新儲位",
                    width: "13ch",
                    sortable: false,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.mark"
                    ),
                    field: "SSZMemo",
                    width: "11ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.SSZMemo +
                            "</div>"
                        );
                    },
                },
            ],
            rows: computed(() => {
                return data2.filter((x) =>
                    x.FlowNumber === modalTitle.value &&
                    (x.MatShort
                        .toLowerCase()
                        .includes(searchTerm2.value.toLowerCase()) ||
                        x.Spec.toLowerCase().includes(searchTerm2.value.toLowerCase()))
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

        /**
        * Row clicked event
        */
        const rowClicked = (row) => {
            // console.log("Row clicked!", row);
        };

        return {
            searchTerm,
            searchTerm2,
            table,
            table2,
            locsArray,
            modalTitle,
            showFooter,
            isInvalid_DB,
            openSSZDetails,
            OutputExcelClick,
            ssz_claim,
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