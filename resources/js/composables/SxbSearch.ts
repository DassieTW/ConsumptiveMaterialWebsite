import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useSxbSearch() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();

    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        // let sxbclient = sessionStorage.getItem("sxbclient");
        let sxbisn = sessionStorage.getItem("sxbisn");
        let sxbsend = sessionStorage.getItem("sxbsend");
        let sxbsxb = sessionStorage.getItem("sxbsend");
        let sxbcheck = sessionStorage.getItem("sxbcheck");
        let sxbbegin = sessionStorage.getItem("sxbbegin");
        let sxbend = sessionStorage.getItem("sxbend");

        // let gettest = await axios.post('/basic/materialsearch');
        // console.log(gettest); // test

        try {
            let response = await axios.post('/api/month/sxb', {
                DB: getDB.data,
                // sxbclient: sxbclient,
                sxbisn: sxbisn,
                sxbsend: sxbsend,
                sxbsxb: sxbsxb,
                sxbcheck: sxbcheck,
                sxbbegin: sxbbegin,
                sxbend: sxbend,
            });

            mats.value = JSON.stringify(response.data);
            //console.log(JSON.parse(mats.value)); // test
        } catch (e) {
            console.log(e); // test
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '  ';
            } // for each errors

            console.log(errors.value); // test
        } // try catch
    } // get mats

    return {
        mats,
        getMats
    } // return
} // useConsumptiveMaterials
