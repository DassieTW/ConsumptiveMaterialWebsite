
<template>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
        <li class="breadcrumb-item">
            <a href="/home">{{ $t('templateWords.websiteName') }}</a>
        </li>
        <li v-for="(item, index) in urls.slice(0,-1)" :key="index" class="breadcrumb-item">
            <a :href="item">{{ $t(whereurl + '.page_name') }}</a>
        </li>
        <li class="breadcrumb-item">
            <span>{{op}}</span>
        </li>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
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

    const { whereurl, getPageNames } = NowWeAt();

    onMounted(getUrl);
    onMounted(getPageNames);
    var test = (document.getElementsByClassName("sidebar-item active")[1].textContent);

    var op = test.replace(/&nbsp;/g, ''); // remove &nbsp;
    op = op.replace(/\s+/g, ' ').trim(); // remove extra white spaces

    // console.log(op); // test

    return {
      urls,
      whereurl,
      op,
    };
  },
  mounted() {
    var thisHtmlLang = document.getElementsByTagName("HTML")[0].getAttribute("lang");
    // get the current locale from html tag
    this.$lang.setLocale(thisHtmlLang); // set the current locale to vue package
    // console.log("The current locale is : " + this.$lang.getLocale()); // test
  },
};
</script>
