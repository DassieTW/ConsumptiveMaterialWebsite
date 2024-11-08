<template>
    <div class="card">
        <div class="card-header">
            <h3>{{ $t('inboundpageLang.locationchange') }}</h3>
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
                <!-- <div class="col col-auto">
                    <button type="submit" id="delete" name="delete" class="col col-auto btn btn-lg btn-danger"
                        @click="deleteRow">
                        <i class="bi bi-trash3-fill fs-4"></i>
                    </button>
                    &nbsp;
                    <button id="download" name="download" class="col col-auto btn btn-lg btn-success"
                        @click="OutputExcelClick">
                        <i class="bi bi-file-earmark-arrow-down-fill fs-4"></i>
                    </button>
                </div> -->
            </div>
            <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
            <span v-if="isInvalid_DB" class="invalid-feedback d-block" role="alert">
                <strong>{{ validation_err_msg }}</strong>
            </span>
            <table-lite id="searchTable" :is-fixed-first-column="true" :isStaticMode="true" :isSlotMode="true"
                :hasCheckbox="true" :messages="table.messages" :columns="table.columns" :rows="table.rows"
                :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
                @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows">
                <template v-slot:調撥數量="{ row, key }">
                    <div class="row">
                        <input v-model="row.調撥數量" @input="CheckCurrentRow($event);"
                            :class="{ 'is-invalid': ((parseInt(row.調撥數量) > parseInt(row.現有庫存)) || (parseInt(row.調撥數量) == 0 && row.新儲位 != '')) }"
                            class="form-control text-center p-0 m-0 col col-auto"
                            style="width: 7ch; border-bottom-right-radius: 0px !important; border-top-right-radius: 0px !important;"
                            type="number" min="0" :id="'tqty' + row.id" :name="'tqty' + key" />
                        <span class="input-group-text text-center p-0 m-0 col col-auto"
                            style="border-bottom-left-radius: 0px !important; border-top-left-radius: 0px !important;">{{
                                row.單位 }}</span>
                    </div>
                </template>

                <template v-slot:新儲位="{ row, key }">
                    <select v-model="row.新儲位" @input="CheckCurrentRow($event);" style="width: 11ch;"
                        class="col col-auto form-select form-select-lg ps-2 p-0 m-0" :id="'newloc' + row.id"
                        :class="{ 'is-invalid': (parseInt(row.調撥數量) > 0 && row.新儲位 == '') }" :name="'newloc' + key">
                        <option id="noneSelected" value="" disabled selected>
                            {{ $t('inboundpageLang.choose') }}
                        </option>
                        <template v-for="item in locsArray">
                            <option :id="item" v-if="row.儲位 !== item" :value="item">
                                {{ item }}
                            </option>
                        </template>
                    </select>
                </template>
            </table-lite>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="row justify-content-center">
                <div class="col col-auto">
                    <button type="submit" id="change" name="change"
                        class="col col-auto fs-3 text-center btn btn-lg btn-info" @click="onSendToDBClick">
                        <i class="bi bi-cloud-upload-fill"></i>
                        {{ $t('basicInfoLang.change') }}
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
    watch,
} from "@vue/runtime-core";
import TableLite from "./TableLite.vue";
import useInboundStockSearch from "../../composables/InboundStockSearch.ts";
import useCommonlyUsedFunctions from "../../composables/CommonlyUsedFunctions.ts";

