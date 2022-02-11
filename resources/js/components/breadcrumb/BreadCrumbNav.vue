
<template>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
      <li v-for="(item, index) in names.slice(0, -1)" :key="index" class="breadcrumb-item">
        <a :href="item">{!! __("templateWords.websiteName") !!}</a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">
        {{ names[names.length-1] }}
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
    const { names, getNames } = NowWeAt();
    onMounted(getNames);
    // console.log(names); // test
    return {
      names,
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>