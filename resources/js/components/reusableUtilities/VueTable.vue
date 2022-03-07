
<template>
  <table-lite
    :is-slot-mode="true"
    :is-loading="table.isLoading"
    :columns="table.columns"
    :rows="table.rows"
    :total="table.totalRecordCount"
    :sortable="table.sortable"
    @do-search="doSearch"
    @is-finished="table.isLoading = false"
  >
    <template v-slot:name="data">
      {{ data.value.name }}
    </template>
  </table-lite>
</template>

<script>
import TableLite from "vue3-table-lite";
import TableLite from "vue3-table-lite/ts"; // TypeScript

export default {
  setup() {
    const { urls, getUrl } = NowWeAt();
    onMounted(getUrl);
    // console.log(names); // test
    return {
      urls,
    };
  },
  mounted() {
    var thisHtmlLang = document
      .getElementsByTagName("HTML")[0]
      .getAttribute("lang");
    // get the current locale from html tag
    this.$lang.setLocale(thisHtmlLang); // set the current locale to vue package
    console.log("The current locale is : " + this.$lang.getLocale()); // test
  },
};
</script>