<template>
    <div class="card-header row align-items-center">
        <h3 class="col col-auto align-middle m-0 p-0">
            {{ $t('templateWords.UserInfo') }}
        </h3>
    </div>
    <div class="card-body">
        <div class="row justify-content-between">
            <div class="col col-auto">
                <input id="pnInput" class="text-center form-control form-control-lg"
                    v-bind:placeholder="$t('loginPageLang.username_search')" v-model="searchTerm" />
            </div>
        </div>
        <div class="w-100" style="height: 1ch"></div><!-- </div>breaks cols to a new line-->
        <table-lite id="searchTable" :is-fixed-first-column="true" :isStaticMode="true" :isSlotMode="true"
            :hasCheckbox="false" :messages="table.messages" :columns="table.columns" :rows="table.rows"
            :total="table.totalRecordCount" :page-options="table.pageOptions" :sortable="table.sortable"
            @do-search="doSearch" @is-finished="table.isLoading = false" @return-checked-rows="updateCheckedRows"
            @row-input="rowUserInput">
            <template v-slot:權限="{ row, key }">
                <div v-if="row.priority === 0" class="m-0 p-0">
                    <select @change="(event) => { (row.權限 = event.target.value); rowUserInput(row, key); }"
                        style="width: 7ch;" class="col col-auto form-select form-select-lg p-0 m-0"
                        :id="'month' + row.id" :name="'month' + key">
                        <option value="是" selected>{{ $t("basicInfoLang.yes") }}</option>
                        <option value="否">{{ $t("basicInfoLang.no") }}</option>
                    </select>
                </div>
                <div v-else class="m-0 p-0">

                </div>
            </template>

            <template v-slot:安全庫存="{ row, key }">
                <div v-if="row.月請購 === '否'">
                    <input @change="rowUserInput(row, key)" :class="{ 'is-invalid': (row.安全庫存 === null) }"
                        class="form-control text-center p-0 m-0" style="width: 8ch;" type="number" :id="'safe' + row.id"
                        :name="'safe' + key" :value="row.安全庫存" />
                </div>
                <div v-else>{{ $t("basicInfoLang.differ_by_client") }}</div>
            </template>
        </table-lite>
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
import useUserSearch from "../../composables/UserSearch.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { users, getUsers, staffs, getStaffs, current_user, getCurrentUser } = useUserSearch(); // axios get the mats data

        let isInvalid_DB = ref(false); // add to DB validation
        let validation_err_msg = ref("");

        onBeforeMount(async () => {
            await getCurrentUser();
            await getStaffs();
            await getUsers();
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

        const triggerModal = async () => {
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            return new Promise((resolve, reject) => {
                resolve();
            });
        } // triggerModal

        watch(users, async () => {
            await triggerModal();
            // console.log(JSON.parse(mats.value)); // test
            let allRowsObj = JSON.parse(mats.value);
            // console.log(allRowsObj.datas.length);
            for (let i = 0; i < allRowsObj.senders.length; i++) {
                senders.push(allRowsObj.senders[i]);
            } // for

            for (let i = 0; i < allRowsObj.datas.length; i++) {
                allRowsObj.datas[i].id = i;
                data.push(allRowsObj.datas[i]);
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
                        "loginPageLang.username"
                    ),
                    field: "帳號",
                    width: "11ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "loginPageLang.priority"
                    ),
                    field: "權限",
                    width: "10ch",
                    sortable: false,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "loginPageLang.name"
                    ),
                    field: "姓名",
                    width: "10ch",
                    sortable: false,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "loginPageLang.dep"
                    ),
                    field: "部門",
                    width: "10ch",
                    sortable: false,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "loginPageLang.mail"
                    ),
                    field: "信箱",
                    width: "10ch",
                    sortable: false,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "loginPageLang.database_list"
                    ),
                    field: "可登入DB",
                    width: "15ch",
                    sortable: false,
                },
            ],
            rows: computed(() => {
                return data.filter((x) =>
                    x.帳號
                        .toLowerCase()
                        .includes(searchTerm.value.toLowerCase()) ||
                    x.姓名
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

        const rowUserInput = (row, rowNum) => {
            // console.log(document.getElementById("unitConsumption" + rowNum).value);
            data[row.id].單價 = document.getElementById("price" + row.id).value;
            data[row.id].幣別 = document.getElementById("money" + row.id).value;
            data[row.id].單位 = document.getElementById("unit" + row.id).value;
            data[row.id].MPQ = document.getElementById("mpq" + row.id).value;
            data[row.id].MOQ = document.getElementById("moq" + row.id).value;
            data[row.id].LT = document.getElementById("lt" + row.id).value;
            data[row.id].月請購 = document.getElementById("month" + row.id).value;
            data[row.id].A級資材 = document.getElementById("gradea" + row.id).value;
            data[row.id].發料部門 = document.getElementById("send" + row.id).value;
            if (document.getElementById("safe" + row.id) == null) {
                data[row.id].安全庫存 = null;
            } else {
                data[row.id].安全庫存 = document.getElementById("safe" + row.id).value;
            } // if else

            // console.log(data); // test
        };

        return {
            searchTerm,
            table,
            rowUserInput,
        };
    }, // setup
});
</script>
<style scoped></style>