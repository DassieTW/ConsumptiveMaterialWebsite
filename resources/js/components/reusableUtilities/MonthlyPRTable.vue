<template>
    <div class="flip-card" ref="detectclasschange">
        <div class="flip-card-inner row w-100">
            <div class="flip-card-front col col-12" id="cardFront">
                <MonthlyPRImportTable ref="myChild"></MonthlyPRImportTable>
            </div>
            <div class="flip-card-back col col-12" id="cardBack">
                <MonthlyPRSearchTable ref="myChild2"></MonthlyPRSearchTable>
            </div>
        </div>
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
import MonthlyPRImportTable from "./MonthlyPRImportTable.vue";
import MonthlyPRSearchTable from "./MonthlyPRSearchTable.vue";
export default defineComponent({
    name: "App",
    components: { MonthlyPRImportTable, MonthlyPRSearchTable },
    setup() {
        const myChild = ref(null);
        const myChild2 = ref(null);
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

        onBeforeUnmount(observer.disconnect());

        onMounted(() => {
            observer.observe(detectclasschange.value, {
                attributes: true,
                attributeOldValue: true,
                attributeFilter: ['class'],
            });

            watch(
                () => myChild.value.flip,
                (newVal) => {
                    $('.flip-card').toggleClass('transition');
                }
            );

            watch(
                () => myChild2.value.flip,
                (newVal) => {
                    $('.flip-card').toggleClass('transition');
                }
            );
        });

        const onclasschange = (classattrvalue) => {
            // console.log(classattrvalue); // test
            const classlist = classattrvalue.split(' ');
            if (classlist.includes('transition')) {
                myChild2.value.triggerSearchUpdate();
            } // if
        } // onclasschange

        return {
            myChild,
            myChild2,
            detectclasschange,
        };
    }, // setup
});
</script>
<style scoped>
.flip-card {
    background-color: transparent;
    perspective: 1000px;
    /* Remove this if you don't want the 3D effect */
    height: 75vh;
    overflow-y: auto;
}

.flip-card::-webkit-scrollbar-track {
    -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
    border-radius: 4px;
    background-color: #F5F5F5;
}

.flip-card::-webkit-scrollbar {
    width: 4px;
    -webkit-appearance: none;
}

.flip-card::-webkit-scrollbar-thumb {
    border-radius: 4px;
    -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
    background-color: rgba(0, 0, 0, 0.3);
}

/* This container is needed to position the front and back side */
.flip-card-inner {
    position: relative;
    transition: transform 0.5s;
    transform-style: preserve-3d;
}

/* Do an horizontal flip when you move the mouse over the flip box container */
.flip-card.transition .flip-card-inner {
    transform: rotateY(180deg);
}

/* Position the front and back side */
.flip-card-front,
.flip-card-back {
    position: absolute;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
}

.flip-card-back {
    transform: rotateY(180deg);
}
</style>