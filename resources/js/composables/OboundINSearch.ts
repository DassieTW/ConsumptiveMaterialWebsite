import {
    ref,
    Ref
} from "vue";
import axios from "axios";
import {
    useRouter
} from "vue-router";

export default function useOboundInboundSearch() {
    const mats = ref("");
    const errors = ref("");
    const router = useRouter();
    const getMats = async () => {
        errors.value = "";
        let getDB = await axios.post('/getCurrentDB');
        let oboundclient = sessionStorage.getItem("oboundclient");
        let oboundisn = sessionStorage.getItem("oboundisn");
        let oboundbound = sessionStorage.getItem("oboundbound");
        let oboundcheck = sessionStorage.getItem("oboundcheck");
        let oboundbegin = sessionStorage.getItem("oboundbegin");
        let oboundend = sessionStorage.getItem("oboundend");
        // let gettest = await axios.post('/basic/materialsearch');
        // console.log(gettest); // test

        try {
            let response = await axios.post('/api/obound/inboundsearch', {
                DB: getDB.data,
                oboundbound: oboundbound,
                oboundclient: oboundclient,
                oboundisn: oboundisn,
                oboundcheck: oboundcheck,
                oboundbegin: oboundbegin,
                oboundend: oboundend
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