export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { mats, errors, getMats, locTransfer } = useInboundStockSearch(); // axios get the mats data
        const { queryResult, locations, validateISN, getLocs } = useCommonlyUsedFunctions();

        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref("");

        onBeforeMount(async () => {
            await getLocs();
            await getMats();
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
        let checkedRows = [];
        const locsArray = reactive([]);

        const triggerModal = async () => {
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            return new Promise((resolve, reject) => {
                resolve();
            });
        } // triggerModal

        function CheckCurrentRow(e) {
            if (!e.target.closest('tr').firstChild.firstChild.checked) {
                e.target.closest('tr').firstChild.firstChild.click();
            } // if
        } // CheckCurrentRow

        const onSendToDBClick = async () => {
            await triggerModal();
            isInvalid_DB.value = false;
            let rowsCount = 0;
            let hasError = false;
            // console.log(data.length); //test
            if (checkedRows.length <= 0) {
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

                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            // ----------------------------------------------

            // loop through the checked rows and validate the data
            for (let i = 0; i < checkedRows.length; i++) {
                let currentRow = checkedRows[i];
                let currentRowPN = currentRow.料號;
                let currentRowLoc = currentRow.儲位;
                let currentRowNewLoc = currentRow.新儲位;
                let currentRowQty = currentRow.調撥數量;

                // check if the new location is empty
                if (currentRowNewLoc === "") {
                    hasError = true;
                    isInvalid_DB.value = true;
                    validation_err_msg.value = currentRowPN + " " + app.appContext.config.globalProperties.$t("inboundpageLang.newloc_empty");
                    break;
                } // if

                // check if the transfer quantity is empty
                if (parseInt(currentRowQty) === 0) {
                    hasError = true;
                    isInvalid_DB.value = true;
                    validation_err_msg.value = currentRowPN + " " + app.appContext.config.globalProperties.$t("inboundpageLang.qty_empty");
                    break;
                } // if

                // check if the transfer quantity is more than the current stock
                if (parseInt(currentRowQty) > parseInt(currentRow.現有庫存)) {
                    hasError = true;
                    isInvalid_DB.value = true;
                    validation_err_msg.value = currentRowPN + " " + app.appContext.config.globalProperties.$t("inboundpageLang.qty_exceed");
                    break;
                } // if

                // check if the transfer quantity is more than 0 and the new location is empty
                if (parseInt(currentRowQty) > 0 && currentRowNewLoc === "") {
                    hasError = true;
                    isInvalid_DB.value = true;
                    validation_err_msg.value = currentRowPN + " " + app.appContext.config.globalProperties.$t("inboundpageLang.newloc_empty");
                    break;
                } // if

                // check if the transfer quantity is more than 0 and the new location is the same as the old location
                if (parseInt(currentRowQty) > 0 && currentRowNewLoc === currentRowLoc) {
                    hasError = true;
                    isInvalid_DB.value = true;
                    validation_err_msg.value = currentRowPN + " " + app.appContext.config.globalProperties.$t("inboundpageLang.newloc_same");
                    break;
                } // if
            } // for

            if (hasError) {
                isInvalid_DB.value = true;
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

                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            // ----------------------------------------------
            // prepare the data arrays to be sent
            let pnArray = [];
            let ogLocArray = [];
            let newLocArray = [];
            let qtyArray = [];
            for (let j = 0; j < checkedRows.length; j++) {
                pnArray.push(checkedRows[j].料號);
                ogLocArray.push(checkedRows[j].儲位);
                newLocArray.push(checkedRows[j].新儲位);
                qtyArray.push(checkedRows[j].調撥數量);
            } // for

            // actually updating database now
            let start = Date.now();
            let result = await locTransfer(pnArray, ogLocArray, newLocArray, qtyArray);
            let timeTaken = Date.now() - start;
            console.log("Total time taken : " + timeTaken + " milliseconds");
            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");

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

                data.splice(0);
                checkedRows.splice(0);
                await getMats();
            } // if
            else {
                if (errors.value.hasOwnProperty('insufficient_pn')) {
                    // console.log(errors.value.insufficient_pn[0]); // test
                    isInvalid_DB.value = true;
                    let temp_str = "";
                    for (let i = 0; i < errors.value.insufficient_pn.length; i++) {
                        temp_str += errors.value.insufficient_pn[i] + "(" + errors.value.insufficient_oldLoc[i] + ")" + ", ";
                    } // for
                    // remove the last comma
                    temp_str = temp_str.slice(0, -2);
                    validation_err_msg.value = temp_str + " " + app.appContext.config.globalProperties.$t("inboundpageLang.altered_by_other_recently");
                    data.splice(0);
                    checkedRows.splice(0);
                    await getMats();
                } // if

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
        } // onSendToDBClick

        watch(mats, async () => {
            await triggerModal();
            // console.log(JSON.parse(mats.value)); // test
            let allRowsObj = JSON.parse(mats.value);
            JSON.parse(locations.value).data.forEach(element => {
                locsArray.push(element.儲存位置);
            });

            for (let i = 0; i < allRowsObj.datas.length; i++) {
                allRowsObj.datas[i].id = i;
                allRowsObj.datas[i].新儲位 = "";
                allRowsObj.datas[i].調撥數量 = 0;
                data.push(allRowsObj.datas[i]);
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
                        "inboundpageLang.nowstock"
                    ),
                    field: "現有庫存",
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
                    width: "10ch",
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
                {
                    label: app.appContext.config.globalProperties.$t(
                        "inboundpageLang.transferamount"
                    ),
                    field: "調撥數量",
                    width: "10ch",
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
                        "inboundpageLang.updatetime"
                    ),
                    field: "最後更新時間",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.最後更新時間 === null || row.最後更新時間 === undefined) row.最後更新時間 = "N/A";
                        return (
                            '<div class="text-nowrap CustomScrollbar"' +
                            ' style="overflow-x: auto; width: 100%;">' +
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
                    x.儲位
                        .includes(searchTerm.value)
                );
            }),
            totalRecordCount: computed(() => {
                return table.rows.length;
            }),
            sortable: {
                order: "最後更新時間",
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
            isInvalid_DB,
            validation_err_msg,
            updateCheckedRows,
            onSendToDBClick,
            locsArray,
            CheckCurrentRow,
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
