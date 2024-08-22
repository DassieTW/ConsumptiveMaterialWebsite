<template>
    <div class="row justify-content-between">
        <div class="row col col-auto">
            <div class="col col-auto">
                <label for="sxbInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
            </div>
            <div class="col col-auto p-0 m-0">
                <input id="sxbInput" class="text-center form-control form-control-lg"
                    v-bind:placeholder="$t('monthlyPRpageLang.entersxb')" v-model="searchTerm" />
            </div>
        </div>
        <div class="col col-auto">
            <button id="download" name="download" class="col col-auto btn btn-lg btn-success"
                :value="$t('monthlyPRpageLang.download')" @click="OutputExcelClick('All')">
                <i class="bi bi-file-earmark-arrow-down-fill fs-4"></i>
            </button>
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
        <template v-slot:狀態="{ row, key }">
            <div class="col col-auto align-items-center m-0 p-0">
                <a v-if="row.狀態 === '未簽核'" @click="openSXBDetails(row.SXB單號)" data-bs-toggle="modal"
                    data-bs-target="#detailTable" class="m-0 p-0" style="color: #dca120;">
                    {{ $t("monthlyPRpageLang.review_pending") }}
                </a>
                <a v-else-if="row.狀態 === '已退單'" @click="openSXBDetails(row.SXB單號)" data-bs-toggle="modal"
                    data-bs-target="#detailTable" class="m-0 p-0" style="color: #808080;">
                    {{ $t("monthlyPRpageLang.review_cancel") }}
                </a>
                <a v-else class="m-0 p-0" @click="openSXBDetails(row.SXB單號)" data-bs-toggle="modal"
                    data-bs-target="#detailTable" style="color: #2bb91b;">
                    {{ $t("monthlyPRpageLang.review_complete") }}
                </a>
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
                    <div class="row justify-content-between">
                        <div class="row col col-auto">
                            <div class="col col-auto">
                                <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }}
                                    :</label>
                            </div>
                            <div class="col col-auto p-0 m-0">
                                <input id="pnInput" class="text-center form-control form-control-lg"
                                    v-bind:placeholder="$t('monthlyPRpageLang.enterisn_or_descr')"
                                    v-model="searchTerm2" />
                            </div>
                        </div>
                        <div class="col col-auto">
                            <button id="download" name="download" class="col col-auto btn btn-lg btn-success"
                                :value="$t('monthlyPRpageLang.download')" @click="OutputExcelClick(modalTitle)">
                                <i class="bi bi-file-earmark-arrow-down-fill fs-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="w-100" style="height: 1ch"></div>
                    <!-- </div>breaks cols to a new line-->
                    <table-lite :is-static-mode="true" :isSlotMode="true" :hasCheckbox="false"
                        :messages="table2.messages" :columns="table2.columns" :rows="table2.rows"
                        :total="table2.totalRecordCount" :page-options="table2.pageOptions" :sortable="table2.sortable"
                        :is-fixed-first-column="false" @row-clicked="rowClicked">
                    </table-lite>
                </div>
                <div v-if="showFooter" class="modal-footer justify-content-between">
                    <button @click="sxb_reject" type="button" class="btn btn-lg btn-danger" style="border-radius: 5px;">
                        {{ $t('monthlyPRpageLang.review_cancel') }}
                    </button>
                    <button @click="sxb_approve" type="button" class="btn btn-lg btn-success"
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
import useSxbSearch from "../../composables/SxbSearch.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { mats, inTransit, getMats, SXB_Reject, SXB_Approve, getTransit } = useSxbSearch(); // axios get the mats data

        onBeforeMount(getMats);

        const searchTerm = ref(""); // Search text
        const searchTerm2 = ref(""); // Search text for modal table
        const modalTitle = ref("");
        let showFooter = ref(false);
        let AllRecords = [];

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

        const openSXBDetails = (SXB) => {
            // console.log("clicked!"); // test
            modalTitle.value = SXB;
            data2.splice(0);
            for (let i = 0; i < AllRecords.length; i++) {
                if (AllRecords[i].SXB單號 === SXB) {
                    data2.push(AllRecords[i]);
                } // if
            } // for

            if (data2[0].狀態 === '未簽核') {
                showFooter.value = true;
            } // if
            else {
                showFooter.value = false;
            } // else
        } // openSXBDetails

        const sxb_reject = async () => {
            await triggerModal();
            let result = await SXB_Reject(data2[0].SXB單號);
            if (result === "success") {
                showFooter.value = false;
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

                await getMats();
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
        } // sxb_reject

        const sxb_approve = async () => {
            await triggerModal();
            let temp_isn = [];
            for (let i = 0; i < data2.length; i++) {
                temp_isn.push(data2[i].料號);
            } // for

            await getTransit(temp_isn); // get existing in-transit data
            let existing = JSON.parse(inTransit.value).data;
            // console.log(existing); // test
            // return ; // test
            let isn = [];
            let amount = [];
            for (let i = 0; i < data2.length; i++) {
                isn.push(data2[i].料號);

                let indexOfObject = existing.findIndex(object => {
                    return (object.料號 === data2[i].料號);
                });

                if (indexOfObject === -1) {
                    amount.push(parseInt(data2[i].本次請購數量));
                } // if
                else {
                    amount.push(parseInt(data2[i].本次請購數量) + parseInt(existing[indexOfObject].請購數量));
                } // else
            } // for

            // console.log(amount, isn); // test

            let result = await SXB_Approve(data2[0].SXB單號, isn, amount);
            if (result === "success") {
                showFooter.value = false;
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

                await getMats();
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
        } // sxb_approve

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

                allRowsObj.datas[i].狀態 = allRowsObj.datas[i].SRM單號;
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
            table.isLoading = false;
        }); // watch for data change

        // Table config
        const table = reactive({
            isLoading: true,
            columns: [
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.sxb"
                    ).replace('SXB', '').replace(' ', ''),
                    field: "SXB單號",
                    width: "14ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.status"
                    ),
                    field: "狀態",
                    width: "6ch",
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
                            '<div class="text-nowrap CustomScrollbar"' +
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
                            '<div class="text-nowrap CustomScrollbar"' +
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
                            '<div class="text-nowrap CustomScrollbar"' +
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
                            '<div class="CustomScrollbar text-nowrap"' +
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
                            '<div class="text-nowrap CustomScrollbar"' +
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
                            '<div class="text-nowrap CustomScrollbar"' +
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
                        .includes(searchTerm2.value.toLowerCase()) ||
                    x.品名
                        .includes(searchTerm2.value)
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
            modalTitle,
            showFooter,
            rowClicked,
            openSXBDetails,
            sxb_approve,
            sxb_reject,
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