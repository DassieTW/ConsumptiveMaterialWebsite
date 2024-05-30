<template>
  <table-lite :is-fixed-first-column="true" :isStaticMode="true" :isSlotMode="true" :hasCheckbox="false"
    :messages="table.messages" :columns="table.columns" :rows="table.rows" :total="table.totalRecordCount"
    :page-options="table.pageOptions" :sortable="table.sortable" @is-finished="table.isLoading = false"
    @row-clicked="rowClicked">
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
    const { news, getNews } = useNewsSearch(); // axios get the news data

    onBeforeMount(getNews);

    const app = getCurrentInstance(); // get the current instance
    let thisHtmlLang = document
      .getElementsByTagName("HTML")[0]
      .getAttribute("lang");
    // get the current locale from html tag
    app.appContext.config.globalProperties.$lang.setLocale(thisHtmlLang); // set the current locale to vue package

    // pour the data in
    const data = reactive([]); // access the value by data[0], data[1] ...

    watch(news, () => {
      let allRowsObj = JSON.parse(news.value);
      // console.log(allRowsObj.datas); // test

      for (let i = 0; i < allRowsObj.datas.length; i++) {
        data.push(allRowsObj.datas[i]);
      } // for

      document
        .getElementById("QueryFlag")
        .click();
    }); // watch for data change

    // Table config
    const table = reactive({
      isLoading: true,
      columns: [
        {
          label: app.appContext.config.globalProperties.$t("templateWords.cat"),
          field: "cat",
          width: "8ch",
          sortable: true,
          display: function (row, i) {
            let returnStr = "";
            // console.log(row); // test
            if (row.level === "urgent") {
              returnStr = '<span id="' + row.id + '" class="col col-auto text-danger-emphasis">' +
                row.category +
                '</span>'
            } // if
            else if (row.level === "important") {
              returnStr = '<span id="' + row.id + '" class="col col-auto text-warning-emphasis">' +
                row.category +
                '</span>'
            } // else if
            else {
              returnStr = '<span id="' + row.id + '" class="col col-auto">' +
                row.category +
                '</span>'
            } // else

            return (
              returnStr
            );
          },
        },
        {
          label: app.appContext.config.globalProperties.$t(
            "templateWords.title"
          ),
          field: "title",
          width: "13ch",
          sortable: true,
          display: function (row, i) {
            let returnStr = "";
            // console.log(row); // test
            if (row.level === "urgent") {
              returnStr = '<span class="col col-auto text-danger-emphasis">' +
                row.title +
                '</span>'
            } // if
            else if (row.level === "important") {
              returnStr = '<span class="col col-auto text-warning-emphasis">' +
                row.title +
                '</span>'
            } // else if
            else {
              returnStr = '<span class="col col-auto">' +
                row.title +
                '</span>'
            } // else

            return (
              returnStr
            );
          },
        },
        {
          label: app.appContext.config.globalProperties.$t(
            "templateWords.dateOfNotice"
          ),
          field: "date",
          width: "8ch",
          sortable: true,
          display: function (row, i) {
            let returnStr = "";
            // console.log(row); // test
            if (row.level === "urgent") {
              returnStr = '<span class="col col-auto text-danger-emphasis">' +
                row.updated_at +
                '</span>'
            } // if
            else if (row.level === "important") {
              returnStr = '<span class="col col-auto text-warning-emphasis">' +
                row.updated_at +
                '</span>'
            } // else if
            else {
              returnStr = '<span class="col col-auto">' +
                row.updated_at +
                '</span>'
            } // else

            return (
              returnStr
            );
          },
        },
      ],
      rows: [],
      totalRecordCount: computed(() => {
        return table.rows.length;
      }),
      sortable: { // make the date field already sorted on mount
        order: "date",
        sort: "desc",
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

    const rowClicked = (row) => {
      console.log("Row clicked ! on " + row); // test
    };

    return {
      table,
      rowClicked,
    };
  }, // setup
});
</script>
