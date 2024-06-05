<template>
    <div class="card-header align-items-center">
        <h3 class="col col-auto align-middle">
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
            @is-finished="table.isLoading = false">
            <template v-slot:priority="{ row, key }">
                <div v-if="row.current_user_priority == 0" class="m-0 p-0">
                    <select class="form-select text-center m-0 p-0" :id="row.username" style="width: 8ch;"
                        @change="priorityChange(row.username, $event.target.value)">
                        <option v-for="item in [0, 1, 2, 3, 4]" :value="item" :selected="row.priority == item">{{ item
                            }}</option>
                    </select>
                </div>
                <div v-else-if="row.current_user_priority == 1" class="m-0 p-0">
                    <select class="form-select text-center m-0 p-0" :id="row.username" style="width: 8ch;"
                        @change="priorityChange(row.username, $event.target.value)"
                        :disabled="row.priority <= row.current_user_priority">
                        <option v-for="item in [1, 2, 3, 4]" :value="item" :selected="row.priority == item">{{ item
                            }}</option>
                    </select>
                </div>
                <div v-else>
                    {{ row.priority }}
                </div>
            </template>
            <template v-slot:available_dblist="{ row, key }">
                <button :value="row.available_dblist" type="button" style="border-radius: 20px;"
                    class="btn btn-outline-info dbInfo btn-sm m-0 px-3 py-0" data-bs-toggle="modal"
                    data-bs-target="#siteListPicker"
                    @click="InfoBtClicked(row.username, row.姓名, row.available_dblist)">Info</button>
            </template>
        </table-lite>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="siteListPicker" aria-hidden="true" aria-labelledby="siteListPicker" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Available Databases for {{ clickedUser }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div v-for="db in db_list" class="form-check form-switch col-9">
                            <input class="form-check-input dbCheckbox" type="checkbox" role="switch"
                                v-bind:id="`${(db.replace('Consumables management', '')).trim()}`"
                                :value="`${(db.replace('Consumables management', '')).trim()}`" v-model="checkedDBs" />
                            <label class="form-check-label"
                                :for="`${(db.replace('Consumables management', '')).trim()}`">
                                {{ db }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="DeleteUser" class="btn btn-danger" data-bs-target="#AreYouSureModal"
                        data-bs-toggle="modal">Delete
                        This User</button>
                    <button type="button" id="ListConfirm" class="btn btn-success" @click="submitNewDBList()">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="AreYouSureModal" aria-hidden="true" aria-labelledby="AreYouSureModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body row justify-content-center">
                    <span class="col col-auto">Are You Sure ?</span>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <button id="ImSure" class="col col-auto btn btn-outline-danger" data-bs-dismiss="modal"
                        @click="DeleteUser()">YES</button>
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
import useUserSearch from "../../composables/UserSearch.ts";
export default defineComponent({
    name: "App",
    components: { TableLite },
    setup() {
        const { users, getUsers, staffs, getStaffs, current_user, getCurrentUser, db_list, getDBList, setPriority, setDBList, delUser } = useUserSearch(); // axios get the mats data

        onBeforeMount(async () => {
            await getDBList();
            await getCurrentUser();
            await getStaffs();
            await getUsers();
        });

        const triggerModal = async () => {
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            return new Promise((resolve, reject) => {
                resolve();
            });
        } // triggerModal

        const searchTerm = ref(""); // Search text
        const app = getCurrentInstance(); // get the current instance
        let thisHtmlLang = document
            .getElementsByTagName("HTML")[0]
            .getAttribute("lang");
        // get the current locale from html tag
        app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

        // pour the data in
        const data = reactive([]);

        const clickedUser = ref("");
        const checkedDBs = ref([]);
        function InfoBtClicked(username, name, available_dblist) {
            clickedUser.value = username + "(" + name + ")";
            checkedDBs.value = available_dblist.split("_");
        } // InfoBtClicked

        async function priorityChange(username, priority) {
            await triggerModal();
            let result = await setPriority(username, priority);
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
        } // priorityChange

        async function submitNewDBList() {
            let dblist_str = "";
            let username = (clickedUser.value).split("(")[0];
            checkedDBs.value.forEach((db) => {
                dblist_str = dblist_str + "_" + db;
            });

            if (checkedDBs.value.length > 0) {
                dblist_str = dblist_str.slice(1);
            } // if

            await triggerModal();
            let result = await setDBList(username, dblist_str);
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
            await getUsers();
        } // submitNewDBList

        async function DeleteUser() {
            let username = (clickedUser.value).split("(")[0];
            await triggerModal();
            let result = await delUser(username);
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

            await getUsers();
        } // DeleteUser

        watch(current_user, async () => {
            if (current_user.value.priority > 0) {
                table.columns.splice(5, 2);
            } // if
        }); // watch for data change

        watch(users, async () => {
            data.splice(0); // clean up previous data
            await triggerModal();
            let allRowsObj = JSON.parse(users.value);
            // console.log(allRowsObj.datas); // test

            if (current_user.value.priority > 0) {
                for (let i = 0; i < allRowsObj.datas.length; i++) {
                    if (allRowsObj.datas[i].priority != 0) {
                        allRowsObj.datas[i].current_user_priority = current_user.value.priority;
                        data.push(allRowsObj.datas[i]);
                    } // if
                } // for
            } else {
                for (let i = 0; i < allRowsObj.datas.length; i++) {
                    allRowsObj.datas[i].current_user_priority = current_user.value.priority;
                    data.push(allRowsObj.datas[i]);
                } // for
            } // if else

            // console.log(data); // test
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
                        "loginPageLang.username"
                    ),
                    field: "username",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.username +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "loginPageLang.priority"
                    ),
                    field: "priority",
                    width: "10ch",
                    sortable: true,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "loginPageLang.name"
                    ),
                    field: "姓名",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.姓名 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "loginPageLang.dep"
                    ),
                    field: "部門",
                    width: "13ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.部門 +
                            "</div>"
                        );
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "loginPageLang.mail"
                    ),
                    field: "email",
                    width: "12ch",
                    sortable: true,
                    display: function (row, i) {
                        if (row.email === null || row.email == "null") {
                            return (
                                '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                'N/A' +
                                "</div>"
                            );
                        } // if
                        else {
                            return (
                                '<div class="scrollableWithoutScrollbar text-nowrap"' +
                                ' style="overflow-x: auto; width: 100%;">' +
                                row.email +
                                "</div>"
                            );
                        }
                    },
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "loginPageLang.database_list"
                    ),
                    field: "available_dblist",
                    width: "10ch",
                    sortable: false,
                },
                {
                    label: app.appContext.config.globalProperties.$t(
                        "loginPageLang.latest_login_time"
                    ),
                    field: "last_login_time",
                    width: "10ch",
                    sortable: true,
                    display: function (row, i) {
                        return (
                            '<div class="scrollableWithoutScrollbar text-nowrap"' +
                            ' style="overflow-x: auto; width: 100%;">' +
                            row.last_login_time +
                            "</div>"
                        );
                    }
                },
            ],
            rows: computed(() => {
                return data.filter((x) =>
                    x.username
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

        return {
            db_list,
            searchTerm,
            table,
            clickedUser,
            InfoBtClicked,
            current_user,
            priorityChange,
            checkedDBs,
            submitNewDBList,
            DeleteUser
        };
    }, // setup
});
</script>
<style scoped>
td,
th {
    text-align: center;
    vertical-align: middle;
}

#siteListPicker::-webkit-scrollbar-track {
    -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
    border-radius: 4px;
    background-color: #F5F5F5;
}

#siteListPicker::-webkit-scrollbar {
    width: 4px;
    -webkit-appearance: none;
}

#siteListPicker::-webkit-scrollbar-thumb {
    border-radius: 4px;
    -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
    background-color: rgba(0, 0, 0, 0.3);
}
</style>