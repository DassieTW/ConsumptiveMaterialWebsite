<template>
    <div class="row justify-content-between">
        <div class="row col col-auto">
            <div class="col col-auto">
                <label for="pnInput" class="col-form-label">{{ $t("basicInfoLang.quicksearch") }} :</label>
            </div>
            <div class="col col-auto p-0 m-0">
                <input id="pnInput" class="text-center form-control form-control-lg"
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
        <template v-slot:料號="{ row, key }">
            <input type="hidden" :name="'isn' + row.id" class="form-control text-center p-0 m-0" style="width: 8ch;"
                :id="'safe' + row.id" :value="row.料號" />
            <div class="scrollableWithoutScrollbar text-nowrap" style="overflow-x: auto; width: 100%;">
                {{ row.料號 }}
            </div>
        </template>
        <template v-slot:規格="{ row, key }">
            <div class="col col-auto align-items-center m-0 p-0">
                <span class="m-0 p-0" style="width: 12ch;">
                    {{ parseInt(row.請購數量).toLocaleString('en', { useGrouping: true }) }}&nbsp;<small>{{ row.單位
                        }}</small>
                </span>
            </div>
        </template>
        <template v-slot:需求數量="{ row, key }">
            <div class="col col-auto align-items-center m-0 p-0">
                <span class="m-0 p-0" style="width: 12ch;">
                    {{ parseInt(row.請購數量).toLocaleString('en', { useGrouping: true }) }}&nbsp;<small>{{ row.單位
                        }}</small>
                </span>
            </div>
        </template>
        <template v-slot:實際領用數量="{ row, key }">
            <div class="col col-auto align-items-center m-0 p-0">
                <span class="m-0 p-0" style="width: 12ch;">
                    {{ parseInt(row.請購數量).toLocaleString('en', { useGrouping: true }) }}&nbsp;<small>{{ row.單位
                        }}</small>
                </span>
            </div>
        </template>
        <template v-slot:需求與領用差異量="{ row, key }">
            <div class="col col-auto align-items-center m-0 p-0">
                <span class="m-0 p-0" style="width: 12ch;">
                    {{ parseInt(row.請購數量).toLocaleString('en', { useGrouping: true }) }}&nbsp;<small>{{ row.單位
                        }}</small>
                </span>
            </div>
        </template>
        <template v-slot:需求與領用差異="{ row, key }">
            <div class="col col-auto align-items-center m-0 p-0">
                <span class="m-0 p-0" style="width: 12ch;">
                    {{ parseInt(row.請購數量).toLocaleString('en', { useGrouping: true }) }}&nbsp;<small>{{ row.單位
                        }}</small>
                </span>
            </div>
        </template>
    </table-lite>
</template>

<script>
import { defineComponent, reactive, ref, computed, defineModel } from "vue";
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
    props: ['modelValue'],
    emits: ['update:modelValue'],
    computed: {
        searchTerm: {
            get() {
                return this.modelValue
            },
            set(value) {
                this.$emit('update:modelValue', value)
            }
        }
    },
    setup(props) {
        const app = getCurrentInstance(); // get the current instance
        let thisHtmlLang = document
            .getElementsByTagName("HTML")[0]
            .getAttribute("lang");
        // get the current locale from html tag
        app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

        const { mats, getMats } = useTransitSearch(); // axios get the mats data

        onBeforeMount(getMats);

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
        }); // watch for data change

        // Table config
        const table = reactive({
            isLoading: false,
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
                        "monthlyPRpageLang.buyamount1"
                    ),
                    field: "請購數量",
                    width: "12ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "outboundpageLang.realpickamount"
                    ),
                    field: "實際領用數量",
                    width: "10ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "callpageLang.req_vs_real"
                    ),
                    field: "需求與領用差異量",
                    width: "13ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "callpageLang.req_vs_real_percent"
                    ),
                    field: "需求與領用差異",
                    width: "13ch",
                    sortable: true,
                },
            ],
            rows: computed(() => {
                if (props.modelValue !== undefined) {
                    return data.filter((x) =>
                        x.料號
                            .toLowerCase()
                            .includes(props.modelValue.toLowerCase()) ||
                        x.品名
                            .includes(props.modelValue)
                    );
                } else {
                    return data;
                } // if else
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
            // console.log(rowsKey); // test
            checkedRows = rowsKey;
        };

        return {
            props,
            table,
            updateCheckedRows,
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