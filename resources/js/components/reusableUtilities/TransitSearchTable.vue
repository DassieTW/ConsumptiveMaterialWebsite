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
    <table-lite :is-fixed-first-column="true" :is-static-mode="true" :hasCheckbox="false" :isLoading="table.isLoading"
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
import useTransitSearch from "../../composables/TransitSearch.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { mats, getMats } = useTransitSearch(); // axios get the mats data

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
                allRowsObj.datas[i].請購數量 = parseInt(allRowsObj.datas[i].請購數量);
                data.push(allRowsObj.datas[i]);
            } // for
        }); // watch for data change

        // Table config
        const table = reactive({
            isLoading: false,
            columns: [
                {
                    label: app.appContext.config.globalProperties.$t("monthlyPRpageLang.client"),
                    field: "客戶別",
                    width: "12ch",
                    sortable: true,
                    isKey: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="client' +
                            i +
                            '" name="client' +
                            i +
                            '" value="' +
                            row.客戶 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.客戶 +
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
                            '<input type="hidden" id="number' +
                            i +
                            '" name="number' +
                            i +
                            '" value="' +
                            row.料號 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
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
                        "monthlyPRpageLang.unit"
                    ),
                    field: "單位",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="position' +
                            i +
                            '" name="position' +
                            i +
                            '" value="' +
                            row.單位 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.單位 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "monthlyPRpageLang.buyamount1"
                    ),
                    field: "請購數量",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<input type="hidden" id="buyamount' +
                            i +
                            '" name="buyamount' +
                            i +
                            '" value="' +
                            row.請購數量 +
                            '">' +
                            '<div class="text-nowrap scrollableWithoutScrollbar"' +
                            ' style="overflow-x: auto !important; width: 100%; -ms-overflow-style: none !important; scrollbar-width: none !important;">' +
                            row.請購數量 +
                            "</div>"
                        );
                    },
                },
            ],
            rows: computed(() => {
                return data.filter((x) =>
                    x.料號.toLowerCase().includes(searchTerm.value.toLowerCase())
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
                    app.appContext.config.globalProperties.$t("basicInfoLang.record") +
                    ", " +
                    app.appContext.config.globalProperties.$t("basicInfoLang.total") +
                    " {2} " +
                    app.appContext.config.globalProperties.$t("basicInfoLang.record"),
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
            console.log(rowsKey)
            // only check one 
        }
        return {
            searchTerm,
            table,
            updateCheckedRows,
        };
    }, // setup
});
</script>
