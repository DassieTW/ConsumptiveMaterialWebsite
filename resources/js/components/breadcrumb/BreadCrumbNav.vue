
<template>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
      <li v-for="(item, index) in urls.slice(0, -1)" :key="index" class="breadcrumb-item">
        <a :href="item">{{ "0.0" }}</a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">
        {{ $lang.getLocale() }}
        {{ $t('checkInvLang.page_name') }}
      </li>
    </ol>
  </nav>

</template>

<script>
import { onMounted } from "@vue/runtime-core";
// composable as to component in Vue is like Service as to Controller in Laravel
import NowWeAt from "../../composables/now_location";
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
    console.log("Component mounted.");
  },
};
</script>