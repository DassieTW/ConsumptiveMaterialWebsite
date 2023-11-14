<template>
    <div class="multi-collapse collapse show" id="multiCollapse1">
        <NonMonthlyPRImportTable></NonMonthlyPRImportTable>
    </div>
    <div class="multi-collapse collapse" id="multiCollapse2" ref="detectclasschange">
        <NonMonthlyPRSearchTable ref="myChild"></NonMonthlyPRSearchTable>
    </div>
</template>

<script>
import { defineComponent, nextTick, reactive, ref, computed } from "vue";
import {
    getCurrentInstance,
    onBeforeMount,
    onBeforeUnmount,
    onMounted,
    watch,
} from "@vue/runtime-core";
// import TableLite from "./TableLite.vue";
import NonMonthlyPRImportTable from "./NonMonthlyPRImportTable.vue";
import NonMonthlyPRSearchTable from "./NonMonthlyPRSearchTable.vue";
export default defineComponent({
    name: "App",
    components: { NonMonthlyPRImportTable, NonMonthlyPRSearchTable },
    setup() {
        const myChild = ref(null);
        const detectclasschange = ref(null);
        const observer = new MutationObserver(mutations => {
            // Whenever the search page is shown,
            // update the table using "onclasschange"
            // and trigger child component's function "triggerSearchUpdate"
            for (const m of mutations) {
                const newValue = m.target.getAttribute(m.attributeName);
                nextTick(() => {
                    onclasschange(newValue, m.oldValue);
                });
            } // for
        });

        onMounted(() => {
            observer.observe(detectclasschange.value, {
                attributes: true,
                attributeOldValue: true,
                attributeFilter: ['class'],
            });
        });

        onBeforeUnmount(observer.disconnect());

        const onclasschange = (classattrvalue) => {
            // console.log(classattrvalue); // test
            const classlist = classattrvalue.split(' ');
            if (classlist.includes('show')) {
                myChild.value.triggerSearchUpdate();
            } // if
        } // onclasschange

        return {
            myChild,
            detectclasschange
        };
    }, // setup
});
</script>