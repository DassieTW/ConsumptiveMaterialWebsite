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
    <table-lite id="searchTable" :is-fixed-first-column="true" :hasCheckbox="true" :isStaticMode="true"
        :isSlotMode="true" :messages="table.messages" :columns="table.columns" :rows="table.rows"
        :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
        @return-checked-rows="updateCheckedRows">
        <template v-slot:料號="{ row, key }">
            <div class="scrollableWithoutScrollbar text-nowrap" style="overflow-x: auto; width: 100%;">
                {{ row.料號 }}
            </div>
        </template>
        <template v-slot:請購數量="{ row, key }">
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
            <div class="scrollableWithoutScrollbar text-nowrap" style="overflow-x: auto; width: 100%;">
                {{ row.料號 }}
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
import { yearTag, monthTag, checkedRows, searchTerm, data, table } from '../../composables/DiffTableStore.js';
import * as XLSX from 'xlsx';
import TableLite from "./TableLite.vue";
import useDiffSearch from "../../composables/DiffSearch.ts";
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

        const { mats, getMats } = useDiffSearch(); // axios get the mats data

        onBeforeMount(getMats);

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
            data.splice(0); // clean up possible old records
            if (mats.value == "") {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                return;
            } // if

            let allRowsObj = JSON.parse(mats.value);
            console.log(allRowsObj); // test
            return; // test
            for (let i = 0; i < allRowsObj.data.length; i++) {
                allRowsObj.data[i].id = i;
                data.push(allRowsObj.data[i]);
            } // for

            $("body").loadingModal("hide");
            $("body").loadingModal("destroy");
        }); // watch for data change

        const updateCheckedRows = (rowsKey) => {
            checkedRows.length = 0;
            checkedRows.push(...rowsKey);
            console.log(checkedRows); // test
        };

        return {
            table,
            searchTerm,
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