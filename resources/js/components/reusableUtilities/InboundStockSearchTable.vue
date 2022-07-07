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
  <div class="w-100" style="height: 1ch"></div>
  <!-- </div>breaks cols to a new line-->
  <table-lite
    :is-fixed-first-column="true"
    :isStaticMode="true"
    :isSlotMode="true"
    :hasCheckbox="true"
    :messages="table.messages"
    :columns="table.columns"
    :rows="table.rows"
    :total="table.totalRecordCount"
    :page-options="table.pageOptions"
    :sortable="table.sortable"
    @do-search="doSearch"
    @is-finished="table.isLoading = false"
    @return-checked-rows="updateCheckedRows"
  >
    <template v-slot:客戶別="{ row, key }">
      <div v-if="row.月請購 == '否'">
        <input
          class="form-control text-center p-0 m-0"
          style="width: 8ch"
          type="number"
          :id="'safe' + key"
          :name="'safe' + key"
          :value="row.安全庫存"
          min="0"
        />
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
import useConsumptiveMaterials from "../../composables/InboundStockSearch.ts";
export default defineComponent({
  name: "App",
  components: { TableLite },
  setup() {
    const { mats, getStocks } = useConsumptiveMaterials(); // axios get the mats data

    onMounted(getStocks);

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
    }); // watch for data change

    // Table config
    const table = reactive({
      isLoading: false,
      columns: [
        {
          label: app.appContext.config.globalProperties.$t(
            "basicInfoLang.month"
          ),
          field: "月請購",
          width: "12ch",
          sortable: true,
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
      searchTerm,
      table,
      updateCheckedRows,
    };
  }, // setup
});
</script>
