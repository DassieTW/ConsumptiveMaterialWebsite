<template>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <h3 class="col col-auto m-0">{{ $t("monthlyPRpageLang.search") }}</h3>
                <button class="col col-auto btn btn-light m-0 p-0 flip-btn" @click="(flip = !flip)">
                    <i class="bi bi-arrow-left-right"> </i>
                    {{ $t("monthlyPRpageLang.importMonthlyData") }}
                </button>
            </div>
        </div>

        <div class="w-100" style="height: 2rem;"></div><!-- </div>breaks cols to a new line-->

        <div class="card-body">
            <div class="row justify-content-between">
                <div class="row col col-auto">
                    <div class="col col-auto">
                        <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
                    </div>
                    <div class="col col-auto p-0 m-0">
                        <input id="pnInput" class="text-center form-control form-control-lg"
                            v-bind:placeholder="$t('monthlyPRpageLang.enter90isn')" v-model="searchTerm" />
                    </div>
                </div>
                <div class="col col-auto">
                    <button id="delete" name="delete" class="col col-auto btn btn-lg btn-danger" @click="deleteRow">
                        <i class="bi bi-trash3-fill fs-4"></i>
                    </button>
                    &nbsp;
                    <button id="download" name="download" class="col col-auto btn btn-lg btn-success"
                        :value="$t('monthlyPRpageLang.download')" @click="OutputExcelClick">
                        <i class="bi bi-file-earmark-arrow-down-fill fs-4"></i>
                    </button>
                </div>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <span v-if="isInvalid_DB" class="invalid-feedback d-block" role="alert">
                <strong>{{ validation_err_msg }}</strong>
            </span>
            <table-lite id="searchTable" :is-fixed-first-column="true" :is-static-mode="true"
                :hasCheckbox="true" :messages="table.messages" :columns="table.columns" :rows="table.rows"
                :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
                @return-checked-rows="updateCheckedRows" @row-clicked="rowClicked"></table-lite>
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
import useMonthlyPRSearch from "../../composables/MonthlyPRSearch.ts";
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

        const { mats, getMats_MPS, deleteMPS } = useMonthlyPRSearch();
        const { queryResult, validateISN } = useCommonlyUsedFunctions();
        const triggerSearchUpdate = async () => {
            await getMats_MPS();

            return new Promise((resolve, reject) => {
                resolve("success");
            });
        } // triggerSearchUpdate

        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref("");
        const file = ref();
        let checkedRows = [];

        const deleteRow = async () => {
            let ninetyisn = [];
            let isn = [];

            if (checkedRows.length == 0) {
                notyf.open({
                    type: "warning",
                    message: app.appContext.config.globalProperties.$t("basicInfoLang.nodata"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });

                return;
            } // if

            for (let i = 0; i < checkedRows.length; i++) {
                ninetyisn.push(checkedRows[i].料號90);
                isn.push(checkedRows[i].料號);
            } // for

            await triggerModal();
            let result = await deleteMPS(ninetyisn, isn);
            if (result === "success") {
                for (let i = 0; i < checkedRows.length; i++) {
                    let indexOfObject = data.findIndex(object => {
                        return parseInt(object.id) === parseInt(checkedRows[i].id);
                    });

                    if (indexOfObject != -1) {
                        data.splice(indexOfObject, 1);
                    } // if
                } // for

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

                document.querySelectorAll('.vtl-tbody-checkbox').forEach(el => el.checked = false);

                if (document.querySelector(".vtl-thead-checkbox").checked) {
                    document.querySelector(".vtl-thead-checkbox").click();
                } // if
                checkedRows = [];
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
        } // deleteRow

        const OutputExcelClick = () => {
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

            let rows = Array();
            for (let i = 0; i < data.length; i++) {
                let tempObj = new Object;
                tempObj.料號90 = data[i].料號90;
                tempObj.料號 = data[i].料號;
                tempObj.本月MPS = data[i].本月MPS;
                tempObj.本月生產天數 = data[i].本月生產天數;
                tempObj.下月MPS = data[i].下月MPS;
                tempObj.下月生產天數 = data[i].下月生產天數;
                tempObj.填寫時間 = data[i].填寫時間;
                rows.push(tempObj);
            } // for

            const worksheet = XLSX.utils.json_to_sheet(rows);
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, app.appContext.config.globalProperties.$t("templateWords.monthly"));
            XLSX.writeFile(workbook,
                app.appContext.config.globalProperties.$t(
                    "templateWords.monthly"
                ) + "_" + today + ".xlsx", { compression: true });

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // OutputExcelClick

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

        watch(mats, async () => {
            await triggerModal();
            data.splice(0);
            if (mats.value == "") {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            let allRowsObj = JSON.parse(mats.value);
            // console.log(allRowsObj.data.length);
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
                        "monthlyPRpageLang.90isn"
                    ),
                    field: "料號90",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="ninetyisn' +
                            row.id +
                            '" name="ninetyisn' +
                            i +
                            '" value="' +
                            row.料號90 +
                            '">' +
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%; height: 100%;">' +
                            row.料號90 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.isn"
                    ),
                    field: "料號",
                    width: "15ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="isn' +
                            row.id +
                            '" name="isn' +
                            i +
                            '" value="' +
                            row.料號 +
                            '">' +
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%; height: 100%;">' +
                            row.料號 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.nowmps"
                    ),
                    field: "本月MPS",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="nowmps' +
                            i +
                            '" name="nowmps' +
                            i +
                            '" value="' +
                            row.本月MPS +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; height: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseInt(row.本月MPS).toLocaleString('en', { useGrouping: true }) +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.nowday"
                    ),
                    field: "本月生產天數",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="nowday' +
                            i +
                            '" name="nowday' +
                            i +
                            '" value="' +
                            row.本月生產天數 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; height: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseInt(row.本月生產天數) +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.nextmps"
                    ),
                    field: "下月MPS",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="nextmps' +
                            i +
                            '" name="nextmps' +
                            i +
                            '" value="' +
                            row.下月MPS +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; height: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseInt(row.下月MPS).toLocaleString('en', { useGrouping: true }) +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.nextday"
                    ),
                    field: "下月生產天數",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="nextday' +
                            i +
                            '" name="nextday' +
                            i +
                            '" value="' +
                            row.下月生產天數 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; height: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            parseInt(row.下月生產天數) +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.writetime"
                    ),
                    field: "填寫時間",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="time' +
                            i +
                            '" name="time' +
                            i +
                            '" value="' +
                            row.填寫時間 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; height: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.填寫時間 +
                            "</div>"
                        );
                    },
                },
            ],
            rows: computed(() => {
                return data.filter((x) =>
                    x.料號90
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

        /**
        * Row checked event
        */
        const updateCheckedRows = (rowsKey) => {
            console.log(rowsKey); // test
            checkedRows = rowsKey;
        };

        /**
         * Row clicked event
         */
        const rowClicked = (row) => {
            // console.log("Row clicked!", row);
        };

        return {
            flip: ref(false),
            isInvalid_DB,
            validation_err_msg,
            searchTerm,
            table,
            updateCheckedRows,
            rowClicked,
            deleteRow,
            OutputExcelClick,
            triggerSearchUpdate
        };
    }, // setup
});
</script>
<style scoped></style>