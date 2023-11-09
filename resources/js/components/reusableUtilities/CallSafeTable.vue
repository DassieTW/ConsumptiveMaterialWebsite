<template>
    <div class="row" style="text-align: left">
        <div class="col col-auto">
            <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
        </div>
        <div class="col col-3 p-0 m-0">
            <input id="pnInput" class="text-center form-control form-control-lg"
                v-bind:placeholder="$t('basicInfoLang.enterisn')" v-model="searchTerm" />
        </div>
    </div>
    <div class="w-100" style="height: 1ch"></div>
    <!-- </div>breaks cols to a new line-->
    <table-lite :is-fixed-first-column="true" :isStaticMode="true" :isSlotMode="true" :hasCheckbox="true"
        :messages="table.messages" :columns="table.columns" :rows="table.rows" :total="table.totalRecordCount"
        :page-options="table.pageOptions" :sortable="table.sortable" @do-search="doSearch"
        @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows">
        <template v-slot:月請購="{ row, key }">
            <div v-if="row.月請購 == '是'">
                <select @change="(event) => (row.月請購 = event.target.value)" style="width: 7ch"
                    class="col col-auto form-select form-select-lg p-0 m-0" :id="'month' + key" :name="'month' + key">
                    <option value="是" selected>
                        {{ $t("basicInfoLang.yes") }}
                    </option>
                    <option value="否">{{ $t("basicInfoLang.no") }}</option>
                </select>
            </div>
            <div v-else>
                <select @change="(event) => (row.月請購 = event.target.value)" style="width: 7ch"
                    class="col col-auto form-select form-select-lg p-0 m-0" :id="'month' + key" :name="'month' + key">
                    <option value="是">{{ $t("basicInfoLang.yes") }}</option>
                    <option value="否" selected>
                        {{ $t("basicInfoLang.no") }}
                    </option>
                </select>
            </div>
        </template>

        <template v-slot:安全庫存="{ row, key }">
            <div v-if="row.月請購 == '否'">
                <input class="form-control text-center p-0 m-0" style="width: 8ch" type="number" :id="'safe' + key"
                    :name="'safe' + key" :value="row.安全庫存" min="0" />
            </div>
            <div v-else>{{ $t("basicInfoLang.differ_by_client") }}</div>
        </template>
    </table-lite>
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
import useConsumptiveMaterials from "../../composables/ConsumptiveMaterials.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { mats, getMats } = useConsumptiveMaterials(); // axios get the mats data

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
        const senders = reactive([]); // access the value by senders[0], senders[1] ...

        watch(mats, () => {
            console.log(JSON.parse(mats.value)); // test
            let allRowsObj = JSON.parse(mats.value);
            console.log(allRowsObj.datas.length);
            for (let i = 0; i < allRowsObj.senders.length; i++) {
                senders.push(allRowsObj.senders[i]);
            } // for

            for (let i = 0; i < allRowsObj.datas.length; i++) {
                data.push(allRowsObj.datas[i]);
            } // for

            document.getElementById("QueryFlag").click();
        }); // watch for data change

        // Table config
        const table = reactive({
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
                            i +
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
                        "basicInfoLang.format"
                    ),
                    field: "規格",
                    width: "13ch",
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
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.規格 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.gradea"
                    ),
                    field: "A級資材",
                    width: "9ch",
                    sortable: true,
                    display: function (row, i) {
                        let returnStr = "";
                        // console.log(row); // test
                        if (row.A級資材 === "是") {
                            returnStr =
                                '<select style="width: 7ch;" class="col col-auto form-select form-select-lg p-0 m-0"' +
                                ' id="gradea' +
                                i +
                                '" name="gradea' +
                                i +
                                '">' +
                                '<option value="是" selected>' +
                                app.appContext.config.globalProperties.$t(
                                    "basicInfoLang.yes"
                                ) +
                                "</option>" +
                                '<option value="否">' +
                                app.appContext.config.globalProperties.$t(
                                    "basicInfoLang.no"
                                ) +
                                "</option>" +
                                "</select>";
                        } // if
                        else {
                            returnStr =
                                '<select style="width: 7ch;" class="col col-auto form-select form-select-lg p-0 m-0"' +
                                ' id="gradea' +
                                i +
                                '" name="gradea' +
                                i +
                                '">' +
                                '<option value="是">' +
                                app.appContext.config.globalProperties.$t(
                                    "basicInfoLang.yes"
                                ) +
                                "</option>" +
                                '<option value="否" selected>' +
                                app.appContext.config.globalProperties.$t(
                                    "basicInfoLang.no"
                                ) +
                                "</option>" +
                                "</select>";
                        } // else

                        return returnStr;
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.month"
                    ),
                    field: "月請購",
                    width: "12ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.senddep"
                    ),
                    field: "發料部門",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        let returnStr = "";
                        returnStr +=
                            '<select style="width: 10ch;" class="form-select form-select-lg p-0 m-0" id="send' +
                            i +
                            '" name="send' +
                            i +
                            '">';
                        senders.forEach((element) => {
                            if (row.發料部門 === element) {
                                returnStr +=
                                    "<option selected>" + element + "</option>";
                            } // if
                            else {
                                returnStr += "<option>" + element + "</option>";
                            } // else
                        }); // for each in sender array
                        returnStr += "</select>";
                        return returnStr; // return
                    },
                },
                // {
                //     label: app.appContext.config.globalProperties.$t(
                //         "basicInfoLang.belong"
                //     ),
                //     field: "耗材歸屬",
                //     width: "10ch",
                //     sortable: true,
                //     display: function (row, i) {
                //         let returnStr = "";
                //         // console.log(row); // test
                //         if (row.耗材歸屬 === "單耗") {
                //             returnStr =
                //                 '<select style="width: 10ch;" class="col col-auto form-select form-select-lg p-0 m-0"' +
                //                 ' id="belong' +
                //                 i +
                //                 '" name="belong' +
                //                 i +
                //                 '">' +
                //                 '<option value="單耗" selected>' +
                //                 app.appContext.config.globalProperties.$t(
                //                     "basicInfoLang.consume"
                //                 ) +
                //                 "</option>" +
                //                 '<option value="站位">' +
                //                 app.appContext.config.globalProperties.$t(
                //                     "basicInfoLang.stand"
                //                 ) +
                //                 "</option>" +
                //                 "</select>";
                //         } // if
                //         else {
                //             returnStr =
                //                 '<select style="width: 10ch;" class="col col-auto form-select form-select-lg p-0 m-0"' +
                //                 ' id="belong' +
                //                 i +
                //                 '" name="belong' +
                //                 i +
                //                 '">' +
                //                 '<option value="單耗">' +
                //                 app.appContext.config.globalProperties.$t(
                //                     "basicInfoLang.consume"
                //                 ) +
                //                 "</option>" +
                //                 '<option value="站位" selected>' +
                //                 app.appContext.config.globalProperties.$t(
                //                     "basicInfoLang.stand"
                //                 ) +
                //                 "</option>" +
                //                 "</select>";
                //         } // else

                //         return returnStr;
                //     },
                // },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.price"
                    ),
                    field: "單價",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input style="width: 10ch;" type="number" id="price' +
                            i +
                            '"' +
                            ' class="form-control text-center p-0 m-0" name="price' +
                            i +
                            '"' +
                            ' value="' +
                            parseFloat(row.單價) +
                            '">'
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.money"
                    ),
                    field: "幣別",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        let currencyDict = [
                            "RMB",
                            "USD",
                            "JPY",
                            "TWD",
                            "VND",
                            "IDR",
                        ];
                        let returnStr = "";
                        returnStr +=
                            '<select style="width: 8ch;" class="form-select form-select-lg p-0 m-0" id="money' +
                            i +
                            '" name="money' +
                            i +
                            '">';
                        currencyDict.forEach((element) => {
                            if (row.幣別 === element) {
                                returnStr +=
                                    "<option selected>" + element + "</option>";
                            } // if
                            else {
                                returnStr += "<option>" + element + "</option>";
                            } // else
                        }); // for each in sender array
                        returnStr += "</select>";
                        return returnStr;
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.unit"
                    ),
                    field: "單位",
                    width: "8ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input style="width:5ch;" type="text" id="unit' +
                            i +
                            '"' +
                            ' name="unit' +
                            i +
                            '" value="' +
                            row.單位 +
                            '"' +
                            ' class="form-control text-center p-0 m-0">'
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.mpq"
                    ),
                    field: "MPQ",
                    width: "8ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input style="width:8ch;" type="number" id="mpq' +
                            i +
                            '"' +
                            ' name="mpq' +
                            i +
                            '" value="' +
                            row.MPQ +
                            '"' +
                            ' class="form-control text-center p-0 m-0" min="0">'
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
                            '<input style="width:8ch;" type="number" id="moq' +
                            i +
                            '"' +
                            ' name="moq' +
                            i +
                            '" value="' +
                            row.MOQ +
                            '"' +
                            ' class="form-control text-center p-0 m-0" min="0">'
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.lt"
                    ),
                    field: "LT",
                    width: "8ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input style="width:8ch;" type="number" id="lt' +
                            i +
                            '"' +
                            ' name="lt' +
                            i +
                            '" value="' +
                            Math.round(row.LT) +
                            '"' +
                            ' class="form-control text-center p-0 m-0" min="0">'
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "basicInfoLang.safe"
                    ),
                    field: "安全庫存",
                    width: "13ch",
                    sortable: true,
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

        const updateCheckedRows = (rowsKey) => {
            console.log(rowsKey);
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
