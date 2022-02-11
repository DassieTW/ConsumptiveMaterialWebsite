import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

export default function NowWeAt() {
    const names = ref([]); // the names to show on the breadcrumb nav
    const errors = ref('');

    const router = useRouter();

    const getNames = function () {
        let currentURL = window.location.pathname;
        const tempArr = currentURL.split("/");
        tempArr.shift(); // the first element in arry will be "", so remove it from array
        if (tempArr.length > 0) {
            tempArr[0] = '/' + tempArr[0];
        } // if

        for (let a = 1; a < tempArr.length; a++) {
            tempArr[a] = tempArr[a-1] + '/' + tempArr[a];
        } // for

        console.log(tempArr); // test
        names.value = tempArr;
    } // function

    return {
        names,
        getNames,
        errors
    } // return

} // NowWeAt