<template>
  <table-lite :is-fixed-first-column="true" :isStaticMode="true" :isSlotMode="true" :hasCheckbox="false"
    :messages="table.messages" :columns="table.columns" :rows="table.rows" :total="table.totalRecordCount"
    :page-options="table.pageOptions" :sortable="table.sortable" @is-finished="table.isLoading = false"
    @return-checked-rows="updateCheckedRows">
    <template v-slot:月請購="{ row, key }">
      <div v-if="row.月請購 == '是'">
        <select @change="event => row.月請購 = event.target.value" style="width: 7ch;"
          class="col col-auto form-select form-select-lg p-0 m-0" :id="'month' + key" :name="'month' + key">
          <option value="是" selected>{{ $t("basicInfoLang.yes") }}</option>
          <option value="否">{{ $t("basicInfoLang.no") }}</option>
        </select>
      </div>
      <div v-else>
        <select @change="event => row.月請購 = event.target.value" style="width: 7ch;"
          class="col col-auto form-select form-select-lg p-0 m-0" :id="'month' + key" :name="'month' + key">
          <option value="是">{{ $t("basicInfoLang.yes") }}</option>
          <option value="否" selected>{{ $t("basicInfoLang.no") }}</option>
        </select>
      </div>
    </template>

    <template v-slot:安全庫存="{ row, key }">
      <div v-if="row.月請購 == '否'">
        <input class="form-control text-center p-0 m-0" style="width: 8ch;" type="number" :id="'safe' + key"
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
import useNewsSearch from "../../composables/NewsSearch.ts";
export default defineComponent({
  name: "App",
  components: { TableLite },
  setup() {
    const { news, selfDB, getNews } = useNewsSearch(); // axios get the news data

    onMounted(getNews);

    const searchTerm = ref(""); // Search text
    const app = getCurrentInstance(); // get the current instance
    let thisHtmlLang = document
      .getElementsByTagName("HTML")[0]
      .getAttribute("lang");
    // get the current locale from html tag
    app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

    // pour the data in
    const data = reactive([]); // access the value by data[0], data[1] ...

    watch(news, () => {
      console.log(JSON.parse(news.value)); // test
      let allRowsObj = JSON.parse(news.value);
      let dbName = JSON.parse(selfDB.value).data;
      console.log(allRowsObj.datas.length); // test
      console.log(dbName); // test

      for (let i = 0; i < allRowsObj.datas.length; i++) {
        data.push(allRowsObj.datas[i]);
      } // for
    }); // watch for data change

    // Table config
    const table = reactive({
      isLoading: false,
      columns: [
        {
          label: app.appContext.config.globalProperties.$t("templateWords.cat"),
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
            "templateWords.title"
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
            "templateWords.dateOfNotice"
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
      console.log(rowsKey);
    };

    return {
      table,
      updateCheckedRows,
    };
  }, // setup
});
</script>
