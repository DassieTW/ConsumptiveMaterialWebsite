import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useInboundListSearch() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();
    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let inboundclient = sessionStorage.getItem("inboundclient");
        let inboundisn = sessionStorage.getItem("inboundisn");
        let inboundlist = sessionStorage.getItem("inboundlist");
        let inboundcheck = sessionStorage.getItem("inboundcheck");
        let inboundbegin = sessionStorage.getItem("inboundbegin");
        let inboundend = sessionStorage.getItem("inboundend");
        // let gettest = await axios.post('/basic/materialsearch');
        // console.log(gettest); // test

        try {
            let response = await axios.post('/api/inbound/search', {
                DB: getDB.data,
                inboundclient: inboundclient,
                inboundisn: inboundisn,
                inboundlist: inboundlist,
                inboundcheck: inboundcheck,
                inboundbegin: inboundbegin,
                inboundend: inboundend
            });

            mats.value = JSON.stringify(response.data);
            // console.log( JSON.parse(mats.value)); // test
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
