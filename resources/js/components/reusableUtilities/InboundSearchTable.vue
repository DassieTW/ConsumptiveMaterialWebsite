<template>
    <div class="row justify-content-between">
        <div class="row col col-auto">
            <div class="col col-auto">
                <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
            </div>
            <div class="col col-6 p-0 m-0">
                <input id="pnInput" class="text-center form-control form-control-lg"
                    v-bind:placeholder="$t('basicInfoLang.enterisn')" v-model="searchTerm" />
            </div>
        </div>
        <div class="col col-auto">
            <!-- <button type="submit" id="delete" name="delete" class="col col-auto btn btn-lg btn-danger"
                @click="DeleteRowsClick">
                <i class="bi bi-trash3-fill fs-4"></i>
            </button>
            &nbsp; -->
            <button id="download" name="download" class="col col-auto btn btn-lg btn-success"
                :value="$t('inboundpageLang.download')" @click="OutputExcelClick">
                <i class="bi bi-file-earmark-arrow-down-fill fs-4"></i>
            </button>
        </div>
    </div>
    <div class="w-100" style="height: 1ch"></div> <!-- </div>breaks cols to a new line-->
    <span class="invalid-feedback d-block" role="alert">
        <strong>{{ $t("inboundpageLang.no_basic_are_highlighted") }}</strong>
    </span>
    <table-lite :is-fixed-first-column="true" :is-static-mode="true" :hasCheckbox="false" :is-loading="table.isLoading"
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
import * as XLSX from 'xlsx';
import useInboundListSearch from "../../composables/InboundListSearch.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { mats, getMats, deleteRows } = useInboundListSearch(); // axios get the mats data

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

        let checkedRows = [];
        const DeleteRowsClick = async () => {
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            let rows_to_be_deleted = {
                list: [],
                isn: [],
                amount: [],
                position: [],
                inpeople: [],
                inreason: [],
                intime: []
            };

            if (checkedRows !== undefined && checkedRows.length > 0) {
                // console.log(checkedRows[0]); // test
                checkedRows.forEach(row => {
                    rows_to_be_deleted.list.push(row.入庫單號);
                    rows_to_be_deleted.isn.push(row.料號);
                    rows_to_be_deleted.amount.push(row.入庫數量);
                    rows_to_be_deleted.position.push(row.儲位);
                    rows_to_be_deleted.inpeople.push(row.入庫人員);
                    rows_to_be_deleted.inreason.push(row.入庫原因);
                    rows_to_be_deleted.intime.push(row.入庫時間);
                });

                let result = await deleteRows(rows_to_be_deleted);
                if (result === "success") {
                    notyf.open({
                        type: "success",
                        message: app.appContext.config.globalProperties.$t("inboundpageLang.change") + " " + app.appContext.config.globalProperties.$t("inboundpageLang.success"),
                        duration: 3000, //miliseconds, use 0 for infinite duration
                        ripple: true,
                        dismissible: true,
                        position: {
                            x: "right",
                            y: "bottom",
                        },
                    });

                    rows_to_be_deleted.intime.forEach(element => {

                        let indexOfObject = checkedRows.findIndex(object => {
                            return object.入庫時間 === element.replace('inboundtime', '');
                        });
                        if (indexOfObject != -1) {
                            data.splice(indexOfObject, 1);
                        } // if
                    });

                    document.getElementsByClassName("vtl-tbody-checkbox").forEach(element => {
                        if (element.checked) {
                            element.click();
                        } // if
                    });

                    checkedRows = [];
                } // if
                else {
                    //庫存小於入庫數量
                    console.log(result.response.data); // test
                    if (result.response.status === 420) {
                        let mess =
                            app.appContext.config.globalProperties.$t("inboundpageLang.lessstock");

                        notyf.open({
                            type: "warning",
                            message: mess,
                            duration: 3000, //miliseconds, use 0 for infinite duration
                            ripple: true,
                            dismissible: true,
                            position: {
                                x: "right",
                                y: "bottom",
                            },
                        });

                        // console.log(result.response.data.success_list); // test
                        result.response.data.success_list.forEach(element => {
                            let indexOfObject = data.findIndex(object => {
                                return parseInt(object.id) === parseInt(element[3].replace('inboundtime', ''));
                            });

                            if (indexOfObject != -1) {
                                data.splice(indexOfObject, 1);
                            } // if
                        });
                    } // if 
                    else if (result.response.status === 421) {
                        var mess = result.response.data.message;
                        alert(mess);
                    } // else if
                } // else
            } // if

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // DeleteRowsClick

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
                tempObj.入庫單號 = data[i].入庫單號;
                tempObj.料號 = data[i].料號;
                tempObj.入庫數量 = data[i].入庫數量 + " " + data[i].單位;
                tempObj.儲位 = data[i].儲位;
                tempObj.入庫人員 = data[i].入庫人員;
                tempObj.入庫原因 = data[i].入庫原因;
                tempObj.入庫時間 = data[i].入庫時間;
                tempObj.備註 = data[i].備註;
                rows.push(tempObj);
            } // for

            const worksheet = XLSX.utils.json_to_sheet(rows);

            // change header name
            XLSX.utils.sheet_add_aoa(worksheet,
                [[
                    app.appContext.config.globalProperties.$t("inboundpageLang.inlist"),
                    app.appContext.config.globalProperties.$t("inboundpageLang.isn"),
                    app.appContext.config.globalProperties.$t("inboundpageLang.inboundnum"),
                    app.appContext.config.globalProperties.$t("inboundpageLang.loc"),
                    app.appContext.config.globalProperties.$t("inboundpageLang.inpeople"),
                    app.appContext.config.globalProperties.$t("inboundpageLang.inreason"),
                    app.appContext.config.globalProperties.$t("inboundpageLang.inboundtime"),
                    app.appContext.config.globalProperties.$t("inboundpageLang.mark"),
                ]],
                { origin: "A1" });

            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, app.appContext.config.globalProperties.$t("inboundpageLang.inlist"));
            XLSX.writeFile(workbook,
                app.appContext.config.globalProperties.$t(
                    "inboundpageLang.inlist"
                ) + "_" + today + ".xlsx", { compression: true });

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        } // OutputExcelClick

        // pour the data in
        const data = reactive([]);

        watch(mats, () => {
            let allRowsObj = JSON.parse(mats.value);
            // console.log(allRowsObj.datas); // test
            for (let i = 0; i < allRowsObj.datas.length; i++) {
                allRowsObj.datas[i].id = i;
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
                        "inboundpageLang.inlist"
                    ),
                    field: "入庫單號",
                    width: "14ch",
                    sortable: true,
                    iskey: true,
                    display: function (row, i) {
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.入庫單號 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.isn"
                    ),
                    field: "料號",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.單位 === null) {
                            return (
                                '<div class="text-danger text-nowrap CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.料號 +
                                "</div>"
                            );
                        } else {
                            return (
                                '<div class="text-nowrap CustomScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.料號 +
                                "</div>"
                            );
                        } // if else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.inboundnum"
                    ),
                    field: "入庫數量",
                    width: "14ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.單位 === null) {
                            return (
                                '<div class="CustomScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.入庫數量 + ' <small class="text-danger">' +
                                app.appContext.config.globalProperties.$t("inboundpageLang.no_basic") + "</small>" +
                                "</div>"
                            );
                        } else {
                            return (
                                '<div class="CustomScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.入庫數量 + " <small>" + row.單位 + "</small>" +
                                "</div>"
                            );
                        } // if else
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.loc"
                    ),
                    field: "儲位",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="position' +
                            i +
                            '" name="position' +
                            i +
                            '" value="' +
                            row.儲位 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.儲位 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.inpeople"
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
                        "inboundpageLang.inreason"
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
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.inboundtime"
                    ),
                    field: "入庫時間",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="inboundtime' +
                            row.id +
                            '" name="inboundtime' +
                            i +
                            '" value="' +
                            row.入庫時間 +
                            '">' +
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.入庫時間 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.mark"
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
            ],
            rows: computed(() => {
                return data.filter((x) =>
                    x.料號
                        .toLowerCase()
                        .includes(searchTerm.value.toLowerCase())
                );
            }),
            totalRecordCount: computed(() => {
                return table.rows.length;
            }),
            sortable: {
                order: "入庫時間",
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
            console.log(rowsKey); // test
            checkedRows = rowsKey;
        };

        return {
            searchTerm,
            table,
            updateCheckedRows,
            DeleteRowsClick,
            OutputExcelClick
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