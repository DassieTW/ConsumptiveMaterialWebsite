<template>
    <div class="row" style="text-align: left">
        <div class="col col-auto">
            <label for="pnInput" class="col-form-label"
                >{{ $t("basicInfoLang.quicksearch") }} :</label
            >
        </div>
        <div class="col col-3 p-0 m-0">
            <input
                id="pnInput"
                class="text-center form-control form-control-lg"
                v-bind:placeholder="$t('basicInfoLang.enterisn')"
                v-model="searchTerm"
            />
        </div>
    </div>
    <table-lite
        :is-fixed-first-column="true"
        :is-static-mode="true"
        :hasCheckbox="false"
        :isLoading="table.isLoading"
        :messages="table.messages"
        :columns="table.columns"
        :columns1="table.columns1"
        :rows="table.rows"
        :rows1="table.rows1"
        :total="table.totalRecordCount"
        :page-options="table.pageOptions"
        :sortable="table.sortable"
        @is-finished="table.isLoading = false"
        @return-checked-rows="updateCheckedRows"
    >
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
import useSxbSearch from "../../composables/SxbSearch.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { mats, getMats } = useSxbSearch(); // axios get the mats data

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
        // const senders = reactive([]); // access the value by senders[0], senders[1] ...

        watch(mats, () => {
            console.log(JSON.parse(mats.value)); // test
            let allRowsObj = JSON.parse(mats.value);
            //console.log(allRowsObj.datas.length);
            for (let i = 0; i < allRowsObj.datas.length; i++) {
                allRowsObj.datas[i].本次請購數量 = parseInt(
                    allRowsObj.datas[i].本次請購數量
                );
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
                        "monthlyPRpageLang.sxb"
                    ),
                    field: "SXB單號",
                    width: "10ch",
                    sortable: true,
                    isKey: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="sxb' +
                            i +
                            '" name="sxb' +
                            i +
                            '" value="' +
                            row.SXB單號 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.SXB單號 +
                            "</div>"
                        );
                    },
                },

                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.isn"
                    ),
                    field: "料號",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
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
                        "monthlyPRpageLang.pName"
                    ),
                    field: "品名",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="buyamount' +
                            i +
                            '" name="buyamount' +
                            i +
                            '" value="' +
                            row.品名 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.品名 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.buyamount"
                    ),
                    field: "本次請購數量",
                    width: "16ch",
                    sortable: true,
                    display: function (row, i) {
                        let returnStr = "";
                        // console.log(row); // test
                        if (row.本次請購數量 !== undefined) {
                            returnStr =
                                '<input type="hidden" id="srm' +
                                i +
                                '" name="srm' +
                                i +
                                '" value="' +
                                row.本次請購數量 +
                                '">' +
                                '<div class="text-nowrap scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.本次請購數量 +
                                "</div>";
                        } // if
                        else {
                            returnStr =
                                '<input type="hidden" id="srm' +
                                i +
                                '" name="srm' +
                                i +
                                '" value="' +
                                row.請購數量 +
                                '">' +
                                '<div class="text-nowrap scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.請購數量 +
                                "</div>";
                        } // else

                        return returnStr;
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.buytime"
                    ),
                    field: "請購時間",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        let returnStr = "";
                        // console.log(row); // test
                        if (row.請購時間 !== undefined) {
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
                        } // if
                        else {
                            returnStr =
                                '<input type="hidden" id="srm' +
                                i +
                                '" name="srm' +
                                i +
                                '" value="' +
                                row.上傳時間 +
                                '">' +
                                '<div class="text-nowrap scrollableWithoutScrollbar"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.上傳時間 +
                                "</div>";
                        } // else

                        return returnStr;
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
            // only check one
        };
        return {
            searchTerm,
            table,
            updateCheckedRows,
        };
    }, // setup
});
</script>
