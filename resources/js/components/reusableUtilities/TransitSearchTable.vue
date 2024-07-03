<template>
    <div class="row justify-content-between">
        <div class="row col col-auto">
            <div class="col col-auto">
                <label for="sxbInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
            </div>
            <div class="col col-auto p-0 m-0">
                <input id="sxbInput" class="text-center form-control form-control-lg"
                    v-bind:placeholder="$t('monthlyPRpageLang.enterisn_or_descr')" v-model="searchTerm" />
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
    <table-lite id="searchTable" :is-fixed-first-column="true" :hasCheckbox="false" :isStaticMode="true"
        :isSlotMode="true" :messages="table.messages" :columns="table.columns" :rows="table.rows"
        :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
        @return-checked-rows="updateCheckedRows">
        <template v-slot:請購數量="{ row, key }">
            <div class="col col-auto align-items-center m-0 p-0">
                <span class="m-0 p-0" style="width: 12ch;">
                    {{ parseInt(row.請購數量).toLocaleString('en', { useGrouping: true }) }}&nbsp;<small>{{ row.單位
                        }}</small>
                </span>
                <button @click="openQtyDetails(row)" type="button" data-bs-toggle="modal" data-bs-target="#detailTable"
                    class="btn btn-outline-info btn-sm ms-1 my-0 px-1 py-0" style="border-radius: 20px;"
                    :id="'sxb' + row.id" :name="'sxb' + key">Edit</button>
            </div>
        </template>
    </table-lite>

    <!-- Modal -->
    <div class="modal fade" id="detailTable" tabindex="-1" aria-labelledby="detailTable" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <h1 class="col col-auto modal-title m-0 p-0 fs-4">
                        {{ modalTitle }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="inputQty" class="form-label">{{ $t("monthlyPRpageLang.on_the_way_search")
                                }}</label>
                            <div class="input-group">
                                <input v-model="inputQty" type="number" :class="{ 'is-invalid': isInvalid }"
                                    class="form-control" id="inputQty" :placeholder="originalQty" min="0">
                                <div class="input-group-text">{{ unit }}</div>
                            </div>
                            <span v-if="isInvalid" class="invalid-feedback d-block" role="alert">
                                <strong>{{ validation_err_msg }}</strong>
                            </span>
                        </div>
                        <div class="col-md-8">
                            <label for="inputDescr" class="form-label">{{ $t("monthlyPRpageLang.description") }}</label>
                            <input v-model="inputDescr" type="text" :class="{ 'is-invalid': isInvalid2 }"
                                class="form-control" id="inputDescr">
                            <span v-if="isInvalid2" class="invalid-feedback d-block" role="alert">
                                <strong>{{ validation_err_msg2 }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-lg btn-danger" style="border-radius: 5px;"
                        data-bs-dismiss="modal" aria-label="Close">
                        {{ $t('templateWords.cancel') }}
                    </button>
                    <button @click="sxb_approve" type="button" class="btn btn-lg btn-success"
                        style="border-radius: 5px;">
                        {{ $t('checkInvLang.edit') }}
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
import useTransitSearch from "../../composables/TransitSearch.ts";
import useCommonlyUsedFunctions from "../../composables/CommonlyUsedFunctions.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const app = getCurrentInstance(); // get the current instance
        let thisHtmlLang = document
            .getElementsByTagName("HTML")[0]
            .getAttribute("lang");
        // get the current locale from html tag
        app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

        const { mats, getMats, updateInTransit } = useTransitSearch(); // axios get the mats data

        onBeforeMount(getMats);

        let isInvalid = ref(false); // edit to DB validation for Qty
        let isInvalid2 = ref(false); // edit to DB validation for descr
        let validation_err_msg = ref("");
        let validation_err_msg2 = ref("");
        const modalTitle = ref("");
        const originalQty = ref("");
        const unit = ref("");
        const inputQty = ref("");
        const inputDescr = ref("");
        const file = ref();
        let checkedRows = [];
        const searchTerm = ref(""); // Search text

        // pour the data in
        const data = reactive([]);

        const triggerModal = async () => {
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            return new Promise((resolve, reject) => {
                resolve();
            });
        } // triggerModal

        const OutputExcelClick = async () => {
            await triggerModal();

            // get today's date for filename
            let today = new Date();
            let dd = String(today.getDate()).padStart(2, '0');
            let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            let yyyy = today.getFullYear();
            today = yyyy + "_" + mm + '_' + dd;

            let rows = Array();
            for (let i = 0; i < data.length; i++) {
                let tempObj = new Object;
                tempObj.料號 = data[i].料號;
                tempObj.在途數量 = data[i].請購數量;
                tempObj.說明 = data[i].說明;
                tempObj.修改人員 = data[i].修改人員;
                tempObj.最後更新時間 = data[i].最後更新時間;

                rows.push(tempObj);
            } // for

            const worksheet = XLSX.utils.json_to_sheet(rows);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, app.appContext.config.globalProperties.$t("monthlyPRpageLang.on_the_way_search"));
            XLSX.writeFile(workbook,
                app.appContext.config.globalProperties.$t(
                    "monthlyPRpageLang.on_the_way_search"
                ) + "_" + today + ".xlsx", { compression: true });

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // OutputExcelClick

        const openQtyDetails = (row) => {
            // console.log("clicked!"); // test
            modalTitle.value = row.料號;
            unit.value = row.單位;
            originalQty.value = parseInt(row.請購數量).toLocaleString('en', { useGrouping: true });
        } // openSXBDetails

        const sxb_approve = async () => {
            isInvalid.value = false;
            isInvalid2.value = false;
            if (inputQty.value.toString().trim() === "" || inputQty.value === null || inputQty.value === undefined) {
                validation_err_msg.value =
                    app.appContext.config.globalProperties.$t(
                        "validation.required"
                    );

                isInvalid.value = true;
            } // if

            if (inputDescr.value.trim() === "" || inputDescr.value === null || inputDescr.value === undefined) {
                validation_err_msg2.value =
                    app.appContext.config.globalProperties.$t(
                        "validation.required"
                    );

                isInvalid2.value = true;
            } // if

            // console.log(inputQty.value); // test
            // console.log(inputDescr.value); // test

            if (isInvalid.value === true || isInvalid2.value === true) {
                return;
            } // if

            await triggerModal();

            let isnArr = [modalTitle.value];
            let qtyArr = [inputQty.value];
            let descrArr = [inputDescr.value];
            let result = await updateInTransit(isnArr, qtyArr, descrArr);
            await getMats();

            if (result === "success") {
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
            data.splice(0);
            if (mats.value == "") {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            let allRowsObj = JSON.parse(mats.value);
            // console.log(allRowsObj.data); // test
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
                        "monthlyPRpageLang.isn"
                    ),
                    field: "料號",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="isn' +
                            row.id +
                            '" name="isn' +
                            row.id +
                            '" value="' +
                            row.料號 +
                            '">' +
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.料號 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.pName"
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
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.品名 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.transit"
                    ),
                    field: "請購數量",
                    width: "12ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.transit_reviser"
                    ),
                    field: "修改人員",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.修改人員 === null || row.修改人員 === undefined) row.修改人員 = "N/A";
                        return (
                            '<input type="hidden" id="reviser' +
                            i +
                            '" name="reviser' +
                            i +
                            '" value="' +
                            row.修改人員 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.修改人員 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.description"
                    ),
                    field: "說明",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.說明 === null || row.說明 === undefined) row.說明 = "N/A";
                        return (
                            '<input type="hidden" id="remark' +
                            i +
                            '" name="remark' +
                            i +
                            '" value="' +
                            row.說明 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.說明 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.updatetime"
                    ),
                    field: "最後更新時間",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.最後更新時間 === null || row.最後更新時間 === undefined) row.最後更新時間 = "N/A";
                        return (
                            '<input type="hidden" id="updatetime' +
                            i +
                            '" name="updatetime' +
                            i +
                            '" value="' +
                            row.最後更新時間 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.最後更新時間 +
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
            isInvalid,
            isInvalid2,
            validation_err_msg,
            validation_err_msg2,
            searchTerm,
            modalTitle,
            unit,
            originalQty,
            inputQty,
            inputDescr,
            table,
            updateCheckedRows,
            OutputExcelClick,
            openQtyDetails,
            sxb_approve,
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