<template>
    <div class="card">
        <div class="card-header">
            <h3 class="m-0 p-0">{{ $t('checkInvLang.check_record') }}</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="row col col-auto">
                    <div class="col col-auto">
                        <label for="serialInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
                    </div>
                    <div class="col col-auto p-0 m-0">
                        <input id="serialInput" class="text-center form-control form-control-lg"
                            v-bind:placeholder="$t('monthlyPRpageLang.entersxb')" v-model="searchTerm" />
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
            <div class="w-100" style="height: 0ch"></div><!-- </div>breaks cols to a new line-->
            <div class="row justify-content-between">
                <span class="col col-auto text-danger fw-bold">
                </span>
                <span class="col col-auto text-danger fw-bold">
                    {{ $t('checkInvLang.approver_priority_notice') }}
                </span>
            </div>
            <table-lite :is-static-mode="true" :isSlotMode="true" :hasCheckbox="false" :messages="table.messages"
                :columns="table.columns" :rows="table.rows" :total="table.totalRecordCount"
                :page-options="table.pageOptions" :sortable="table.sortable" :is-fixed-first-column="false"
                :is-loading="table.isLoading" @is-finished="table.isLoading = false">
                <template v-slot:單號="{ row, key }">
                    <div class="col col-auto align-items-center m-0 p-0">
                        <button @click="openDetails(row.單號)" type="button" data-bs-toggle="modal"
                            data-bs-target="#detailTable" class="btn btn-outline-info btn-sm ms-1 my-0 px-1 py-0"
                            style="border-radius: 20px;" :id="'sxb' + row.id" :name="'sxb' + key">More</button>
                    </div>
                </template>
                <template v-slot:狀態="{ row, key }">
                    <div class="col col-auto align-items-center m-0 p-0">
                        <a v-if="row.狀態 === '未簽核'" @click="openDetails(row.單號)" data-bs-toggle="modal"
                            data-bs-target="#detailTable" class="m-0 p-0" style="color: #dca120;">
                            {{ $t("monthlyPRpageLang.review_pending") }}
                        </a>
                        <!-- <a v-else-if="row.狀態 === '已退單'" @click="openDetails(row.單號)" data-bs-toggle="modal"
                            data-bs-target="#detailTable" class="m-0 p-0" style="color: #808080;">
                            {{ $t("monthlyPRpageLang.review_cancel") }}
                        </a> -->
                        <a v-else class="m-0 p-0" @click="openDetails(row.單號)" data-bs-toggle="modal"
                            data-bs-target="#detailTable" style="color: #2bb91b;">
                            {{ $t("monthlyPRpageLang.review_complete") }}
                        </a>
                    </div>
                </template>
            </table-lite>

            <!-- Modal -->
            <div class="modal fade" id="detailTable" tabindex="-1" aria-labelledby="detailTable" aria-hidden="false">
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
                                        <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch")
                                            }}
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
                                :total="table2.totalRecordCount" :page-options="table2.pageOptions"
                                :sortable="table2.sortable" :is-fixed-first-column="false"
                                :is-loading="table2.isLoading" @is-finished="table2.isLoading = false">
                            </table-lite>
                        </div>
                        <div v-if="showFooter" class="modal-footer justify-content-between">
                            <button @click="checking_reject" type="button" class="btn btn-lg btn-danger"
                                style="border-radius: 5px;">
                                {{ $t('monthlyPRpageLang.review_cancel') }}
                            </button>
                            <button @click="checking_approve" type="button" class="btn btn-lg btn-success"
                                style="border-radius: 5px;">
                                {{ $t('monthlyPRpageLang.review_complete') }}
                            </button>
                        </div>
                    </div>
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
    watch,
} from "@vue/runtime-core";
import ExcelJS from 'exceljs';
import FileSaver from "file-saver";
import TableLite from "./TableLite.vue";
import useCheckingInventory from "../../composables/CheckingInventory.ts";
import useUserSearch from "../../composables/UserSearch.ts";

