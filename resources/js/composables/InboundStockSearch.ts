import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useInboundStockSearch() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();
    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let inboundclient = sessionStorage.getItem("inboundstockclient");
        let inboundisn = sessionStorage.getItem("inboundstockisn");
        let inboundloc = sessionStorage.getItem("inboundstockloc");
        let inboundsend = sessionStorage.getItem("inboundstocksend");
        let inboundmonth = sessionStorage.getItem("inboundstockmonth");
        let inboundnogood = sessionStorage.getItem("inboundstocknogood");
        // let gettest = await axios.post('/basic/materialsearch');
        // console.log(gettest); // test

        try {
            let response = await axios.post('/api/inbound/searchstock', {
                DB: getDB.data,
                inboundclient: inboundclient,
                inboundisn: inboundisn,
                inboundloc: inboundloc,
                inboundsend: inboundsend,
                inboundmonth: inboundmonth,
                inboundnogood: inboundnogood
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