export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { checking_records, get_checking_records, checking_Reject, checking_Approve } = useCheckingInventory(); // axios get the mats data
        const { current_user, getCurrentUser } = useUserSearch();
        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref("");

        onBeforeMount(async () => {
            table.isLoading = true;
            table2.isLoading = true;
            await getCurrentUser();
            await get_checking_records(JSON.stringify(picked.value));
        });

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

        const openDetails = (serial_number) => {
            // console.log("clicked!"); // test
            table2.isLoading = true;
            modalTitle.value = serial_number;
            data2.splice(0);
            for (let i = 0; i < AllRecords.length; i++) {
                if (AllRecords[i].單號 === serial_number) {
                    data2.push(AllRecords[i]);
                } // if
            } // for

            if (data2[0].狀態 === '未簽核' && parseInt(current_user.value.priority) <= 1) {
                showFooter.value = true;
            } // if
            else {
                showFooter.value = false;
            } // else
            table2.isLoading = false;
        } // openDetails

        const OutputExcelClick = async (output_range) => {
            await triggerModal();
            let temp = AllRecords.filter(function (record) {
                return record.單號 == output_range;
            });

            let rows = temp.map(({
                A級資材, LT, MOQ, MPQ, approved_at, approved_by, email, id, updated_by, 主管工號, 單價, 單號, 姓名, 安全庫存, 工號, 幣別, 月請購, 狀態, 發料部門, 部門,
                ...keepAttrs }) => keepAttrs);

            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet(app.appContext.config.globalProperties.$t("checkInvLang.check"));

            worksheet.columns = [
                { header: app.appContext.config.globalProperties.$t("inboundpageLang.isn"), key: '料號' },
                { header: app.appContext.config.globalProperties.$t("inboundpageLang.stock"), key: '現有庫存' },
                { header: app.appContext.config.globalProperties.$t("inboundpageLang.loc"), key: '儲位' },
                { header: app.appContext.config.globalProperties.$t("outboundpageLang.opentime"), key: '開單時間' },
                { header: app.appContext.config.globalProperties.$t("inboundpageLang.pName"), key: '品名' },
                { header: app.appContext.config.globalProperties.$t("inboundpageLang.format"), key: '規格' },
                { header: app.appContext.config.globalProperties.$t("inboundpageLang.unit"), key: '單位' },
            ];

            rows.forEach(row => {
                worksheet.addRow(row);
            });

            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            FileSaver.saveAs(blob, app.appContext.config.globalProperties.$t("checkInvLang.check") + "_" + output_range + ".xlsx");

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        };

        const checking_reject = async () => {
            await triggerModal();
            console.log(data2[0].單號); // test
            let result = await checking_Reject(data2[0].單號);
            if (result === "success") {
                showFooter.value = false;
                notyf.open({
                    type: "success",
                    message: app.appContext.config.globalProperties.$t("checkInvLang.update_success"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });

                await get_checking_records(JSON.stringify(picked.value));
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
        } // checking_reject

        const checking_approve = async () => {
            await triggerModal();

            let isn = [];
            let loc = [];
            let stock = [];
            for (let i = 0; i < data2.length; i++) {
                isn.push(data2[i].料號);
                loc.push(data2[i].儲位);
                stock.push(data2[i].現有庫存);
            } // for

            let result = await checking_Approve(data2[0].單號, isn, loc, stock);
            if (result === "success") {
                showFooter.value = false;
                notyf.open({
                    type: "success",
                    message: app.appContext.config.globalProperties.$t("checkInvLang.update_success"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });

                await get_checking_records(JSON.stringify(picked.value));
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
        } // checking_approve

        watch(picked, async () => {
            await triggerModal();
            table.isLoading = true;
            await get_checking_records(JSON.stringify(picked.value));
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
            table.isLoading = false;
        }); // watch for data change

        watch(checking_records, async () => {
            await triggerModal();
            // console.log(JSON.parse(checking_records.value)); // test
            // console.log(current_user.value.priority); // test
            let allRowsObj = JSON.parse(checking_records.value);
            data.splice(0);
            AllRecords = [];
            for (let i = 0; i < allRowsObj.data.length; i++) {
                allRowsObj.data[i].id = i;
                if (allRowsObj.data[i].approved_by === null || allRowsObj.data[i].approved_by === undefined) {
                    allRowsObj.data[i].狀態 = "未簽核";
                } // if
                else {
                    allRowsObj.data[i].狀態 = "已簽核";
                } // else

                let indexOfObject = data.findIndex(object => {
                    return (object.單號 === allRowsObj.data[i].單號);
                });
                if (indexOfObject === -1) {
                    data.push(allRowsObj.data[i]);
                } // if

                AllRecords.push(allRowsObj.data[i]);
            } // for

            // console.log(allRowsObj.data); // test
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
                        "checkInvLang.updated_at"
                    ),
                    field: "開單時間",
                    width: "15ch",
                    sortable: true,
                    display: function (row, i) {
                        let returnStr = "";
                        // console.log(row); // test
                        returnStr =
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.created_at +
                            "</div>";

                        return returnStr;
                    },
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
                        "monthlyPRpageLang.info"
                    ),
                    field: "單號",
                    width: "4ch",
                    sortable: false,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "checkInvLang.updated_by"
                    ),
                    field: "盤點人",
                    width: "8ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.updated_by === null || row.updated_by === undefined) row.updated_by = "N/A";
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.updated_by +
                            "</div>"
                        );
                    },
                },
            ],
            rows: computed(() => {
                return data.filter((x) =>
                    x.updated_by
                        .toLowerCase()
                        .includes(searchTerm.value.toLowerCase())
                );
            }),
            totalRecordCount: computed(() => {
                return table.rows.length;
            }),
            sortable: {
                order: "請購時間",
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
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.format"
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
                        "inboundpageLang.stock"
                    ),
                    field: "庫存",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.現有庫存 + '&nbsp;<small>' + row.單位 + '</small>' +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.loc"
                    ),
                    field: "儲位",
                    width: "12ch",
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
                order: "儲位",
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
            // checkedRows = rowsKey;
        };

        return {
            searchTerm,
            searchTerm2,
            picked,
            table,
            table2,
            modalTitle,
            showFooter,
            openDetails,
            OutputExcelClick,
            checking_approve,
            checking_reject,
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
